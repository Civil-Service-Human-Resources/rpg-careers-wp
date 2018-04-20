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
	rpgCareers_conditional_styles();
	
	//REMOVE STD JQUERY AS AIMING TO ADD TO END OF BODY NOT THE HEAD
	wp_deregister_script('jquery');
	
	wp_enqueue_script('rpgcareers-jquery', get_template_directory_uri() .'/assets/js/jquery-1.12.4.min.js', null, null, true);
	wp_enqueue_script('rpgcareers-slick-js', get_template_directory_uri() .'/assets/js/slick.min.js', array('rpgcareers-jquery'), null, true);
	wp_enqueue_script('rpgcareers-helper-js', get_template_directory_uri() .'/assets/js/scripts.js', array('rpgcareers-jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'rpgCareers_frontend_scripts');

//REGISTER MAIN NAV MENU
register_nav_menus( array(
	'main-nav' => esc_html__( 'Primary', 'rpgcareers' ),
) );

//REGISTER WIDGETS
function rpgCareers_widgets_init() {
	global $wp_widget_factory;  
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

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

//FOOTER HOOK
function rpgCareers_footer_hook() {
	//TODO:
}
add_action( 'wp_footer', 'rpgCareers_footer_hook' );


//SHORTCODE FOR FOOTER
function rpgCareers_footer_content() {
	//TODO:
	return '';
}

add_shortcode('rpg_footer_content', 'rpgCareers_footer_content');

//REMOVE TYPE ATTR FROM SCRIPT TAGS
function rpgCareers_clean_script_tag($input) {
    $input = str_replace("type='text/javascript' ", '', $input);
    return str_replace("'", '"', $input);
}

add_filter('script_loader_tag', 'rpgCareers_clean_script_tag');

//WPML - REMOVE META TAG FROM HTML SOURCE
global $sitepress;
remove_action('wp_head', array($sitepress, 'meta_generator_tag'));

//BESPOKE NAV WALKER FOR MENU
class RPG_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '<ul>';
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = array();
        if(!empty( $item->classes)) {
            $classes = (array) $item->classes;
        }

        $active_class = '';
        if(in_array('current-menu-item', $classes)) {
            $active_class = ' class="masthead__nav-current"';
        } else if(in_array('current-menu-parent', $classes)) {
            $active_class = ' class="masthead__nav-current"';
        } else if(in_array('current-page-ancestor', $classes)) {
            $active_class = ' class="masthead__nav-current"';
        }

        $url = '';
        if(!empty( $item->url)) {
            $url = $item->url;
        }

        $output .= '<li'. $active_class . '><a href="' . $url . '">' . $item->title . '</a></li>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= '</li>';
    }
}