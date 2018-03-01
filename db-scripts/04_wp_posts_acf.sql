INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, comment_count)
SELECT '1',NOW(), NOW(),'a:7:{s:8:"location";a:1:{i:0;a:1:{i:0;a:3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:12:"rpg-snippets";}}}s:8:"position";s:6:"normal";s:5:"style";s:8:"seamless";s:15:"label_placement";s:3:"top";s:21:"instruction_placement";s:5:"label";s:14:"hide_on_screen";s:0:"";s:11:"description";s:0:"";}', 'RPG Snippet', 'rpg-snippet', 'publish','closed','closed','group_5a8172568f4bb', '','',NOW(), NOW(),'',0,'',0,'acf-field-group',0
FROM wp_posts
WHERE NOT EXISTS(
SELECT post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, comment_count
    FROM wp_posts
    WHERE post_type = 'acf-field-group' 
	AND post_excerpt = 'rpg-snippet'
)
LIMIT 1;

SELECT @site_url:=option_value FROM wp_options WHERE option_name = 'siteurl';
SELECT @post_id:=ID FROM wp_posts WHERE post_type = 'acf-field-group' AND post_excerpt = 'rpg-snippet' ORDER BY ID DESC LIMIT 1;
UPDATE wp_posts
SET
guid = (SELECT CONCAT(@site_url, '/?post-type=acf-field-group&#038;p=', @post_id))
WHERE ID = @post_id;