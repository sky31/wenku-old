<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 自己写的Mongodb类库，只用于gridfs文件操作
 */
class MY_Mongo{
	
	private $client  = NULL;
	private $filedb  = NULL;
	private $swfdb   = NULL;
	private $config  = NULL;
	
	/**
	 * 进行初始化操作，配置文件为config/mongo.php
	 */
	public function __construct() {
		$ci = get_instance();
		$ci->load->config('mongo');
		$config = $ci->config->item('mongo');
		$server = 'mongodb://'.$config['host'].':'.$config['port'];
		
		$this->client = new MongoClient($server);
		$this->config = $config;
	}
	
	/**
	 * 选择数据库
	 */
	public function select_db($dbname) {
		return $this->client->selectDB($dbname);
	}
	
	/**
	 * 获取文件的db
	 */
	public function file_db() {
		if($this->filedb==NULL) {
			$this->filedb = $this->select_db($this->config['filedb']);
		}
		return $this->filedb;
	}
	
	/**
	 * 获取swf文件的DB
	 */
	public function swf_db() {
		if($this->swfdb==NULL) {
			$this->swfdb = $this->select_db($this->config['swfdb']);
		}
		return $this->swfdb;
	}
	
	/**
	 * 获取一个新的mongoid
	 */
	public function new_mongo_id(){
		return new MongoId();
	}
}