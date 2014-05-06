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
		$this->load->library('redis');
		$data = array(
				'uid' => $uid,
				'fid'  => $fid.'',
				'fname' => $fname,
				'extension'=> $extension
		);
		$str = $this->db->insert_string('files', $data);
		//初始化浏览数和下载数
		$this->redis->hset('DOC.'.$fid, 'VIEW', 0);
		$this->redis->hset('DOC.'.$fid, 'DOWN', 0);
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
	
	/**
	 * 根据文件id获取文件名
	 * @param unknown $fid
	 */
	public function file_name($fid) {
		$this->load->database();
		$this->db->select('fname');
		$query = $this->db->get_where('files', array('fid'=>$fid));
		if($query->num_rows()>0) {
			$row = $query->row_array();
			return $row['fname'];
		}
		return false;
	}
	
	/**
	 * 根据fid获取某个文件的信息
	 * @param string  $fid
	 * @param unknown $fileds
	 */
	public function file_info($fid, $fileds) {
		if(!is_array($fileds)) {
			$fileds = array($fileds);
		}
		$select = '';
		$length = count($fileds);
		for($i=0; $i<$length; $i++) {
			if($i!=$length-1)
				$select .= $fileds[$i].',';
			else
				$select .= $fileds[$i];
		}
		$this->db->select($select);
		$query = $this->db->get_where('files', array('fid'=>$fid));
		
		return $query->row_array();
	}
	/**
	 * 获取搜索结果列表
	 * @param array $fids
	 */
	public function search($query, $page) {
		$this->load->database();
		$this->load->library('xun');
		$this->load->library('redis');
		//设置搜索
		$search = $this->xun->getSearch();
		$search->setFuzzy()->setLimit(20, ($page-1)*20);
		$docs = $search->search($query, false);
		$count = $search->getLastCount();
		//var_dump($count);
		
		$list = array(); // 返回给客户端的列表
		$fids = array(); // 用来搜索的fid列表
		foreach($docs as $m) {
			$fids[] = $m->fid;
			$list[$m->fid] = array(
					'fid' => $m->fid,
					'fname' => $search->highlight($m->fname),
					'intro' => $search->highlight($m->intro),
					'catalog' => $m->catalog 
			);
		}
		//var_dump($fids);
		$this->db->select('fid, nickname, jf');
		$this->db->from('files');
		$this->db->join('user', 'files.uid = user.id');
		$this->db->where_in('fid', $fids);
		$query = $this->db->get();
		foreach($query->result() as $m) {
			$list[$m->fid]['nickname'] = $m->nickname;
			$list[$m->fid]['jf'] = $m->jf;
			$list[$m->fid]['view_times'] = $this->redis->hget('DOC.'.$m->fid, 'VIEW');
			$list[$m->fid]['down_times'] = $this->redis->hget('DOC.'.$m->fid, 'DOWN');
			$list[$m->fid]['pages'] = $this->redis->hget('DOC.'.$m->fid, 'PAGE');
		}
		
		return array(
			'list'=>$list,
			'count' => $count
		);
	}
}