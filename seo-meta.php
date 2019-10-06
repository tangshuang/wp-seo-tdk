<?php

add_action('wp_head','seo_head_meta',0);
// 将关键词和描述输出在wp_head区域
function seo_head_meta(){
    seo_head_meta_keywords();
    seo_head_meta_description();
}
// 网页关键字描述
function seo_head_meta_keywords(){
    if(is_paged()) // 为了避免翻页带来的问题，把翻页以后的给屏蔽掉
    {
        return;
    }

    $keywords = '';
	$blog_name = get_bloginfo('name');
	$blog_description = get_bloginfo('description');

    if(is_home() || is_front_page())
    {
        $keywords = seo_get('site_keywords');
	    if($keywords)
	    {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description
		    );
		    $keywords = str_replace($partten,$res,$keywords);
	    }
    }
    elseif(is_category())
    {
	    global $cat;
	    $cat_name = single_cat_title('',false);
	    $cat_parents = seo_get_category_parents($cat);
	    $keywords = seo_get_term_meta($cat,'seo_keywords');
	    if($keywords) {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}',
			    '{title}',
			    '{parents}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description,
			    $cat_name,
			    $cat_parents
		    );
		    $keywords = str_replace($partten,$res,$keywords);
	    }
    }
    elseif(is_tag())
    {
        global $wp_query;
        $tag_id = $wp_query->queried_object->term_id;
	    $tag_name = $wp_query->queried_object->name;
        $keywords = seo_get_term_meta($tag_id,'seo_keywords');
	    if($keywords) {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}',
			    '{title}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description,
			    $tag_name
		    );
		    $keywords = str_replace($partten,$res,$keywords);
	    }
    }
    elseif(is_single())
    {
        global $post;
	    $post_id = $post->ID;
	    $post_title = trim($post->post_title);
	    $post_time = $post->post_date;
        $post_cats = strip_tags(get_the_category_list( ',', 'multiple', $post_id ));
        $post_tags = strip_tags(get_the_tag_list('',',',''));
        $post_meta = seo_get_post_meta($post_id, '_seo_keywords');
        $keywords = $post_meta ? $post_meta : $post_tags.($post_tags ? ',' : '').$post_cats;
	    if($keywords) {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}',
			    '{title}',
			    '{time}',
			    '{categories}',
			    '{tags}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description,
			    $post_title,
			    $post_time,
			    $post_cats,
			    $post_tags
		    );
		    $keywords = str_replace($partten,$res,$keywords);
	    }
    }
    elseif(is_singular())
    {
        global $post;
	    $post_title = trim($post->post_title);
        $keywords = seo_get_post_meta($post->ID, '_seo_keywords');
	    if($keywords) {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}',
			    '{title}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description,
			    $post_title
		    );
		    $keywords = str_replace($partten,$res,$keywords);
	    }
    }

    $keywords = seo_clear_code($keywords);
    $keywords = strip_tags($keywords);
    $keywords = trim($keywords);

    if($keywords)
    {
        echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
    }
}
// 网页描述
function seo_head_meta_description(){
    if(is_paged())
    {
        return;
    }

    $description = '';
	$blog_name = get_bloginfo('name');
	$blog_description = get_bloginfo('description');

    if(is_home() || is_front_page())
    {
        $description = seo_get('site_description');
	    if($description)
	    {
		    $partten = array(
			    '{blog_name}',
			    '{blog_description}'
		    );
		    $res = array(
			    $blog_name,
			    $blog_description
		    );
		    $description = str_replace($partten,$res,$description);
	    }
    }
    elseif(is_category())
    {
		global $cat;
		$description = seo_get_term_meta($cat,'seo_description');
		if (!$description || trim($description) == '') {
			$description = category_description();
		}
    }
    elseif(is_tag())
    {
		global $wp_query;
		$tag_id = $wp_query->queried_object->term_id;
		$description = seo_get_term_meta($tag_id,'seo_description');
		if (!$description || trim($description) == '') {
			$description = tag_description();
		}
    }
    elseif(is_single())
    {
        global $post;
        $post_id = $post->ID;
        $post_meta = seo_get_post_meta($post_id, '_seo_description');
        $post_excpert = $post->post_excerpt;
        $post_content = seo_strimwidth(strip_tags($post->post_content),0,300,'...');

        $description = $post_meta ? $post_meta : ($post_excpert ? $post_excpert : $post_content);
    }
    elseif(is_singular()){
        global $post;
        $description = seo_get_post_meta($post->ID, '_seo_description');
    }

    $description = seo_clear_code($description);
    $description = strip_tags($description);
    $description = trim($description);

    if($description)
    {
        echo '<meta name="description" content="'.$description.'" />'."\n";
    }
}