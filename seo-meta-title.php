<?php

add_filter('wp_title','seo_title_filter',100);
function seo_title_filter($title){
    global $page,$paged;
    $split = get_option('seo_split');
    $title = trim($title);

    // 首页标题优化
    if((is_home() || is_front_page())){
        $title = get_option('seo_site_title');
        if(!$title) $title = get_bloginfo('name').$split.get_bloginfo('description');
    }
    // 分类页标题
    elseif(is_category()){
        global $cat;
        $cat_id = is_object($cat) ? $cat->cat_ID : $cat;
        $cat_title = single_cat_title('',false);
        $cat_seo_title = get_term_meta($cat_id,'seo_title',true);
        $title = $cat_seo_title ? $cat_seo_title : $cat_title;

        $seo_cat_title_type = get_option('seo_cat_title_type');
        if($seo_cat_title_type == 1) { // 父栏目
            $_cat = is_object($cat) ? $cat : get_category($cat_id);
            $_cat = get_category($_cat->parent);
            $title .= $split.$_cat->name;
        }

        $title .= $split.get_bloginfo('name');
    }
    // 标签页标题
    elseif(is_tag()){
        global $wp_query;
        $tag_id = $wp_query->queried_object->term_id;
        $tag_name = $wp_query->queried_object->name;
        $tag_seo_title = get_term_meta($tag_id,'seo_title',true);
        $title = $tag_seo_title ? $tag_seo_title : $tag_name;
        $title .= $split.get_bloginfo('name');
    }
    // 文章页的标题
    elseif(is_singular()){
        global $post;
        $title = trim($post->post_title) ? $post->post_title : $post->post_date;

        $seo_post_title_type = get_option('seo_post_title_type');
        if($seo_post_title_type == 1) { // 显示分类名称
            $_cat = get_the_category($post->ID);
            $_cat = $_cat[0];
            $title .= $split.$_cat->name;
        }

        $title .= $split.get_bloginfo('name');
    }
    elseif(is_feed()){
        return $title;
    }
    // 其他情况
    else{
        $title .= $split.get_bloginfo('name');
    }
    if($paged >= 2 || $page >= 2){
        $title .= $split.sprintf(__('第%s页'),max($paged,$page));
    }
    $title = seo_clear_code($title);
    return $title;
}