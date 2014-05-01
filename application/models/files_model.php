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
	public function add_file($uid, $fid, $fname) {
		$this->load->database();
		$data = array(
				'uid' => $uid,
				'fid'  => $fid,
				'name' => 
		);
		$str = $this->db->insert_string('user', $data);
		
		return $this->db->query($str);
	}
	
	/**
	 * 将文件保存到mongodb
	 * @param string $tmp_name 临时文件名
	 * @param string $filename 文件名
	 * @return string MongoDB存储的文件ID
	 */
	public function store_file($tmp_name, $filename) {
		
	}
}