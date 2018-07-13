<?php
/**
 * Template Name: Hub : Image + Logo list
 */
get_header(); ?>

<?php 
	$post_id = get_the_ID();
	$image_ids = array();

	$bill_heading = get_post_meta($post_id,'billboard_heading',true);
	$bill_intro = get_post_meta($post_id,'billboard_intro_text',true);
	$bill_image_src = intval(get_post_meta($post_id,'billboard_image',true));
	$bill_image_src_alt = get_post_meta($bill_image_src, '_wp_attachment_image_alt', true);
	$image_ids[] = $bill_image_src;

	$cont_block_vert_image = intval(get_post_meta($post_id,'content_block_vertical_image',true));
	$cont_block_vert_image_alt = get_post_meta($cont_block_vert_image, '_wp_attachment_image_alt', true);
	$cont_block_vert_quote = get_post_meta($post_id,'content_block_vertical_quote',true);
	$cont_block_vert_forename = get_post_meta($post_id,'content_block_vertical_forename',true);
	$cont_block_vert_surname = get_post_meta($post_id,'content_block_vertical_surname',true);
	$cont_block_vert_role = get_post_meta($post_id,'content_block_vertical_role',true);
	$image_ids[] = $cont_block_vert_image;

	//GET ALL POSTS - SAVES DB CALLS VIA wp_get_attachment_image_src
	$cache = get_posts(array('post_type' => 'attachment', 'numberposts' => -1, 'post__in' => $image_ids));

	if ($bill_image_src) {
		$bill_image_src = wp_get_attachment_image_src($bill_image_src, 'full');
	}
	if ($cont_block_vert_image) {
		$cont_block_vert_image = wp_get_attachment_image_src($cont_block_vert_image, 'medium_large');
	}
?>
 <div class="hero-text">
    <div class="hero-text__inner">
        <div class="hero-text__img">
            <img src="<?php echo $bill_image_src[0]; ?>" alt="<?php echo $bill_image_src_alt; ?>" />
        </div>
        <h1 class="hero-text__title oversized"><?php echo esc_html($bill_heading); ?></h1>
        <p class="hero-text__text intro"><?php echo esc_html($bill_intro); ?></p>
    </div>
</div>
<main id="content" role="main">
<div class="content-two-col content-two-col--two-thirds-left">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
			<div class="text-image-list text-image-list--three-col">
				<?php $list_items = get_post_meta($post_id, 'list_repeater_items', true);
				if($list_items):
				for ($i=0;$i<$list_items;$i++) { ?>
					<div class="text-image-list__item">
					<?php 
					$item_theme = get_post_meta($post_id, 'list_repeater_items_'.$i.'_team', true);
					$logo_band = '';
					$logo_src = '';
					$theme_display_name = '';
					$theme_display_name_2 = '';
					$theme_display_name_3 = '';

					if($item_theme !==''){
						//BACK END ONLY?
						$back_end = get_term_meta($item_theme, 'content_team_back_end_only', true);
				
						if($back_end === ''){
							$theme_colour = get_term_meta($item_theme, 'content_team_theme_colour', true);
							if($theme_colour !==''){
								$logo_band = ' border-left-color:' . $theme_colour . ';';
							}
				
							$theme_logo = intval(get_term_meta($item_theme, 'content_team_logo_id', true));
				
							if($theme_logo !==''){
								$theme_logo = wp_get_attachment_image_src($theme_logo, 'full');
								
								if($theme_logo){
									$logo_src = 'background-image:url(' . $theme_logo[0] . ');';		
								}
							}
				
							$theme_display_name = get_term_meta($item_theme, 'content_team_display_name', true);
							$theme_display_name_2 = get_term_meta($item_theme, 'content_team_display_name_2', true);
							$theme_display_name_3 = get_term_meta($item_theme, 'content_team_display_name_3', true);

							if($theme_display_name ===''){
								$theme_display_name = 'THEME NOT SET';
							}
						}
					}
					?>
						<div class="text-image-list__img">
							<img src="<?php echo wp_get_attachment_image_src(get_post_meta($post_id, 'list_repeater_items_'.$i.'_image', true), 'medium_large')[0]; ?>" alt="<?php echo get_post_meta(get_post_meta($post_id, 'list_repeater_items_'.$i.'_image', true), '_wp_attachment_image_alt', true); ?>">
						</div>
						<div class="text-image-list__logo">
							<span class="logo logo--small"  style="<?php echo $logo_src; echo $logo_band; ?>">
								<span class="logo__text"><?php echo esc_html($theme_display_name); ?>
								<?php 
								if ($theme_display_name_2 !== '') {
									echo '<br/>'.esc_html($theme_display_name_2);
								}
								if ($theme_display_name_3 !== '') {
									echo '<br/>'.esc_html($theme_display_name_3);
								}
								?>
								</span>
							</span>
						</div>
						<div class="text-image-list__content">
							<p class="smaller"><?php echo esc_html(get_post_meta($post_id, 'list_repeater_items_'.$i.'_body', true)); ?></p>
							<span class="smaller"><a href="<?php echo esc_html(get_post_meta($post_id, 'list_repeater_items_'.$i.'_target', true)); ?>" class="readmore-link"><?php echo esc_html(get_post_meta($post_id, 'list_repeater_items_'.$i.'_target_text', true)); ?></a></span>
						</div>
					</div>
				<?php } ?>
				<?php endif; ?>
			</div>
        </div>
        <div class="content-two-col__last">
            <div class="aside aside--img-top">
                <div class="aside__inner">
                   <div class="aside__img">
                        <img src="<?php echo $cont_block_vert_image[0]; ?>" alt="<?php echo esc_html($cont_block_vert_image_alt); ?>">
                    </div>
                    <blockquote class="aside__content">
                        <p><?php echo esc_html($cont_block_vert_quote); ?></p>
                        <footer><?php echo esc_html($cont_block_vert_forename); ?> <?php echo esc_html($cont_block_vert_surname); ?><strong><?php echo esc_html($cont_block_vert_role); ?></strong></footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();