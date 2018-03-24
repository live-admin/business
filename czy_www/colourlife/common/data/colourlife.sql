-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 04 月 24 日 12:05
-- 服务器版本: 5.1.26
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `colourlife`
--

-- --------------------------------------------------------

--
-- 表的结构 `api_auth`
--

CREATE TABLE IF NOT EXISTS `api_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `secret` char(16) NOT NULL COMMENT '密钥',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '创建IP',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `update_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '更新IP',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `last_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '最后访问IP',
  `expire` int(11) NOT NULL DEFAULT '0' COMMENT '登录过期时间',
  `data` blob NOT NULL COMMENT '会话数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='API认证';

-- --------------------------------------------------------

--
-- 表的结构 `app`
--

CREATE TABLE IF NOT EXISTS `app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `url` varchar(200) NOT NULL COMMENT '文件路径',
  `image` varchar(255) NOT NULL COMMENT '图片',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `create_employee_id` int(11) NOT NULL COMMENT '创建人',
  `description` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='精品推荐';

-- --------------------------------------------------------

--
-- 表的结构 `app_stat`
--

CREATE TABLE IF NOT EXISTS `app_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL COMMENT '精品推荐',
  `click_count` int(11) NOT NULL COMMENT '用户点击数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='精品推荐统计';

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '分类',
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `description` text NOT NULL COMMENT '内容',
  `create_employee_id` int(11) NOT NULL COMMENT '创建人/作者',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `type` tinyint(3) NOT NULL COMMENT '文章类型',
  `is_open` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否公开',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `file_url` varchar(255) DEFAULT NULL COMMENT '文件或图片路径',
  `display_order` int(11) DEFAULT '0' COMMENT '显示顺序,置顶',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章';

-- --------------------------------------------------------

--
-- 表的结构 `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '上级分类',
  `article_category_name` varchar(200) NOT NULL COMMENT '分类名',
  `display_order` int(11) NOT NULL COMMENT '显示顺序',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类';

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET utf8,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) NOT NULL COMMENT '组织架构名称',
  `parent_id` int(11) NOT NULL COMMENT '组织架构的父级',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `build`
--

CREATE TABLE IF NOT EXISTS `build` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '楼栋名',
  `community_id` int(11) NOT NULL COMMENT '小区',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `community`
--

CREATE TABLE IF NOT EXISTS `community` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `region_id` int(11) NOT NULL COMMENT '地区',
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `name` varchar(200) NOT NULL COMMENT '小区名称',
  `domain` varchar(200) NOT NULL COMMENT '小区域名',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `other_id` int(11) NOT NULL COMMENT '第三方系统的小区标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `community_app_relation`
--

CREATE TABLE IF NOT EXISTS `community_app_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL COMMENT '小区',
  `app_id` int(11) NOT NULL COMMENT '精品推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区关联精品推荐';

-- --------------------------------------------------------

--
-- 表的结构 `community_article_relation`
--

CREATE TABLE IF NOT EXISTS `community_article_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL COMMENT '小区',
  `article_id` int(11) NOT NULL COMMENT '文章',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区文章关联';

-- --------------------------------------------------------

--
-- 表的结构 `community_customer_property`
--

CREATE TABLE IF NOT EXISTS `community_customer_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL COMMENT '小区',
  `customer_id` int(11) NOT NULL COMMENT '业主',
  `name` varchar(20) NOT NULL COMMENT '业主姓名',
  `card_id` varchar(20) NOT NULL COMMENT '身份证号码',
  `update_time` int(11) NOT NULL COMMENT '填写时间',
  `update_ip` varchar(20) NOT NULL COMMENT '填写IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区业主物业';

-- --------------------------------------------------------

--
-- 表的结构 `community_employee_relation`
--

CREATE TABLE IF NOT EXISTS `community_employee_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `employee_id` int(11) NOT NULL COMMENT '物业用户',
  `community_id` int(11) NOT NULL COMMENT '小区',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `complain`
--

