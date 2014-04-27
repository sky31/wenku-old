<?php
/**
 * @author Heister
 * @version 1.0
 */
class MyUrlFetch
{
	
	/**
	 * CURL 的变量
	 */
	private $ch = NULL;
	private $body;
	
	
	public function __construct() {
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR,  "");
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, "");
	}
	
	/**
	 * 执行url
	 * @param string $url
	 */
	public function get($url) {
		$content = '';
		curl_setopt($this->ch, CURLOPT_POST, false);
		curl_setopt($this->ch, CURLOPT_URL, $url);	
		$content = curl_exec($this->ch);

		$this->body = $content;
		return $content;
	}
	
	
	public function post($url) {
		$content = "";
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$content = curl_exec($this->ch);
		
		$this->body = $content;
		return $content;
	}
	
	/**
	 * 设置用于POST的数组
	 * @param Array $datas
	 */
	public function setPostArray($datas) {
		
		if($this->ch != NULL) {
			if(is_array($datas)) $datas = http_build_query($datas);
			//echo $datas.'|||';
			curl_setopt($this->ch, CURLOPT_POST, true);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $datas);
			
		} else {
			$datas = http_build_query($datas);
			$this->fetch->setOpt('post', $datas);
		}

	}
	
	public function getHttpCode(){
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}
	
	public function getResponseBody() {
		return $this->body;
	}
	
	public function close() {
		curl_close($this->ch);
	}
	
	public function setSSL(){
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	
}