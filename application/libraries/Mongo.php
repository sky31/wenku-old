<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 自己写的Mongodb类库，只用于gridfs文件操作
 */
class MY_Mongo{
	
	private $client  = NULL;
	private $db      = NULL;
	private $girdfs  = NULL;
	
	/**
	 * 进行初始化操作，配置文件为config/mongo.php
	 */
	public function __construct() {
		
		$ci = get_instance();
		$ci->load->config('mongo');
		$config = $ci->config->item('mongo');
	
		$server = 'mongodb://'.$config['host'].':'.$config['port'];
		
		$this->client = new MongoClient($server);
		$this->db     = $this->client->selectDB($config['database']);
		$this->gridfs = $this->db->getGridFS();
	}
	
	/**
	 * 上传文件
	 */
	public function put($file) {
		$id = $this->gridfs->put($file);
		return $id;
	}
	
	/**
	 * 下载文件
	 */
	public function get($id) {
		$oid = new MongoId($id);
		$files = $this->gridfs->findOne(array('_id'=>$oid));
		return $files->getBytes();
	}
	/**
	 * 移除文件
	 */
	public function remove($id) {
		return $this->gridfs->remove(array('_id'=>new MongoId($id)));
	}
}