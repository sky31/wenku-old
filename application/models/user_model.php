<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 * @author heister
 *
 */
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 注册用户
	 * @param string $login_num
	 * @param string $password
	 * @param string $name
	 * @param string $nickname
	 * @param email $email
	 * @param number $is_verify
	 */
	public function add_user($login_num, $password, $name, $nickname, $email, $is_verify=0) {
		$data = array(
				'login_num' => $login_num,
				'password'  => $password,
				'name'      => $name,
				'nickname'  => $nickname,
				'email'     => $email,
				'is_verify' => $is_verify,
				'last_login_time' => time(),
				'last_login_ip'   => $this->input->ip_address()
 	 	);
		$str = $this->db->insert_string('user', $data);
		return $str;
	}
}