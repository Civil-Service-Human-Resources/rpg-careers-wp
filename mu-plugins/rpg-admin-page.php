<?php 
/*
Plugin Name: RPG Admin Page
Description: Add in bespoke admin page
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgadminpage
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

if(!class_exists('rpgadminpage')):

class rpgadminpage{

	var $version = '1.0.0';
    var $settings = array();
    
    function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

	function initialize(){
        $this->settings = array(
            'name'               => __('RPG Admin Page', 'rpgadminpage'),
            'version'            => $this->version,
        );

		$this->rpg_admin_page_constants('SCREEN_TITLE_NAME', 'rpg_dashboard_screen_title_name');
		$this->rpg_admin_page_constants('SCREEN_HTML_NAME', 'rpg_dashboard_screen_html_name');
		$this->rpg_admin_page_constants('HIDDEN_FIELD_NAME', 'rpg_dashboard_submit_hidden');
		$this->rpg_admin_page_constants('HIDDEN_FIELD_VAL', 'XzxY');

		add_action('admin_menu',array($this,'rpg_dashboard_menu'));
		add_action('admin_menu',array($this,'rpg_admin_page_settings_menu'));
    }

	function rpg_dashboard_menu() {
		$opt_title_val = str_replace('\\','',(get_option(RPG_ADMINPG_SCREEN_TITLE_NAME)));
	
		if ($opt_title_val==''){
			$opt_title_val = 'Sample Admin Page';
		}

		//ADD THE DASHBOARD MENU ITEM	
		if (function_exists('add_dashboard_page')) {
			add_dashboard_page(__($opt_title_val), __($opt_title_val), 'read', basename(__FILE__), array($this,'custom_rpg_dashboard_page'));
		}
	}

	function rpg_admin_page_settings_menu() {
		if (function_exists('add_options_page')) {
			add_options_page(__('RPG Admin Page'), __('RPG Admin Page'), 'manage_options', basename(__FILE__), array($this,'custom_rpg_dashboard_settings_page'));
		}
	}

	function custom_rpg_dashboard_page(){
		//GET EXISITNG OPTIONS FROM DB
		$opt_html_val = get_option(RPG_ADMINPG_SCREEN_HTML_NAME);

		//SET DEFAULT PAGE CONTENT	
		if ($opt_html_val==''){
			$opt_html_val = '<div><h1>*** Sample RPG Admin Page ***</h1><p><strong>Note:</strong>To add bespoke html code to this page visit @quot;RPG Admin Page@quot; under Settings admin menu.</p></div>';
		}
	
		echo str_replace('\\', '', $opt_html_val);
	}

	function custom_rpg_dashboard_settings_page(){
		$opt_title_val = get_option(RPG_ADMINPG_SCREEN_TITLE_NAME);
		$opt_html_val = get_option(RPG_ADMINPG_SCREEN_HTML_NAME);

		if(isset($_POST[RPG_ADMINPG_HIDDEN_FIELD_NAME]) && $_POST[RPG_ADMINPG_HIDDEN_FIELD_NAME]==RPG_ADMINPG_HIDDEN_FIELD_VAL) {
			$opt_title_val = $_POST[RPG_ADMINPG_SCREEN_TITLE_NAME];
			$opt_html_val = $_POST[RPG_ADMINPG_SCREEN_HTML_NAME];
			update_option(RPG_ADMINPG_SCREEN_TITLE_NAME, $opt_title_val);
			update_option(RPG_ADMINPG_SCREEN_HTML_NAME, $opt_html_val);
		?>
			<div class="updated"><p><strong>Settings saved</strong></p></div>
		<?php
		}
		?>
		<div class="wrap">
		<h2>RPG Admin Page</h2>
		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo RPG_ADMINPG_HIDDEN_FIELD_NAME; ?>" value="<?php echo RPG_ADMINPG_HIDDEN_FIELD_VAL; ?>">
		<p>Menu title:
		<input type="text" name="<?php echo RPG_ADMINPG_SCREEN_TITLE_NAME; ?>" value="<?php echo $opt_title_val; ?>" size="20"> <br/><br/>
		<textarea id="wacp-txtarea" name="<?php echo RPG_ADMINPG_SCREEN_HTML_NAME; ?>" cols="100" rows="20"><?php echo str_replace('\\','',$opt_html_val); ?></textarea>
		</p><hr />
		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save changes') ?>" /></p>
		</form>
		</div>
	<?php
	}


	function rpg_admin_page_constants($constant_name, $value) {
		$constant_name_prefix = 'RPG_ADMINPG_';
		$constant_name = $constant_name_prefix . $constant_name;
		if (!defined( $constant_name))
			define($constant_name, $value);
	}
}

function rpgadminpage() {
    global $rpgadminpage;
    
    if( !isset($rpgadminpage) ) {
        $rpgadminpage = new rpgadminpage();
        $rpgadminpage->initialize();
    }
    
    return $rpgadminpage;
}

//KICK OFF
rpgadminpage();

endif;
?>