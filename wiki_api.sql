SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `api_admin_user`;
CREATE TABLE `api_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `user_password` char(32) NOT NULL COMMENT '密码',
  `user_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `user_reg_ip` varchar(25) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `user_mobile` varchar(20) DEFAULT '' COMMENT '用户手机',
  `last_login_ip` varchar(25) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `user_header_img` varchar(255) DEFAULT '' COMMENT '用户头像160*160',
  `user_group` int(10) unsigned DEFAULT '0' COMMENT '用户所属权限组 对应auth_group id',
  `user_status` tinyint(1) DEFAULT '1' COMMENT '用户状态 1正常 2禁用',
  `create_time` int(11) unsigned zerofill NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned zerofill NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Table structure for api_date_type
-- ----------------------------
DROP TABLE IF EXISTS `api_date_type`;
CREATE TABLE `api_date_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) DEFAULT NULL COMMENT '数据类型',
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='接口数据类型';

-- ----------------------------
-- Table structure for api_project
-- ----------------------------
DROP TABLE IF EXISTS `api_project`;
CREATE TABLE `api_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(30) DEFAULT '' COMMENT '项目名称',
  `project_icon` varchar(60) DEFAULT NULL COMMENT '项目图标',
  `project_about` varchar(60) DEFAULT '' COMMENT '项目简介',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='项目表';

-- ----------------------------
-- Table structure for api_project_api
-- ----------------------------
DROP TABLE IF EXISTS `api_project_api`;
CREATE TABLE `api_project_api` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '接口编号',
  `project_module_id` int(11) DEFAULT NULL COMMENT '模块id',
  `project_id` int(11) DEFAULT NULL COMMENT '项目id',
  `project_cate_id` int(11) DEFAULT '0' COMMENT '接口分类id',
  `api_url` varchar(100) DEFAULT NULL COMMENT '请求地址',
  `api_name` varchar(100) DEFAULT NULL COMMENT '接口名',
  `api_des` varchar(300) DEFAULT NULL COMMENT '接口描述',
  `api_parameter` text COMMENT '请求参数{所有的主求参数,以json格式在此存放}',
  `api_parameter_json` text COMMENT '请求参数的json格式',
  `api_re` text COMMENT '返回值{以json格式在此存放}',
  `api_re_json` text COMMENT '返回数据的json格式',
  `api_isdel` tinyint(4) unsigned DEFAULT '0' COMMENT '{0:正常,1:删除}',
  `project_url_id` int(11) NOT NULL COMMENT '项目url域名前缀id',
  `api_oeder` int(11) DEFAULT '0' COMMENT '排序(值越大,越靠前)',
  `request_type_id` int(11) NOT NULL COMMENT '请求类型id',
  `api_memo` text COMMENT '备注',
  `update_time` int(11) unsigned DEFAULT NULL COMMENT '最后操作时间',
  `create_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='接口明细表';

-- ----------------------------
-- Table structure for api_project_cate
-- ----------------------------
DROP TABLE IF EXISTS `api_project_cate`;
CREATE TABLE `api_project_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(60) DEFAULT NULL,
  `project_id` int(11) NOT NULL COMMENT '项目id',
  `project_module_id` int(11) DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='项目模块->分类表';

-- ----------------------------
-- Table structure for api_project_module
-- ----------------------------
DROP TABLE IF EXISTS `api_project_module`;
CREATE TABLE `api_project_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(60) DEFAULT NULL COMMENT '项目模块名',
  `project_id` int(11) DEFAULT NULL COMMENT '项目id',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='项目模块表';

-- ----------------------------
-- Table structure for api_project_url
-- ----------------------------
DROP TABLE IF EXISTS `api_project_url`;
CREATE TABLE `api_project_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT '项目id',
  `url_domain` varchar(60) NOT NULL COMMENT '项目域名',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='项目域名表';

-- ----------------------------
-- Table structure for api_request_type
-- ----------------------------
DROP TABLE IF EXISTS `api_request_type`;
CREATE TABLE `api_request_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) DEFAULT NULL COMMENT '请求类型',
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='接口请求类型';
