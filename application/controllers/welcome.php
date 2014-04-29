<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	function test () {
		
		$this->load->library("mongo");
		//echo FCPATH;
		//echo __FILE__.'b.doc';
		//exit();file_ge
		//exit();
		//echo $this->mongo->put(FCPATH.'b.doc');
		// octet-stream 表示任意二进制数据
		header("Content-Type: application/octet-stream");
		
		//处理中文文件名
		$filename = "你们好.doc";
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$encoded_filename = rawurlencode($filename);
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		} else if (preg_match("/Firefox/", $ua)) {
			header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
		} else {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}
		
		echo $this->mongo->get('53572bf35f896d8c27000030');
		//var_dump($this->mongo->remove('5356653b319ccc481400002f'));
	}
	
	function xuntest() {
		$this->load->library('xun');
		$doc = array(
			'pid'=>'1001',
			'subject'=>'2012湘潭大学三翼校园',
			'message'=>'这里是三翼校园，这里是三翼校园'
		);
		//var_dump($this->xun->del('1001'));
		//var_dump($this->xun->add($doc));
		$search = $this->xun->getSearch();
		$docs = $search->search('三翼');
		foreach($docs as $doc) {
			$subject = $search->highlight($doc->subject);
			echo $subject.'<br/>';
			$message = $search->highlight($doc->message);
			echo $message.'<br/>';
		}
	}
	
	function testSomething() {
		$this->load->library('User_check');
		var_dump($this->user_check->get_info('00960485', '666666'));
		//var_dump($this->user_check->get_info('2011960509', '123456'));
	}
	
	
	function model_test() {
		$this->load->model('user_model');
		
		echo $this->user_model->add_user(
				'2011960509', '123456', '郭子仟', '杜草', 'heimonsy@gmail.com', 1);
	}
	
	/**
	 * 
	 */
	function user_agent() {
		//var_($_REQUEST);
		print_r($_SERVER);
		//echo $this->input->user_agent();
	}
	
	/**
	 *
	 */
	function upload_test() {
		$this->load->library("mongo");
		echo $this->mongo->put(FCPATH.'b.doc', array(
				'preid'=>'sdfsdf',
				'page'=> 1
		));
	}
	
	/**
	 * 
	 */
	function do_upload() {
		$res = array();
		if(!empty($_FILES['Filedata'])) {
			$size     = $_FILES['Filedata']['size'];
			$filename = $_FILES['Filedata']['name'];
			if($size<1024*1024*30){
				//允许的文件类型
				$allow_file_types = array('doc','docx','ppt','pptx', 'xlsx', 'xls', 'pdf');
				$fileParts = pathinfo($filename);
				if(in_array($fileParts['extension'], $allow_file_types)) {
					move_uploaded_file($_FILES['Filedata']['tmp_name'], './uploads/'.$filename);
					$res['ret'] = 0;
					$res['info'] = 'ok';
				} else {
					$res['ret'] = 1;
					$res['info'] = $filename.' 文件超过30M';
				}
				
			} else {
				$res['ret'] = 1;
				$res['info'] = $filename.' 文件超过30M';
			}
		}
		$this->ajax_return($res);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */