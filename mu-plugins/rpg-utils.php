<?php
/*
Plugin Name: RPG Utils
Description: Helper functions to support RPG
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgutils
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

$cookie_banner_set = false;

if(!class_exists('rpgutils')):

class rpgutils{

    var $version = '1.0.0';
    var $settings = array();
    
    function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

    function initialize(){
        $this->settings = array(
            'name'               => __('RPG Utils', 'rpgutils'),
            'version'            => $this->version,
            'all_teams'          => '',
            'users_teams'        => '',
        );

        //REGISTER ACTIONS/FILTERS
		add_action('init', array($this, 'register_user_taxonomy'));
		add_action('init', array($this, 'check_cookie_banner_cookie'));
        add_filter('login_redirect', array($this, 'login_redirect'), 10, 3);

		//PAGE EDITS
		add_filter('post_row_actions', array($this, 'amend_quick_links'), 10, 2);
		add_filter('page_row_actions', array($this, 'amend_quick_links'), 10, 2);
		add_filter('tag_row_actions', array($this, 'amend_quick_links'), 10, 2);
        
        //TEAMS ACCESS CONTROL
        add_filter('manage_page_posts_columns', array($this, 'manage_columns'));
        add_action('manage_page_posts_custom_column', array($this, 'custom_column'), 10, 2);
        add_action('add_meta_boxes_page', array($this, 'add_meta_boxes'), 10, 2);
        add_action('admin_init', array($this, 'admin_init'));

        add_filter('filter_gtm_instance', array($this, 'filter_gtm_instance'),1);
		add_filter('post_date_column_time' , array($this, 'custom_date_column_time') , 10 , 2);

		add_action('user_profile_update_errors', array($this, 'check_profile_errors'));
		add_filter('pre_option_default_role', array($this, 'set_default_role'));
		add_action('admin_menu', array($this, 'amend_menus'));

		add_action('get_header', array($this, 'remove_admin_login_header'));
		show_admin_bar(false);

        //add_action('shutdown', array($this, 'sql_logger'));

		//MEDIA FILES
		add_action('pre_get_posts',  array($this, 'filter_media_files'));
		add_filter('attachment_fields_to_edit', array($this, 'media_team_fields_create'), 10, 2);
		add_filter('attachment_fields_to_save', array($this, 'media_team_fields_save'), 10, 2);
		add_action('wp_ajax_save-attachment', array($this, 'media_save_ajax'), 0, 1); 
		add_action('wp_ajax_save-attachment-compat', array($this, 'media_team_fields_save_ajax'), 0, 1); 
		add_filter('manage_media_columns', array($this, 'media_team_add_custom_columns'));
		add_action('manage_media_custom_column', array($this, 'media_team_manage_custom_columns'), 10, 2);
		add_action('admin_enqueue_scripts', array($this, 'media_team_scripts'));
		add_action('add_attachment', array($this, 'media_add_attachment'));
		add_filter('upload_mimes', array($this, 'media_mime_types'), 1, 1);
		add_filter('wp_handle_upload_prefilter', array($this, 'media_check_size'));
		add_action('admin_head', array($this, 'media_css'));
		add_action('post-upload-ui', array($this, 'media_max_size_info'));
		add_filter('option_uploads_use_yearmonth_folders', '__return_false', 100);

		//FORCE IMAGE SIZES - UNABLE TO UPDATE VIA ADMIN SCREENS
		add_filter('pre_update_option_thumbnail_size_w', function(){ return 180; });
		add_filter('pre_update_option_thumbnail_size_h', function(){ return 180; });
		add_filter('pre_update_option_thumbnail_crop', function(){ return 0; });
		add_filter('pre_update_option_medium_size_w', function(){ return 320; });
		add_filter('pre_update_option_medium_size_h', function(){ return 240; });
		add_filter('pre_update_option_medium_large_size_w', function(){ return 480; });
		add_filter('pre_update_option_medium_large_size_h', function(){ return 360; });
		add_filter('pre_update_option_large_size_w', function(){ return 800; });
		add_filter('pre_update_option_large_size_h', function(){ return 600; });

    }

	function remove_admin_login_header() {
        remove_action('wp_head', '_admin_bar_bump_cb');
	}

	function amend_menus() {
		remove_action('admin_notices', 'update_nag', 3);
		remove_menu_page('edit.php');
		remove_menu_page('edit-comments.php');
	}

	function check_profile_errors(&$errors) {
		if ( empty( $_POST['content_team'] ) )
			$errors->add( 'empty_missing_', '<strong>ERROR</strong>: Profile not saved - a team must be selected' );
	}

    function sql_logger() {
        //PLUS define( 'SAVEQUERIES', true ); IN wp-config.php
        global $wpdb;
        $log_file = fopen(ABSPATH.'/sql_log.txt', 'a');
        fwrite($log_file, "//////////////////////////////////////////\n\n" . date("F j, Y, g:i:s a")."\n");
        foreach($wpdb->queries as $q) {
            fwrite($log_file, $q[0] . " - ($q[1] s)" . "\n\n");
        }
        fclose($log_file);
    }

    function admin_init(){
        add_action('save_post', array($this, 'save_post'),10, 3);
        add_action('admin_notices', array($this, 'handle_admin_error'));
        add_action('load-edit.php', array($this, 'load_edit'));

        //GET ALL CURRENT TEAMS AND STORE THEM - SAVES LOOKUPS LATER ON IN CODE
        $teams = array();
        $currentteams = get_terms(array('taxonomy' => 'content_team','hide_empty' => false, 'parent' => 0));
        $count = 0;
        foreach($currentteams as $team) {

            $teams[$count]['term_id'] = $team->term_id;
            $teams[$count]['name'] = $team->name;
            $count++;
        }

        $this->settings['all_teams'] = $teams;

        //STORE TEAMS CURRENT USER HAS ACCESS TO 
        //NB: HOOKS INTO FUNCTION FROM THE 'WP User Groups' PLUGIN
        $this->settings['users_teams'] = (wp_get_terms_for_user(get_current_user_id(), 'content_team')) ? wp_get_terms_for_user(get_current_user_id(), 'content_team') : array();

        //UNCOMMENT THIS TO REMOVE UNWANTED CAPABILITIES - SET THEM IN THE FUNCTION
        //$this->clean_unwanted_caps();

        //add_meta_box('submitdiv', 'Publish', array($this, 'custom_sumbit_meta_box'), 'page', 'side', 'high');

        //***START: KEEP AT BOTTOM OF FUNCTION***
        //NB: KEEP AT BOTTOM OF FUNCTION AS A FEW return STATEMENTS TO BE CAREFUL OF
        global $pagenow;
        if ($pagenow!=='profile.php' && $pagenow!=='user-edit.php') {
            return;
        }
 
        //IF CURRENT USER CAN CREATE USERS THEN DO NOT AMEND THE SCREEN
        if (current_user_can('create_users')) {
            return;
        }
 
        //CALL OFF TO AMEND THE PROFILE SCREEN
        add_action('admin_footer', array($this,'amend_profile_fields_disable_js'));
        //***END: KEEP AT BOTTOM OF FUNCTION***
    }
    
	function amend_quick_links($actions, $post) {

		if (isset($actions['inline hide-if-no-js'])) {
			unset($actions['inline hide-if-no-js']);
		}

		//ALLOW DELETE LINK FOR TEAMS
		if(isset($post->taxonomy)) {
			if($post->taxonomy == 'content_team'){
				return $actions;
			}
		}

		if (isset($actions['trash'])) {
			unset($actions['trash']);
		}

		return $actions;
	}

    function custom_sumbit_meta_box($post, $args = array()){
        global $post;
		global $pagenow;

		if($post->post_type!=='page'){ return;}

		$edit_page = false;
		if (($pagenow=='post.php') && ($post->post_type=='page')) {$edit_page = true;}

        remove_meta_box('submitdiv', 'page', 'side');
		$post_type_object = get_post_type_object($post->post_type);
		$can_publish = current_user_can($post_type_object->cap->publish_posts);
    ?>
        <div class="submitbox" id="submitpost"><div id="minor-publishing"><div style="display:none;">
        <?php submit_button( __( 'Save' ), '', 'save' ); ?>
		</div>

		<div id="minor-publishing-actions">
		<div id="save-action">
		<?php if ( 'publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status ) { ?>
		<input <?php if ( 'private' == $post->post_status ) { ?>style="display:none"<?php } ?> type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save Draft'); ?>" class="button" />
		<span class="spinner"></span>
		<?php } elseif ( 'pending' == $post->post_status && $can_publish ) { ?>
		<input type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save as Pending'); ?>" class="button" />
		<span class="spinner"></span>
		<?php } ?>
		</div>
		<?php if ( is_post_type_viewable( $post_type_object ) ) : ?>
		<div id="preview-action">
		<?php
		$preview_link = esc_url( get_preview_post_link( $post ) );
		if ( 'publish' == $post->post_status ) {
			$preview_button_text = __( 'Preview Changes' );
		} else {
			$preview_button_text = __( 'Preview' );
		}

		$preview_button = sprintf( '%1$s<span class="screen-reader-text"> %2$s</span>',
			$preview_button_text,
			/* translators: accessibility text */
			__( '(opens in a new window)' )
		);
		?>
		<a class="preview button" href="<?php echo $preview_link; ?>" target="wp-preview-<?php echo (int) $post->ID; ?>" id="post-preview"><?php echo $preview_button; ?></a>
		<input type="hidden" name="wp-preview" id="wp-preview" value="" />
		</div>
		<?php endif; // public post type ?>
		<?php
		/**
		 * Fires before the post time/date setting in the Publish meta box.
		 *
		 * @since 4.4.0
		 *
		 * @param WP_Post $post WP_Post object for the current post.
		 */
		do_action( 'post_submitbox_minor_actions', $post );
		?>
		<div class="clear"></div>
		</div>
        <div id="misc-publishing-actions">
        <div class="misc-pub-section misc-pub-post-status">
        Status: <span id="post-status-display"><?php
