<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 一些日常统计功能
 * @author heister
 *
 */
class Rank_model extends CI_Model {
	
	private $month_second = 2592000;
	private $week_second = 604800;
	private $time;
	
	public function __construct() {
		parent::__construct();
		$this->load->library('redis');
		
		$this->time = time();
	}
	
	/**
	 * 增加文件的周下载量
	 * @param unknown $fid
	 * @param number $incr
	 */
	public function incr_file_week($fid, $incr = 1) {
		$key = $this->week_key();
		if(!$this->redis->exists($key)) {
			// 但星期rank不存在，初始化一个
			$this->init_week_rank($key);
		}
		// 为文件增加下载量
		$this->redis->zincrby($key, $incr, $fid);
		
	}
	
	/**
	 * 增加文件月下载量
	 * @param unknown $fid
	 * @param number $incr
	 */
	public function incr_file_month($fid, $incr = 1) {
		$key = $this->month_key();
		if(!$this->redis->exists($key)) {
			// 但星期rank不存在，初始化一个
			$this->init_month_rank($key);
		}
		// 为文件增加下载量
		$this->redis->zincrby($key, $incr, $fid);
	}
	
	/**
	 * 获取月排行
	 */
	public function month_top(){
		$key = $this->month_key();
		if(!$this->redis->exists($key)) {
			// 不存在则初始化
			$this->init_month_rank($key);
		}
		return $this->top($key);
	}
	
	/**
	 * 获取上月排行
	 */
	public function prev_month_top(){
		$key = $this->prev_month_key();
		if(!$this->redis->exists($key)) {
			// 不存在则初始化
			$this->init_month_rank($key);
		}
		return $this->top($key);
	}
	
	/**
	 * 获取当个星期的排行
	 */
	public function week_top() {
		$key = $this->week_key();
		if(!$this->redis->exists($key)) {
			// 不存在则初始化
			$this->init_rank($key);
		}
		return $this->top($key);
	}
	
	/**
	 * 获取上个星期的排行
	 */
	public function prev_week_top() {
		$key = $this->prev_week_key();
		if(!$this->redis->exists($key)) {
			// 不存在则初始化
			$this->init_rank($key);
		}
		return $this->top($key);
	}
		
	/**
	 * 获取当天的日期的key
	 */
	private function week_key() {
		return 'R.W.'.date('Y-W', $this->time);
	}
	
	/**
	 * 获取前一个星期的key
	 */
	private function prev_week_key(){
		$time = $this->time - $this->week_second;
		return 'R.W.'.date('Y-W', $time);
	}
	
	/**
	 * 获取当天的日期的key
	 */
	private function month_key() {
		return 'R.M.'.date('Y-n', $this->time);
	}
	
	/**
	 * 获取前一个星期的key
	 */
	private function prev_month_key(){
		$y = date('Y', $this->time);
		$m = date('n', $this->time) - 1;
		if($m==0) {
			$m=12;
			$y-=1;
		}
		return "R.M.$y-$m";
	}
	
	/**
	 * 初始化星期的rank
	 */
	private function init_week_rank($key) {
		$this->init_rank($key);
		$this->redis->expire($key, $this->week_second * 3);
	}
	
	/**
	 * 初始化月份的rank
	 */
	private function init_month_rank($key) {
		$this->init_rank($key);
		$this->redis->expire($key, $this->month_second * 3);
	}
	
	
	/**
	 * 初始化KEY所代表的排行榜
	 * @param string $key
	 */
	private function init_rank($key){
		$this->load->model('files_model');
		// 提取出最近成功的5个文件
		$result = $this->redis->lrange('Q.SUCCESS', 0 , 5);
		$list = array();
		foreach($result as $fid) {
			$info = $this->files_model->file_info($fid, array('fname'));
			$info['fid'] = $fid;
			$this->redis->zincrby($key, 0, $fid);
		}
	}
	
	/**
	 * 获取某个键的前n个数，倒序
	 * @param unknown $key
	 */
	private function top($key, $nums=6){
		$this->load->model('files_model');
		
		$result = $this->redis->zrevrange($key, 0, $nums-1, 'WITHSCORES');
		$list = array();
		$count = count($result);
		for($i=0; $i<$count; $i+=2) {
			$info = $this->files_model->file_info($result[$i], array('fname'));
			$list[] = array(
					'fid'=>$result[$i],
					'fname' => $info['fname'], 
					'ranks'=>$result[$i+1]
			);
		}
		return $list;
	}
}