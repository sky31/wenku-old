<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 自己写的Mongodb类库，只用于gridfs文件操作
 */
class MY_Mongo{
	
	private $client  = NULL;
	private $filedb  = NULL;
	private $swf_grid = NULL;
	private $file_grid = NULL;
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
		$this->filedb = $this->select_db($this->config['filedb']);
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
		return $this->filedb;
	}
	
	/**
	 * file_grid
	 */
	public function file_grid() {
		if($this->file_grid==NULL) {
			$this->file_grid = $this->filedb->getGridFS();
		}
		return $this->file_grid;
	}
	
	/**
	 * 获取swf文件的DB
	 */
	public function swf_grid() {
		if($this->swf_grid==NULL) {
			$this->swf_grid = $this->filedb->getGridFS('swf');
		}
		return $this->swf_grid;
	}
	
	/**
	 * 获取一个新的mongoid
	 */
	public function new_mongo_id(){
		return new MongoId();
	}
}