switch ($post->post_status) {
	case 'private':
		_e('Privately Published');
		break;
	case 'publish':
		_e('Published');
		break;
	case 'future':
		_e('Scheduled');
		break;
	case 'pending':
		_e('Pending Review');
		break;
	case 'draft':
	case 'auto-draft':
		_e('Draft');
		break;
}
?></span>
        </div>
		<?php 
		$revision_count = count(wp_get_post_revisions($post->ID));
		$latest_revision = current(wp_get_post_revisions($post->ID));
		$revision_id = $latest_revision->ID;

		if ($revision_count>0) : ?>
<div class="misc-pub-section misc-pub-revisions">
	<?php
		/* translators: Post revisions heading. 1: The number of available revisions */
		printf( __( 'Revisions: %s' ), '<b>' . number_format_i18n($revision_count) . '</b>' );
	?>
	<a class="hide-if-no-js" href="<?php echo esc_url(get_edit_post_link($revision_id)); ?>"><span aria-hidden="true"><?php _ex( 'Browse', 'revisions' ); ?></span> <span class="screen-reader-text"><?php _e( 'Browse revisions' ); ?></span></a>
</div>
<?php endif; 

$datef = __( 'M j, Y @ H:i' );
if ( 0 != $post->ID ) {
	if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
		/* translators: Post date information. 1: Date on which the post is currently scheduled to be published */
		$stamp = __('Scheduled for: <b>%1$s</b>');
	} elseif ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
		/* translators: Post date information. 1: Date on which the post was published */
		$stamp = __('Published on: <b>%1$s</b>');
	} elseif ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
		$stamp = __('Publish <b>immediately</b>');
	} elseif ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
		/* translators: Post date information. 1: Date on which the post is to be published */
		$stamp = __('Schedule for: <b>%1$s</b>');
	} else { // draft, 1 or more saves, date specified
		/* translators: Post date information. 1: Date on which the post is to be published */
		$stamp = __('Publish on: <b>%1$s</b>');
	}
	$date = date_i18n( $datef, strtotime( $post->post_date ) );
} else { // draft (no saves, and thus no date specified)
	$stamp = __('Publish <b>immediately</b>');
	$date = date_i18n( $datef, strtotime( current_time('mysql') ) );
}
?>
	<div class="misc-pub-section curtime misc-pub-curtime">
		<span id="timestamp">
		<?php printf($stamp, $date); ?></span>
	</div>
