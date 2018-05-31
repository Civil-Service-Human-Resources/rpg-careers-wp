<?php
/**
 * Template Name: Hub : Standard
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
			<div class="text-image-list">
		<?php $main_items = get_post_meta($post_id, 'main_repeater_items', true);
		if($main_items):
			for ($i=0;$i<$main_items;$i++) { ?>
                <div class="text-image-list__item">
                    <div class="text-image-list__img">
						<img src="<?php echo wp_get_attachment_image_src(get_post_meta($post_id, 'main_repeater_items_'.$i.'_image', true), 'medium_large')[0]; ?>" alt="<?php echo get_post_meta(get_post_meta($post_id, 'main_repeater_items_'.$i.'_image', true), '_wp_attachment_image_alt', true); ?>">
                    </div>
                    <div class="text-image-list__content">
                        <h2 class="h3"><a href="<?php echo esc_html(get_post_meta($post_id, 'main_repeater_items_'.$i.'_heading_target', true)); ?>"><?php echo esc_html(get_post_meta($post_id, 'main_repeater_items_'.$i.'_heading', true)); ?></a></h2>
                        <p><?php echo esc_html(get_post_meta($post_id, 'main_repeater_items_'.$i.'_body', true)); ?></p>
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
		<?php $sub_items = get_post_meta($post_id, 'sub_repeater_items', true);
		if($sub_items):
			for ($j=0;$j<$sub_items;$j++) { ?>
			<div class="cta">
				<img class="cta__img" src="<?php echo wp_get_attachment_image_src(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_image', true), 'medium')[0]; ?>" alt="<?php echo get_post_meta(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_image', true), '_wp_attachment_image_alt', true); ?>">
				<div class="cta__content">
					<h3 class="cta__title"><a href="<?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_heading_target', true)); ?>"><?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_heading', true)); ?></a></h3>
					<p><?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_body', true)); ?></p>
				</div>
			</div>
		<?php } ?>
		<?php endif; ?>
        </div>
    </div>
</div>
<?php
get_footer();