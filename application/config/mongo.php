<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * mongodb 的配置文件
 * 包括两个库，一个是原始的文件的，一个是转码的swf文件
 */

// 主机
$config['mongo']['host'] = 'localhost';
// 端口		
$config['mongo']['port'] = '27017';
// 文件的DB
$config['mongo']['filedb'] = 'xtudoc';
// swf的db
$config['mongo']['swfdb']  = 'xtudocswf';