<?php if($edit_page){echo '';} ?>
        </div>
        </div>

        <div id="major-publishing-actions">
        <div id="publishing-action" style="width: 100%;">
        <span class="spinner"></span>
        <input name="original_publish" type="hidden" id="original_publish" value="Publish">
        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publish" style="display: none;">
        </div>
        <div class="clear"></div>
        </div>
        </div>
        <?php
    }

    function filter_gtm_instance($code_tag){
        if(GTM_ON){
            $code_tag=str_replace('!!CONTAINER_ID!!', GTM_CONTAINER_ID, $code_tag);
        }else{
            $code_tag = '';
        }
        return $code_tag;
    }

	function filter_media_files($query){

		//ONLY FOR ADMIN PAGES
		if(is_admin()){
			$post_type = $query->query["post_type"];

			//ON THE MEDIA LIBRARY BACKEND PAGE OR DOING A REVISION (I.E. DIRECT URL ROUTE)?
			if(isset($post_type) && $post_type === 'attachment'){

				//IF USER CAN SEE manage_options (i.e. ADMIN TYPE USE) - NO FILTERING
				if(!$this->restrict_access()){
					return;
				}

				 //GET TEAMS CURRENT USER IS MEMBER OF
				$teams = $this->get_setting('users_teams');

				if($post_type === 'attachment'){
					//FILTER MEDIA BASED ON TEAMS MEMBER OF - BUILD QUERY VAR
					if(count($teams)>0){

						if(count($teams) > 1) {
							$meta_query = array('relation' => 'OR');
						}
					
						foreach ($teams as $team) {
							$values_to_search[] = $team->term_id;
						}
					}else{
						//NOT IN ANY TEAMS SO CANNOT SEE ANYTHING - SET KEY TO ONE THAT WILL NEVER BE VALID - FORCES 'NO MEDIA FOUND' MESSAGE TO SHOW
						$values_to_search[] = 'NOT-VALID';
					}

					foreach ($values_to_search as $value) {
						$meta_query[] = array(
							'key'       => 'team-access-'.$value,
							'value'     => '1',
							'compare'   => '=',
						);
					}

					//ADD QUERY VAR
					$query->set('meta_query', $meta_query);
				}

				if($post_type === 'revision'){
					//LOOP ROUND TEAMS AND CHECK META DATA FOR POST
					if(count($teams)>0){
						foreach ($teams as $team) {
							$check = get_post_meta(get_the_ID(), 'team-access-'.$team->term_id, true);
							if(strlen($check)>0){
								//GOT A MATCH...
								$fail = false;
								break;
							}else{
								$fail = true;
							}
						}
					}else{
						//NOT IN ANY TEAMS
						$fail = true;
					}

					//NO MATCHES THEN DISPLAY MESSAGE BACK
					if($fail){
						echo $this->get_die_html('Unable to edit post','Sorry it is not possible to edit that post.');
						exit();
					}
				}
				
			}
		}
	}

	function media_team_fields_create($form_fields, $post) {

 		//GET TEAMS CURRENT USER IS MEMBER OF
		$teams = $this->get_setting('users_teams');
		
		//GET THE NON DUPLICATES BETWEEN IMAGE TEAMS AND USERS TEAMS - NEED TO CHECK THESE AGAINST USERS TEAMS WHEN RENDERING
		$user_teams = array();
		$unique_items =  $this->get_teams_non_dupes($post->ID, $user_teams);

		//RENDER CHECKBOXES
		if(count($teams)>0){
			$match = false;
			
			//ANY ASSIGNED IMAGE TEAMS THAT CURRENT USER DOES NOT HAVE ACCESS TO?
			foreach($unique_items as $team_no_access){
				if(!in_array($team_no_access,$user_teams)){
					$match = true;
					break;
				}
			}

			//RENDER CHECKBOXES FOR USER TEAMS
			$loop = 0;
			foreach ($user_teams as $key => $team) {
				$field_id = 'team-'.$user_teams[$key];
				$form_fields[$field_id] = array(
					'label' => (($loop==0)?'Assign to':''),
					'input' => 'html',
					'html' => '<label class="selectit" style="display:inline-block;margin-top:6px;" for="attachments['.$post->ID.']['.$field_id.']"><input type="checkbox" value="1"'. ((get_post_meta( $post->ID, 'team-access-'.$user_teams[$key], true )=='1') ? ' checked="checked"' : '')  .' name="attachments['.$post->ID.']['.$field_id.']" id="attachments['.$post->ID.']['.$field_id.']"'. (count($teams)==1 && !$match ? ' onclick="this.checked=!this.checked;" checked="checked"': '') .' style="margin-top:-3px;" />'.$teams[$key]->name.'</label>'
				);
				$loop++;
			}
			
			//RENDER CHECKBOXES FOR ASSIGNED IMAGE TEAMS THAT USER DOES NOT HAVE ACCESS TO
			$loop = 0;
			//DEAL WITH UNIQUE TEAMS - CHECK TO SEE IF NOT IN $user_teams
			foreach($unique_items as $team_no_access){
				if(!in_array($team_no_access,$user_teams)){
					$field_id = 'team-'.$team_no_access;
					$form_fields[$field_id] = array(
						'label' => (($loop==0)?'Also assigned to':''),
						'input' => 'html',
						'html' => '<label class="selectit" style="display:inline-block;margin-top:6px;" for="attachments['.$post->ID.']['.$field_id.']"><input type="checkbox" value="1" checked="checked" name="attachments['.$post->ID.']['.$field_id.']" id="attachments['.$post->ID.']['.$field_id.']" disabled="disabled" style="margin-top:-3px;" />'.get_term_by('id', $team_no_access, 'content_team')->name.'</label>'
					);
					$loop++;
				}
			}

			$field_id = 'team-save-msg';

			$form_fields[$field_id] = array(
                'label' => '',
                'input' => 'html',
                'html' => '<span id="attachments['.$post->ID.']['.$field_id.']" style="font-weight:bold;color:#ff0000;"></span>',
                'show_in_edit' => false,
			);

		}else{
			//NOT IN ANY TEAMS - SO DO NOT ALLOW ANY MEDIA TO BE ADDED
		}
		return $form_fields;
	}
 
	function media_team_fields_save($post, $attachment) {
		$fail = false;
		$failcount = 0;
		$post_id = $post['post_ID'];
		//CHECK THE POSTED VALUES

		//IF AJAX RETURN AS media_team_fields_save_ajax ALREADY RUN
		if(wp_doing_ajax()){
			return $post;
		}

		//GET TEAMS CURRENT USER IS MEMBER OF
		$teams = $this->get_setting('users_teams');

		//GET THE NON DUPLICATES BETWEEN IMAGE TEAMS AND USERS TEAMS - NEED TO CHECK THESE AGAINST USERS TEAMS WHEN RENDERING
		$user_teams = array();
		$unique_items =  $this->get_teams_non_dupes($post_id, $user_teams);

		//FILTER MEDIA BASED ON TEAMS MEMBER OF - BUILD QUERY VAR
		if(count($teams)>0){
			$match = false;

			//ANY ASSIGNED IMAGE TEAMS THAT CURRENT USER DOES NOT HAVE ACCESS TO?
			foreach($unique_items as $team_no_access){
				if(!in_array($team_no_access,$user_teams)){
					$match = true;
					break;
				}
			}

			//ONLY CHECK IF NO OTHER ASSIGNED TEAMS SELECTED 
			if(!$match){
				foreach ($teams as $team) {
					$field_id = 'team-'.$team->term_id;

					//CHECK THAT AT LEAST ONE TEAM HAS BEEN selected
					if(!isset($attachment[$field_id])){
						$failcount++;
					}

					if($failcount===count($teams)){
						//NO TEAMS SELECTED
						$fail = true;
					}
				}
			}

			if(!$fail){
				foreach ($teams as $team) {
					$field_id = 'team-'.$team->term_id;
					//IF AT LEAST ONE TEAM SELECTED UPDATE THE POST META DATA
					if(isset($attachment[$field_id])){
						update_post_meta($post_id, 'team-access-'.$team->term_id, $attachment[$field_id]);
					} else {
						update_post_meta($post_id, 'team-access-'.$team->term_id, '0');
					}
				}
			}
		}else{
			//NOT IN ANY TEAMS - SO DO NOT ALLOW MEDIA TO BE SAVED
			$fail = true;
		}

		if($fail){
			echo $this->get_die_html('Saving media file','Unable to save changes to media - no team selected.<br/><br/><a href="javascript:history.back();">Go back and fix the media</a>');
			exit();
		}


		//CHECK THE ALT TEXT
		if(isset($post['_wp_attachment_image_alt'])){
			if(trim($post['_wp_attachment_image_alt']) === ''){
				echo $this->get_die_html('Saving media file','Unable to save changes to media - alt text has been left blank.<br/><br/><a href="javascript:history.back();">Go back and fix the media</a>');
				exit();
			}
		}

		return $post;
	}

	function media_team_add_custom_columns($posts_columns) {
		unset($posts_columns['date']);
		//ADD IN DATE + TEAM COLUMN
		$posts_columns['media_date'] = _x('Date', 'column name');
		$posts_columns['media_teams'] = _x('Teams', 'column name');
		return $posts_columns;
	}

	function media_team_manage_custom_columns($column_name, $id) {
		switch($column_name) {
			case 'media_teams':
				$meta = get_post_meta($id, '');
				$empty = true;
				$teams='';

				foreach($meta as $key => $value){
					if (strpos($key, 'team-access-') === 0) {
						if($value[0]==='1'){
							$teams.= get_term_by('id', (int)substr($key, strlen('team-access-')), 'content_team')->name.', ';
							$empty = false;
						}
					}
				}
				if($empty){
					echo '&mdash;';
				} else {
					echo rtrim($teams, ', ');
				}
				break;
			case 'media_date':
				echo get_post_time('d/m/Y', false, $id);
				break;

			default:
				break;
		}
	}

	function media_save_ajax() {
		$post_id = $_POST['id'];
		$err = 'Media not saved - ';
		$fail = false;

		$changes = $_POST['changes'];

		if(isset($changes["alt"])){
			if(trim($changes['alt']) === ''){
				$err.='Alt Text cannot be blank';
				$fail = true;
			}
		}
		
		if($fail){
			wp_send_json_error(array('attachments['.$post_id.'][team-save-msg]' => __($err)));
		}

		clean_post_cache($post_id);
	}

	function media_team_fields_save_ajax() {
		$post_id = $_POST['id'];
		$fail = false;
		$failcount = 0;
		$attachments = $_POST['attachments'][$post_id];
		
		//GET TEAMS CURRENT USER IS MEMBER OF
		$teams = $this->get_setting('users_teams');

		//GET THE NON DUPLICATES BETWEEN IMAGE TEAMS AND USERS TEAMS - NEED TO CHECK THESE AGAINST USERS TEAMS WHEN RENDERING
		$user_teams = array();
		$unique_items =  $this->get_teams_non_dupes($post_id, $user_teams);

		//FILTER MEDIA BASED ON TEAMS MEMBER OF - BUILD QUERY VAR
		if(count($teams)>0){
			$match = false;

			//ANY ASSIGNED IMAGE TEAMS THAT CURRENT USER DOES NOT HAVE ACCESS TO?
			foreach($unique_items as $team_no_access){
				if(!in_array($team_no_access,$user_teams)){
					$match = true;
					break;
				}
			}

			//ONLY CHECK IF NO OTHER ASSIGNED TEAMS SELECTED 
			if(!$match){
				foreach ($teams as $team) {
					$field_id = 'team-'.$team->term_id;

					//CHECK THAT AT LEAST ONE TEAM HAS BEEN selected
					if(!isset($attachments[$field_id])){
						$failcount++;
					}

					if($failcount===count($teams)){
						//NO TEAMS SELECTED
						$fail = true;
					}
				}
			}

			if(!$fail){
				foreach ($teams as $team) {
					$field_id = 'team-'.$team->term_id;
					//IF AT LEAST ONE TEAM SELECTED UPDATE THE POST META DATA
					if(isset($attachments[$field_id])){
						update_post_meta($post_id, 'team-access-'.$team->term_id, $attachments[$field_id]);
					} else {
						update_post_meta($post_id, 'team-access-'.$team->term_id, '0');
					}
				}
			}
		}else{
			//NOT IN ANY TEAMS - SO DO NOT ALLOW MEDIA TO BE SAVED
			$fail = true;
		}

		if($fail){
			wp_send_json_error(array('attachments['.$post_id.'][team-save-msg]' => __('Media not saved - a team must be selected')));
		}

		clean_post_cache($post_id);
	} 

	function media_team_scripts($hook){
		wp_enqueue_script('custom_media_script', get_template_directory_uri() . '/customMedia.js', '','',true );
	?>
<script type="text/javascript">(function(){window.wprpg = {'mediateams':'<?php echo count($this->get_setting('users_teams')); ?>'};})();</script>
	<?php
	}

	function media_add_attachment($post_ID){
		//ATTACHMENT JUST BEEN ADDED - NEED TO SORT TEAM META DATA
		$teams = $this->get_setting('users_teams');

		//IF CURRENT USER ONLY IN ONE TEAM - JUST UPDATE META DATA TO REFLECT THIS
		if(count($teams)>0){
			if(count($teams)===1){
				update_post_meta($post_ID, 'team-access-'.$teams[0]->term_id, '1');
			}else{
				//MORE THAN ONE TEAM - CUSTOM js HANDLES THIS USING A RE-DIRECT - SEE customMedia.js IN THEME
			}

			//SET META DATA ON THE ATTACHMENT
			if (wp_attachment_is_image($post_ID)) {
				$image_title = get_post($post_ID)->post_title;
				$image_title = preg_replace('%\s*[-_\s]+\s*%', ' ',$image_title);
				$image_title = ucwords(strtolower($image_title));
				$image_meta = array(
					'ID' => $post_ID,
					'post_title' => $image_title,
				);

				//ALT TEXT
				update_post_meta($post_ID, '_wp_attachment_image_alt', $image_title);
				
				// Set the image meta (e.g. Title, Excerpt, Content)
				wp_update_post($image_meta);
			}

		}else{
			//USER IN NO TEAMS - REMOVE MEDIA - WARN USER
			wp_delete_attachment($post_ID, 'true');
			wp_send_json_error(array('message' => 'Media cannot be added - no teams available'));
		}
	}

	function media_mime_types($mime_types){

		//ADD IN ADDITIONAL MIME TYPES
		$mime_types['svg'] = 'image/svg+xml';
  
		//REMOVE UNWANTED STD MIME TYPES
		unset($mime_types['ico']);
		unset($mime_types['pdf']);
		unset($mime_types['doc']);
		unset($mime_types['docx']);
		unset($mime_types['ppt']);
		unset($mime_types['pptx']);
		unset($mime_types['pps']);
		unset($mime_types['ppsx']);
		unset($mime_types['odt']);
		unset($mime_types['xls']);
		unset($mime_types['xlsx']);
		unset($mime_types['psd']);
  
		unset($mime_types['mp3']);
		unset($mime_types['m4a']);
		unset($mime_types['ogg']);
		unset($mime_types['wav']);

		unset($mime_types['m4v']);
		unset($mime_types['mov']);
		unset($mime_types['wmv']);
		unset($mime_types['avi']);
		unset($mime_types['mpg']);
		unset($mime_types['ogv']);
		unset($mime_types['3gp']);
		unset($mime_types['3g2']);

		return $mime_types;
	}

	function media_check_size($file){

		$media_size = $file['size']/1024;
		$media_limit_large = MEDIA_LIMIT_LARGE;
		$media_limit = MEDIA_LIMIT;
		$media_is_video = false;

		if (strpos($file['type'], 'video') !== false) {
			$media_limit = $media_limit_large;
		}

		if ($media_size > $media_limit){
			$file['error'] = 'Media cannot be added - file size is too large.  The maximum allowed size is '. $media_limit .'KB.';
		}

		return $file;
	}

	function media_css(){
		echo '<style>.max-upload-size{display:none;}</style>';
	}

	function media_max_size_info(){
		echo '<p>Maximum upload file size: Images: '.((MEDIA_LIMIT < 1000)? MEDIA_LIMIT.' kb': (MEDIA_LIMIT/1000).' MB').' Videos: '.((MEDIA_LIMIT_LARGE < 1000)? (MEDIA_LIMIT_LARGE).' kb': (MEDIA_LIMIT_LARGE/1000).' MB').'</p>';
	}

    function load_edit(){
        if ($_GET['post_type'] !== 'page') return;
        add_filter('posts_join', array($this, 'posts_join'), 10, 2);
        add_filter('posts_where', array($this, 'posts_where'),10, 2);
        add_filter('views_edit-page', array($this, 'fix_post_counts')); 
    }

    function login_redirect( $redirect_to, $request, $user ){
        if(isset($_REQUEST['redirect_to'])){
            return $_REQUEST['redirect_to'];
        }
        return admin_url();
    }

    function amend_profile_fields_disable_js(){
    ?>
<script type="text/javascript">jQuery(document).ready(function($){var a=jQuery("h3:contains('Relationships')").next('.form-table').find('tr').has('td'); b=a.find('input[type="checkbox"]'),c=a.find('a');if(b){b.each(function(){$(this).attr('disabled','disabled');});}if(c){c.each(function(){$(this).attr('style','display:none');});}});</script>
    <?php
    }

    function bespoke_js_script(){
        global $pagenow;

        if($pagenow==='post-new.php' || $pagenow==='post.php'){
            //NOT PRETTY BUT GETS JOB DONE...
            if (!wp_script_is('jquery','done')) {
                wp_enqueue_script('jquery');
            }
       ?>
<script type="text/javascript">(function(){jQuery(function(){jQuery('#menu_order').attr('style','display:none;');jQuery('#menu_order').next().attr('style','display:none');jQuery('#menu_order').prev().attr('style','display:none');var f=setInterval(function(){if(jQuery('#step_submit').length){jQuery('#step_submit').attr('style','margin-left:5px;');jQuery('#step_submit').prev('a').attr('style','');clearInterval(f);}},100);});})();</script>
    <?php
        }
    }

    function save_post($post_id, $post, $update){
        if($post->post_type==='page'){

            $error = false;
            $match = false;

            //DELETE ALL META DATA FOR TEAMS
            delete_post_meta($post_id, 'rpg-team');

            //CHECK THAT TEAM HAS BEEN SELECTED
            foreach($_POST as $key => $value)
            {
                if (strstr($key, 'rpg-team')){
                    $match = true;
                }
            }

            if($match){
                //GET ANY TEAMS THAT HAVE BEEN SELECTED
                foreach($_POST as $key => $value)
                {
                    if (strstr($key, 'rpg-team')){
                        //NEED TO CHECK CURRENT USER CAN UPDATE THIS PAGE?

                        //STORE IN META DATA
                        add_post_meta($post_id, 'rpg-team', $value);
                    }
                }
            } else {
                $error = new WP_Error('missing-team', 'No team selected - page status has been changed to DRAFT');
            }

            if ($error) {
                //TRIGGER THE ERROR MESSAGE
                add_filter('redirect_post_location', function($location) use ($error) {
                    return add_query_arg(array('rpg-team'=>$error->get_error_code(), 'message'=>10), $location);
                });
            }
        }
    }

    function handle_admin_error(){
        if (array_key_exists('rpg-team', $_GET)) { 
            $errors = get_option('rpg-team');
            $error_msg = '';

            switch($_GET['rpg-team']) {
                case 'missing-team':
                    $error_msg = 'No team selected - page status has been changed to DRAFT';
                    break;
                default:
                    $error_msg = 'An error ocurred when saving the page';
                    break;
            }
            
            //AMEND STATUS OF THE PAGE TO DRAFT - CANNOT BE PUBLISHED WITHOUT A TEAM SELECTED
            global $post;
            wp_update_post(array('ID' => $post->ID, 'post_status' => 'draft'));

            echo '<div class="error"><p>' . $error_msg . '</p></div>';
        }
    }

    function fix_post_counts($views){
        global $current_user, $wp_query;

        if($this->restrict_access()){

            unset($views['mine']);

            $types = array( 
                array('status' =>  NULL),  
                array('status' => 'publish'),  
                array('status' => 'draft'),  
                array('status' => 'pending'),  
                array('status' => 'trash')  
            );  

            //GET THE QUERY VAR post_status
            $status = isset($wp_query->query_vars['post_status']) ? $wp_query->query_vars['post_status'] : NULL;

            foreach($types as $type) {  
                $query = array( 
                    'post_type'   => 'page',  
                    'post_status' => $type['status']  
                );  
                $result = new WP_Query($query); 

                switch($type['status']){
                    case NULL:
                        if($result->found_posts > 0){
                            $class = ($status == NULL) ? ' class="current"' : '';  
                            $views['all'] = sprintf(__('<a href="%s" '.$class.'>All <span class="count">(%d)</span></a>', 'all'), admin_url('edit.php?post_type=page'), $result->found_posts); 
                        }
                        break;

                    case 'publish':
                        if($result->found_posts > 0){
                            $class = ($status == 'publish') ? ' class="current"' : '';  
                            $views['publish'] = sprintf(__('<a href="%s" '.$class.'>Published <span class="count">(%d)</span></a>', 'publish'), admin_url('edit.php?post_status=publish&post_type=page'), $result->found_posts); 
                        }
                        break;

                    case 'draft':
                        if($result->found_posts > 0){
                            $class = ($status == 'draft') ? ' class="current"' : ''; 
                            $views['draft'] = sprintf(__('<a href="%s" '.$class.'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'), admin_url('edit.php?post_status=draft&post_type=page'), $result->found_posts);
                        }
                        break;
                
                    case 'pending':
                        if($result->found_posts > 0){
                            $class = ($status == 'pending') ? ' class="current"' : ''; 
                            $views['pending'] = sprintf(__('<a href="%s" '.$class.'>Pending <span class="count">(%d)</span></a>', 'pending'), admin_url('edit.php?post_status=pending&post_type=page'), $result->found_posts); 
                        }
                        break;

                    case 'trash':
                        if($result->found_posts > 0){
                            $class = ($status == 'trash') ? ' class="current"' : ''; 
                            $views['trash'] = sprintf(__('<a href="%s" '.$class.'>Bin <span class="count">(%d)</span></a>', 'trash'), admin_url('edit.php?post_status=trash&post_type=page'), $result->found_posts);  
                        }
                        break;
                }
            }
        }

        return $views;
    }

    function posts_where($where, $query) {
        global $pagenow, $wpdb;

        if (is_admin()){
            if ($pagenow == 'edit.php') {
                
                if($this->restrict_access()){
                    //GET TEAMS CURRENT USER IS MEMBER OF
                    $teams = $this->get_setting('users_teams');

                    //FILTER THE LIST BASED ON TEAMS MEMBER OF
                    if(count($teams)>0){
                        $where .= " AND ($wpdb->postmeta.meta_key = 'rpg-team' AND $wpdb->postmeta.meta_value IN (";
                        foreach ($teams as $team) {
                            $where .= $team->term_id.',';
                        }

                        $where = rtrim($where,',');
                        $where .= '))';
                    }
                }
            }
        }

        return $where;
    }

    function posts_join($join, $query) {
        global $pagenow, $wpdb;

        if (is_admin()){
            if ($pagenow == 'edit.php') {
                if($this->restrict_access()){
                    $join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
                }
            }
        }

        return $join;
    }

    function add_meta_boxes($post) {
        if($post->post_type==='page'){

            //EARLY HOOK INTO THE POST EDIT SCREENS - USE TO CHECK THAT CURRENT USER CAN VIEW THE PAGE
            if($this->restrict_access()){
                $canaccess = false;
                
                global $pagenow;

                if($pagenow==='post-new.php'){
                    //NEW PAGE SO LET REQUEST THROUGH
                    $canaccess = true;
                }else{
                    //EDITING A PAGE SO CHECK CAN ACCESS
                    $teams = $this->get_setting('users_teams');
                    $post_teams = get_post_meta($post->ID, 'rpg-team');

                    if(count($teams)>0){
                        foreach ($teams as $team) {
                            if(in_array($team->term_id, $post_teams)){
                                $canaccess = true;
                                break;
                            }
                        }
                    }
                }

                //FAILED ACCESS CONTROL - REDIRECT BACK TO PAGE LISTING
                if(!$canaccess){
                    wp_redirect(admin_url('/edit.php?post_type=page', 'https'), 302);
                    exit;
                }
            }
            add_meta_box(
                    'rpg-teams-access',
                    __('Teams'),
                    array($this, 'render_meta_box'),
                    null,
                    'side',
                    'high'
                );

        }
    }

    function render_meta_box($object = null, $box = null){
        $output = '';
        $checked = '';
        $hasteams = false;

        //GET THE TEAMS THAT CURRENT USER HAS BEEN GRANTED ACCESS TO
        $teams = $this->get_setting('users_teams');
        
        //ANY TEAMS TO RENDER?
        if(count($teams)>0){

            //NEW PAGE?
            global $pagenow;

            switch($pagenow){
                case 'post-new.php':
                    if(count($teams)===1){
                        //ONLY 1 TEAM SO MAKE THE CHECKBOX CHECKED
                        $checked = 'checked="checked"';
                    }
                    break;

                case 'post.php':
                    //EXISTING PAGE - ENSURE CHECKBOXES FOR CURRENTLY ASSIGNED TEAMS ARE RENDERED CORRECTLY
                    $post_teams = get_post_meta(get_post()->ID, 'rpg-team');
                    if(count($post_teams)>0) $hasteams = true;
                    break;
            }


            $output .= '<ul id="teamlist" class="form-no-clear">';

            foreach ($teams as $team) {

                if($hasteams){
                    //DO WE NEED TO CHECK THE TEAM CHECKBOX?
                    $checked = '';
                    if(in_array($team->term_id, $post_teams)){
                        $checked = 'checked="checked"';
                    }
                }

                $output .= '<li id="rpg-'.$team->slug.'"><label class="selectit"><input value="'.$team->term_id.'" name="rpg-team'.$team->term_id.'" id="in-rpg-'.$team->slug.'" ' .$checked. ' type="checkbox"'. (count($teams)==1 ? ' onclick="this.checked=!this.checked;""': '').'>'.$team->name.'</label></li>';
            }

            $output .= '</ul>';
        }else{
            $output .= 'No teams available';
        }

        echo $output;
        $this->bespoke_js_script();
    }

    function custom_column($column_name, $post_id) {
        $output = '';

        //GET ALL THE TEAMS THAT ARE ASSIGNED FOR THIS PAGE
        if($column_name==='teams-read'){
            $post_teams = get_post_meta($post_id, 'rpg-team');
            $teams = $this->get_setting('all_teams');

            foreach($post_teams as $team){
                foreach($teams as $masterteams){
                    if($team == $masterteams['term_id']){
                        $output.=$masterteams['name'].',';
                    }
                }
            }

            $output = rtrim($output,',');

            if($output ===''){
                //NO TEAMS ASSIGNED TO THIS PAGE
                $output='&mdash;';
            }
        }

        echo $output;
    }

    function manage_columns($column_headers) {
        $column_headers['teams-read'] = sprintf(
            '<span title="%s">%s</span>',
            esc_attr(__('One or more teams granting access to pages.', 'teams')),
            esc_html(_x('Teams', 'Column header', 'teams'))
        );
        return $column_headers;
    }

	function custom_date_column_time($h_time, $post) {
		$h_time = get_post_time('d/m/Y', false, $post);
		return $h_time;
	}

	function check_cookie_banner_cookie(){
		global $cookie_banner_set;
		if(!isset($_COOKIE[COOKIE_BANNER_COOKIE_NAME])) {
			setcookie(COOKIE_BANNER_COOKIE_NAME, 'yes', time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
			$cookie_banner_set = true;
		}
	}

	function register_user_taxonomy() {
        //IF CLASS NOT AVAILABLE BAIL
        if (!class_exists('WP_User_Taxonomy')){
            return;
        }

        //CREATE THE NEW USER TAXONOMY
        new WP_User_Taxonomy('content_team', 'users/content-team', array(
            'singular' => __('Team',  'rpgutils'),
            'plural'   => __('Teams', 'rpgutils'),
            'exclusive' => false,
       ));
    }

	function set_default_role($default_role){
		return 'content_author';
	}

    function clean_unwanted_caps(){
        $delete_caps = array('ow_delete_workflow_history');
        global $wp_roles;
        foreach ($delete_caps as $cap) {
            foreach (array_keys($wp_roles->roles) as $role) {
                $wp_roles->remove_cap($role, $cap);
            }
        }
    }

    function get_setting($name, $value = null){
        if(isset($this->settings[$name])) {
            $value = $this->settings[$name];
        }
        return $value;
    }

	function get_teams_non_dupes($post_id, &$user_teams){
		global $wpdb;

		//GET TEAMS CURRENTLY ASSIGNED TO THE IMAGE
		$teams_for_image = $wpdb->get_results("SELECT RIGHT(meta_key, LENGTH(meta_key) - 12) as term_id FROM {$wpdb->postmeta} where post_id = " . $post_id . " and meta_key LIKE 'team-access-%' and meta_value = 1;");
		
		//CONVERT term_id TO int - WP DEFAULTS TO STRING
		$image_teams = array();

		foreach($teams_for_image as $image_team){
			$image_teams[] = absint($image_team->term_id);
		}

 		//GET TEAMS CURRENT USER IS MEMBER OF
		$teams = $this->get_setting('users_teams');
		
		//EXTRACT OUT term_id INTO SEPARATE ARRAY
		foreach($teams as $user_team){
			$user_teams[] = $user_team->term_id;
		}

		//GET THE NON DUPLICATES BETWEEN IMAGE TEAMS AND USERS TEAMS - NEED TO CHECK THESE AGAINST USERS TEAMS WHEN RENDERING
		return array_diff(array_merge($user_teams,$image_teams),array_intersect($user_teams,$image_teams));
	}

    function restrict_access(){
        //IF CURRENT USER HAS manage_options CAPABILITY THEN CAN SEE EVERYTHING
        $restrict = true;

		//ONLY CHECK IF NOT DEBUGGING
		if(!$this->is_debug()){
			if(current_user_can('manage_options')) $restrict = false;
		}
        return $restrict;
    }

	function get_die_html($title,$message){
		$page = '<!DOCTYPE html><html lang="en-GB"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width"><meta name="robots" content="noindex,follow" /><title>'.$title.'</title>';
		$page.= '<style type="text/css">html {background: #f1f1f1;}body {background: #fff;color: #444;font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;margin: 2em auto;padding: 1em 2em;max-width: 700px;-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);box-shadow: 0 1px 3px rgba(0,0,0,0.13);}h1 {border-bottom: 1px solid #dadada;clear: both;color: #666;font-size: 24px;margin: 30px 0 0 0;padding: 0;padding-bottom: 7px;}#error-page {margin-top: 50px;}#error-page p {font-size: 14px;line-height: 1.5;margin: 25px 0 20px;}#error-page code {font-family: Consolas, Monaco, monospace;}ul li {margin-bottom: 10px;font-size: 14px;}a {color: #0073aa;}a:hover,a:active {color: #00a0d2;}a:focus {color: #124964;-webkit-box-shadow:0 0 0 1px #5b9dd9,0 0 2px 1px rgba(30, 140, 190, .8);box-shadow:0 0 0 1px #5b9dd9,0 0 2px 1px rgba(30, 140, 190, .8);outline: none;}.button {background: #f7f7f7;border: 1px solid #ccc;color: #555;display: inline-block;text-decoration: none;font-size: 13px;line-height: 26px;height: 28px;margin: 0;padding: 0 10px 1px;cursor: pointer;-webkit-border-radius: 3px;-webkit-appearance: none;border-radius: 3px;white-space: nowrap;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;-webkit-box-shadow: 0 1px 0 #ccc;box-shadow: 0 1px 0 #ccc;vertical-align: top;}.button.button-large {height: 30px;line-height: 28px;padding: 0 12px 2px;}.button:hover,.button:focus {background: #fafafa;border-color: #999;color: #23282d;}.button:focus  {border-color: #5b9dd9;-webkit-box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );outline: none;}.button:active {background: #eee;border-color: #999;-webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );-webkit-transform: translateY(1px);-ms-transform: translateY(1px);transform: translateY(1px);}</style></head><body id="error-page"><p>'.$message.'</p></body></html>';
		return $page;
	}

	function is_debug(){
		if (defined('WP_DEBUG')){
			return WP_DEBUG;
		}
		return false;
	}

}

function rpgutils() {
    global $rpgutils;
    
    if(!isset($rpgutils)) {
        $rpgutils = new rpgutils();
        $rpgutils->initialize();
    }
    
    return $rpgutils;
}

//KICK OFF
rpgutils();

endif;
?>
