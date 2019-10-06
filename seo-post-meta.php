<?php

// 添加后台界面meta_box
add_action('add_meta_boxes','seo_post_metas_box_init');
function seo_post_metas_box_init(){
	add_meta_box('seo-metas','SEO','seo_post_metas_box',array('post','page'),'side','high');
}
function seo_post_metas_box($post){
	if($post->ID) {
		$post_id = $post->ID;
		$seo_title = seo_get_post_meta($post_id,'_seo_title');
		$seo_keywords = seo_get_post_meta($post_id,'_seo_keywords');
		$seo_description = seo_get_post_meta($post_id,'_seo_description');
	}
	else {
		$seo_title = '';
		$seo_keywords = '';
		$seo_description = '';
	}
	?>
	<div class="seo-metas">
		<p>SEO标题：<input type="text" class="regular-text" name="seo_title" value="<?php echo seo_quota_encode($seo_title); ?>" style="max-width: 98%;"></p>
		<p>SEO关键词：<input type="text" class="regular-text" name="seo_keywords" value="<?php echo seo_quota_encode($seo_keywords); ?>" style="max-width: 98%;"></p>
		<p>SEO描述：<br><textarea class="large-text" name="seo_description"><?php echo $seo_description; ?></textarea></p>
	</div>
<?php
}

// 保存填写的meta信息
add_action('save_post','seo_post_metas_box_save');
function seo_post_metas_box_save($post_id){
	$seo_title = strip_tags($_POST['seo_title']);
	$seo_keywords = strip_tags($_POST['seo_keywords']);
	$seo_description = seo_clear_code(strip_tags($_POST['seo_description']));
	update_post_meta($post_id,'_seo_title',$seo_title);
	update_post_meta($post_id,'_seo_keywords',$seo_keywords);
	update_post_meta($post_id,'_seo_description',$seo_description);
}