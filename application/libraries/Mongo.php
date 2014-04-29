<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 自己写的Mongodb类库，只用于gridfs文件操作
 */
class MY_Mongo{
	
	private $client  = NULL;
	
	/**
	 * 进行初始化操作，配置文件为config/mongo.php
	 */
	public function __construct() {
		$ci = get_instance();
		$ci->load->config('mongo');
		$config = $ci->config->item('mongo');
		$server = 'mongodb://'.$config['host'].':'.$config['port'];
		
		$this->client = new MongoClient($server);
	}
	
	/**
	 * 选择数据库
	 */
	public function select_db($dbname) {
		return $this->client->selectDB($dbname);
	}
}