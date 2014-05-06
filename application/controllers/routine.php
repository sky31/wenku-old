<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 一些事务处理，主要是文件上传后的处理队列工作
 * @author heimonsy
 *
 */
class Routine extends MY_Controller {
	
	/**
	 * 建立索引的通知
	 */
	function build_xunsearch_index_notify($token) {
		if($token=='heimonsy') {
			ignore_user_abort(true);
			set_time_limit(0);
			
		}
	} 
}