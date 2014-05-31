<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 * @author heister
 *
 */
class Files_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('redis');
	}
	
	/**
	 * 添加一个用户上传的文件添加文件
	 * @param integer $uid 用户id 
	 */
	public function add_file($uid, $fid, $fname, $extension, $size) {
		$data = array(
				'uid' => $uid,
				'fid'  => $fid.'',
				'fname' => $fname,
				'extension'=> $extension,
				'up_date' => time(),
				'size'   => $size
		);
		$str = $this->db->insert_string('files', $data);
		//初始化浏览数和下载数
		$this->incr_down_times($fid, 0);
		$this->incr_view_times($fid, 0);
		
		return $this->db->query($str);
	}
	
	/**
	 * 更新单个文件的信息
	 */
	public function update_file($fid, $datas)
	{
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
		$res = $this->redis->lpush('Q.TRANS', $fid);
		$this->redis->save();
		return $res;
	}
	
	/**
	 * 根据文件id获取文件名
	 * @param unknown $fid
	 */
	public function file_name($fid) {
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
	public function search($query, $page, $per_page=20) {
		$this->load->library('xun');
		//设置搜索
		$search = $this->xun->getSearch();
		$search->setFuzzy()->setLimit($per_page, ($page-1)*$per_page);
		$docs = $search->search($query);
		$count = $search->getLastCount();
		//var_dump($count);
		if($count==0) {
			return array(
					'count'=>0,
					'list'=> array(),
			);
		}
		
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

		$this->db->select('fid, nickname, jf, catalog,extension, up_date');
		$this->db->from('files');
		$this->db->join('user', 'files.uid = user.id');
		$this->db->where_in('fid', $fids);
		$query = $this->db->get();

		foreach($query->result() as $m) {

			$list[$m->fid]['nickname'] = $m->nickname;
			$list[$m->fid]['jf'] = $m->jf;
			$list[$m->fid]['up_date'] = $m->up_date;
			$list[$m->fid]['extension'] = $m->extension;
			//$list[$m->fid]['view_times'] = $this->redis->hget('DOC.'.$m->fid, 'VIEW');
			$list[$m->fid]['down_times'] = $this->redis->hget('DOC.'.$m->fid, 'DOWN');
			$list[$m->fid]['pages'] = $this->redis->hget('DOC.'.$m->fid, 'PAGE');
		}
		
		return array(
			'list'=>$list,
			'count' => $count
		);
	}
	
	/**
	 * 获取文件列表
	 * @param unknown $catalog
	 * @param unknown $page
	 * @param number $per_page
	 */
	function file_list($catalog, $page, $per_page=20) {
		
		$this->db->select('fid, fname, intro, nickname, catalog, jf, extension, up_date');
		$this->db->from('files');
		$this->db->join('user', 'files.uid = user.id');
		$this->db->where('is_set', 1);
		$this->db->where('files.is_del', 0);
		if($catalog!=='all') {
			$this->db->where('catalog', $catalog);
		}
		$this->db->order_by('up_date', 'desc'); // 上传时间倒序
		$this->db->limit($per_page, ($page-1)*$per_page);
		
		$query = $this->db->get();
		
		$list = $query->result_array();
		foreach($list as &$m) {
			$m['down_times'] = $this->redis->hget('DOC.'.$m['fid'], 'DOWN');
			$m['pages'] = $this->redis->hget('DOC.'.$m['fid'], 'PAGE');
		}
		
		return $list;
	}
	
	/**
	 * 获取用户文件列表
	 * @param unknown $uid
	 * @param unknown $page
	 * @param number $per_page
	 */
	public function user_file_list($uid, $page, $per_page=20) {
		$this->db->select('fid, fname , jf, is_set, extension, up_date');
		$this->db->where('uid', $uid);
		$this->db->order_by('up_date', 'desc');
		$this->db->limit($per_page, ($page-1)*$per_page);
		$query = $this->db->get('files');
		$result = $query->result_array();
		foreach($result as &$m) {
			$m['down_times'] = $this->down_times($m['fid']);
		} 
		return $result;
	}
	
	/**
	 * 获取文章数量
	 * @param string $catalog 分类
	 */
	function count($catalog='all') {
		$sql = 'select count(*) as count from '.$this->db->dbprefix('files').
		' where is_del=0 and is_set=1';
		if($catalog !== 'all') {
			$sql .= ' and catalog="'.$catalog.'"';
		}
		
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result[0]['count'];
	}
	
	/**
	 * 根据文章fid获取某个文章的信息 
	 * @param unknown $fid
	 */
	function view_file($fid) {
		
		$this->db->select('fid, fname, uid, face, intro, catalog, nickname, jf, extension, up_date');
		$this->db->from('files');
		$this->db->join('user', 'files.uid = user.id');
		$this->db->where('fid', $fid);
		$query = $this->db->get();
		if($query->num_rows()==0) {
			return NULL;
		}
		$result = $query->row_array();
		
		$result['view_times'] = $this->view_times($fid);
		$result['down_times'] =$this->down_times($fid);
		$result['pages'] = $this->page_nums($fid);

		return $result;
	}
	
	/**
	 * 增加浏览次数
	 */
	public function incr_view_times($fid, $incr=1) {
		return $this->redis->hincrby('DOC.'.$fid, 'VIEW',$incr);
	}
	
	/**
	 * 增加下载次数
	 */
	public function incr_down_times($fid, $incr=1) {
		return $this->redis->hincrby('DOC.'.$fid, 'DOWN',$incr);
	}
	
	/**
	 * 获取浏览次数
	 * @param unknown $fid
	 */
	public function view_times($fid) {
		return $this->redis->hget('DOC.'.$fid, 'VIEW');
	}
	/**
	 * 获取下载次数
	 * @param unknown $fid
	 */
	public function down_times($fid) {
		return $this->redis->hget('DOC.'.$fid, 'DOWN');
	}
	
	/**
	 * 获取文档的页数
	 * @param unknown $fid
	 */
	public function page_nums($fid) {
		return $this->redis->hget('DOC.'.$fid, 'PAGE');
	}
	
	/**
	 * 获取最新上传的文件列表（转换成功 ）
	 * 
	 */
	public function new_file_list($nums = 6) {
		$fids = $this->redis->lrange('Q.SUCCESS', 0 , 5);
		if(count($fids)>0){
			$this->db->select('fid, nickname, fname');
			$this->db->from('files');
			$this->db->join('user', 'files.uid = user.id');
			$this->db->where_in('fid', $fids);
			$this->db->order_by('up_date', 'desc'); // 上传时间倒序
			$query = $this->db->get();
			return $query->result_array();
		} else {
			return array();
		}
	}
	
	/**
	 * 根据文件名获取类似的文件
	 * @param unknown $fname
	 */
	public function similar_file_list($fname, $fid, $nums=6){
		$query = 'fname:'.$fname.' -fid:'.$fid;
		
		$this->load->library('xun');
		//设置搜索
		$search = $this->xun->getSearch();
		$search->setFuzzy()->setLimit($nums);
		$docs = $search->search($query);
		$count = $search->getLastCount();
		//var_dump($count);
		if($count==0) {
			return array();
		}
		$list = array();
		foreach($docs as &$m) {
			$list[] = array(
				'fid' => $m->fid,
				'fname'=> $search->highlight($m->fname),
				'extension'=> $m->ext
			);
		}
		return $list;
	}
	
	/**
	 * 判断文件是否存在
	 */
	public function have_file($size, $md5) {
		$this->load->library('mongo');
		$db = $this->mongo->file_db();
		return $db->fs->files->findOne(array(
				'length'=>$size,
				'md5'   =>$md5
		), array('_id')) == NULL ? false:true;
	}
	
	/**
	 * 删除一个文件
	 */
	public function delete($fid) {
		
	}
	
}