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
	
	/**
	 * 获取用户收藏的文件列表
	 */
	public function collection_list($uid, $page, $per_page=20) {
		$this->db->select('collection.fid, fname , jf, extension, cot, nickname, user.id as uid');
		$this->db->from('collection');
		$this->db->join('files', 'files.fid=collection.fid');
		$this->db->join('user', 'files.uid=user.id');
		$this->db->where('collection.uid', $uid);
		$this->db->order_by('cot', 'desc'); // 上传时间倒序
		$this->db->limit($per_page, ($page-1)*$per_page);
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	
	/**
	 * 移除收藏
	 */
	public function remove($uid, $fid) {
		return $this->db->delete('collection', array('uid'=>$uid, 'fid'=>$fid));
	} 
	
	
}