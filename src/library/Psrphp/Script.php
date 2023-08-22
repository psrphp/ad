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
    `tips` varchar(255) COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告位';
DROP TABLE IF EXISTS `prefix_psrphp_ad_item`;
CREATE TABLE `prefix_psrphp_ad_item` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `billboard_id` int(10) unsigned NOT NULL COMMENT '所属广告牌id',
    `type` varchar(255) NOT NULL COMMENT '类型',
    `data` text COMMENT '数据',
    `tips` varchar(255) COMMENT '备注',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否发布',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='广告';
str;
        FrameworkScript::execSql($sql);
    }

    public static function onUnInstall()
    {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_ad_billboard`;
DROP TABLE IF EXISTS `prefix_psrphp_ad_item`;
str;
        FrameworkScript::execSql($sql);
    }
}
