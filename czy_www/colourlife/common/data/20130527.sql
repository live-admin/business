
-- --------------------------------------------------------

--
-- 表的结构 `app`
--

CREATE TABLE IF NOT EXISTS `app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `contact` longtext NOT NULL COMMENT '内容',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构ID',
  `client_count` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `download_url` varchar(255) NOT NULL DEFAULT '' COMMENT '下载链接',
  `developers` varchar(255) NOT NULL DEFAULT '' COMMENT '开发商家',
  `pic_title_url` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图片路径',
  `pic_detail_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `pic_contact_url` varchar(255) NOT NULL DEFAULT '' COMMENT '介绍图片路径',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `branch_id` (`branch_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_category`
--

CREATE TABLE IF NOT EXISTS `app_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '精品推荐分类名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '组织架构名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '部门类型',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级组织架构',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '管辖部门ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`),
  KEY `branch_id` (`branch_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `build`
--

CREATE TABLE IF NOT EXISTS `build` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '楼栋名称',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `community_id` (`community_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `id` char(128) NOT NULL COMMENT 'ID',
  `expire` int(11) DEFAULT NULL COMMENT '会话过期时间',
  `value` blob COMMENT '缓存数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='缓存';

-- --------------------------------------------------------

--
-- 表的结构 `community`
--

CREATE TABLE IF NOT EXISTS `community` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL DEFAULT '0' COMMENT '地区',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '小区名称',
  `domain` varchar(255) NOT NULL DEFAULT '' COMMENT '小区域名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `colorcloud_community` varchar(255) NOT NULL DEFAULT '' COMMENT '彩之云小区ID',
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `branch_id` (`branch_id`),
  KEY `domain` (`domain`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `community_employee_relation`
--

CREATE TABLE IF NOT EXISTS `community_employee_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '物业ID',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区ID',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `community_id` (`community_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `complain`
--

CREATE TABLE IF NOT EXISTS `complain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '投诉分类ID',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '被投诉小区ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '提交投诉的业主D',
  `content` text NOT NULL COMMENT '巡检的内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '提交投诉的时间',
  `accept_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理的员工',
  `accept_content` text NOT NULL COMMENT '接受处理投诉的内容',
  `accept_time` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理投诉的时间',
  `complete_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '完成处理的员工',
  `complete_content` text NOT NULL COMMENT '完成投诉处理的内容',
  `complete_time` int(11) NOT NULL DEFAULT '0' COMMENT '完成投诉处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `community_id` (`community_id`),
  KEY `customer_id` (`customer_id`),
  KEY `create_time` (`create_time`),
  KEY `accept_employee_id` (`accept_employee_id`),
  KEY `accept_time` (`accept_time`),
  KEY `complete_employee_id` (`complete_employee_id`),
  KEY `complete_time` (`complete_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `complain_category`
--

CREATE TABLE IF NOT EXISTS `complain_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '投诉分类名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT '键',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型',
  `value` text NOT NULL COMMENT '值，php序列化字符串',
  `update_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新的员工',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新的时间',
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(8) NOT NULL DEFAULT '' COMMENT '密码加盐码',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '业主姓名',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区ID',
  `build_id` int(11) NOT NULL DEFAULT '0' COMMENT '楼栋',
  `room` varchar(255) NOT NULL DEFAULT '' COMMENT '门牌号',
  `is_show_in_neighbor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在邻里中,一个地址只能显示一个,业主可以取消显示',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `mobile` (`mobile`),
  KEY `email` (`email`),
  KEY `community_id` (`community_id`),
  KEY `build_id` (`build_id`),
  KEY `is_show_in_neighbor` (`is_show_in_neighbor`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `customer_api_auth`
--

CREATE TABLE IF NOT EXISTS `customer_api_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secret` char(16) NOT NULL DEFAULT '' COMMENT '密钥',
  `create_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '创建IP',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `expire` int(11) NOT NULL DEFAULT '0' COMMENT '登录过期时间',
  `update_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '更新IP',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `data` blob NOT NULL COMMENT '会话数据',
  PRIMARY KEY (`id`),
  KEY `expire` (`expire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `discount`
--

CREATE TABLE IF NOT EXISTS `discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `contact` longtext NOT NULL COMMENT '内容',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构ID',
  `effective_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效时间',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(12) NOT NULL DEFAULT '' COMMENT '电话',
  `pic_title_url` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图片路径',
  `pic_detail_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `pic_contact_url` varchar(255) NOT NULL DEFAULT '' COMMENT '介绍图片路径',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `branch_id` (`branch_id`),
  KEY `effective_time` (`effective_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `discount_category`
--

CREATE TABLE IF NOT EXISTS `discount_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '打折信息分类名',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `discount_picture`
--

CREATE TABLE IF NOT EXISTS `discount_picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `desc` text NOT NULL COMMENT '备注内容',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片内容',
  `discount_id` int(11) NOT NULL DEFAULT '0' COMMENT '电子优惠券ID',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(8) NOT NULL DEFAULT '' COMMENT '密码加验码',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号码',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名字',
  `tel` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `oa_username` varchar(255) NOT NULL DEFAULT '' COMMENT 'OA账号',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT 'Email',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`),
  KEY `branch_id` (`branch_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `employee_api_auth`
--

CREATE TABLE IF NOT EXISTS `employee_api_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secret` char(16) NOT NULL DEFAULT '' COMMENT '密钥',
  `create_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '创建IP',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `expire` int(11) NOT NULL DEFAULT '0' COMMENT '登录过期时间',
  `update_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '更新IP',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `data` blob NOT NULL COMMENT '会话数据',
  PRIMARY KEY (`id`),
  KEY `expire` (`expire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `employee_session`
--

CREATE TABLE IF NOT EXISTS `employee_session` (
  `id` char(32) NOT NULL COMMENT 'ID',
  `expire` int(11) DEFAULT NULL COMMENT '会话过期时间',
  `data` blob COMMENT '会话数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会话';

-- --------------------------------------------------------

--
-- 表的结构 `facility`
--

CREATE TABLE IF NOT EXISTS `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `contact` longtext NOT NULL COMMENT '内容',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构ID',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` int(13) NOT NULL DEFAULT '0' COMMENT '电话',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'Logo',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `branch_id` (`branch_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `facility_category`
--

CREATE TABLE IF NOT EXISTS `facility_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '便民信息分类名',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `friend_id` int(11) NOT NULL DEFAULT '0' COMMENT '家人ID',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `friend_id` (`friend_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属商家的ID',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品类型，分为：加盟商品、业主商品、服务',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家类别，如:饮料、家电',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `brief` text NOT NULL COMMENT '商品别名',
  `description` mediumtext NOT NULL COMMENT '商品描述',
  `is_on_sale` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否上架',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `note` text NOT NULL COMMENT '卖家说明',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '加盟价格',
  `customer_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '业主价格',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品状态',
  `audit` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `is_deleted` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `update_employee_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `is_cheap` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核是否推荐,0=>带审核,1=>pass,2=>no pass',
  `cheap_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推荐价格',
  `start_cheap_time` int(11) NOT NULL DEFAULT '0' COMMENT '优惠开始时间',
  `end_cheap_time` int(11) NOT NULL DEFAULT '0' COMMENT '优惠结束时间',
  `display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `audit_cheap` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核优惠，0=>待审核，1=>pass,2=>no',
  `good_image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `type` (`type`),
  KEY `category_id` (`category_id`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `customer_price` (`customer_price`),
  KEY `state` (`state`),
  KEY `audit` (`audit`),
  KEY `is_cheap` (`is_cheap`),
  KEY `cheap_price` (`cheap_price`),
  KEY `start_cheap_time` (`start_cheap_time`),
  KEY `end_cheap_time` (`end_cheap_time`),
  KEY `display_order` (`display_order`),
  KEY `audit_cheap` (`audit_cheap`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods_category`
--

CREATE TABLE IF NOT EXISTS `goods_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品分类名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建人',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `display_order` (`display_order`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `inspect`
--

CREATE TABLE IF NOT EXISTS `inspect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '巡检分类ID',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构ID',
  `report_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '报告人ID',
  `content` text NOT NULL COMMENT '巡检的内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理巡检的员工',
  `accept_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理巡检的员工',
  `accept_content` text NOT NULL COMMENT '接受处理巡检的内容',
  `accept_time` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理巡检的时间',
  `complete_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '完成巡检处理的员工',
  `complete_content` text NOT NULL COMMENT '完成巡检处理的内容',
  `complete_time` int(11) NOT NULL DEFAULT '0' COMMENT '完成巡检处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `branch_id` (`branch_id`),
  KEY `report_employee_id` (`report_employee_id`),
  KEY `create_time` (`create_time`),
  KEY `accept_employee_id` (`accept_employee_id`),
  KEY `accept_time` (`accept_time`),
  KEY `complete_employee_id` (`complete_employee_id`),
  KEY `complete_time` (`complete_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `inspect_category`
--

CREATE TABLE IF NOT EXISTS `inspect_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '巡检分类名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `life_category`
--

CREATE TABLE IF NOT EXISTS `life_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '生活服务分类名',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(255) NOT NULL COMMENT '版本',
  `apply_time` int(11) DEFAULT NULL COMMENT '变更时间',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库变更';

-- --------------------------------------------------------

--
-- 表的结构 `notify`
--

CREATE TABLE IF NOT EXISTS `notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `contact` longtext NOT NULL COMMENT '内容',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构ID',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `branch_id` (`branch_id`),
  KEY `create_time` (`create_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `notify_category`
--

CREATE TABLE IF NOT EXISTS `notify_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '通知分类名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '卖家,处理该订单的商家,可以是加盟商、供应商、在线商家等',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '提供商品的商家(供应商)',
  `buyer_model` varchar(100) NOT NULL DEFAULT '' COMMENT '买家模型,关联买家的表名',
  `buyer_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家,加盟商/业主',
  `income_pay_id` int(11) NOT NULL DEFAULT '0' COMMENT '购物支付,平台收入',
  `expense_pay_id` int(11) NOT NULL DEFAULT '0' COMMENT '退货支付,平台支出',
  `buyer_name` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人姓名（默认填业主姓名，可以修改）',
  `buyer_address` text NOT NULL COMMENT '收货地址（默认填业主地址，可以修改）',
  `buyer_tel` varchar(100) NOT NULL DEFAULT '' COMMENT '收货电话（默认填业主电话，可以修改）',
  `buyer_postcode` varchar(100) NOT NULL DEFAULT '' COMMENT '收货邮编（非必选项）',
  `comment` text NOT NULL COMMENT '买家留言',
  `seller_contact` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家联系人名称',
  `seller_tel` varchar(100) NOT NULL DEFAULT '' COMMENT '卖家联系电话',
  `note` text NOT NULL COMMENT '卖家备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '销售时间',
  `create_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '买家IP',
  `income_pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态',
  PRIMARY KEY (`id`),
  KEY `sn` (`sn`),
  KEY `seller_id` (`seller_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `buyer_model` (`buyer_model`),
  KEY `buyer_id` (`buyer_id`),
  KEY `income_pay_id` (`income_pay_id`),
  KEY `expense_pay_id` (`expense_pay_id`),
  KEY `create_time` (`create_time`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `order_goods_relation`
--

CREATE TABLE IF NOT EXISTS `order_goods_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `order_log`
--

CREATE TABLE IF NOT EXISTS `order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `user_model` varchar(255) NOT NULL DEFAULT '' COMMENT '用户模型,关联用户的表名',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户,物业/商家/业主',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '改变后的状态',
  `note` text NOT NULL COMMENT '自动备注',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_model` (`user_model`),
  KEY `user_id` (`user_id`),
  KEY `create_time` (`create_time`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `others_fees`
--

CREATE TABLE IF NOT EXISTS `others_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(32) DEFAULT '' COMMENT 'sn',
  `model` varchar(20) DEFAULT '' COMMENT '操作对像',
  `object_id` int(11) DEFAULT '0' COMMENT '操作ID',
  `customer_id` int(11) DEFAULT '0' COMMENT '业主ID',
  `payment_id` int(11) DEFAULT '0' COMMENT '支付方式',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `note` text COMMENT '备注',
  `create_ip` varchar(20) DEFAULT '0' COMMENT '创建IP',
  `create_time` int(11) DEFAULT '0' COMMENT '时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  `pay_time` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `sn` (`sn`),
  KEY `model` (`model`),
  KEY `object_id` (`object_id`),
  KEY `customer_id` (`customer_id`),
  KEY `payment_id` (`payment_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `others_fees_log`
--

CREATE TABLE IF NOT EXISTS `others_fees_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `others_fees_id` int(11) NOT NULL COMMENT '单号id',
  `user_model` varchar(255) NOT NULL DEFAULT '' COMMENT '用户模型,关联用户的表名',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户,物业/商家/业主',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '改变后的状态',
  `note` text NOT NULL COMMENT '自动备注',
  PRIMARY KEY (`id`),
  KEY `others_fees_id` (`others_fees_id`),
  KEY `user_model` (`user_model`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `parking_fees`
--

CREATE TABLE IF NOT EXISTS `parking_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_number` varchar(20) DEFAULT '0' COMMENT '车牌',
  `parking_card_number` varchar(20) DEFAULT '0' COMMENT '停车卡号',
  `community_id` int(20) DEFAULT '0' COMMENT '小区ID',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '停车类别',
  `period` int(11) NOT NULL DEFAULT '0' COMMENT '缴费时间数',
  PRIMARY KEY (`id`),
  KEY `community_id` (`community_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `parking_fees_type`
--

CREATE TABLE IF NOT EXISTS `parking_fees_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT '' COMMENT '名称',
  `fees` decimal(10,2) DEFAULT '0.00' COMMENT '费用',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `community_id` (`community_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pay`
--

CREATE TABLE IF NOT EXISTS `pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL COMMENT '支付方式',
  `model` varchar(50) NOT NULL DEFAULT ' ' COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL DEFAULT '0' COMMENT '对象,订单/订购退货/物业缴费',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型,平台收入/支出',
  `income_model` varchar(50) NOT NULL DEFAULT ' ' COMMENT '收入对象模型',
  `income_id` int(11) NOT NULL DEFAULT '0' COMMENT '收入对象',
  `expense_model` varchar(50) NOT NULL DEFAULT ' ' COMMENT '支出模型',
  `expense_id` int(11) NOT NULL DEFAULT '0' COMMENT '支出对象',
  `statement_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算报表',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `offline_sn` int(11) NOT NULL DEFAULT '0' COMMENT '线下支付交易号',
  `amount` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '金额',
  PRIMARY KEY (`id`),
  KEY `payment_id` (`payment_id`),
  KEY `model` (`model`),
  KEY `object_id` (`object_id`),
  KEY `type` (`type`),
  KEY `income_model` (`income_model`),
  KEY `income_id` (`income_id`),
  KEY `expense_model` (`expense_model`),
  KEY `expense_id` (`expense_id`),
  KEY `statement_id` (`statement_id`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付码',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `description` text NOT NULL COMMENT '描述',
  `config` text NOT NULL COMMENT '评价内容',
  `property_discount` decimal(3,2) NOT NULL DEFAULT '1.00' COMMENT '物业费折扣',
  `parking_discount` decimal(3,2) NOT NULL DEFAULT '1.00' COMMENT '停车费折扣',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是线上支付',
  `is_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持手机',
  `display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '评价回复时间',
  `update_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `state` (`state`),
  KEY `is_online` (`is_online`),
  KEY `is_mobile` (`is_mobile`),
  KEY `display_order` (`display_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL DEFAULT '' COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL DEFAULT '0' COMMENT '对象,报修/投诉/巡检',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片URL',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `object_id` (`object_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `property_fees`
--

CREATE TABLE IF NOT EXISTS `property_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) DEFAULT '0' COMMENT '小区ID',
  `build` varchar(32) DEFAULT '' COMMENT '楼栋',
  `room` varchar(32) DEFAULT '' COMMENT '房间号',
  `customer_name` varchar(32) DEFAULT '' COMMENT '业主名称',
  `colorcloud_building` varchar(255) NOT NULL DEFAULT '' COMMENT '彩之云楼栋ID',
  `colorcloud_unit` varchar(255) NOT NULL DEFAULT '' COMMENT '彩之云业主单位ID',
  `colorcloud_bills` text NOT NULL COMMENT '彩之云欠费账单ID CSV',
  `colorcloud_order` varchar(255) NOT NULL DEFAULT '' COMMENT '彩之云订单ID',
  PRIMARY KEY (`id`),
  KEY `community_id` (`community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '地区名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级地区',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `repair`
--

CREATE TABLE IF NOT EXISTS `repair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '报修分类ID',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '业主ID',
  `content` text NOT NULL COMMENT '报修的内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '报修的内容',
  `accept_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理报修的员工',
  `accept_content` text NOT NULL COMMENT '接受处理报修的内容',
  `accept_time` int(11) NOT NULL DEFAULT '0' COMMENT '接受处理报修的时间',
  `complete_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '完成报修处理的员工',
  `complete_content` text NOT NULL COMMENT '完成报修处理的内容',
  `complete_time` int(11) NOT NULL DEFAULT '0' COMMENT '完成报修处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `community_id` (`community_id`),
  KEY `customer_id` (`customer_id`),
  KEY `create_time` (`create_time`),
  KEY `accept_employee_id` (`accept_employee_id`),
  KEY `accept_time` (`accept_time`),
  KEY `complete_employee_id` (`complete_employee_id`),
  KEY `complete_time` (`complete_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `repair_category`
--

CREATE TABLE IF NOT EXISTS `repair_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '报修分类名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `reserve`
--

CREATE TABLE IF NOT EXISTS `reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '业主',
  `content` text NOT NULL COMMENT '预订内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '预订时间',
  `create_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '预订IP',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `customer_id` (`customer_id`),
  KEY `create_time` (`create_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `reserve_reply`
--

CREATE TABLE IF NOT EXISTS `reserve_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserve_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '评论人,商家/业主',
  `content` text NOT NULL COMMENT '评论内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '预订时间',
  `create_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '预订IP',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `reserve_id` (`reserve_id`),
  KEY `type` (`type`),
  KEY `create_time` (`create_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL DEFAULT '0' COMMENT '对象,商家或商品',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '业主',
  `content` text NOT NULL COMMENT '评价内容',
  `reply` text COMMENT '评价回复内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '评价时间',
  `create_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '评价IP',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '评价回复时间',
  `update_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '评价回复IP',
  `audit` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态,待审/已审/审核不通过',
  `score` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '评价评分',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `object_id` (`object_id`),
  KEY `customer_id` (`customer_id`),
  KEY `create_time` (`create_time`),
  KEY `audit` (`audit`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `contact` longtext NOT NULL COMMENT '内容',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `shop_id` (`shop_id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `service_category`
--

CREATE TABLE IF NOT EXISTS `service_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `is_deleted` (`is_deleted`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商家类型',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商家名',
  `username` varchar(100) NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(8) NOT NULL DEFAULT '' COMMENT '密码加验码',
  `contact` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人名字',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号码',
  `tel` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `desc` text NOT NULL COMMENT '商家描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `update_employee_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `score` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '评价评分',
  `is_auto_chance_community` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否自动增加服务小区范围',
  `lat` decimal(10,6) DEFAULT NULL COMMENT '纬度',
  `lng` decimal(10,6) DEFAULT NULL COMMENT '经度',
  `logo_image` varchar(255) NOT NULL DEFAULT '' COMMENT 'Logo图片',
  `detail_image` varchar(255) NOT NULL DEFAULT '' COMMENT '详情图片',
  `map_image` varchar(255) NOT NULL DEFAULT '' COMMENT '地图图片',
  `map_thumb_image` varchar(255) NOT NULL DEFAULT '' COMMENT '地图缩略图片',
  `life_cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '生活服务分类ID',
  `life_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '周边特惠优先级',
  `is_benefit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是便民',
  `benefit_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '便民优先级',
  `is_house` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是房屋',
  `house_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '房屋优先级',
  `is_educate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是教育',
  `educate_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '教育优先级',
  `is_breakfast` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是营养早餐',
  `breakfast_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '营养早餐优先级',
  `is_rabbit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是小白兔',
  `rabbit_display_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '小白兔优先级',
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`),
  KEY `type` (`type`),
  KEY `username` (`username`),
  KEY `mobile` (`mobile`),
  KEY `is_auto_chance_community` (`is_auto_chance_community`),
  KEY `lat` (`lat`),
  KEY `lng` (`lng`),
  KEY `life_cate_id` (`life_cate_id`),
  KEY `life_display_order` (`life_display_order`),
  KEY `is_benefit` (`is_benefit`),
  KEY `benefit_display_order` (`benefit_display_order`),
  KEY `is_house` (`is_house`),
  KEY `house_display_order` (`house_display_order`),
  KEY `is_educate` (`is_educate`),
  KEY `educate_display_order` (`educate_display_order`),
  KEY `is_breakfast` (`is_breakfast`),
  KEY `breakfast_display_order` (`breakfast_display_order`),
  KEY `is_rabbit` (`is_rabbit`),
  KEY `rabbit_display_order` (`rabbit_display_order`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop_session`
--

CREATE TABLE IF NOT EXISTS `shop_session` (
  `id` char(32) NOT NULL COMMENT 'ID',
  `expire` int(11) DEFAULT NULL COMMENT '会话过期时间',
  `data` blob COMMENT '会话数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会话';

-- --------------------------------------------------------

--
-- 表的结构 `shop_community_relation`
--

CREATE TABLE IF NOT EXISTS `shop_community_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `community_id` (`community_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop_goods_community_relation`
--

CREATE TABLE IF NOT EXISTS `shop_goods_community_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售商品的商家，如加盟商、在线商家',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联小区',
  `is_on_sale` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否销售、上下架',
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `goods_id` (`goods_id`),
  KEY `community_id` (`community_id`),
  KEY `is_on_sale` (`is_on_sale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shop_relation`
--

CREATE TABLE IF NOT EXISTS `shop_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '提供商品的商家,供应商',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售商品的商家,加盟商',
  `community_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `seller_id` (`seller_id`),
  KEY `community_id` (`community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL DEFAULT '0' COMMENT '手机号码',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '随机4位数字',
  `token` varchar(16) NOT NULL DEFAULT '' COMMENT '下一步操作令牌',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`),
  KEY `code` (`code`),
  KEY `token` (`token`),
  KEY `status` (`status`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- 限制导出的表
--

--
-- 限制表 `authassignment`
--
ALTER TABLE `authassignment`
ADD CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `authitemchild`
--
ALTER TABLE `authitemchild`
ADD CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;