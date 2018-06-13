<?php 
/*
Plugin Name: RPG Restrict Login
Description: Add in ability to restrict login attempts for users
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgrestrictlogin
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

if(!class_exists('rpgrestrictlogin')):

class rpgrestrictlogin{

	var $version = '1.0.0';
    var $settings = array();
    
    function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

	function initialize(){
        $this->settings = array(
            'name'               => __('RPG Restrict Login', 'rpgrestrictlogin'),
            'version'            => $this->version,
			'failed_login_limit' => USER_LOGIN_LIMIT,
			'lockout_duration'   => LOCKOUT_DURATION,
			'transient_name'	 => 'rpg_restrict_login_',
        );

		add_filter('authenticate', array( $this, 'check_attempted_login' ), 30, 3);
        add_action('wp_login_failed', array( $this, 'login_failed' ), 10, 1);
    }

	public function check_attempted_login($user, $username, $password) {
		$current_user = get_user_by('login', $username);
		
		if($current_user){
			$trans_name = $this->settings['transient_name'].$current_user->ID;

			if (get_transient($trans_name)) {
				$datas = get_transient($trans_name);

				if ($datas['tried'] >= $this->settings['failed_login_limit']) {
					//PLAYBACK ERROR MESSAGE
					return new WP_Error('too_many_tried', sprintf( __( '<strong>ERROR</strong>: You have reached the maximum number of attempts and have been locked out.')));
				}
			}
		}
        return $user;
    }

	public function login_failed($username) {
		$current_user = get_user_by('login', $username);

		if($current_user){
			$trans_name = $this->settings['transient_name'].$current_user->ID;
			if (get_transient($trans_name)) {
				$datas = get_transient($trans_name);
				$datas['tried']++;

				if ($datas['tried'] <= $this->settings['failed_login_limit']){
					set_transient($trans_name, $datas, $this->settings['lockout_duration']);
				}
			} else {
				$datas = array('tried' => 1);
				set_transient($trans_name, $datas, $this->settings['lockout_duration']);
			}
		}
	}
}

function rpgrestrictlogin() {
    global $rpgrestrictlogin;
    
    if( !isset($rpgrestrictlogin) ) {
        $rpgrestrictlogin = new rpgrestrictlogin();
        $rpgrestrictlogin->initialize();
    }
    
    return $rpgrestrictlogin;
}

//KICK OFF
rpgrestrictlogin();

endif;
?>