# wp-seo-tdk
WordPress SEO Plugin，一个超简单的WordPress SEO插件

[wordpress plugin dir](https://wordpress.org/plugins/wp-seo-tdk/)

## 安装
上传插件到你的插件目录，进入wordpress后台，启动该插件。

## 使用
进入wordpress后台，在“设置”菜单中有一个子菜单“SEO”，进入即可进行设置。

## 修改主题
修改你的wordpress主题，网页的标题必须用<title><?php wp_title(''); ?></title>进行输出，默认情况下，关键词和网页描述依赖于wp_head()，如果你的主题中没有，可以自己手动添加seo_head_meta()这个函数到<title>下方。
