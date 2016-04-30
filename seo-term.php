<?php

add_action('category_add_form_fields','seo_extra_term_fields');
add_action('created_category','seo_extra_term_fileds_save');
add_action('edit_category_form_fields','seo_extra_term_fields');
add_action('edited_category','seo_extra_term_fileds_save');

add_action('add_tag_form_fields','seo_extra_term_fields');
add_action('created_post_tag','seo_extra_term_fileds_save');
add_action('edit_tag_form_fields','seo_extra_term_fields');
add_action('edited_post_tag','seo_extra_term_fileds_save');

function seo_extra_term_fields($term){
    $metas = array(
        array('meta_name' => 'SEO标题','meta_key' => 'seo_title'),
        array('meta_name' => 'SEO关键词','meta_key' => 'seo_keywords')
    );
    if(isset($term->term_id))
        $term_id = $term->term_id;
    foreach($metas as $meta) {
        $meta_name = $meta['meta_name'];
        $meta_key = $meta['meta_key'];
        if(isset($term_id)) $meta_value = get_term_meta($term_id,$meta_key,true);
        else $meta_value = '';
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_<?php echo $meta_key; ?>"><?php echo $meta_name; ?></label></th>
            <td><input type="text" name="term_meta_<?php echo $meta_key; ?>" id="term_<?php echo $meta_key; ?>" class="regular-text" value="<?php echo $meta_value; ?>"></td>
        </tr>
    <?php
    }
}

function seo_extra_term_fileds_save($term_id){
    if(!empty($_POST)) foreach($_POST as $key => $value){
        if(strpos($key,'term_meta_') === 0 && trim($value) != '') {
            $meta_key = str_replace('term_meta_','',$key);
            $meta_value = trim($value);
            update_term_meta($term_id,$meta_key,$meta_value) OR add_term_meta($term_id,$meta_key,$meta_value,true);
        }
    }
}