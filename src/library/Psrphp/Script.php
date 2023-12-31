<?php

declare(strict_types=1);

namespace App\Psrphp\Ad\Psrphp;

use PsrPHP\Framework\Script as FrameworkScript;

class Script
{
    public static function onInstall()
    {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_ad_billboard`;
CREATE TABLE `prefix_psrphp_ad_billboard` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `name` varchar(255) NOT NULL COMMENT '名称',
    `title` varchar(255) COMMENT '标题',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告位';
DROP TABLE IF EXISTS `prefix_psrphp_ad_item`;
CREATE TABLE `prefix_psrphp_ad_item` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `billboard_id` int(10) unsigned NOT NULL COMMENT '所属广告牌id',
    `title` varchar(255) NOT NULL COMMENT '标题',
    `type` varchar(255) NOT NULL COMMENT '类型',
    `data` text COMMENT '数据',
    `showtimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '展现量',
    `max_showtimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大展现量',
    `click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
    `max_click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大点击量',
    `starttime` datetime COMMENT '展现开始时间',
    `endtime` datetime COMMENT '展现结束时间',
    `pc` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'pc展示',
    `mobile` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'mobile展示',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否发布',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告';
DROP TABLE IF EXISTS `prefix_psrphp_ad_show`;
CREATE TABLE `prefix_psrphp_ad_show` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `item_id` int(10) unsigned NOT NULL COMMENT '广告id',
    `date` date COMMENT '日期',
    `hour` tinyint(3) unsigned NOT NULL COMMENT '小时',
    `times` int(10) unsigned NOT NULL COMMENT '展现量',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='点击记录';
DROP TABLE IF EXISTS `prefix_psrphp_ad_click`;
CREATE TABLE `prefix_psrphp_ad_click` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `item_id` int(10) unsigned NOT NULL COMMENT '广告id',
    `ip` varchar(255) COMMENT 'IP',
    `user_agent` varchar(255) COMMENT 'user agent',
    `referer` varchar(255) COMMENT 'http referer',
    `time` datetime COMMENT '点击时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='点击记录';
str;
        FrameworkScript::execSql($sql);
    }

    public static function onUnInstall()
    {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_ad_billboard`;
DROP TABLE IF EXISTS `prefix_psrphp_ad_item`;
DROP TABLE IF EXISTS `prefix_psrphp_ad_show`;
DROP TABLE IF EXISTS `prefix_psrphp_ad_click`;
str;
        FrameworkScript::execSql($sql);
    }
}
