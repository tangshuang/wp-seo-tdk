<?php

add_action('after_setup_theme','seo_title_action',100);
function seo_title_action()
{
    remove_all_filters( 'wp_title', 9999 );
    add_filter('wp_title','seo_title_filter',10000);
	if(current_theme_supports('title-tag')) {
		remove_theme_support('title-tag');
		add_action( 'wp_head', 'seo_render_title', 0 );
	}
}
function seo_render_title() {
	?>
	<title><?php wp_title(''); ?></title>
	<?php
}
function seo_title_filter($title){
    global $page,$paged;
    $title = trim($title);

    $seo_split = seo_get('split');

    $seo_site_title = seo_get('site_title');

    $seo_cat_title = seo_get('cat_title');
    $seo_tag_title = seo_get('tag_title');
    $seo_post_title = seo_get('post_title');
    $seo_page_title = seo_get('page_title');

    $blog_name = get_bloginfo('name');
    $blog_description = get_bloginfo('description');

    // 首页标题优化
    if((is_home() || is_front_page())){
        if($seo_site_title) {
            $partten = array(
                '{blog_name}',
                '{blog_description}',
                '{split}'
            );
            $res = array(
                $blog_name,
                $blog_description,
                $seo_split
            );
            $title = str_replace($partten,$res,$seo_site_title);
        }
        else {
            $title = $blog_name.$seo_split.$blog_description;
        }
    }
    // 分类页标题
    elseif(is_category()){
        global $cat;
        $cat_name = single_cat_title('',false);
        $cat_parents = seo_get_category_parents($cat);
        $cat_meta = seo_get_term_meta($cat,'seo_title');

        if($seo_cat_title) {
            $partten = array(
                '{blog_name}',
                '{blog_description}',
                '{split}',
                '{title}',
                '{meta_or_title}',
                '{parents_split}'
            );
            $res = array(
                $blog_name,
                $blog_description,
                $seo_split,
                $cat_name,
                $cat_meta ? $cat_meta : $cat_name,
	            $cat_parents ? $cat_parents.$seo_split : ''
            );
            $title = str_replace($partten,$res,$seo_cat_title);
        }
        else {
            $title = ($cat_meta ? $cat_meta : $cat_name).$seo_split.$blog_name;
        }
    }
    // 标签页标题
    elseif(is_tag()){
        global $wp_query;
        $tag_id = $wp_query->queried_object->term_id;
        $tag_name = $wp_query->queried_object->name;
        $tag_meta = get_term_meta($tag_id,'seo_title',true);

        if($seo_tag_title) {
            $partten = array(
                '{blog_name}',
                '{blog_description}',
                '{split}',
                '{title}',
                '{meta_or_title}'
            );
            $res = array(
                $blog_name,
                $blog_description,
                $seo_split,
                $tag_name,
                $tag_meta ? $tag_meta : $tag_name
            );
            $title = str_replace($partten,$res,$seo_tag_title);
        }
        else {
            $title = ($tag_meta ? $tag_meta : $tag_meta).$seo_split.$blog_name;
        }
    }
    // 文章页的标题
    elseif(is_single()){
        global $post;
        $post_id = $post->ID;
        $post_title = trim($post->post_title);
        $post_time = $post->post_date;
        $post_meta = seo_get_post_meta($post_id,'_seo_title');

        $post_cats = strip_tags(get_the_category_list( ',', 'multiple', $post_id ));
        $post_tags = strip_tags(get_the_tag_list('',',',''));

        if($seo_post_title) {
            $partten = array(
                '{blog_name}',
                '{blog_description}',
                '{split}',
                '{title}',
                '{title_or_time}',
	            '{time}',
	            '{meta}',
                '{meta_or_title}',
                '{meta_or_title_or_time}',
                '{categories_split}',
                '{tags_split}'
            );
            $res = array(
                $blog_name,
                $blog_description,
                $seo_split,
                $post_title,
                $post_title ? $post_title : $post_time,
	            $post_time,
	            $post_meta,
                $post_meta ? $post_meta : $post_title,
                $post_meta ? $post_meta : ($post_title ? $post_title : $post_time),
	            $post_cats ? $post_cats.$seo_split : '',
                $post_tags ? $post_cats.$seo_split : ''
            );
	        if(strpos($seo_post_title,'|'))
	        {
	        	$tail = '';
		        $pos = strpos($seo_post_title,'||');
	        	if($pos)
		        {
		        	$tail = substr($seo_post_title,$pos + 2);
			        $seo_post_title = substr($seo_post_title,0,$pos);
		        }
	        	$titles = explode('|',$seo_post_title);
		        foreach ($titles as $tl)
		        {
		        	$title = str_replace($partten,$res,$tl);
			        $title = trim($title);
			        if($title)
			        {
				        break;
			        }
		        }
		        if($tail)
		        {
			        $title .= str_replace($partten,$res,$tail);
		        }
	        }
            else
            {
            	$title = str_replace($partten,$res,$seo_post_title);
            }
        }
        else {
            $title = ($post_meta ? $post_meta : ($post_title ? $post_title : $post_time)).$seo_split.$blog_name;
        }
    }
    elseif(is_singular()) {
        global $post;
        $post_id = $post->ID;
        $post_title = trim($post->post_title);
        $post_meta = seo_get_post_meta($post_id,'_seo_title');

        if($seo_page_title) {
            $partten = array(
                '{blog_name}',
                '{blog_description}',
                '{split}',
                '{title}',
                '{meta_or_title}'
            );
            $res = array(
                $blog_name,
                $blog_description,
                $seo_split,
                $post_title,
                $post_meta ? $post_meta : $post_title
            );
            $title = str_replace($partten,$res,$seo_page_title);
        }
        else {
            $title = ($post_meta ? $post_meta : $post_title).$seo_split.$blog_name;
        }
    }

    if($paged >= 2 || $page >= 2){
        $title .= $seo_split.sprintf(__('第%s页'),max($paged,$page));
    }

    $title = seo_clear_code($title);
    $title = strip_tags($title);
    $title = trim($title);

    return $title;
}