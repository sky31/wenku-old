DROP TABLE IF EXISTS `doc32_user`;
CREATE TABLE `doc32_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_num` varchar(20) NOT NULL COMMENT '登录号，学号或者工号',
  `email` varchar(254) NOT NULL COMMENT '邮箱', -- 邮箱，用于登录 
  `password` char(40) NOT NULL COMMENT '密码',  -- 密码，前端一次md5+slat，服务端一次sha1
  `name` varchar(24) NOT NULL COMMENT '名字',   -- 名字，不超过12个中文字符
  `nickname` varchar(25) NOT NULL COMMENT '昵称',
  `is_verify` TINYINT(2) NOT NULL DEFAULT NULL 0 COMMENT '是否通过认证',      -- 是否认证通过
  `last_login_time` INT DEFAULT NULL COMMENT '最后登录的时间',
  `last_login_ip`   INT DEFAULT NULL COMMENT '最后登录的ip',
  `is_del`   INT DEFAULT 0 COMMENT '是否删除(禁用)',
  PRIMARY KEY (`id`),
  unique key (`email`),
  unique key (`login_num`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `doc32_files`;
CREATE TABLE `doc32_` (
  `fid` varchar(25) NOT NULL AUTO_INCREMENT,   -- mongodb生成的ID
  `uid` int(11) not null,                      -- 用户的ID
  `fname`  varchar(20) NOT NULL COMMENT '文件名', -- 文件名
  `sumary` varchar(512) NOT NULL COMMENT '文章摘要',
  `jf`     SAMLLINT(4) NOT NULL DEFAULT 0 COMMENT '下载文章所需的积分',
  PRIMARY KEY (`id`),
  unique key (`email`),
  unique key (`login_num`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


