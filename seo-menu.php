<?php

// 保存SEO设置的内容
add_action('admin_init','seo_add_admin_options_submenu_save');
function seo_add_admin_options_submenu_save(){
    if($_GET['page'] == 'seo' && $_POST['action'] == 'seo-update'){
        check_admin_referer();
        update_option('seo_split',$_POST['seo_split']);
        update_option('seo_site_title',$_POST['seo_site_title']);
        update_option('seo_site_keywords',$_POST['seo_site_keywords']);
        update_option('seo_site_description',$_POST['seo_site_description']);
        update_option('seo_cat_title_type',$_POST['seo_cat_title_type']);
        update_option('seo_post_title_type',$_POST['seo_post_title_type']);
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
    $seo_split = get_option('seo_split');
    $seo_site_title = get_option('seo_site_title');
    $seo_site_keywords = get_option('seo_site_keywords');
    $seo_site_description = get_option('seo_site_description');
    $seo_cat_title_type = get_option('seo_cat_title_type');
    $seo_post_title_type = get_option('seo_post_title_type');
    ?>
    <div class="wrap" id="seo-admin">
        <h2>SEO设置</h2>
        <div class="metabox-holder">
            <form method="post">
                <div class="postbox">
                    <h3>全局设置</h3>
                    <div class="inside">
                        <p>
                            间隔符：<input type="text" class="regular-text" style="width:60px;" name="seo_split" value="<?php echo $seo_split; ?>" /><br />
                            一般来说都从"-"、"_"、"|"中进行选择。注意，下面的演示中有的时候用_，有的时候用-，但实际效果还是根据这里的设置而定。
                        </p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>首页（blog）设置</h3>
                    <div class="inside">
                        <p>标题：<input type="text" class="regular-text" name="seo_site_title" value="<?php echo $seo_site_title; ?>" /></p>
                        <p>关键词：<input type="text" class="regular-text" name="seo_site_keywords" value="<?php echo $seo_site_keywords; ?>" /></p>
                        <p>网页描述：<br><textarea class="large-text" name="seo_site_description"><?php echo $seo_site_description; ?></textarea></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>分类（category）标签（tag）页设置</h3>
                    <div class="inside">
                        <p>标题格式：
                            <select name="seo_cat_title_type">
                                <option value="0" <?php selected($seo_cat_title_type,0); ?>>分类名-博客名</option>
                                <option value="1" <?php selected($seo_cat_title_type,1); ?>>分类名-父分类-博客名</option>
                            </select>
                        </p>
                    </div>
                    <div class="inside">
                        <p>一般情况下，wp_title都会直接打印出分类名作为分类页的标题，本功能允许你设置自己的分类页标题。在编辑具体的分类页时可以看到category_meta字段，你可以填写对应的值。分类页的描述将直接采用分类描述。<br>
                            注意：这些meta值应该与你在这里填写的标题格式进行统筹规划。</p>
                        <p>例如，您在原本为“帆布鞋”的分类中填写了新的标题字段为“帆布鞋 凡客诚品”，那么在页面中将使用后面的代替前面的，如你的标题将变为“帆布鞋 凡客诚品-父分类-根分类-博客名”</p>
                        <p>分类页的关键词由category_meta_keywords确定，如果不填写，直接使用分类名。</p>
                        <p>分类页的描述由分类的描述确定。</p>
                        <p>因为标签没有父标签之说，所以这里的设置对标签无效。</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>文章（post）页设置</h3>
                    <div class="inside">
                        <p>
                            标题格式：
                            <select name="seo_post_title_type">
                                <option value="0" <?php selected($seo_post_title_type,0); ?>>文章名-博客名</option>
                                <option value="1" <?php selected($seo_post_title_type,1); ?>>文章名-分类层级-博客名</option>
                            </select>
                        </p>
                    </div>
                    <div class="inside">
                        <p>文章页的重要性不必多说了吧！</p>
                        <p>文章页的关键词：首先使用文章标签作为关键词，接着你自己创建的seo_keywords自定义栏目的值作为关键词，再接着使用文章名和分类名称作为关键词。本插件会同时使用它们，无论缺少谁都不会直接影响关键词的使用。</p>
                        <p>文章页的描述：首先使用文章自定义栏目seo_description的值作为描述，如果没有的话，使用填写的文章摘要作为描述，如果还没有的话，摘取文章开头的150个字作为描述。注意，它们之间的选择是有先后顺序的，如果你同时填写了description自定义栏目和摘要，只会选择自定义栏目的值作为摘要。记住，你的文章开头150个字也很重要。</p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>说明</h3>
                    <div class="inside">
                        <p>作者：<a href="http://www.tangshuang.net" target="_blank">唐霜</a></p>
                        <p>向插件作者捐赠：支付宝<code>476206120@qq.com</code>。</p>
                        <p>如果你还想要更高级的功能，例如在后台写文章的时候增加选项，可以选择这篇文章只同步到上面配置的博客列表中的某一个或几个（非全部）的话，可以和我联系，获得更高级别的开发成果。</p>
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
        <p>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-0625745788201806" data-ad-slot="7099159194"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </p>
    </div>
<?php
}
