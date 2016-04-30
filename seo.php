<?php

/*
Plugin Name: WP SEO
Plugin URI: http://www.tangshuang.net/wp-seo
Description: 为您提供一个通用的网页标题、关键词、描述方案，实现最基本的SEO TDK目的。
Version: 1.0
Author: Tison
Author URI: http://www.tangshuang.net
Origin: https://github.com/tangshuang/wp-seo
*/

// 准备好一个字符串处理函数
function seo_clear_code($string){
    $string = trim($string);
    if(!$string)
        return false;
    $string = str_replace("\r\n",'',$string);//清除换行符
    $string = str_replace("\n",'',$string);//清除换行符
    $string = str_replace("\t",'',$string);//清除制表符
    $pattern = array("/> *([^ ]*) *</","/[\s]+/","/<!--[^!]*-->/","/\" /","/ \"/","'/\*[^*]*\*/'","/\[(.*)\]/");
    $replace = array(">\\1<"," ","","\"","\"","","");
    return preg_replace($pattern,$replace,$string);
}

// 菜单
require 'seo-menu.php';

// 在后台添加分类、标签的SEO
require 'seo-term.php';

// 完成前台标题、关键词
require 'seo-meta-title.php';
require 'seo-meta-head.php';