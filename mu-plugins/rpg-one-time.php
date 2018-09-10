<?php
/*
Plugin Name: RPG One Time
Description: Helper functions that are run only once
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgonetime
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

if(!class_exists('rpgonetime')):

class rpgonetime{

	function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

    function initialize(){
		add_action('init', array($this, 'run'));
	}

	function run(){
		$this->add_roles();
		$this->remove_hooks();
		$this->set_date_time_format();
        $this->set_image_sizes();
        $this->set_sitemap_options();
		add_action('shutdown', array($this, 'unlink'), PHP_INT_MAX);
	}

	function unlink(){
		unlink(__FILE__);
	}

	function add_roles(){
        //REMOVE OOTB ROLES
        remove_role('subscriber');
        remove_role('editor');
        remove_role('contributor');
        remove_role('author');

		//REMOVE CUSTOM ROLES - ENSURES CAPABILITIES ARE ALWAYS SET CORRECTLY
		remove_role('content_author');
        remove_role('content_approver');
        remove_role('content_publisher');
        remove_role('content_admin');
		remove_role('content_snippets');

        //CUSTOM CAPABILITIES FOR WORKFLOW ACCESS CONTROL
        global $wp_roles;

        if (class_exists('WP_Roles')) {
            if (!isset($wp_roles)) {
                $wp_roles = new WP_Roles();
            }
        }
        $wp_roles->add_cap('administrator', 'ow_rpg_unpublish_workflow_item');
        $wp_roles->add_cap('administrator', 'ow_rpg_delete_workflow_item');

        //DEFINE CAPABILITIES
        $contentAuthorCaps = array(
            'read'							=> true,
            'edit_pages'					=> true,
			'edit_others_pages'				=> true,
			'edit_published_pages'			=> true,
            'edit_posts'					=> true,
            'publish_posts'                 => true,
            'edit_others_posts'				=> true,
			'upload_files'					=> true,
            'ow_submit_to_workflow'			=> true,
            'ow_abort_workflow'			    => true,
        );

        $contentApproverCaps = array(
            'read'							=> true,
            'edit_pages'					=> true,
            'edit_others_pages'				=> true,
            'publish_pages'					=> true,
            'read_private_pages'			=> true,
            'delete_pages'					=> true,
            'delete_private_pages'			=> true,
            'delete_published_pages'		=> true,
            'delete_others_pages'			=> true,
            'edit_private_pages'			=> true,
            'edit_published_pages'			=> true,
            'edit_posts'					=> true,
            'publish_posts'                 => true,
            'edit_others_posts'				=> true,
            'upload_files'					=> true,
            'ow_reassign_task'				=> true,
            'ow_sign_off_step'				=> true,
            'ow_skip_workflow'				=> true,
            'ow_submit_to_workflow'			=> true,
            'ow_view_others_inbox'			=> true,
            'ow_view_reports'				=> true,
            'ow_view_workflow_history'		=> true,
            'ow_abort_workflow'			    => true,
        );

        $contentPublisherCaps = array(
            'read'							=> true,
            'edit_pages'					=> true,
            'edit_others_pages'				=> true,
            'publish_pages'					=> true,
            'read_private_pages'			=> true,
            'delete_pages'					=> true,
            'delete_private_pages'			=> true,
            'delete_published_pages'		=> true,
            'delete_others_pages'			=> true,
            'edit_private_pages'			=> true,
            'edit_published_pages'			=> true,
            'edit_posts'					=> true,
            'publish_posts'                 => true,
            'edit_others_posts'				=> true,
            'upload_files'					=> true,
            'ow_reassign_task'				=> true,
            'ow_sign_off_step'				=> true,
            'ow_skip_workflow'				=> true,
            'ow_submit_to_workflow'			=> true,
            'ow_view_others_inbox'			=> true,
            'ow_view_reports'				=> true,
            'ow_view_workflow_history'		=> true,
            'ow_abort_workflow'			    => true,
            'ow_rpg_unpublish_workflow_item'=> true,
        );

        $contentAdminCaps = array(
            'read'							=> true,
            'edit_dashboard'				=> true,
            'edit_pages'					=> true,
            'edit_others_pages'				=> true,
            'publish_pages'					=> true,
            'read_private_pages'			=> true,
            'delete_pages'					=> true,
            'delete_private_pages'			=> true,
            'delete_published_pages'		=> true,
            'delete_others_pages'			=> true,
            'edit_private_pages'			=> true,
            'edit_published_pages'			=> true,
			'delete_posts'					=> true,
			'delete_others_posts'			=> true, 
            'edit_posts'					=> true,
            'publish_posts'                 => true,
            'edit_others_posts'				=> true,
            'upload_files'					=> true,
            'manage_rpgsnippets'			=> true,
            'create_users'					=> true,
            'delete_users'					=> true,
            'edit_users'					=> true,
            'list_roles'					=> true,
            'list_users'					=> true,
            'promote_users'					=> true,
            'remove_users'					=> true,
            'ow_reassign_task'				=> true,
            'ow_sign_off_step'				=> true,
            'ow_skip_workflow'				=> true,
            'ow_submit_to_workflow'			=> true,
            'ow_view_others_inbox'			=> true,
            'ow_view_reports'				=> true,
            'ow_view_workflow_history'		=> true,
            'ow_abort_workflow'			    => true,
            'ow_rpg_unpublish_workflow_item'=> true,
            'ow_rpg_delete_workflow_item'   => true,
        );

        $contentSnippets = array(
            'manage_rpgsnippets'			=> true,
            'read'							=> true,
        );

        //CREATE CUSTOM ROLES
        add_role('content_author', __('Content Author'), $contentAuthorCaps);
        add_role('content_approver', __('Content Approver'), $contentApproverCaps);
        add_role('content_publisher', __('Content Publisher'), $contentPublisherCaps);
        add_role('content_admin', __('Content Admin'), $contentAdminCaps);
        add_role('content_snippets', __('Content Snippets'), $contentSnippets);
    }

	function remove_hooks(){
        remove_action('init', 'wp_register_default_user_group_taxonomy');
        remove_action('init', 'wp_register_default_user_type_taxonomy');
    }

	function set_date_time_format(){
		//FORCE DATE FORMAT + TIME FORMAT
        update_option('date_format', 'd/m/y');
        update_option('time_format', 'H:i');
	}

	function set_image_sizes(){
		//SET DEFAULT IMAGE SIZES
		update_option('thumbnail_size_w', 320);
		update_option('thumbnail_size_h', 241);	
		update_option('thumbnail_crop', 0);
		
		update_option('medium_size_w', 338);
		update_option('medium_size_h', 254);
		
		update_option('medium_large_size_w', 405);
		update_option('medium_large_size_h', 304);
		
		update_option('large_size_w', 540);
		update_option('large_size_h', 405);

		update_option('image_default_align', 'center');
		update_option('image_default_size', 'large');
    }

    function set_sitemap_options(){
        $sitemap_options = get_option('sm_options');

		if(isset($sitemap_options)){
			$sitemap_options['sm_i_hide_survey'] = true;
			$sitemap_options['sm_i_hide_note'] = true;
			$sitemap_options['sm_i_hide_works'] = true;
			$sitemap_options['sm_i_hide_donors'] = true;

			$sitemap_options['sm_in_posts'] = false;
			$sitemap_options['sm_b_prio_provider'] = '';

			update_option('sm_options', $sitemap_options);
		}
    }
}

function rpgonetime() {
    global $rpgonetime;
    
    if(!isset($rpgonetime)) {
        $rpgonetime = new rpgonetime();
        $rpgonetime->initialize();
    }
    
    return $rpgonetime;
}

//KICK OFF
rpgonetime();

endif;
?>