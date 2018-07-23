<?php

if( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('acf_field_rpg_team') ) :

class acf_field_rpg_team extends acf_field {

	function __construct( $settings ) {

		$this->name = 'team';
		$this->label = __('Team', 'acf-rpg-team');
		$this->category = 'choice';
		$this->defaults = acf_rpg_team_helpers::get_defaults();
		$this->settings = $settings;

		parent::__construct();
	}

	function render_field_settings( $field ) {

		$teams = acf_rpg_team_helpers::get_teams();
		acf_render_field_setting( $field, array(
			'label'			=> __('Choices','acf'),
			'name'			=> 'choices',
			'type'			=> 'textarea',
			'wrapper' => array(
				'class' => 'hidden',
			),
			'value' => acf_encode_choices($teams),
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Return Format','acf'),
			'instructions'	=> __('Specify the value returned','acf'),
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'choices'		=> array(
				'array'	=> __('Team code and name (as array)', 'acf-rpg-team'),
				'code'	=> __('Team code', 'acf-rpg-team'),
				'name'	=> __('Team name', 'acf-rpg-team')
			)
		));

	}

	function render_field( $field ) {
		global $post;
		$revision = wp_is_post_revision($post->ID);

		if($revision){
			//FETCH VALUE FOR TEAM AS NOT POPULATED AUTOMATICALLY
			//GET FIELD POSITION
			$array_split = $array = explode('-', $field['id']);
			$pos = 0;

			if(count($array_split)===4){
				$pos = $array_split[2];
			}

			global $wpdb;
			$results = $wpdb->get_results($wpdb->prepare('SELECT meta_key FROM `'.$wpdb->postmeta.'` WHERE meta_value = "%s" AND post_id = %s', $field['key'], $post->ID), ARRAY_A);
			if($results){
				//GRAB VALUE THEN UPDATE $field OBJECT
				$meta_key = $results[$pos]['meta_key'];
				$meta_key = substr($meta_key, 1);
				$meta_val = get_post_meta($post->ID, $meta_key, true);
				$field['value'] = $meta_val;
			}
		}

		acf_rpg_team_helpers::render_field( $field );
	}

	function load_value( $value, $post_id, $field ) {
		return $value;
	}

	function update_value( $value, $post_id, $field ) {

		// validate
		if( empty($value) ) {
			return $value;
		}

		// array
		if( is_array($value) ) {
			// save value as strings, so we can clearly search for them in SQL LIKE statements
			$value = array_map('strval', $value);
		}

		// return
		return $value;

	}

	function validate_value( $valid, $value, $field, $input ) {

		if($field['required'] && empty($value)) {
			return $valid = __('You must select a team', 'acf-rpg-team');
		}

		return $valid;
	}

}

new acf_field_rpg_team( $this->settings );

endif;

?>