<?php

add_action('wp_head','seo_head_meta',0);
// 将关键词和描述输出在wp_head区域
function seo_head_meta(){
    seo_head_meta_keywords();
    seo_head_meta_description();
}
// 网页关键字描述
function seo_head_meta_keywords(){
    // 为了避免翻页带来的问题，把翻页以后的给屏蔽掉
    if(is_paged())
        return;

    $keywords = '';
    if((is_home() || is_front_page())){
        $keywords = get_option('seo_site_keywords');
    }
    elseif(is_category()){
        global $cat;
        $cat_id = is_object($cat) ? $cat->cat_ID : $cat;
        $keywords = get_term_meta($cat_id,'seo_keywords',true);
    }
    elseif(is_tag()){
        global $wp_query;
        $tag_id = $wp_query->queried_object->term_id;
        $keywords = get_term_meta($tag_id,'seo_keywords',true);
    }
    elseif(is_singular()){
        global $post;
        // 第一种是使用标签
        $tags = strip_tags(get_the_tag_list('',',',''));
        // 第二种是使用自定义域的keywords
        $metakeywords = stripslashes(strip_tags(get_post_meta($post->ID,'seo_keywords',true)));
        // 当存在标签时，使用标签；当存在自定义keywords时，把它附加到标签上，如果没有标签，就使用自定义的keywords；如果这两者都不存在，就使用分类名称
        if($tags && $metakeywords){
            $keywords = $tags.','.$metakeywords;
        }
        elseif($tags && !$metakeywords){
            $keywords = $tags;
        }
        elseif(!$tags && $metakeywords){
            $keywords = $metakeywords;
        }
        $keywords = str_replace('"','',$keywords);
    }
    $keywords = seo_clear_code($keywords);
    if($keywords)
        echo '<meta name="keywords" content="'.trim($keywords).'" />'."\n";
}
// 网页描述
function seo_head_meta_description(){
    // 为了避免翻页带来的问题，把翻页以后的给屏蔽掉
    if(is_paged())
        return;

    $description = '';
    if((is_home() || is_front_page())){
        $description = get_option('seo_site_description');
    }
    elseif(is_category()){
        $description = category_description();
    }
    elseif(is_tag()){
        $description = tag_description();
    }
    elseif(is_singular()){
        global $post;
        // 第一种是使用自定义域的
        $description = get_post_meta($post->ID,'seo_description',true);
        // 第二种是使用摘要
        $excerpt = $post->post_excerpt;
        // 第三种是使用文章的前200字
        $content = mb_strimwidth(strip_tags($post->post_content),0,300,'...');
        // 将三者结合起来
        if($description == '')
            $description = $excerpt;
        if($description == '')
            $description = $content;
        $description = str_replace('"','',$description);
    }
    $description = seo_clear_code(strip_tags($description));
    if($description)
        echo '<meta name="description" content="'.trim($description).'" />'."\n";
}