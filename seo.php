<?php

/*
Plugin Name: WP SEO TDK
Plugin URI: http://www.tangshuang.net/wp-seo
Description: 为您提供一个通用的网页标题、关键词、描述方案，实现最基本的SEO TDK目的。
Version: 10.0.2
Author: 否子戈
Author URI: http://www.tangshuang.net
*/

define('WP_SEO_PULGIN',__FILE__);
define('WP_SEO_DIR',dirname(WP_SEO_PULGIN));

require_once(WP_SEO_DIR.'/seo-functions.php');

// 菜单
require_once(WP_SEO_DIR.'/seo-menu.php');

// 在后台添加分类、标签的SEO
require_once(WP_SEO_DIR.'/seo-term-meta.php');
require_once(WP_SEO_DIR.'/seo-post-meta.php');

// 完成前台标题、关键词
require_once(WP_SEO_DIR.'/seo-title.php');
require_once(WP_SEO_DIR.'/seo-head.php');
