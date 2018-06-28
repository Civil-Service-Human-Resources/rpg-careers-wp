<?php 
/*
Plugin Name: RPG Non Auth Preview
Description: Aloows non-published pages to be viewed by non-authenticated users
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgnonauthpreview
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

if(!class_exists('rpgnonauthpreview')):

class rpgnonauthpreview{

	var $version = '1.0.0';
    var $settings = array();
    
    function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

	function initialize(){
        $this->settings = array(
            'name'               => __('RPG Non Auth Preview', 'rpgnonauthpreview'),
            'version'            => $this->version,
        );

		if (!is_admin()) {
			add_filter('pre_get_posts', array($this,'rpg_show_preview'));
		}else{
			add_action('admin_init', array($this, 'admin_init'));
		}
    }

	function admin_init(){
		add_filter('preview_post_link', array($this, 'get_preview_link'), 10, 2);
	}

	function get_preview_link($link, $post){
		if('page' === $post->post_type){
			$rpg_nonce = $this->rpg_create_nonce('rpg_nap_nonce-' . $post->ID);
			$link = add_query_arg('rpg_nap_nonce',$rpg_nonce, $link);
		}

		return $link;
	}

	function rpg_show_preview($query){
		if($query->is_main_query() && $query->is_preview() && $query->is_singular() && isset($_GET['rpg_nap_nonce'])) {
			if (!headers_sent()) {
				nocache_headers();
			}
			remove_filter('pre_get_posts', array($this,'rpg_show_preview'));
			add_filter('posts_results', array($this,'rpg_show_draft'), 10, 2);

			//JUST PAGES NOT POSTS
			$query->set('post_type', 'page');
		}
	}

	function rpg_show_draft($posts, &$query){

		remove_filter('posts_results', array($this,'rpg_show_draft' ), 10);

		if (sizeof($posts) != 1){
			return $posts;
		}

		$post_status = get_post_status($posts[0]);
		$post_status_obj = get_post_status_object($post_status);

		if (in_array($post_status, $this->get_published_statuses())) {
			//GO TO PUBLISHED PAGE URL
			wp_redirect(get_permalink($posts[0]->ID), 301);
			exit;
		}

		if ($post_status_obj != null) {
			if ($post_status_obj->name != 'draft') {
				//VERIFY NONCE
				if (!$this->rpg_verify_nonce($_GET['rpg_nap_nonce'], 'rpg_nap_nonce-'. $posts[0]->ID)) {
					$this->throw404();
				}else{
					//SHOW THE PAGE
					return $posts;
				}
			}
		}else{
			//NO OBJECT - RETURN A 404
			$this->throw404();
		}

		//IT IS A DRAFT - VERIFY NONCE
		if (!$this->rpg_verify_nonce($_GET['rpg_nap_nonce'], 'rpg_nap_nonce-'. $posts[0]->ID)) {
			return $posts;
		}

		//SHOW THE DRAFT PAGE
		$query->_draft_post = $posts;
		add_filter('the_posts', array($this,'show_draft_page'), null, 2);
	}

	function show_draft_page($posts, &$query) {
		remove_filter('the_posts', 'show_draft_page', null, 2);
		return $query->_draft_post;
	}

	function get_published_statuses() {
		return array('publish');
	}

	function rpg_create_nonce($action = -1) {
		$i = $this->nonce_tick();
		return substr(wp_hash($i . $action, 'nonce'), -12, 10);
	}

	function nonce_tick() {
		$nonce_life = PREVIEW_LINK_DURATION;
		return ceil(time() / ($nonce_life / 2));
	}

	function throw404(){
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		get_template_part(404); 
		exit();
	}

	function rpg_verify_nonce($nonce, $action = -1) {
		$i = $this->nonce_tick();

		//NONCE GENERATED BETWEEN START AND MID POINT OF NONCE LIFE
		if (substr(wp_hash($i . $action, 'nonce'), -12, 10) == $nonce) {
			return true;
		}

		//NONCE GENERATED MID POINT AND END OF NONCE LIFE
		if (substr(wp_hash(($i - 1) . $action, 'nonce'), -12, 10) == $nonce) {
			return true;
		}

		//INVALID NONCE
		return false;
	}

}

function rpgnonauthpreview() {
    global $rpgnonauthpreview;
    
    if( !isset($rpgnonauthpreview) ) {
        $rpgnonauthpreview = new rpgnonauthpreview();
        $rpgnonauthpreview->initialize();
    }
    
    return $rpgnonauthpreview;
}

//KICK OFF
rpgnonauthpreview();

endif;
?>