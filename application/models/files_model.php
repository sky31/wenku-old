<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 * @author heister
 *
 */
class Files_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 添加一个用户上传的文件添加文件
	 * @param integer $uid 用户id 
	 */
	public function add_file($uid, $fid, $fname, $extension) {
		$this->load->database();
		$data = array(
				'uid' => $uid,
				'fid'  => $fid,
				'fname' => $fname,
				'extension'=> $extension
		);
		$str = $this->db->insert_string('files', $data);
		
		return $this->db->query($str);
	}
	
	/**
	 * 更新单个文件的信息
	 */
	public function update_file($fid, $datas)
	{
		$this->load->database();
		$this->db->where('fid', $fid);
		return $this->db->update('files', $datas);
	}
	
	/**
	 * 将文件保存到mongodb
	 * @param string $tmp_name 临时文件名
	 * @param string $filename 文件名
	 * @return string MongoDB存储的文件ID
	 */
	public function store_file($tmp_name, $filename)
	{
		//filename
		$this->load->library("mongo");
		$db = $this->mongo->file_db();
		$grid = $db->getGridFS();
		return $grid->put($tmp_name, array('filename'=>$filename));
	}
	
	/**
	 * 进入数据处理队列
	 */
	public function push_trans_queue($fid){
		$this->load->library('redis');
		$res = $this->redis->lpush('Q.TRANS', $fid);
		$this->redis->save();
		return $res;
	}
}