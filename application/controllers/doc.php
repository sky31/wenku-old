<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文库的主控制类
 * @author heimonsy
 *
 */
class Doc extends MY_Controller {
	
	//用来传递到视图中的数据
	private $datas = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user_model');
		if($this->user_model->is_login()){
			//如果用户登录了，则显示用户的登录信息
			$this->datas['is_login'] = true;
			$this->datas['user_nickname'] = $this->user_model->user_info('nickname');
			$this->datas['user_face'] = $this->user_model->user_info('face');
			$this->datas['user_id'] = $this->user_model->user_info('id');
		} else {
			$this->datas['is_login'] = false;
		}

	}
	
	
	/**
	 * 首页
	 */
	function index() {
		$this->datas['nav'] = 'index';  //用来在导航上输出class="active"
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('doc/index.php');
		$this->load->view('common/footer.php');
		
	}
	
	/**
	 * 文库页
	 */
	function lists($catalog='all', $page=1) {
		$this->datas['nav'] = 'list';
		
		$this->load->model('catalog_model');
		if($catalog=='all') {
			$this->datas['prefix_title'] = '全部文档列表 | ';
		} else {
			$this->datas['prefix_title'] =
				$this->catalog_model->get_value($catalog).' | ';
		}
		
		$this->load->model('files_model');
		$this->datas['count'] = $this->files_model->count($catalog);
		$this->datas['list'] = $this->files_model->file_list($catalog, $page);
		
		// 进行分页
		$this->load->library('pages');
		$this->datas['pagination'] = $this->pages->create_links(
				'/lists/'.$catalog.'/',
				$this->datas['count'], 3, $page
		);
		
		// 获取分页列表
		$this->load->model('catalog_model');
		$this->datas['catalog_array'] = $this->catalog_model->get_array();
		
		$this->datas['cur_catalog'] = $catalog;
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('doc/list.php');
		$this->load->view('common/footer.php');
	}
	
	
	/**	
	 * 搜索
	 */
	function search($type='all',$words=NULL, $page=1) {
		$search_query = urldecode($words);
		//判断类型，和关键字
		$type_allows = array( 'all', 'doc', 'xls', 'ppt', 'pdf');
		if($search_query===NULL || $search_query==='' || !in_array($type, $type_allows)) {
			$this->load->helper('url');
			redirect("/");
		}
 		//建立query
 		if($type=='all'){
 			$query = $search_query;
 		} else {
 			$query = 'ext:'.$type.' AND '.$search_query;
 		}

 		$this->load->model('files_model');
 		$result = $this->files_model->search($query, $page); //搜索
 		$this->datas['search_list'] = $result['list'];
 		$this->datas['search_count'] = $result['count'];
 		
 		//进行分页
 		$this->load->library('pages');
 		$this->datas['pagination'] = $this->pages->create_links(
 				'/search/'.$type.'/'.$words.'/',
 				$this->datas['search_count'], 4, $page
 		);
 		
 		$this->datas['search_word'] = urldecode($words);
 		$this->datas['search_type'] = urldecode($type);
 		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('doc/search.php');
		$this->load->view('common/footer.php');
	}

	/**
	 * 显示文章
	 */
	function view($fid=null) {
		if($fid===null) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		$this->datas['nav'] = 'list';
		
		$this->load->model('files_model');
		$this->datas['file'] = $this->files_model->view_file($fid);

		if($this->datas['file']===NULL) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		// 获取用户是否收藏了此文章
		if($this->datas['is_login']) {
			$this->load->model('collection_model');
			$this->datas['is_collection'] = 
				$this->collection_model->have($this->datas['user_id'], $fid);
		}
		
		$this->load->model('catalog_model');
		$this->datas['catalog_name'] = 
				$this->catalog_model->get_value($this->datas['file']['catalog']);
		
		// 增加一次阅读量
		$this->files_model->incr_view_times($fid, 1);
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('doc/view.php');
		$this->load->view('common/upload_modal.php');
		$this->load->view('common/down_modal.php');
		$this->load->view('common/footer.php');
	}
	
	/**
	 * 获取SWF单个的页面
	 */
	function page() {
		$fid = $this->input->get('fid', NULL);
		$page = $this->input->get('pn', 0);
		if($fid===NULL) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		$page++;
		$this->load->library("mongo");
		
		$grid = $this->mongo->swf_grid();
		
		$query = array(
				'realfid'=>$fid,
				'page'=> intval($page)
		);

		//如果不 配置此项，将可能抛出Mongo异常
		ini_set('mongo.long_as_object', 1);
		$file = $grid->findOne($query);

		if($file==NUll) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		$this->output
			->set_content_type('application/x-shockwave-flash');
		
		echo $file->getBytes();
	}
}