CREATE TABLE IF NOT EXISTS `complain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL COMMENT '小区',
  `customer_id` int(11) NOT NULL COMMENT '业主',
  `accept_employee_id` int(11) NOT NULL COMMENT '接受处理的物业用户',
  `complete_employee_id` int(11) NOT NULL COMMENT '完成处理的物业用户',
  `content` text NOT NULL COMMENT '巡检的内容',
  `create_time` int(11) NOT NULL COMMENT '报告时间',
  `accept_time` int(11) NOT NULL COMMENT '接受处理的时间',
  `complete_time` int(11) NOT NULL COMMENT '完成处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `accept_content` text NOT NULL COMMENT '接受处理的说明',
  `complete_content` text NOT NULL COMMENT '完成处理的说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投诉';

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `key` int(11) NOT NULL COMMENT '键',
  `value` varchar(200) NOT NULL COMMENT '值，php序列化字符串',
  `update_employee_id` int(11) NOT NULL COMMENT '最后更新的物业用户',
  `update_time` int(11) NOT NULL COMMENT '最后更新的时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置';

-- --------------------------------------------------------

--
-- 表的结构 `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `community_id` int(11) NOT NULL COMMENT '小区',
  `build_id` int(11) NOT NULL COMMENT '楼栋',
  `room` int(11) NOT NULL COMMENT '门牌号',
  `username` varchar(50) NOT NULL COMMENT '账号,可以修改,域名规则',
  `password` char(32) NOT NULL COMMENT '登陆密码',
  `salt` char(8) NOT NULL COMMENT '支付密码',
  `name` varchar(50) NOT NULL COMMENT '业主姓名',
  `mobile` varchar(15) NOT NULL COMMENT '业主电话',
  `email` varchar(25) NOT NULL COMMENT 'Email',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `last_time` int(11) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(20) NOT NULL COMMENT '登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `is_show_in_neighbor` tinyint(2) NOT NULL COMMENT '是否显示在邻里中,一个地址只能显示一个,业主可以取消显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `parent_id` int(11) NOT NULL COMMENT '上级部门',
  `name` varchar(200) NOT NULL COMMENT '部门名称',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `department_id` int(11) NOT NULL COMMENT '部门',
  `username` varchar(255) NOT NULL COMMENT '账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(8) NOT NULL COMMENT '密码',
  `mobile` varchar(15) NOT NULL COMMENT '私人电话',
  `tel` varchar(15) NOT NULL COMMENT '办公电话',
  `name` varchar(255) NOT NULL COMMENT '名字',
  `oa_username` varchar(255) NOT NULL COMMENT 'OA账号',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `create_time` int(11) NOT NULL COMMENT '后台创建时间',
  `last_time` int(11) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '最后登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `costumer_id` int(11) NOT NULL COMMENT '业主',
  `friend_id` int(11) NOT NULL COMMENT '业主好友',
  `note` varchar(255) NOT NULL COMMENT '好友备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0待通过，1已通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友,业主关系';

-- --------------------------------------------------------

--
-- 表的结构 `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `model` varchar(50) NOT NULL COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL COMMENT '对象,商品/商家',
  `url` varchar(255) NOT NULL COMMENT '图片URL',
  `description` varchar(255) NOT NULL COMMENT '图片描述',
  `thumb_url` varchar(255) NOT NULL COMMENT '缩略图URL',
  `original_url` varchar(255) NOT NULL COMMENT '原始图URL',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `shop_id` int(11) NOT NULL COMMENT '商家',
  `category_id` int(11) NOT NULL COMMENT '商品分类',
  `name` varchar(50) NOT NULL COMMENT '商品名称',
  `brief` text NOT NULL COMMENT '商品简述',
  `description` text NOT NULL COMMENT '商品描述',
  `is_on_sale` int(11) NOT NULL COMMENT '是否销售,上下架,针对供应商销售给加盟商',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '最后更新时间',
  `note` text NOT NULL COMMENT '卖家备注',
  `shop_price` decimal(10,0) NOT NULL COMMENT '加盟商价格',
  `customer_price` decimal(10,0) NOT NULL COMMENT '业主价格',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `audit` tinyint(1) NOT NULL COMMENT '审核状态,待审/已审/审核不通过',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `update_employee_time` int(11) NOT NULL COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attribute`
--

CREATE TABLE IF NOT EXISTS `goods_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(11) NOT NULL COMMENT '商品分类',
  `name` varchar(255) NOT NULL COMMENT '属性名字',
  `type` tinyint(3) NOT NULL COMMENT '类型,文本/单选',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attribute_relation`
--

CREATE TABLE IF NOT EXISTS `goods_attribute_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT '商品',
  `attribute_id` int(11) NOT NULL COMMENT '属性',
  `value` varchar(255) NOT NULL COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `goods_category`
--

CREATE TABLE IF NOT EXISTS `goods_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL COMMENT '上级分类',
  `name` varchar(255) NOT NULL COMMENT '分类名',
  `display_order` int(11) NOT NULL COMMENT '显示顺序',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `inspect`
--

