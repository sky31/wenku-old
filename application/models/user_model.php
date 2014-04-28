<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 * @author heister
 *
 */
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
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
		$this->load->database();
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
		
		return $this->db->query($str);
	}
	
	/**
	 * 用户登录
	 * @param string $login_num
	 * @param string $password
	 */
	public function login($login_num, $password) {
		$this->load->database();
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
			} else {
				$ret = array('ret'=>1, 'info'=>'帐号或密码错误');
			}
			
		} else {
			$ret = array('ret'=>1, 'info'=>'帐号或密码错误');
		}
		return $ret;
	}
	
	/**
	 * 判断用户是否已经登录
	 */
	function is_login() {
		if(isset($_SESSION['IS_LOGIN']) && $_SESSION['IS_LOGIN']==="YES") {
			return true;
		}
		return false;
	}
	
	/**
	 * 登出的操作
	 */
	function logout() {
		unset($_SESSION['IS_LOGIN']);
	}
	
	/**
	 * 返回当前登录的用户的信息
	 */
	public function user_info($field='all') {
		if(is_array($field)){
			
		} else if(isset(
				$_SESSION['USER_'.strtoupper($field)] )){
			$ret =  $_SESSION['USER_'.strtoupper($field)];
		} else {
			// 从数据库获取单个字段
			
		}
		return $ret;
	}
}