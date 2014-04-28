<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 主要增加了ajax_return的支持
 * @author heimonsy
 *
 */
class MY_Controller extends CI_Controller{
	
	protected function ajax_return($data=array()) {
		if(is_array($data))
			$data = json_encode($data);
		
		$this->output
			->set_content_type('application/json')
			->set_output($data);
	}
}