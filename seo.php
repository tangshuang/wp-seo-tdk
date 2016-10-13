<?php

/*
Plugin Name: WP SEO
Plugin URI: http://www.tangshuang.net/wp-seo
Description: 为您提供一个通用的网页标题、关键词、描述方案，实现最基本的SEO TDK目的。
Version: 2.0.1
Author: 否子戈
Author URI: http://www.tangshuang.net
*/

require 'seo-functions.php';

// 菜单
require 'seo-menu.php';

// 在后台添加分类、标签的SEO
require 'seo-term-meta.php';
require 'seo-post-meta.php';

// 完成前台标题、关键词
require 'seo-meta-title.php';
require 'seo-meta-head.php';