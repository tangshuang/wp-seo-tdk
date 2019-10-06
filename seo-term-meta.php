<?php

add_action('category_add_form_fields','seo_extra_create_term_fields');
add_action('created_category','seo_extra_term_fileds_save');
add_action('edit_category_form_fields','seo_extra_edit_term_fields');
add_action('edited_category','seo_extra_term_fileds_save');

add_action('add_tag_form_fields','seo_extra_create_term_fields');
add_action('created_post_tag','seo_extra_term_fileds_save');
add_action('edit_tag_form_fields','seo_extra_edit_term_fields');
add_action('edited_post_tag','seo_extra_term_fileds_save');

function _seo_generate_extra_term_fields($term, $create_field){
    $metas = array(
        array('meta_name' => 'SEO标题','meta_key' => 'seo_title'),
        array('meta_name' => 'SEO关键词','meta_key' => 'seo_keywords'),
        array('meta_name' => 'SEO描述','meta_key' => 'seo_description', 'meta_type' => 'textarea')
    );
    if(isset($term->term_id)) {
        $term_id = $term->term_id;
    }
    foreach($metas as $meta) {
        $meta_name = $meta['meta_name'];
        $meta_key = $meta['meta_key'];
        $meta_type = $meta['meta_type'];
        if(isset($term_id)) {
            $meta_value = seo_get_term_meta($term_id, $meta_key);
        }
        else {
            $meta_value = '';
        }
        $create_field($meta_type, $meta_name, $meta_key, $meta_value);
    }
}

function seo_extra_create_term_fields($term) {
    _seo_generate_extra_term_fields($term, function($meta_type, $meta_name, $meta_key, $meta_value) {
        if ($meta_type == 'textarea') {
            $input = '<textarea name="term_meta_'.$meta_key.'" id="term_'.$meta_key.'" class="large-text" rows="5">'.esc_html($meta_value).'</textarea>';
        }
        else {
            $input = '<input type="text" name="term_meta_'.$meta_key.'" id="term_'.$meta_key.'" value="'.esc_html($meta_value).'">';
        }
        echo '<div class="form-field">
            <label for="term_'.$meta_key.'">'.$meta_name.'</label>
            '.$input.'
        </div>';
    });
}

function seo_extra_edit_term_fields($term) {
    _seo_generate_extra_term_fields($term, function($meta_type, $meta_name, $meta_key, $meta_value) {
        if ($meta_type == 'textarea') {
            $input = '<textarea name="term_meta_'.$meta_key.'" id="term_'.$meta_key.'" class="large-text" rows="5">'.esc_html($meta_value).'</textarea>';
        }
        else {
            $input = '<input type="text" name="term_meta_'.$meta_key.'" id="term_'.$meta_key.'" value="'.esc_html($meta_value).'">';
        }
        echo '<tr class="form-field">
            <th scope="row" valign="top"><label for="term_'.$meta_key.'">'.$meta_name.'</label></th>
            <td>'.$input.'</td>
        </tr>';
    });
}

function seo_extra_term_fileds_save($term_id){
    if(!empty($_POST)) foreach($_POST as $key => $value){
        if(strpos($key,'term_meta_') === 0) {
			$meta_key = str_replace('term_meta_','',$key);
			if (trim($value) == '') {
				delete_term_meta($term_id, $meta_key);
			}
			else {
            	$meta_value = esc_html(trim($value));
            	update_term_meta($term_id, $meta_key, $meta_value) OR add_term_meta($term_id, $meta_key, $meta_value, true);
			}
        }
    }
}