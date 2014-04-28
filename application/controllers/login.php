<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文库的主控制类
 * @author heimonsy
 *
 */
class Login extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		if($this->user_model->is_login()) {
			$this->load->helper('url');
			redirect('/home');
		}
		
	}
	
	/**
	 * 登录页
	 */
	public function index() {
		$datas['is_login'] = false;
		$this->load->view("common/header.php", $datas);
		$this->load->view("login/index.php");
		$this->load->view("common/footer.php");
	}
	
	/**
	 * 用户登录
	 */
	public function post() {
		$login_num = $this->input->post('num');
		$password  = $this->input->post('pass');
		
		$res = $this->user_model->login($login_num, $password);
		$this->ajax_return($res);
	}
	
	/**
	 * 连入系统
	 */
	public function access() {
		$login_num = $this->input->post('num');
		$pass = $this->input->post('pass');
		
		$this->load->library('User_check');
		$res = $this->user_check->get_info($login_num, $pass);
		if($res['ret']==0) {
			$_SESSION['REG_NAME'] = $res['info'];
			$_SESSION['REG_NUM'] = $login_num;
			$_SESSION['REG_PASS'] = $pass;
			$this->ajax_return(
				array('ret'=>0, 'info'=>'ok')
				
			);
		} else {
			$this->ajax_return(
				$res
			);
		}
		
	}
	
	/**
	 * 注册用户
	 */
	public function register() {
		if( !empty($_SESSION['REG_NAME']) && !empty($_SESSION['REG_NUM'])) {
			if($_SERVER['REQUEST_METHOD']=='GET') {
				$datas['is_login'] = false;
				$datas['reg_name'] = $_SESSION['REG_NAME'];		
				$datas['reg_num'] = $_SESSION['REG_NUM'];
				$this->load->view("common/header.php", $datas);
				$this->load->view("login/register.php");
				$this->load->view("common/footer.php");
			} else {
				//处理POST数据
				$email     = $this->input->post('email', TRUE);
				$nickname  = $this->input->post('nickname', TRUE);
				$face      = $this->input->post('face');
				$name      = $_SESSION['REG_NAME'];
				$login_num = $_SESSION['REG_NUM'];
				$password  = $_SESSION['REG_PASS'];
				
				$this->user_model->add_user($login_num, $password, $name,
						$nickname, $face, $email, 1);
				$datas['login_num'] = $login_num;
				$datas['email']     = $email;
				$datas['is_login'] = false;
				
				//销毁session
				unset($_SESSION['REG_NAME']);
				unset($_SESSION['REG_NUM']);
				
				$this->load->view("common/header.php", $datas);
				$this->load->view("login/register_success.php");
				$this->load->view("common/footer.php");
			}
		} else {
			$this->load->helper('url');
			redirect('/login');
		}
	}
}