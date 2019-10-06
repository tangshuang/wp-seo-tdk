<?php

// 保存SEO设置的内容
add_action('admin_init','seo_add_admin_options_submenu_save');
function seo_add_admin_options_submenu_save(){
    if(@$_GET['page'] == 'seo' && @$_POST['action'] == 'seo-update'){
        check_admin_referer();
        seo_set('split',$_POST['seo_split']);

        seo_set('site_title',$_POST['seo_site_title']);
        seo_set('site_keywords',$_POST['seo_site_keywords']);
        seo_set('site_description',$_POST['seo_site_description']);

        seo_set('cat_title',$_POST['seo_cat_title']);
        seo_set('tag_title',$_POST['seo_tag_title']);
        seo_set('post_title',$_POST['seo_post_title']);
        seo_set('page_title',$_POST['seo_page_title']);

        wp_redirect(admin_url('options-general.php?page=seo&saved=true&time='.time()));
        exit;
    }
}

// 创建后台设置页面
add_action('admin_menu','seo_add_admin_options_submenu');
function seo_add_admin_options_submenu(){
    add_options_page('SEO设置','SEO','edit_theme_options','seo','seo_add_admin_options_submenu_view');
}
function seo_add_admin_options_submenu_view(){
    if(@$_GET['saved'] == 'true')echo '<div id="message" class="updated fade"><p><strong>更新成功！</strong></p></div>';
    $seo_split = seo_get('split');

    $seo_site_title = seo_get('site_title');
    $seo_site_keywords = seo_get('site_keywords');
    $seo_site_description = seo_get('site_description');

    $seo_cat_title = seo_get('cat_title');
    $seo_tag_title = seo_get('tag_title');
    $seo_post_title = seo_get('post_title');
    $seo_page_title = seo_get('page_title');
    ?>
    <style>
        .postbox h3 {border-bottom: #dedede solid 1px;}
        .postbox .inside {margin: 0;border-bottom: #dedede solid 1px;}
        .postbox .inside:last-child {border-bottom: 0;}
        .postbox .document {background: #f9f9f9;font-size: 12px;padding: 12px}
        .postbox .document p:first-child {margin-top: 1px;}
    </style>
    <div class="wrap" id="seo-admin">
        <h2>SEO设置</h2>
        <div class="metabox-holder">
            <form method="post">
                <div class="postbox">
                    <h3>基础设置</h3>
                    <div class="inside">
                        <p>间隔符：<input type="text" class="regular-text" style="width:60px;" name="seo_split" value="<?php echo $seo_split; ?>" /></p>
                        <p>间隔符：一般来说都从"-"、"_"、"|"中进行选择。注意，下面的演示中有的时候用_，有的时候用-，但实际效果还是根据这里的设置而定。</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>首页</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_site_title" value="<?php echo seo_quota_encode($seo_site_title); ?>" /></p>
                        <p>关键词：<input type="text" class="regular-text" name="seo_site_keywords" value="<?php echo seo_quota_encode($seo_site_keywords); ?>" /></p>
                        <p>描述：<br><textarea class="large-text" name="seo_site_description"><?php echo $seo_site_description; ?></textarea></p>
                    </div>
                    <div class="inside document">
                        <p>在“标题”中可以使用<code>{blog_name}</code>来代表blog name,<code>{blog_description}</code>代表blog description，<code>{split}</code>代表上面填写的间隔符。<strong>上面三个都支持</strong>。<br>例："<code>{blog_name} - {blog_description}</code>"，注意这里的 - 并不是上面填写的间隔符，间隔符不会在这里生效。</p><p>如果不填写标题，默认使用“{blog_name}{split}{blog_description}”。</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>分类页</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_cat_title" value="<?php echo seo_quota_encode($seo_cat_title); ?>"></p>
                    </div>
                    <div class="inside document">
                        <p>关键词由后台填写的“SEO关键词”确定，如果不填写，则没有关键词标签。描述由后台填写的描述确定。</p>
                        <p>可以使用<code>{blog_name}</code>来代表blog name,<code>{blog_description}</code>代表blog description，<code>{split}</code>代表上面填写的间隔符，<code>{title}</code>来代表category name，<code>{meta_or_title}</code>代替后台填写的“SEO标题”，如没填写则用category name，<code>{parents_split}</code>代表父分类的category name（逗号分开，且后面跟上split）。</p>
                        <p>此处如果不填写，默认使用“<code>{meta_or_title}{split}{blog_name}</code>”。</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>标签页</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_tag_title" value="<?php echo seo_quota_encode($seo_tag_title); ?>"></p>
                    </div>
                    <div class="inside document">
                        <p>关键词由后台填写的“SEO关键词”确定，如果不填写，则没有关键词标签。描述由后台填写的描述确定。</p>
                        <p>可以使用<code>{blog_name}</code>来代表blog name,<code>{blog_description}</code>代表blog description，<code>{split}</code>代表上面填写的间隔符，<code>{title}</code>来代表tag name，<code>{meta_or_title}</code>代替后台填写的“SEO标题”，如没填写则用tag name。</p>
                        <p>此处如果不填写，默认使用“<code>{meta_or_title}{split}{blog_name}</code>”</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>文章页</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_post_title" value="<?php echo seo_quota_encode($seo_post_title); ?>"></p>
                    </div>
                    <div class="inside document">
                        <p>关键词由后台填写的“SEO关键词”确定，如果不填写，则使用便签、分类的混合信息作为关键词。文章页的描述由后台填写的“SEO描述”确定，如果没有填写，使用填写的文章摘要作为描述，如果还没有填写，摘取文章的前150个字作为描述。</p>
                        <p>可以使用<code>{blog_name}</code>来代表blog name,<code>{blog_description}</code>代表blog description，<code>{split}</code>代表上面填写的间隔符，<code>{title}</code>来代表文章标题，<code>{title_or_time}</code>代表文章标题，如果标题为空则代表文章的发布时间，<code>{categories_split}</code>代表文章的分类列表（逗号分开，且后面跟上split），<code>{tags_split}</code>代表文章的标签列表（逗号分开，且后面跟上split），<code>{meta_or_title}</code>代替后台填写的“SEO标题”，如没填写则用post title，还可以使用<code>{meta_or_title_or_time}</code>就不解释了。</p>
                        <p>此处如果不填写，默认使用“<code>{meta_or_title_or_time}{split}{blog_name}</code>”</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>独立页面</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_page_title" value="<?php echo seo_quota_encode($seo_page_title); ?>"></p>
                    </div>
                    <div class="inside document">
                        <p>关键词由后台填写的“SEO关键词”确定，如果不填写，则没有关键词标签。描述由后台填写的“SEO描述”确定，如果没有填写，使用填写的摘要作为描述，如果还没有填写，摘取前150个字作为描述。</p>
                        <p>可以使用<code>{blog_name}</code>来代表blog name,<code>{blog_description}</code>代表blog description，<code>{split}</code>代表上面填写的间隔符，<code>{title}</code>来代表页面标题，<code>{meta_or_title}</code>代替后台填写的“SEO标题”，如没填写则用post title。</p>
                        <p>此处如果不填写，默认使用“<code>{meta_or_title}{split}{blog_name}</code>”</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>说明</h3>
                    <div class="inside">
                        <p>作者：<a href="http://www.tangshuang.net" target="_blank">否子戈</a></p>
                        <p>捐赠：支付宝<code>476206120@qq.com</code></p>
                    </div>
                </div>
                <p class="submit">
                    <button type="submit" class="button-primary">提交</button>
                </p>
                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
                <input type="hidden" name="action" value="seo-update" />
                <?php wp_nonce_field(); ?>
            </form>
        </div>
    </div>
<?php
}