CREATE TABLE IF NOT EXISTS `inspect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `report_employee_id` int(11) NOT NULL COMMENT '报告的物业用户',
  `accept_employee_id` int(11) NOT NULL COMMENT '接受处理的物业用户',
  `complete_employee_id` int(11) NOT NULL COMMENT '完成处理的物业用户',
  `content` text NOT NULL COMMENT '巡检的内容',
  `create_time` int(11) NOT NULL COMMENT '报告时间',
  `accept_time` int(11) NOT NULL COMMENT '接受处理的时间',
  `complete_time` int(11) NOT NULL COMMENT '完成处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `accept_content` text NOT NULL COMMENT '接受处理的说明',
  `complete_content` text NOT NULL COMMENT '完成处理的说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='巡检';

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL COMMENT '卖家,处理该订单的商家',
  `supplier_id` int(11) NOT NULL COMMENT '提供商品的商家(供应商)',
  `buyer_mode` int(11) NOT NULL COMMENT '买家模型,关联买家的表名',
  `buyer_id` int(11) NOT NULL COMMENT '买家,加盟商/业主',
  `income_pay_id` int(11) NOT NULL COMMENT '购物支付,平台收入',
  `expense_pay_id` int(11) NOT NULL COMMENT '退货支付,平台支出',
  `buyer_name` varchar(255) NOT NULL COMMENT '收货人姓名（默认填业主姓名，可以修改）',
  `buyer_address` varchar(255) NOT NULL COMMENT '收货地址（默认填业主地址，可以修改）',
  `buyer_tel` varchar(20) NOT NULL COMMENT '收货电话（默认填业主电话，可以修改）',
  `buyer_postcode` varchar(50) DEFAULT NULL COMMENT '收货邮编（非必选项）',
  `comment` text NOT NULL COMMENT '买家留言',
  `seller_contact` varchar(20) NOT NULL COMMENT '卖家联系人名称',
  `seller_tel` varchar(20) NOT NULL COMMENT '卖家联系电话',
  `note` text NOT NULL COMMENT '卖家备注',
  `create_time` int(11) NOT NULL COMMENT '销售时间',
  `create_ip` varchar(20) NOT NULL COMMENT '买家IP',
  `income_pay_time` int(11) NOT NULL COMMENT '付款时间',
  `amount` decimal(10,0) NOT NULL COMMENT '订单金额',
  `status` tinyint(3) NOT NULL COMMENT '订单状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';

-- --------------------------------------------------------

--
-- 表的结构 `order_goods_relation`
--

CREATE TABLE IF NOT EXISTS `order_goods_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单',
  `goods_id` int(11) NOT NULL COMMENT '商品',
  `name` varchar(255) NOT NULL COMMENT '商品名',
  `price` decimal(10,0) NOT NULL COMMENT '单价',
  `count` int(11) NOT NULL COMMENT '数量',
  `amount` decimal(10,0) NOT NULL COMMENT '总价',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单内商品';

-- --------------------------------------------------------

--
-- 表的结构 `order_log`
--

CREATE TABLE IF NOT EXISTS `order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单',
  `user_model` varchar(50) NOT NULL COMMENT '用户模型,关联用户的表名',
  `user_id` int(11) NOT NULL COMMENT '用户,物业/商家/业主',
  `create_time` int(11) NOT NULL COMMENT '处理时间',
  `status` int(3) NOT NULL COMMENT '改变后的状态',
  `note` varchar(255) NOT NULL COMMENT '自动备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pay`
--

CREATE TABLE IF NOT EXISTS `pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` int(11) NOT NULL COMMENT '支付号',
  `payment_id` int(11) NOT NULL COMMENT '支付方式',
  `model` int(11) NOT NULL COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL COMMENT '对象,订单/订购退货/物业缴费',
  `type` int(11) NOT NULL COMMENT '类型,平台收入/支出',
  `income_model` int(11) NOT NULL COMMENT '收入对象模型',
  `income_id` int(11) NOT NULL COMMENT '收入对象',
  `expense_model` int(11) NOT NULL COMMENT '支出模型',
  `expense_id` int(11) NOT NULL COMMENT '支出对象',
  `statement_id` int(11) NOT NULL COMMENT '结算报表',
  `amount` decimal(10,0) NOT NULL COMMENT '金额',
  `status` tinyint(3) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `pay_time` int(11) NOT NULL COMMENT '付款时间',
  `offline_sn` int(11) NOT NULL COMMENT '线下支付交易号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付';

-- --------------------------------------------------------

--
-- 表的结构 `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL COMMENT '支付代码',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `description` text NOT NULL COMMENT '描述',
  `config` int(11) NOT NULL COMMENT '配置',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_online` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否是线上支付',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `update_time` int(11) NOT NULL COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付方式';

-- --------------------------------------------------------

