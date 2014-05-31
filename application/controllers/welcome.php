<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$h = $this->input->get("heimonsy");
		if($h!="admin") {
			exit("ERROR");
		}
	}

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
	/**
	 * 
	 */
	function xunsearch() {
		$this->load->library('xun');
		$search = $this->xun->getSearch();
		$docs = $search->setFuzzy()->search('什么 ');
		var_dump($docs);
		foreach($docs as $doc) {
			$subject = $search->highlight($doc->fname);
			echo $subject.'<br/>';
			$message = $search->highlight($doc->intro);
			echo $message.'<br/>';
		}
		echo "done!";
	}
	
	function rebuild() {
		$this->load->library('xun');
	}
	
	function xunclean() {
		$this->load->library('xun');
		var_dump($this->xun->clean());
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
			$res['info'] = array();
			$size     = $_FILES['Filedata']['size'];
			$filename = $_FILES['Filedata']['name'];
			$res['info']['fname'] = $filename;
			if($size<1024*1024*30){
				//允许的文件类型
				$allow_file_types = array('doc','docx','ppt','pptx', 'xlsx', 'xls', 'pdf');
				$fileParts = pathinfo($filename);
				if(in_array($fileParts['extension'], $allow_file_types)) {
					// 执行上传操作
					// move_uploaded_file($_FILES['Filedata']['tmp_name'], './uploads/'.$filename);
					$res['ret'] = 0;
					$res['info']['fid'] = 'fuck'.rand(0, 1000000);
				} else {
					$res['ret'] = 1;
					$res['info']['msg'] = '文件类型错误';
				}
				
			} else {
				$res['ret'] = 1;
				$res['info']['msg'] = '文件类型错误';	
			}
		}
		$this->ajax_return($res);
	}
	
	/**
	 * 
	 */
	function post_jf() {
		parse_str($this->input->post('data'), $data);
		print_r($data);
	}
	
	function new_test() {
		echo '<form action="/upload_file" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="Filedata" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>';
	}
	
	function upload_2(){
		$sid = $_GET['SID'];
		echo 'sid : '.$sid.'<br>';
		//session_unset();
		session_write_close();
		session_id($sid);
		session_start();
		
		echo 'IS_LOGIN : '.$_SESSION['IS_LOGIN'].'<br>';
	}
	
	
	/**
	 * 
	 */
	function test_rank(){
		$this->load->model('rank_model');
		$this->rank_model->test();
	}
	
	function test_file() {
		$this->load->model('files_model');
		var_dump($this->files_model->have_file(180436, "b351788ead48a4f5aaef888be44336d0"));
	}
	
	function rebuild_index(){
		$this->load->library('xun');
		$this->xun->clean();
		$this->load->database();
		$sql = 'select fid, fname, extension, intro,catalog from '
				.$this->db->dbprefix('files').' where is_set=1';
		$query = $this->db->query($sql);
		foreach($query->result_array() as $m){
			$doc =  array(
					'fid' => $m['fid'],
					'fname' => $m['fname'],
					'ext'   => substr($m['extension'], 0, 3), //只允许3个字符，docx只能保留成doc
					'intro' => $m['intro'],
					'catalog' => $m['catalog']
			);
			// 添加搜索索引
			try{
				$this->xun->add($doc);
			}catch (XSException $e) {
				log_message('error', '添加索引失败：'.$e->getTraceAsString());
			}
		}
	}
	
	/**
	 * 将错误队列里面的文件全部设置为-1
	 */
	function anayse_error() {
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */