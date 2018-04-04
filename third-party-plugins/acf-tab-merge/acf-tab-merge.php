<?php
/**
 * Plugin Name: ACF Tab Merge
 * Plugin URI: https://enshrined.co.uk/2017/06/14/merging-acf-field-group-tabs/
 * Description: Merges ACF tabs across field groups into a single tab list
 * Version: 1.0.2
 * Author: enshrined
 * Author URI: https://enshrined.co.uk
*/

/**
 * Merge Advanced Custom Fields tab groups into a single group.
 *
 * @since 1.0.0
 */
function enshrined_merge_acf_tab_groups() {
	$screen = get_current_screen();
	if ( 'post' === $screen->base ) {
		echo '
			<!-- ACF Merge Tabs -->
			<script>
				acf.add_action("ready", function( $el ){
					var tab_groups = jQuery("#postbox-container-2 .acf-postbox:not(.acf-hidden) > .acf-fields > .acf-tab-wrap > .acf-tab-group");
					var tab_wraps = jQuery("#postbox-container-2 .acf-postbox:not(.acf-hidden) > .acf-fields > .acf-tab-wrap").parent(".inside");
			
					// Merge the tabs
					if ( tab_groups.length > 1 ) {
						var firstBox = tab_groups.first();
			
						tab_groups.not(firstBox).each(function(){
							jQuery(this).find("li").removeClass("active").appendTo(firstBox);
							jQuery(this).parent(".acf-tab-wrap").remove();
						});
					}
			
					// Merge the tab content
					if ( tab_wraps.length > 1 ) {
						var firstBox = tab_wraps.first();
			
						tab_wraps.not(firstBox).each(function(){
							jQuery(this).children().addClass("hidden-by-tab").appendTo(firstBox);
							jQuery(this).parents(".acf-postbox").remove();
						});
					}
				});
			</script>';
	}
}

add_action( 'admin_footer', 'enshrined_merge_acf_tab_groups' );