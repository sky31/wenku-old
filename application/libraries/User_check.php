<?php

require_once 'tools/MyUrlFetch.class.php';

/**
 * 检查用户
 * @author heister
 *
 */
class My_User_check {
	
	private $fetch;
	private $check_success;
	private $user;
	private $password;
	
	public function __construct() {
		$this->fetch = new MyUrlFetch();
		$this->check_success = false;
	}
	
	/**
	 * 获取用户信息
	 */
	public function get_info($user, $password) {
		return $this->_login($user, $password);
	}
	
	private function _login($user, $password) {
		// 登录教室管理平台
		$ret = $this->_login_as_student($user, $password);
	
		if($ret['ret']!=0) {
			$this->fetch->close();
			$this->fetch = NULL;
			$this->fetch = new MyUrlFetch();
			//登录教师管理平台
			$ret = $this->_login_as_teacher($user, $password);
		}
		return $ret;
	}
	
	/**
	 * 登录到学生平台
	 */
	private function _login_as_student($user, $password) {
		$this->fetch->get("http://202.197.224.134:8083/jwgl/login.jsp");
		$fields = array(
				'username'=>$user, 'password'=>$password,
				'identity'=>'student', 'role'=>'1');
		
		$this->fetch->setPostArray($fields);
		
		$response = iconv("GBK", "UTF-8//IGNORE",
				$this->fetch->post("http://202.197.224.134:8083/jwgl/logincheck.jsp"));
		$http_code = $this->fetch->getHttpCode()."";
		if($http_code[0]==2) {
			if(strpos($response, "密码错误") == FALSE) {
				$response = iconv("GBK", "UTF-8//IGNORE",
						$this->fetch->post("http://202.197.224.134:8083/jwgl/index1.jsp"));

				preg_match('/<font color=red>(.+?)同学<\/font>/', $response, $matchs);

				$res['ret']  = 0;
				$res['info'] = $matchs[1];
			} else {
				$res['ret']  = 1;
				$res['info'] = "帐号或密码错误。也可能是教务管理系统挂掉了，可以再试一下T_T..";
			}
		} else {
			$res['ret']  = 2;
			$res['info'] = "无法访问学校网站。可能是学校网站挂掉了T_T..";
		}
		return $res;
	}
	
	/**
	 * 登录到教师平台 //00960485
	 */
	private function _login_as_teacher($user, $password) {
		 $this->fetch->get("http://202.197.224.134:8083/jwgl/login.jsp");
		
		$fields = array(
				'username'=>$user, 'password'=>$password, 'identity'=>'teacher', 'role'=>2);
		
		$this->fetch->setPostArray($fields);
		
		$response = iconv("GBK", "UTF-8//IGNORE",
			$this->fetch->post("http://202.197.224.134:8083/jwgl/logincheck.jsp"));

		if(preg_match('/角色/',$response)) {
			$response = iconv("GBK", "UTF-8//IGNORE",
					$this->fetch->post("http://202.197.224.134:8083/jwgl/index1.jsp"));
			echo '<textarea>'.$response.'</textarea>';
			
			
			preg_match('/color=red>(.+?)老师/', $response, $matchs);
			
			return array(
				'ret'=>0,
				'info'=>$matchs[1]
			);
		} else {
			return array(
				'ret' => 1,
				'info' => '帐号或密码错误，无法进行接入认证，也可能是教务管理系统崩溃了。'
			);
		}
	}
}