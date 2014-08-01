<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文库的个人中心主控制类
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
	function lists($page=1) {
		$this->datas['action'] = 'index';
		$this->datas['user_jf'] = 
			$this->user_model->user_jf($this->datas['user_id']);
		$this->datas['user_doc_count'] = 
			$this->user_model->user_doc_count($this->datas['user_id']);
		$this->datas['user_collection_nums'] =
			$this->user_model->user_collection_nums($this->datas['user_id']);
		
		// 获取用户文件列表
		$this->load->model('files_model');
		$this->datas['list'] = $this->files_model->user_file_list($this->datas['user_id'], $page);
		
		//进行分页
		$this->load->library('pages');
		$this->datas['pagination'] = $this->pages->create_links(
				'/home/lists/',
				$this->datas['user_doc_count'], 3, $page
		);
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('home/home_top.php');
		$this->load->view('home/index.php');
		$this->load->view('home/home_bottom.php');
		$this->load->view('common/set_file_info_modal.php');
		$this->load->view('common/upload_modal.php');
		$this->load->view('common/footer.php');
	}
	
	/**
	 * 用户收藏的文件信息
	 */
	function collection($page=1) {
		$this->datas['action'] = 'collection';
		$this->datas['user_jf'] =
			$this->user_model->user_jf($this->datas['user_id']);
		$this->datas['user_doc_count'] =
			$this->user_model->user_doc_count($this->datas['user_id']);
		$this->datas['user_collection_nums'] =
			$this->user_model->user_collection_nums($this->datas['user_id']);
		
		// 获取用户收藏文件列表
		$this->load->model('collection_model');
		$this->datas['list'] = 
			$this->collection_model->collection_list($this->datas['user_id'], $page);
		
		//进行分页
		$this->load->library('pages');
		$this->datas['pagination'] = $this->pages->create_links(
				'/home/collection/',
				$this->datas['user_collection_nums'], 3, $page
		);
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('home/home_top.php');
		$this->load->view('home/collection.php');
		$this->load->view('home/home_bottom.php');
		$this->load->view('common/upload_modal.php');
		$this->load->view('common/footer.php');
	}
	
	
	/**
	 * 修改密码
	 */
	function cpass() {
		$this->datas['action'] = 'cpass';
		$this->datas['user_jf'] =
		$this->user_model->user_jf($this->datas['user_id']);
		$this->datas['user_doc_count'] =
		$this->user_model->user_doc_count($this->datas['user_id']);
		$this->datas['user_collection_nums'] =
		$this->user_model->user_collection_nums($this->datas['user_id']);
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('home/home_top.php');
		$this->load->view('home/cpass.php');
		$this->load->view('home/home_bottom.php');
		$this->load->view('common/upload_modal.php');
		$this->load->view('common/footer.php');
	}
	
	
	/**
	 * 修改用户密码
	 */
	function change_pass() {
		$old_pass = $this->input->post('oldpass');
		$new_pass = $this->input->post('newpass');
		
		$info = $this->user_model->user_info(array(
			'password'
		), $this->datas['user_id']);
		
		if(md5($old_pass) == $info['password']) {
			$ret = $this->user_model->update($this->datas['user_id'], array(
				'password' => md5($new_pass)
			));
			
			if($ret){
				$this->ajax_return(array('ret'=>0, 'info'=>'修改成功'));
			} else {
				$this->ajax_return(array('ret'=>1, 'info'=>'修改失败'));
			}
			
		} else {
			$this->ajax_return(array('ret'=>1, 'info'=>'旧密码不正确'));
		}
	}
	
	
	/**
	 * 修改用户信息
	 */
	function edit() {
		$this->datas['action'] = 'edit';
		$this->datas['user_jf'] =
			$this->user_model->user_jf($this->datas['user_id']);
		$this->datas['user_doc_count'] =
			$this->user_model->user_doc_count($this->datas['user_id']);
		$this->datas['user_collection_nums'] =
			$this->user_model->user_collection_nums($this->datas['user_id']);
		
		$info = $this->user_model->user_info(array('name', 'email'), $this->datas['user_id']);
		$this->datas['user_name'] = $info['name']; 
		$this->datas['user_email'] = $info['email'];
		
		$this->load->view('common/header.php', $this->datas);
		$this->load->view('home/home_top.php');
		$this->load->view('home/edit.php');
		$this->load->view('home/home_bottom.php');
		$this->load->view('common/upload_modal.php');
		$this->load->view('common/footer.php');
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
// 			echo ini_get('upload_max_filesize');
// 			print_r($_FILES);
// 			exit();
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
						$this->load->model('files_model');
						
						$md5 = strtolower(md5_file($_FILES['Filedata']['tmp_name']));
						// 判断文件是否存在
						if(!$this->files_model->have_file($size, $md5)) {
							// 如果大小和md5相同的文件不存在
							// 执行上传操作
							// move_uploaded_file($_FILES['Filedata']['tmp_name'], './uploads/'.$filename);
								
							$fid = $this->files_model->store_file($_FILES['Filedata']['tmp_name'], $filename);
							// 将文件上传的结果存储到数据库
							$this->files_model->add_file(
									$this->datas['user_id'], $fid, $fileParts['filename'], $fileParts['extension'], $size);
								
							$res['ret'] = 0;
							$res['info']['fid'] = $fid.'';
							// 给用户增加积分
							$this->user_model->incr_jf($this->datas['user_id'], 2);
							// 增加用户的文件数统计
							$this->user_model->incr_doc_count($this->datas['user_id']);
							
							
						} else {
							$res['ret'] = 1;
							$res['info']['msg'] = '文件已经存在';
						}
						
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
        else {
            log_message('error', '上传内容为空');
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
				$this->xun->del($key);
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
	 * 收藏新的文件
	 */
	public function add_collection_file($fid=NULL) {
		if( $fid=== NULL || $fid=='' ){
			$this->ajax_return(array('ret'=>-1, 'info'=>'fid不能为空'));
		}
		$this->load->model('collection_model');

		$uid = $this->datas['user_id'];
		if($this->collection_model->have($uid, $fid)) {
			$this->ajax_return(array(
				'ret'=>-1,
				'info'=>'文件已经收藏过'
			));
		} else {
			$this->collection_model->add($uid, $fid);
			$this->ajax_return(array(
					'ret'=>0,
					'info'=>'文件收藏成功'
			));
		}
		
	}
	
	
	/**
	 * 修改用户信息
	 */
	function change_user_info() {
		$nickname  = $this->input->post('nickname', true);
		$face      = $this->input->post('face', true);
		$email     = $this->input->post('email', true);
		$ret = $this->user_model->update($this->datas['user_id'], array(
				'nickname' => $nickname,
				'face'     => $face,
				'email'    => $email
		));
	
		if($ret) {
			$this->ajax_return(array('ret'=>0, 'info'=>'修改成功'));
		} else {
			$this->ajax_return(array('ret'=>1, 'info'=>'修改失败'));
		}
	}
	
	/**
	 * 确认文件信息和用户信息
	 */
	public function down_make_sure($fid=NULL) {
		if( $fid=== NULL || $fid=='' ){
			$this->ajax_return(array('ret'=>-1, 'info'=>'fid不能为空'));
		}
		
		$this->load->model('files_model');
		$this->load->model('user_model');
		$file_info = $this->files_model->file_info($fid, array('fname, size, jf, extension'));
		$user_jf   = $this->user_model->user_jf($this->datas['user_id']);
		
		if($file_info==NULL) {
			$this->ajax_return(array(
					'ret'=>1,
					'info'=> '文件不存在',
			));
		} else {
			
			$this->ajax_return(array(
					'ret'=>0,
					'info'=> array(
							'fileName' => $file_info['fname'].'.'.$file_info['extension'],
							'fileSize' => $file_info['size'],
							'fileJF'   => $file_info['jf'],
							'userJF'   => $user_jf,
							'have_down' => $this->user_model->have_down(
									$this->datas['user_id'], $fid)
					)
			));
		}
		
	}
	
	/**
	 * 下载文件
	 */
	public function down_file($fid=NULL) {
		if($fid===null) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		$uid = $this->datas['user_id'];
		
		// 判断用户积分是否足够
		$this->load->model('user_model');
		$this->load->model('files_model');
		$user_jf = $this->user_model->user_jf($uid);
		$file_info = $this->files_model->file_info($fid, 'jf, uid');
		$file_jf = $file_info['jf'];
		if($user_jf<$file_jf) {
			show_error( '你的积分不足，<a href="/home">上传文件获取积分</a>', 403, '没有下载权限');
		}
		

		$this->load->library("mongo");

		//如果不 配置此项，将可能抛出Mongo异常
		$grid = $this->mongo->file_grid();
		ini_set('mongo.long_as_object', 1);
		$file = $grid->get(new MongoId($fid));
		if($file==NULL) {
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		// 增加上传者积分,自己下载自己的文件不加分
		if($file_info['uid'] != $uid){
			$this->user_model->incr_jf($file_info['uid'], $file_jf+2);
		}
		
		// 扣除用户积分
		if($file_jf!=0 && !$this->user_model->have_down($uid, $fid)) {
			// 积分不为零且没有下载过才需要扣除积分
			$this->user_model->add_down_file($uid, $fid);
			$this->user_model->incr_jf($uid, $file_jf * -1);
		}
		
		// 统计下载
		$this->files_model->incr_down_times($fid, 1);
		$this->load->model('rank_model');
		$this->rank_model->incr_file_week($fid);
		$this->rank_model->incr_file_month($fid);
		
		header("Content-Type: application/octet-stream");
		$filename = $file->getFilename();
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$encoded_filename = rawurlencode($filename);
		//处理中文文件名的情况
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		} else if (preg_match("/Firefox/", $ua)) {
			header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
		} else {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}

		echo $file->getBytes();
	}
	
	/**
	 * 获取用户个人的文件信息
	 */
	function get_file_info($fid=NULL){
		if($fid===NULL){
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		$this->load->model('files_model');
		$fileinfo = $this->files_model->file_info($fid, array('fname', 'extension', 'uid', 'jf', 'catalog', 'intro'));
		$res = array();
		if($fileinfo != NULL){
			if($fileinfo['uid'] == $this->datas['user_id']) {
				$res['ret'] = 0;
				$res['info'] = array(
						'filename' => $fileinfo['fname'].'.'.$fileinfo['extension'],
						'jf'       => $fileinfo['jf'],
						'catalog'  => $fileinfo['catalog'],
						'intro'  => $fileinfo['intro']
				);
			} else {
				$res['ret'] = 1;
				$res['info'] = array('msg'=>'不是你的文件无法编辑');
			}
		} else {
			$res['ret'] = 1;
			$res['info'] = array('msg'=>'文件不存在');
		}
		$this->ajax_return($res);
	}
	
	/**
	 * 移除一个用户的收藏文档
	 */
	public function remove_collection($fid=NULL) {
		if($fid===NULL){
			show_error( '您所请求的文档没有找到，<a href="/">前文库首页搜索</a>', 404, '文档未找到');
		}
		
		$this->load->model('collection_model');
		if($this->collection_model->remove($this->datas['user_id'], $fid)) {
			$this->ajax_return(array('ret'=>0, 'info'=>'ok'));
		} else {
			$this->ajax_return(array('ret'=>1, 'info'=>'没有该收藏文档'));
		}
		
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
