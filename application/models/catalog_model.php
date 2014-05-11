<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 分类模型，前端需要手动修改doc.js和/doc/lists的模版
 * @author heister
 *
 */
class Catalog_model extends CI_Model {
	private $catalog_array = array(
			'math'=>'数学', 'literatrue'=> ' 文学', 'law'=>'法学', 'english'=>'英语', 'foreign'=> '小语种',
			'chemical'=>'化工', 'physical'=>'物理', 'histophilo'=>'哲史', 'political'=>'思想政治', 'ba'=>'工商管理',
			'economic'=>'经济/经融', 'newsspread'=>'新闻/传播', 'advfilm'=>'广告/影视', 'art'=>'艺术/美学',
			'music'=>'音乐', 'mechanics'=>'机械', 'material'=>'材料', 'civilbuild'=>'土木/建筑', 'computer'=>'计算机科学',
			'electronic'=>'电子技术', 'notice'=>'通知公告', 'table'=>'表格', 'other'=> '其他'
	);
	
	private $catalog_re_array = NULL;
	
	/**
	 * 判断键是否存在
	 */
	function have($key) {
		return empty($this->catalog_array[$key]);
	}
	
	/**
	 * 获取数组
	 */
	function get_array() {
		return $this->catalog_array;
	}
	
	/**
	 * 根据key获取值
	 */
	function get_value($key) {
		return $this->catalog_array[$key];		
	}
	
	/**
	 * 根据value获取key值
	 */
	function get_key($value) {
		if($this->catalog_re_array==NULL) {
			$this->re_catalog();
		}
		return $this->catalog_re_array[$value];
	}
	
	private function re_catalog() {
		$this->catalog_re_array = array();
		foreach($this->catalog_array as $key => $value) {
			$this->catalog_re_array[$value] = $key;
		}
	}
}