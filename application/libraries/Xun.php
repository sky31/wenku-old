<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 引入SDK
 */
require_once 'xunsearch_sdk/lib/XS.php';


/**
 * 自己写的Mongodb类库，只用于gridfs文件操作
 */
class MY_Xun{
	
	private $xs = NULL;
	
	/**
	 * 初始化
	 * @param string $app
	 */
	public function __construct() {
		$this->xs = new XS('demo');
	}
	
	/**
	 * 增加索引
	 * @param array $doc
	 */
	public function add($doc) {
		return $this->xs->index->add(new XSDocument($doc));
	}
	
	/**
	 * 更新指定的索引
	 * 
	 */
	public function del($primaryKey) {
		return $this->xs->index->del($primaryKey);
	}
	
	/**
	 * 更新制定的索引, $doc中必须包含主键
	 * @param array $doc
	 */
	public function update($doc) {
		return $this->xs->index->del($doc);
	}
	
	/**
	 *  清空索引 
	 */
	public function clean() {
		return $this->xs->index->clean();
	}
	
	/**
	 * 索引同步
	 */
	public function flush_index() {
		return $this->xs->index->flush_index();
	}
	
	
	/**
	 * 搜索
	 */
	public function getSearch() {
		return $this->xs->getSearch();
	}
	
	
	public function __destruct() {
		$this->xs = NULL;
	}
}