--
-- 表的结构 `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` int(11) NOT NULL COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL COMMENT '对象,报修/投诉/巡检',
  `url` varchar(100) NOT NULL COMMENT '图片URL',
  `description` text NOT NULL COMMENT '图片描述',
  `thumb_url` varchar(100) NOT NULL COMMENT '缩略图URL',
  `origin_url` varchar(100) NOT NULL COMMENT '原始图URL',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报修/投诉/巡检图片';

-- --------------------------------------------------------

--
-- 表的结构 `property_maintenance_fee`
--

CREATE TABLE IF NOT EXISTS `property_maintenance_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT '小区业主物业',
  `pay_id` int(11) NOT NULL COMMENT '支付',
  `amount` decimal(10,0) NOT NULL COMMENT '缴纳物业费',
  `create_time` int(11) NOT NULL COMMENT '缴费时间',
  `create_ip` varchar(20) NOT NULL COMMENT '缴费IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物业管理费';

-- --------------------------------------------------------

--
-- 表的结构 `purchase_return`
--

CREATE TABLE IF NOT EXISTS `purchase_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `seller_id` int(11) NOT NULL COMMENT '卖家，供应商',
  `buyer_id` int(11) NOT NULL COMMENT '买家，加盟商',
  `order_id` int(11) NOT NULL COMMENT '关联订单，可以为空不关联',
  `pay_id` int(11) NOT NULL COMMENT '支付，退款',
  `status` tinyint(3) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `purchase_return_log`
--

CREATE TABLE IF NOT EXISTS `purchase_return_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `return_id` int(11) NOT NULL COMMENT '订购退货',
  `user_model` varchar(50) NOT NULL COMMENT '用户模型，关联用户的表名',
  `user_id` int(11) NOT NULL COMMENT '用户，物业/商家',
  `create_time` int(11) NOT NULL COMMENT '处理时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '改变后的状态',
  `note` varchar(255) NOT NULL COMMENT '自动备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caller_model` int(11) NOT NULL COMMENT '主叫用户模型,关联对象的表名',
  `caller_id` int(11) NOT NULL COMMENT '主叫用户模型,物业/业主',
  `answer_model` int(11) NOT NULL COMMENT '被叫用户模型，关联对象的表名',
  `answer_id` int(11) NOT NULL COMMENT '被叫对象，物业/业主',
  `communicate_phone_time` int(11) NOT NULL COMMENT '通话时间',
  `communicate_phone_minutes` int(11) NOT NULL COMMENT '通话时长（分钟）',
  `type` varchar(50) NOT NULL COMMENT '通话记录类型（已接、未接）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通话记录';

-- --------------------------------------------------------

--
-- 表的结构 `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '地区名称',
  `parent_id` int(11) NOT NULL COMMENT '上级地区',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `repair`
--

CREATE TABLE IF NOT EXISTS `repair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL COMMENT '小区',
  `customer_id` int(11) NOT NULL COMMENT '业主',
  `accept_employee_id` int(11) NOT NULL COMMENT '接受处理的物业用户',
  `complete_employee_id` int(11) NOT NULL COMMENT '完成处理的物业用户',
  `content` text NOT NULL COMMENT '保修的内容',
  `create_time` int(11) NOT NULL COMMENT '报告时间',
  `accept_time` int(11) NOT NULL COMMENT '接受处理的时间',
  `complete_time` int(11) NOT NULL COMMENT '完成处理的时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `accept_content` text NOT NULL COMMENT '接受处理的说明',
  `complete_content` text NOT NULL COMMENT '完成处理的说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报修';

-- --------------------------------------------------------

--
-- 表的结构 `reserve`
--

CREATE TABLE IF NOT EXISTS `reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `shop_id` int(11) NOT NULL COMMENT '商家',
  `customer_id` int(11) NOT NULL COMMENT '业主',
  `content` text NOT NULL COMMENT '预订内容',
  `create_time` int(11) NOT NULL COMMENT '预订时间',
  `create_ip` varchar(30) NOT NULL COMMENT '预订IP',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `reserve_reply`
--

CREATE TABLE IF NOT EXISTS `reserve_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reserve_id` int(11) NOT NULL COMMENT '预订',
  `type` int(11) NOT NULL COMMENT '评论人,商家/业主',
  `content` text NOT NULL COMMENT '评论内容',
  `create_time` int(11) NOT NULL COMMENT '评论时间',
  `create_ip` varchar(30) NOT NULL COMMENT '评论IP',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `model` varchar(50) NOT NULL COMMENT '模型,关联对象的表名',
  `object_id` int(11) NOT NULL COMMENT '对象,商家或商品',
  `customer_id` int(11) NOT NULL COMMENT '业主',
  `content` text NOT NULL COMMENT '评价内容',
  `reply` text NOT NULL COMMENT '评价回复内容',
  `create_time` int(11) NOT NULL COMMENT '评价时间',
  `create_ip` varchar(15) NOT NULL COMMENT '评价IP',
  `update_time` int(11) NOT NULL COMMENT '评价回复时间',
  `update_ip` varchar(20) NOT NULL COMMENT '评价回复IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `audit` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态,待审/已审/审核不通过',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `score` decimal(10,0) NOT NULL COMMENT '评分,只针对商家的评价',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` char(32) NOT NULL COMMENT 'ID',
  `expire` int(11) DEFAULT NULL COMMENT '会话过期时间',
  `data` blob COMMENT '会话数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会话';

