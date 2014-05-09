<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 收藏模型
 * @author heister
 *
 */
class Collection_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 用户添加新的收藏文件
	 */
	public function add($uid, $fid) {
		$data = array(
				'uid' => $uid,
				'fid'  => $fid.'',
				'cot'  => time()
		);
		$sql = $this->db->insert_string('collection', $data);
	
		return $this->db->query($sql);
	}
	
	/**
	 * 判断是否已经收藏
	 */
	public function have($uid, $fid) {
		$sql = 'select count(*) as count from ' 
				. $this->db->dbprefix('collection') 
				. ' where uid=? and fid=?';
		$query = $this->db->query($sql, array($uid, $fid));
		$result = $query->row_array();
		return $result['count']==0?false:true;
	}
	
	
}