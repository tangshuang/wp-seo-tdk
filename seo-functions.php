<?php

// 准备好一个字符串处理函数
function seo_clear_code($string) {
	if(!$string)
		return '';
	$string = str_replace("\r\n",' ',$string);//清除换行符
	$string = str_replace("\n",' ',$string);//清除换行符
	$string = str_replace("\t",' ',$string);//清除制表符
	$pattern = array("/> *([^ ]*) *</","/[\s]+/","/<!--[^!]*-->/","/\" /","/ \"/","'/\*[^*]*\*/'","/\[(.*)\]/");
	$replace = array(">\\1<"," ","","\"","\"","","");
	return preg_replace($pattern,$replace,$string);
}

function seo_quota_encode($value) {
	$value = str_replace('"','&#34;',$value);
	$value = str_replace("'",'&#39;',$value);
	return $value;
}

function seo_strimwidth($str ,$start , $width ,$trimmarker ){
	if(function_exists('mb_strimwidth'))
	{
		return mb_strimwidth( $str ,$start , $width ,$trimmarker );
	}
	else
	{
		$output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str);
		return $output.$trimmarker;
	}
}

function seo_get($key) {
	$value = get_option('seo_'.$key);
	$value = stripslashes($value);
	return $value;
}

function seo_set($key,$value) {
	$value = strip_tags($value);
	$value = seo_clear_code($value);
	update_option('seo_'.$key,$value) OR add_option('seo_'.$key,$value);
}

function seo_get_post_meta($post_id,$key) {
	$value = get_post_meta($post_id,$key,true);
	$value = stripslashes($value);
	return $value;
}
function seo_get_term_meta($term_id,$key) {
	$value = get_term_meta($term_id,$key,true);
	$value = stripslashes($value);
	return $value;
}
function seo_get_meta($id,$key,$type = 'post') {
	$function = 'seo_get_'.$type.'_meta';
	if(function_exists($function)) {
		return $function($id,$key);
	}
	else {
		return '';
	}
}

function seo_get_category_parents($term_id) {
	$chain = _seo_get_category_parents($term_id);
	if($chain)
	{
		$chain = substr($chain,0,-1);
	}
	return $chain;
}
function _seo_get_category_parents( $term_id, $separator = ',', $visited = array()) {
	$chain = '';
	$term = get_term( $term_id, 'category' );
	if ( is_wp_error( $term ) )
		return $term;

	if ( $term->parent && ( $term->parent != $term->term_id ) && !in_array( $term->parent, $visited ) ) {
		$visited[] = $term->parent;
		$chain .= _seo_get_category_parents( $term->parent, $separator, $visited );
	}

	$chain .= $chain.$separator;

	return $chain;
}