-- --------------------------------------------------------

--
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `branch_id` int(11) NOT NULL COMMENT '组织架构',
  `type` tinyint(3) NOT NULL COMMENT '商家类型',
  `name` varchar(255) NOT NULL COMMENT '商家名',
  `username` varchar(100) NOT NULL COMMENT '账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(8) NOT NULL COMMENT '密码加验码',
  `contact` varchar(20) NOT NULL COMMENT '联系人名字',
  `mobile` varchar(15) NOT NULL COMMENT '手机号码',
  `tel` varchar(15) NOT NULL COMMENT '电话',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `last_time` int(11) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '登录IP',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,启禁用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `update_employee_time` int(11) NOT NULL COMMENT '操作时间',
  `update_employee_id` int(11) NOT NULL COMMENT '操作人',
  `score` decimal(10,0) NOT NULL COMMENT '评价评分',
  `is_auto_chance_community` tinyint(3) NOT NULL COMMENT '是否自动增加服务小区范围,即当所属组织架构管理小区变化时自动更新商家与小区的关联表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop_community_relation`
--

CREATE TABLE IF NOT EXISTS `shop_community_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `shop_id` int(11) NOT NULL COMMENT '商家',
  `community_id` int(11) NOT NULL COMMENT '小区',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop_goods_community_relation`
--

CREATE TABLE IF NOT EXISTS `shop_goods_community_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `seller_id` int(11) NOT NULL COMMENT '销售商品的商家',
  `goods_id` int(11) NOT NULL COMMENT '商品',
  `community_id` int(11) NOT NULL COMMENT '小区',
  `is_on_sale` tinyint(3) NOT NULL COMMENT '是否销售,上下架,针对所有商家销售给业主',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shop_relation`
--

CREATE TABLE IF NOT EXISTS `shop_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supplier_id` int(11) NOT NULL COMMENT '提供商品的商家,供应商',
  `seller_id` int(11) NOT NULL COMMENT '销售商品的商家,加盟商',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `secret` int(11) NOT NULL COMMENT '随机4位数字',
  `token` varchar(100) NOT NULL COMMENT '下一步操作令牌',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信,手机验证';

-- --------------------------------------------------------

--
-- 表的结构 `statement`
--

CREATE TABLE IF NOT EXISTS `statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` int(11) NOT NULL COMMENT '结算模型，关联表的表名',
  `object_id` int(11) NOT NULL COMMENT '对象，组织架构/商家/业主',
  `period` int(11) NOT NULL COMMENT '结算周期表示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `create_employee_id` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='结算报表';

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `logo_url` varchar(255) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签库';

-- --------------------------------------------------------

--
-- 表的结构 `tag_relation`
--

CREATE TABLE IF NOT EXISTS `tag_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL COMMENT '标签',
  `model` int(11) NOT NULL COMMENT '模型，关联对象的表名',
  `object_id` int(11) NOT NULL COMMENT '对象，商家/商品/报修/投诉/巡检',
  `display_order` int(11) NOT NULL COMMENT '显示顺序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签关系';

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

-- 加入物业的管理员帐号   为admin/admin
SET @salt = SUBSTR(MD5(RAND()), 1, 8);
INSERT INTO `employee` (`username`,`password`,`salt`,`name`) VALUES ('admin', MD5(CONCAT(MD5('admin'), @salt)), @salt, '管理员');

-- 加入业主账号
SET @salt = SUBSTR(MD5(RAND()), 1, 8);
INSERT INTO `customer` (`username`,`password`,`salt`,`mobile`,`name`) VALUES ('test', MD5(CONCAT(MD5('test'), @salt)), @salt, '18812345678', '测试用户');

-- 修改权限表的data字段编码
ALTER TABLE  `authitem` CHANGE  `data`  `data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;