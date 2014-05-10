<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 * @author heister
 *
 */
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('redis');
	}
	
	/**
	 * 接入
	 * @param string $login_num
	 * @param string $password
	 * @param string $name
	 * @param string $nickname
	 * @param email $email
	 * @param number $is_verify
	 */
	public function add_user($login_num, $password, $name, $nickname, $face, $email, $is_verify=0) {
		$data = array(
				'login_num' => $login_num,
				'password'  => md5($password),
				'name'      => $name,
				'nickname'  => $nickname,
				'email'     => $email,
				'face'      => $face,
				'is_verify' => $is_verify,
				'last_login_time' => time(),
				'last_login_ip'   => $this->input->ip_address()
 	 	);
		$str = $this->db->insert_string('user', $data);
		$res = $this->db->query($str);
		
		$uid = $this->db->insert_id();
		
		$this->incr_jf($uid, 20);
		$this->incr_doc_count($uid, 0);
		
		return $res; 
	}
	
	/**
	 * 用户登录
	 * @param string $login_num
	 * @param string $password
	 */
	public function login($login_num, $password) {
		$sql = 'select id, nickname,password, face from '.$this->db->dbprefix('user').' where `login_num`=?';
		$result = $this->db->query($sql , array($login_num));
		if($result->num_rows()!=0) {
			$row = $result->row_array(); 
			if($row['password']===md5($password)) {
				$_SESSION['IS_LOGIN'] = 'YES';
				$_SESSION['USER_ID'] = $row['id'];
				$_SESSION['USER_NICKNAME'] = $row['nickname'];
				$_SESSION['USER_FACE'] = $row['face'];
				$ret = array('ret'=>0, 'info'=>'ok');
				
				//更新用户的登录信息
				$this->db->where('id', $row['id']);
				$this->db->update('user', array(
					'last_login_time' => time(),
					'last_login_ip'   => $this->input->ip_address()
				));
				
			} else {
				$ret = array('ret'=>1, 'info'=>'帐号或密码错误');
			}
			
		} else {
			$ret = array('ret'=>1, 'info'=>'帐号或密码错误');
		}
		return $ret;
	}
	
	/**
	 * cookie 登录
	 */
	function cookie_login() {
		$cauth = $this->input->cookie('DOC31CAUTH');
		$fid   = $this->input->cookie('fid'); //fid = fakeid
		if($cauth && $fid) {
			$cauth_key = $this->cookie_redis_key($fid, $cauth);
			$uid = $this->redis->get($cauth_key); //从 redis 获得用户的id
			
			if($cauth==$this->calc_cauth($uid, $this->input->user_agent().'')) {
				// 提取用户的信息
				$this->db->select('nickname, password, face');
				$result = $this->db->get_where('user', array('id'=>$uid));
				$row = $result->row_array();
				$_SESSION['IS_LOGIN'] = 'YES';
				$_SESSION['USER_ID'] = $uid;
				$_SESSION['USER_NICKNAME'] = $row['nickname'];
				$_SESSION['USER_FACE'] = $row['face'];
				return true;
				
			} else{
				$this->redis->del($cauth_key);
				$this->input->set_cookie(array(
					'name'=>'DOC31CAUTH',
					'value'=>''
				));
				return false;
			}
		}
		return false;
	}
	
	/**
	 * 设置cookie登录
	 * @param integer $uid 用户的id
	 * @param integer $time 存续时间，秒，默认为一个星期：60*60*24*7=604800
	 */
	function set_cookie_login($uid, $expire=604800) {
		$cauth = $this->calc_cauth($uid, $this->input->user_agent().'');
		// 一个假的id，用来确保唯一性，因为sha1也不一定唯一
		$fid = $uid + 24308;
		
		$this->input->set_cookie(array(
			'name'=>'DOC31CAUTH',
			'value'=>$cauth,
			'expire'=>$expire
		));
		
		$this->input->set_cookie(array(
				'name'=>'fid',
				'value'=>$fid,
				'expire'=>$expire
		));
		
		// 存储在redis中
		$this->redis->setex(
				$this->cookie_redis_key($fid, $cauth), $expire, $uid);
	}
	
	/**
	 * 计算cauth
	 */
	private function calc_cauth($uid, $uagent) {
		return sha1('heimonsy'.$uid.$uagent);
	}
	
	/**
	 * 计算redis 的 cookie key
	 */
	private function cookie_redis_key($fid, $cauth){
		return 'D.CAUTH.'.$fid.$cauth;
	}
	
	/**
	 * 判断用户是否已经登录
	 */
	function is_login() {
		if(isset($_SESSION['IS_LOGIN']) && $_SESSION['IS_LOGIN']==="YES") {
			return true;
		} else if($this->cookie_login()){
			return true;
		}
		return false;
	}
	
	/**
	 * 登出的操作
	 */
	function logout() {
		$this->input->set_cookie(array(
			'name'=>'DOC31CAUTH',
			'value'=>'',
		));
		unset($_SESSION['IS_LOGIN']);
	}
	
	/**
	 * 返回当前登录的用户的信息
	 */
	public function user_info($field='all') {
		if(is_array($field)){
			
		} else if(isset(
				$_SESSION['USER_'.strtoupper($field)] )) {
			$ret =  $_SESSION['USER_'.strtoupper($field)];
		} else {
			// 从数据库获取单个字段
			
		}
		return $ret;
	}
	
	/**
	 * 获取用户上传的文件数
	 */
	public function user_doc_count($uid) {
		return intval($this->redis->hget('USR.'.$uid, 'DC'));
	}
	
	/**
	 * 获取用户的积分
	 */
	public function user_jf($uid) {
		return intval($this->redis->hget('USR.'.$uid, 'JF'));
	}
	
	/**
	 * 增加用户的积分
	 */
	public function incr_jf($uid, $incr) {
		return $this->redis->hincrby('USR.'.$uid, 'JF', $incr);
	}
	
	/**
	 * 增加用户的文档数
	 */
	public function incr_doc_count($uid, $incr = 1) {
		return $this->redis->hincrby('USR.'.$uid, 'DC', $incr);
	}
	
	/**
	 * 用户收集的文档数
	 */
	public function user_collection_nums($fid) {
		$sql = 'select count(*) as count from '.$this->db->dbprefix('collection')
				.' where fid="'.$fid.'"';
		$query = $this->db->query($sql);
		$row = $query->row_array();
		
		return intval($row['count']);
	}
	
	/**
	 * 判断某个文件是否下载过
	 * @param unknown $uid
	 * @param unknown $fid
	 */
	public function have_down($uid, $fid) {
		return $this->redis->sismember('DOC.S.'.$uid, $fid);
	}
	/**
	 * 增加用户下载的集合
	 * @param unknown $uid
	 * @param unknown $fid
	 */
	public function add_down_file($uid, $fid) {
		return $this->redis->sadd('DOC.S.'.$uid, $fid);
	}
	
	/**
	 * 检查email是否存在
	 */
	public function check_email($email) {
		$sql = 'select id from '.$this->db->dbprefix('user').' where `email`=?';
		$query = $this->db->query($sql, array($email));
		return $query->num_rows()==0?false:true;
	}
}