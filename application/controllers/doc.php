<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文库的主控制类
 * @author heimonsy
 *
 */
class Doc extends MY_Controller {
	
	/**
	 * 首页
	 */
	function index() {
		$this->load->view("common/header.php");
		$this->load->view("doc/index.php");
		$this->load->view("common/footer.php");
	}
	
	/**
	 * 文库页
	 */
	function lists() {
		$this->load->view("common/header.php");
		$this->load->view("doc/list.php");
		$this->load->view("common/footer.php");
	}
	
	/**
	 * 搜索
	 */
	function search() {
		$this->load->view("common/header.php");
		$this->load->view("doc/search.php");
		$this->load->view("common/footer.php");
	}
}