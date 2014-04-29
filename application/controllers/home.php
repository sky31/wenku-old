<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文库的主控制类
 * @author heimonsy
 *
 */
class Home extends MY_Controller {
	private $datas = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		if(!$this->user_model->is_login()){
			$this->load->helper('url');
			redirect("/login");
		}
		$this->datas['is_login'] = true;
		$this->datas['user_nickname'] = $this->user_model->user_info('nickname');
		$this->datas['user_face'] = $this->user_model->user_info('face');
		$this->datas['user_id']   = $this->user_model->user_info('id');
	}
	
	/**
	 * 个人中心首页
	 */
	function index() {
		$this->load->view("common/header.php", $this->datas);
		$this->load->view("home/index.php");
		$this->load->view("common/upload_modal.php");
		$this->load->view("common/footer.php");
	}
	
	/**
	 * 登出
	 */
	public function logout() {
		$res = $this->user_model->logout();
	
		$this->load->helper('url');
		redirect('/');
	}
	
	/**
	 * 上传文件
	 */
	public function upload_file(){
		
	}
}