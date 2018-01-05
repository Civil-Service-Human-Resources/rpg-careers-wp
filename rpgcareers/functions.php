<?php // RPG Careers Theme Custom Functions

if (!defined('ABSPATH')) exit;

//DEFINE MAX CONTENT WIDTH
function rpgCareers_content_width() {
	$GLOBALS['content_width'] = 960;
}
add_action('after_setup_theme', 'rpgCareers_content_width', 0);

//TODO: IS THIS STILL NEEDED?
function rpgCareers_setup() {

}
add_action('after_setup_theme', 'rpgCareers_setup');

//HANDLE PARENT / CHILD STYLES
function rpgCareers_conditional_styles() {
	if (is_child_theme()) {
		//LOAD PARENT STYLES IF ACTIVE CHILD THEME
		wp_enqueue_style('rpgcareers-parent', trailingslashit(get_template_directory_uri()) .'style.css', array(), null);
	}
	
	//ALWAYS LOAD ACTIVE THEME STYLESHEET
	wp_enqueue_style('rpgcareers', get_stylesheet_uri(), array(), null);
}

//FRONT END SCRIPTS + STYLES
function rpgCareers_frontend_scripts() {
	// wp_enqueue_style( $handle, $src, $deps, $ver, $media )
	rpgCareers_conditional_styles();
	
	// wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer )
	//wp_enqueue_script('rpgcareers', get_template_directory_uri() .'/js/blank.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'rpgCareers_frontend_scripts');

//REGISTER MAIN NAV MENU
register_nav_menus( array(
	'main-nav' => esc_html__( 'Primary', 'rpgcareers' ),
) );

//REGISTER WIDGETS
function rpgCareers_widgets_init() {
	$widget_args_1 = array(
		'name'          => __('Widgets Sidebar', 'rpgcareers'),
		'id'            => 'widgets_sidebar',
		'class'         => '',
		'description'   => __('Widgets added here are displayed in the sidebar', 'rpgcareers'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
	);
	
	register_sidebar($widget_args_1);
}
add_action('widgets_init', 'rpgCareers_widgets_init');

function rpgCareers_disable_feed() {
	global $wp_query;
	$wp_query->set_404();
	status_header(404);
	nocache_headers();
	include(get_query_template('404'));
	exit;
}

//THROW 404 FOR ALL REQUESTS FOR RSS FEEDS 
add_action('do_feed', 'rpgCareers_disable_feed', 1);
add_action('do_feed_rdf', 'rpgCareers_disable_feed', 1);
add_action('do_feed_rss', 'rpgCareers_disable_feed', 1);
add_action('do_feed_rss2', 'rpgCareers_disable_feed', 1);
add_action('do_feed_atom', 'rpgCareers_disable_feed', 1);
add_action('do_feed_rss2_comments', 'rpgCareers_disable_feed', 1);
add_action('do_feed_atom_comments', 'rpgCareers_disable_feed', 1);
 
 //TIDY UP META TAGS
function rpgCareers_tidy_head_links() {
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head');
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('wp_head', 'wp_resource_hints', 2);
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_head', 'noindex', 1);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('rest_api_init', 'wp_oembed_register_route');
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}

add_action('wp_loaded', 'rpgCareers_tidy_head_links');

//AMEND REQUEST HEADERS
function rpgCareers_amend_headers($headers){
	if(is_feed()){
		//FORCE CONTENT TYPE FOR FEED REQUESTS - MAKES SURE THE 404 IS CORRECTLY SHOWN
		$headers['Content-Type'] = 'text/html; charset=utf-8';
	}

	$headers['x-frame-options'] = 'SAMEORIGIN';
	$headers['x-xss-protection'] = '1; mode=block';
	$headers['X-UA-Compatible'] = 'IE=edge';
	return $headers;     
}

add_filter('wp_headers', 'rpgCareers_amend_headers');

//SET UP CUSTOM HANDLER FOR ANY SERVER ERRORS
function rpgCareers_custom_error_handler() {
    //TODO - GENERATE HTML CONTENT + LOG ERROR
	//return;
}

add_filter('wp_die_handler', 'rpgCareers_custom_error_handler');

//TODO: NEED ABILITY TO HIDE BACK END MENU ITEMS?
function rpgCareers_amend_admin_menu() {
    global $menu;
	//EXAMPLE ARRAY...
	//$restricted = array('Comments','Posts');
    end ($menu);

    while (prev($menu)){
		$value = trim(explode('<',$menu[key($menu)][0])[0]);

		if(in_array($value,$restricted)) {
			unset($menu[key($menu)]);
		}
    }
}
add_action('admin_menu', 'rpgCareers_amend_admin_menu');
