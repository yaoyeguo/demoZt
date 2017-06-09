# Host: localhost  (Version: 5.5.40)
# Date: 2017-05-25 23:52:51
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "image"
#

CREATE TABLE `image` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '栏目id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `description` text NOT NULL COMMENT '图片描述',
  `image_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_menu` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否开启 1开启 0未开启',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "image"
#


#
# Structure for table "category"
#

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL COMMENT '分类父级ID',
  `cat_name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `cat_path` varchar(100) DEFAULT NULL COMMENT '分类路径(从根至本结点的路径,逗号分隔,首部有逗号)',
  `level` varchar(1) DEFAULT NULL,
  `order_sort` int(10) unsigned DEFAULT NULL COMMENT '排序',
  `modified_time` int(11) unsigned DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目';

#
# Data for table "category"
#

