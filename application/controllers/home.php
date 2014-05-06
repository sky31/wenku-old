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
		// 此处非常重要
		// uploadify上传时用的是swf(as3的库)，没有将SESSION ID 传过来
		// 所以必须这样认证
		if(!empty($_POST['DOC_SESS_ID'])) {
			session_write_close(); // 需要把之前的session关闭掉（注意，关闭不是销毁）
			session_id($_POST['DOC_SESS_ID']);
			session_start();
		}
		
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
		$res = array();

		if(!empty($_FILES['Filedata'])) {
			$res['info'] = array();
			$size     = $_FILES['Filedata']['size'];
			$filename = $_FILES['Filedata']['name'];
			$res['info']['fname'] = $filename;
			if($size<1024*1024*30){
				//允许的文件类型
				$allow_file_types = array('doc','docx','ppt','pptx', 'xlsx', 'xls', 'pdf');
				$fileParts = $this->path_info($filename);
				if(in_array($fileParts['extension'], $allow_file_types)) {
					//通过文件头部判断类型（防止改后缀名乱传）
					$allow_heads = array(
							"\xd0\xcf\x11\xe0\xa1",
							"\x25\x50\x44\x46\x2d",
							"\x50\x4b\x03\x04\x14"
					);
					$ftmp = fopen($_FILES['Filedata']['tmp_name'], 'r');
					$head5bit = fread($ftmp, 5);
					rewind($ftmp);
					fclose($ftmp);
					//log_message('error', $head5bit);
					if(in_array($head5bit, $allow_heads)) {
						// 执行上传操作
						// move_uploaded_file($_FILES['Filedata']['tmp_name'], './uploads/'.$filename);
						$this->load->model('files_model');
							
						$fid = $this->files_model->store_file($_FILES['Filedata']['tmp_name'], $filename);
						// 将文件上传的结果存储到数据库
						log_message('error', $fileParts['filename'].'$$'.$filename);
						$this->files_model->add_file(
								$this->user_model->user_info('id'), $fid, $fileParts['filename'], $fileParts['extension']);
							
						$res['ret'] = 0;
						$res['info']['fid'] = $fid.'';
						
					} else {
						$res['ret'] = 1;
						$res['info']['msg'] = '文件类型错误(B)';
					}
					
				} else {
					$res['ret'] = 1;
					$res['info']['msg'] = '文件类型错误';
				}
				
			} else {
				$res['ret'] = 1;
				$res['info']['msg'] = '文件类型错误';	
			}
		}
		return $this->ajax_return($res);
	}
	
	/**
	 * 设置文件信息
	 */
	function set_file_info() {
		// 将收到的Jquery序列化的内容反序列化
		parse_str($this->input->post('data'), $post_array);

		$data = array();
		foreach($post_array as $key=>&$value) {
			if(preg_match('/^(.*)_jf$/i', $key, $matchs)) {
				@$data[$matchs[1]]['jf'] = $value;
			} else if(preg_match('/^(.*)_catalog$/i', $key, $matchs)) {
				@$data[$matchs[1]]['catalog'] = $value;
			} else if(preg_match('/^(.*)_intro$/i', $key, $matchs)) {
				@$data[$matchs[1]]['intro'] =  $this->security->xss_clean($value);
			}
		}
		$this->load->model('files_model');
		$this->load->library('Xun');
		$this->load->library('redis');
		foreach ($data as $key=>&$value){
			$value['is_set'] = 1;
			//更新配置信息
			$this->files_model->update_file($key, $value);
			//检索出文件信息并添加索引
			$finfo = $this->files_model->file_info($key, array('fname', 'extension'));
			$doc =  array(
				'fid' => $key,
				'fname' => $finfo['fname'],
				'ext'   => substr($finfo['extension'], 0, 3), //只允许3个字符，docx只能保留成doc
				'intro' => $value['intro'],
				'catalog' => $value['catalog']
			);
			// 添加搜索索引
			try{
				$this->xun->add($doc);
			}catch (XSException $e) {
				log_message('error', '添加索引失败：'.$e->getTraceAsString());
			}
			// 设置好了之后应该要添加到转换队列里面去
			$this->files_model->push_trans_queue($key);
		}
		$this->ajax_return(array('ret'=>0, 'info'=>'ok'));
	}
	
	/**
	 * 处理文件信息
	 * @param unknown $filepath
	 * @return multitype:string
	 */
	private function path_info($filepath)
	{
		$path_parts = array();
		$path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";
		$path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");
		$path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
		$path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");
		return $path_parts;
	}
}