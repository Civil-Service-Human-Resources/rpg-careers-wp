START TRANSACTION;

INSERT INTO wp_options (option_name, option_value, autoload)
SELECT 'ewww_image_optimizer_tracking_notice', '1', 'yes'
FROM wp_options
WHERE NOT EXISTS(
    SELECT option_name, option_value, autoload
    FROM wp_options
    WHERE option_name = 'ewww_image_optimizer_tracking_notice'
	AND option_value = '1'
	AND autoload = 'yes'
)
LIMIT 1;

UPDATE wp_options
SET
option_value = 'a:4:{s:4:"code";s:165:"<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=!!CONTAINER_ID!!" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>";s:9:"code_head";s:381:"<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!=\'dataLayer\'?\'&amp;l=\'+l:\'\';j.async=true;j.src=\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);})(window,document,\'script\',\'dataLayer\',\'!!CONTAINER_ID!!\');</script>";s:9:"variables";a:6:{i:0;a:2:{s:4:"name";s:5:"title";s:5:"value";s:12:"%post_title%";}i:1;a:2:{s:4:"name";s:6:"author";s:5:"value";s:13:"%author_name%";}i:2;a:2:{s:4:"name";s:9:"wordcount";s:5:"value";s:11:"%wordcount%";}i:3;a:2:{s:4:"name";s:9:"logged_in";s:5:"value";s:11:"%logged_in%";}i:4;a:2:{s:4:"name";s:7:"page_id";s:5:"value";s:9:"%page_id%";}i:5;a:2:{s:4:"name";s:9:"post_date";s:5:"value";s:11:"%post_date%";}}s:18:"external_variables";a:0:{}}'
WHERE option_name = 'metronet_tag_manager';

INSERT INTO wp_options (option_name, option_value, autoload)
SELECT 'members_settings', 'a:12:{s:12:"role_manager";b:1;s:11:"multi_roles";b:1;s:20:"explicit_denied_caps";b:1;s:15:"show_human_caps";b:1;s:25:"content_permissions_error";s:59:"Sorry, but you do not have permission to view this content.";s:18:"private_feed_error";s:54:"You must be logged into the site to view this content.";s:19:"content_permissions";b:0;s:17:"login_form_widget";b:0;s:12:"users_widget";b:0;s:12:"private_blog";b:0;s:16:"private_rest_api";b:0;s:12:"private_feed";b:0;}', 'yes'
FROM wp_options
WHERE NOT EXISTS(
    SELECT option_name, option_value, autoload
    FROM wp_options
    WHERE option_name = 'members_settings'
	AND option_value = 'a:12:{s:12:"role_manager";b:1;s:11:"multi_roles";b:1;s:20:"explicit_denied_caps";b:1;s:15:"show_human_caps";b:1;s:25:"content_permissions_error";s:59:"Sorry, but you do not have permission to view this content.";s:18:"private_feed_error";s:54:"You must be logged into the site to view this content.";s:19:"content_permissions";b:0;s:17:"login_form_widget";b:0;s:12:"users_widget";b:0;s:12:"private_blog";b:0;s:16:"private_rest_api";b:0;s:12:"private_feed";b:0;}'
	AND autoload = 'yes'
)
LIMIT 1;

COMMIT;