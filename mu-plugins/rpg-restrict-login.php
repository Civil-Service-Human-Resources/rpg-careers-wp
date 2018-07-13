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
            'name'               	=> __('RPG Restrict Login', 'rpgrestrictlogin'),
            'version'            	=> $this->version,
			'failed_login_limit' 	=> USER_LOGIN_LIMIT,
			'lockout_duration'   	=> LOCKOUT_DURATION,
			'lockout_trans'	 		=> LOCKOUT_TRANS_NAME,
			'user_meta_nonce_key'	=> AUTH_EXTEND_NONCE_KEY,
			'token_meta_key'	 	=> AUTH_EXTEND_TOKEN_KEY,
			'field_name'		 	=> 'rpg-veri-code',
			'resend_name'		 	=> 'rpg-code-resend',
			'token_duration'	 	=> AUTH_EXTEND_DURATION,	 
        );

		add_filter('authenticate', array($this, 'check_attempted_login'), 30, 3);
		add_action('wp_login_failed', array($this, 'login_failed'), 10, 1);
		add_action('wp_login', array($this, 'login_success'), 10, 2);
		add_action('login_form_validate_2fa', array($this, 'login_form_validate_2fa'));
    }

	public function check_attempted_login($user, $username, $password) {
		$current_user = get_user_by('login', $username);
		
		if($current_user){
			$trans_name = $this->settings['lockout_trans'].$current_user->ID;

			if (get_transient($trans_name)) {
				$datas = get_transient($trans_name);

				if ($datas['tried'] >= $this->settings['failed_login_limit']) {
					//PLAYBACK ERROR MESSAGE
					return new WP_Error('too_many_tried', sprintf( __( 'ERROR: You have reached the maximum number of attempts and have been locked out.')));
				}
			}
		}
        return $user;
    }

	public function login_failed($username) {
		$current_user = get_user_by('login', $username);

		if($current_user){
			$trans_name = $this->settings['lockout_trans'].$current_user->ID;
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

	public function login_success($user_login, $user) {
		if(AUTH_EXTEND_ON) {
			wp_clear_auth_cookie();
			$this->show_two_factor_login($user);
			exit;
		}else{
			//REMOVE LOCKOUT TRANSIENT AS SUCCESSFULLY LOGGED IN
			$trans_name = $this->settings['lockout_trans'].$user->ID;
			delete_transient($trans_name);
		}
	}

	public function show_two_factor_login($user) {
		if (!$user) {
			$user = wp_get_current_user();
		}

		$login_nonce = $this->create_login_nonce($user->ID);
		if (!$login_nonce) {
			$this->login_failure($user->user_login);
		}

		$redirect_to = isset($_REQUEST['redirect_to'])?$_REQUEST['redirect_to']:$_SERVER['REQUEST_URI'];
		$this->login_html($user, $login_nonce['key'], $redirect_to);
	}


	public function login_html($user, $login_nonce, $redirect_to, $error_msg = '') {
		$wp_login_url = wp_login_url();
		$interim_login = isset($_REQUEST['interim-login']);

		$rememberme = 0;
		if (isset($_REQUEST['rememberme']) && $_REQUEST['rememberme']) {
			$rememberme = 1;
		}

		login_header();

		if (!empty($error_msg))  {
			echo '<div id="login_error">'.$error_msg.'<br /></div>';
		}
		?>

		<form name="validate_2fa_form" id="loginform" action="<?php echo esc_url(set_url_scheme(add_query_arg('action', 'validate_2fa', $wp_login_url), 'login_post')); ?>" method="post" autocomplete="off">
				<input type="hidden" name="wp-auth-id" id="wp-auth-id" value="<?php echo esc_attr($user->ID ); ?>" />
				<input type="hidden" name="wp-auth-nonce" id="wp-auth-nonce" value="<?php echo esc_attr($login_nonce); ?>" />
				<?php if ($interim_login) { ?>
					<input type="hidden" name="interim-login" value="1" />
				<?php } else { ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
				<?php } ?>
				<input type="hidden" name="rememberme" id="rememberme" value="<?php echo esc_attr($rememberme); ?>" />
				<?php 
				if (!$user) {
					return;
				}

				if (!$this->user_has_token($user->ID)) {
					$this->generate_and_email_token($user);
				}

				require_once(ABSPATH . '/wp-admin/includes/template.php');
		?>
		<p style="margin-bottom:25px;">A verification code has been sent to the email address associated with your account.</p>
		<p><label for="authcode">Verification Code:</label>
			<input type="tel" name="<?php echo esc_attr($this->settings['field_name']); ?>" id="authcode" class="input" value="" size="20" maxlength="12" pattern="[0-9]*" autocomplete="off" /></p>
			<?php submit_button('Log in'); ?>
		<p>
			<input type="submit" class="button" name="<?php echo esc_attr($this->settings['resend_name']); ?>" value="Resend Code" />
		</p>
		<script type="text/javascript">setTimeout(function(){var d;try{d=document.getElementById('authcode');d.value='';d.focus();}catch(e){}},200);</script>
		</form>

		<p id="backtoblog"><a href="<?php echo esc_url(home_url('/')); ?>" title="Are you lost?"><?php echo esc_html(sprintf(__('&larr; Back to %s'), get_bloginfo('title', 'display'))); ?></a></p>
		<?php do_action('login_footer'); ?>
		<div class="clear"></div>
		</div>
		</body>
		</html>
		<?php
	}

	public function login_form_validate_2fa(){
		if (!isset($_POST['wp-auth-id'], $_POST['wp-auth-nonce'])) {
			return;
		}

		$user = get_userdata($_POST['wp-auth-id']);
		if (!$user) {
			return;
		}

		$nonce = $_POST['wp-auth-nonce'];
		if (true!==$this->verify_login_nonce($user->ID, $nonce)) {
			$this->login_failure($user->user_login);
		}

		//REQUEST TO RE-SEND THE CODE?
		if (true === $this->pre_process_authentication($user)) {
			$login_nonce = $this->create_login_nonce($user->ID);
			if (!$login_nonce) {
				$this->login_failure($user->user_login);
			}

			$this->login_html($user, $login_nonce['key'], $_REQUEST['redirect_to'], '');
			exit;
		}

		//CHECK HOW MANY ATTEMPTS BEFORE VERIFYING AGAIN
		$trans_name = $this->settings['lockout_trans'].$user->ID;

		if (get_transient($trans_name)) {
			$datas = get_transient($trans_name);

			if ($datas['tried'] >= $this->settings['failed_login_limit']) {
				//PLAYBACK ERROR MESSAGE
				$login_nonce = $this->create_login_nonce($user->ID);
				if (!$login_nonce) {
					$this->login_failure();
				}

				$this->login_html($user, $login_nonce['key'], $_REQUEST['redirect_to'], '<strong>ERROR</strong>: You have reached the maximum number of attempts and have been locked out');
				exit;
			}
		}


		//VERIFY THE POSTED VALUES
		if (true !== $this->validate_authentication($user)) {
			do_action('wp_login_failed', $user->user_login);

			$login_nonce = $this->create_login_nonce($user->ID);
			if (!$login_nonce) {
				$this->login_failure();
			}

			$this->login_html($user, $login_nonce['key'], $_REQUEST['redirect_to'], '<strong>ERROR</strong>: Verification code failure');
			exit;
		}

		//GOOD TO PROCEED...
		$this->delete_login_nonce($user->ID);

		//REMOVE LOCKOUT TRANSIENT AS SUCCESSFULLY LOGGED IN
		$trans_name = $this->settings['lockout_trans'].$user->ID;
		delete_transient($trans_name);

		$rememberme = false;
		if (isset($_REQUEST['rememberme']) && $_REQUEST['rememberme']) {
			$rememberme = true;
		}

		wp_set_auth_cookie($user->ID, $rememberme);

		//DEALING WITH AN INTERIM LOGIN?
		global $interim_login;
		$interim_login = isset($_REQUEST['interim-login']);

		if ($interim_login) {
			$message = '<p class="message">You have logged in successfully.</p>';
			$interim_login = 'success';
			login_header('', $message); ?>
			</div>
			<?php
			/** This action is documented in wp-login.php */
			do_action('login_footer'); ?>
			</body></html>
			<?php
			exit;
		}

		$redirect_to = apply_filters('login_redirect', $_REQUEST['redirect_to'], $_REQUEST['redirect_to'], $user);
		wp_safe_redirect($redirect_to);
		exit;
	}

	public function generate_token($user_id) {
		$token = $this->make_code();
		$trans_name = $this->settings['token_meta_key'].$user_id;
		set_transient($trans_name, wp_hash($token), $this->settings['token_duration']);
		return $token;
	}

	public function user_has_token($user_id) {
		$hashed_token = $this->get_user_token($user_id);
		if (!empty($hashed_token)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_user_token($user_id) {
		$trans_name = $this->settings['token_meta_key'].$user_id;
		$hashed_token = get_transient($trans_name);
		if (!empty($hashed_token) && is_string($hashed_token)) {
			return $hashed_token;
		}
		return false;
	}

	public function validate_token($user_id, $token) {
		$hashed_token = $this->get_user_token($user_id);
		
		if (empty($hashed_token) || (wp_hash($token) !== $hashed_token)) {
			return false;
		}
		
		$this->delete_token($user_id);
		return true;
	}

	public function delete_token($user_id) {
		$trans_name = $this->settings['token_meta_key'].$user_id;
		delete_transient($trans_name);
	}

	public function generate_and_email_token($user) {
		$token = $this->generate_token($user->ID);
		
		$data = array('email' => $user->user_email, 'templateID' => AUTH_EXTEND_TOKEN_TEMPLATE, 'notifyCode' => $token);
		$data_string = json_encode($data);       
																															 
		$ch = curl_init(AUTH_EXTEND_TOKEN_SEND);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);   
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, AUTH_EXTEND_TOKEN_AUTH_A . ':' . AUTH_EXTEND_TOKEN_AUTH_B);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);                                                                
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
		
		$errors = curl_error($ch);
		$result = curl_exec($ch);
		$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		//TODO: HOW TO HANDLE ERRORS
		curl_close($ch);

	
	}

	public function pre_process_authentication($user) {
		if (isset($user->ID) && isset($_REQUEST[$this->settings['resend_name']])) {
			$this->generate_and_email_token($user);
			return true;
		}
		return false;
	}

	public function validate_authentication($user) {
		if (!isset($user->ID) || !isset($_REQUEST[$this->settings['field_name']])) {
			return false;
		}
		return $this->validate_token($user->ID, $_REQUEST[$this->settings['field_name']]);
	}

	function make_code($length=6, $chars='1234567890') {
		$code = '';
		if (!is_array($chars)) {
			$chars = str_split($chars);
		}
		for ($i=0;$i<$length;$i++) {
			$key = array_rand($chars);
			$code .= $chars[$key];
		}
		return $code;
	}

	function create_login_nonce($user_id) {
		$login_nonce = array();
		try {
			$login_nonce['key'] = bin2hex(random_bytes(32));
		} catch (Exception $ex) {
			$login_nonce['key'] = wp_hash($user_id.mt_rand().microtime(), 'nonce');
		}
		$login_nonce['expiration'] = time() + HOUR_IN_SECONDS;

		if (!update_user_meta($user_id, $this->settings['user_meta_nonce_key'], $login_nonce)) {
			return false;
		}

		return $login_nonce;
	}

	function delete_login_nonce($user_id) {
		return delete_user_meta($user_id, $this->settings['user_meta_nonce_key']);
	}

	function verify_login_nonce( $user_id, $nonce ) {
		$login_nonce = get_user_meta($user_id, $this->settings['user_meta_nonce_key'], true);
		if (!$login_nonce) {
			return false;
		}

		if ($nonce!==$login_nonce['key'] || time() > $login_nonce['expiration']) {
			$this->delete_login_nonce($user_id);
			return false;
		}

		return true;
	}

	function login_failure($username=''){
		if($username !== ''){
			do_action('wp_login_failed', $username);
		}
		wp_safe_redirect(get_bloginfo('url'));
		exit;
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