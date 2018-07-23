<?php
/*
Plugin Name: Advanced Custom Fields: ACF RPG Team
Description: Display a select field of all available teams.
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: acfrpgteam
Domain Path: /lang
*/

if( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('acf_rpg_team') ) :

class acf_rpg_team {

	function __construct() {

		// vars
		$this->settings = array(
			'version'	=> '1.1.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		add_action('acf/include_field_types', 	array($this, 'include_field_types'));
	}

	function include_field_types() {
		include_once('fields/acf-rpg-team-helpers.php');
		include_once('fields/acf-rpg-team.php');
	}

}

new acf_rpg_team();
endif;
?>