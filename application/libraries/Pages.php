<?php defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Pages{
	private $ci;
	public function __construct() {
		$this->ci = get_instance();
		
	}
	
	function create_links($url, $total_rows, $segment, $cur_page, $per_page=20) {
		$this->ci->load->library('pagination');
		$total_page = $total_rows/20;
		$config = array(
				'base_url'   => $url,
				'total_rows' => $total_rows,
				'per_page'   => $per_page,
				'uri_segment' => $segment,
				'use_page_numbers' => TRUE,
				'num_links'        =>  ($cur_page<5 || $total_page-$cur_page<5) ?
											9-( $cur_page<5? $cur_page:$total_page-$cur_page): 4,
				'full_tag_open' => '<ul class="pagination pagination-sm">',
				'full_tag_close' => '</ul>',
				'first_link' => false,
				'last_link'  => false,
				'next_link'  => '下一页',
				'prev_link'  => '上一页',
				'cur_tag_open'=> '<li class="active"><a href="#">',
				'cur_tag_close'=> '</a></li>',
				'num_tag_open' => '<li>',
				'num_tag_close' => '</li>',
				'next_tag_open' => '<li>',
				'next_tag_close' => '</li>',
				'prev_tag_open' => '<li>',
				'prev_tag_close' => '</li>',
				'first_tag_open' => '<li>',
				'first_tag_close' => '</li>',
				'last_tag_open' => '<li>',
				'last_tag_close' => '</li>'
 		
		);
		$this->ci->pagination->initialize($config);
		return $this->ci->pagination->create_links();
	}
}