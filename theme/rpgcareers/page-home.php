<?php
/**
 * Template Name: Home
 */
get_header(); ?>

<?php 
	$post_id = get_the_ID();
	$image_ids = array();

	$bill_heading = get_post_meta($post_id,'billboard_heading',true);
	$bill_intro = get_post_meta($post_id,'billboard_intro_text',true);
	$bill_image_src = intval(get_post_meta($post_id,'billboard_image',true));
	$bill_image_src_resp = '';
	$image_ids[] = $bill_image_src;

	$cont_block_vert_image = intval(get_post_meta($post_id,'content_block_vertical_image',true));
	$cont_block_vert_image_alt = get_post_meta($cont_block_vert_image, '_wp_attachment_image_alt', true);
	$cont_block_vert_quote = get_post_meta($post_id,'content_block_vertical_quote',true);
	$cont_block_vert_forename = get_post_meta($post_id,'content_block_vertical_forename',true);
	$cont_block_vert_surname = get_post_meta($post_id,'content_block_vertical_surname',true);
	$cont_block_vert_role = get_post_meta($post_id,'content_block_vertical_role',true);
	$image_ids[] = $cont_block_vert_image;

	$cont_block_cta_heading = get_post_meta($post_id,'content_block_with_cta_heading',true);
	$cont_block_cta_para_1 = get_post_meta($post_id,'content_block_with_cta_paragraph_1',true);
	$cont_block_cta_para_2 = get_post_meta($post_id,'content_block_with_cta_paragraph_2',true);
	$cont_block_cta_text = get_post_meta($post_id,'content_block_with_cta_cta_text',true);
	$cont_block_cta_target = get_post_meta($post_id,'content_block_with_cta_cta_target',true);

	$cont_block_cta_img_heading = get_post_meta($post_id,'content_block_with_cta_+_image_heading',true);
	$cont_block_cta_img_para_1 = get_post_meta($post_id,'content_block_with_cta_+_image_paragraph_1',true);
	$cont_block_cta_img_para_2 = get_post_meta($post_id,'content_block_with_cta_+_image_paragraph_2',true);
	$cont_block_cta_img_text = get_post_meta($post_id,'content_block_with_cta_+_image_cta_text',true);
	$cont_block_cta_img_target = get_post_meta($post_id,'content_block_with_cta_+_image_cta_target',true);
	$cont_block_cta_img_image = intval(get_post_meta($post_id,'content_block_with_cta_+_image_image',true));
	$cont_block_cta_img_image_alt = get_post_meta($cont_block_cta_img_image, '_wp_attachment_image_alt', true);
	$image_ids[] = $cont_block_cta_img_image;

	$cont_block_promo_tag = get_post_meta($post_id,'content_block_promo_promo_tag',true);
	$cont_block_promo_heading = get_post_meta($post_id,'content_block_promo_heading',true);
	$cont_block_promo_heading_target = get_post_meta($post_id,'content_block_promo_heading_target',true);
	$cont_block_promo_para_1 = get_post_meta($post_id,'content_block_promo_paragraph_1',true);
	$cont_block_promo_para_2 = get_post_meta($post_id,'content_block_promo_paragraph_2',true);
	$cont_block_promo_image = intval(get_post_meta($post_id,'content_block_promo_image',true));
	$cont_block_promo_image_alt = get_post_meta($cont_block_promo_image, '_wp_attachment_image_alt', true);
	$image_ids[] = $cont_block_promo_image;

	$cont_block_image_heading = get_post_meta($post_id,'content_block_image_heading',true);
	$cont_block_image_body = get_post_meta($post_id,'content_block_image_body',true);
	$cont_block_image_image = intval(get_post_meta($post_id,'content_block_image_image',true));
	$cont_block_image_image_alt = get_post_meta($cont_block_image_image, '_wp_attachment_image_alt', true);
	$image_ids[] = $cont_block_image_image;

	$cont_block_promo_cta_before = get_post_meta($post_id,'content_block_promo_cta_text_before',true);
	$cont_block_promo_cta_after = get_post_meta($post_id,'content_block_promo_cta_text_after',true);
	$cont_block_promo_cta_target_text = get_post_meta($post_id,'content_block_promo_cta_target_text',true);
	$cont_block_promo_cta_target_link = get_post_meta($post_id,'content_block_promo_cta_target_link',true);

	//GET ALL POSTS - SAVES DB CALLS VIA wp_get_attachment_image_src
	$cache = get_posts(array('post_type' => 'attachment', 'numberposts' => -1, 'post__in' => $image_ids));

	if ($bill_image_src) {
		$bill_image_src = wp_get_attachment_image_src($bill_image_src, 'full');
		$bill_image_src_resp = wp_get_attachment_image_src($bill_image_src, 'medium_large');
	}
	if ($cont_block_vert_image) {
		$cont_block_vert_image = wp_get_attachment_image_src($cont_block_vert_image, 'medium_large');
	}
	if ($cont_block_cta_img_image) {
		$cont_block_cta_img_image = wp_get_attachment_image_src($cont_block_cta_img_image, 'large');
	}
	if ($cont_block_promo_image) {
		$cont_block_promo_image = wp_get_attachment_image_src($cont_block_promo_image, 'large');
	}
	if ($cont_block_image_image) {
		$cont_block_image_image = wp_get_attachment_image_src($cont_block_image_image, 'full');
	}
?>
<div class="hero">
    <div class="hero__inner">
        <div class="hero__img" style="background-image: url(<?php echo $bill_image_src[0]; ?>)"></div>
        <div class="hero__content">
            <h1 class="oversized has-line"><?php echo esc_html($bill_heading); ?></h1>
            <p class="intro"><?php echo esc_html($bill_intro); ?></p>
        </div>
    </div>
</div>
<main id="content" role="main">
<div class="content-two-col content-two-col--reversed">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
            <h2><?php echo esc_html($cont_block_cta_heading); ?></h2>
            <p><?php echo esc_html($cont_block_cta_para_1); ?></p>
			<?php if ($cont_block_cta_para_2) {?>
				<p><?php echo esc_html($cont_block_cta_para_2); ?></p>
			<?php } ?>
            <p><a href="<?php echo esc_html($cont_block_cta_target); ?>" class="readmore-link"><?php echo esc_html($cont_block_cta_text); ?></a></p>
        </div>
        <div class="content-two-col__last">
            <div class="aside aside--img-top aside--tertiary">
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
<div class="content-two-col">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
            <h2><?php echo esc_html($cont_block_cta_img_heading); ?></h2>
			<p><?php echo esc_html($cont_block_cta_img_para_1); ?></p>
			<?php if ($cont_block_cta_img_para_2) {?>
				<p><?php echo esc_html($cont_block_cta_img_para_2); ?></p>
			<?php } ?>
			<p><a href="<?php echo esc_html($cont_block_cta_img_target); ?>" class="readmore-link"><?php echo esc_html($cont_block_cta_img_text); ?></a></p>
        </div>
        <div class="content-two-col__last">
            <div class="image">
				<img src="<?php echo $cont_block_cta_img_image[0]; ?>" alt="<?php echo esc_html($cont_block_cta_img_image_alt); ?>">
            </div>
        </div>
    </div>
</div>
<div class="hero hero--where-to-find">
    <div class="hero__inner">
        <div class="hero__img" style="background-image: url(<?php echo $cont_block_image_image[0]; ?>)"></div>
        <div class="hero__content">
            <h2><?php echo esc_html($cont_block_image_heading); ?></h2>
            <p><?php echo esc_html($cont_block_image_body); ?></p>
        </div>
    </div>
</div>
<div class="content-two-col">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
            <div class="image">
				<img src="<?php echo $cont_block_promo_image[0]; ?>" alt="<?php echo esc_html($cont_block_promo_image_alt); ?>">
            </div>
        </div>
        <div class="content-two-col__last">
            <span class="tag"><?php echo esc_html($cont_block_promo_tag); ?></span>
            <h2><a href="<?php echo esc_html($cont_block_promo_heading_target); ?>"><?php echo esc_html($cont_block_promo_heading); ?></a></h2>
			<p><?php echo esc_html($cont_block_promo_para_1); ?></p>
			<?php if ($cont_block_promo_cta_before || $cont_block_promo_cta_after || $cont_block_promo_cta_target_text) {?>
				<p><?php echo esc_html($cont_block_promo_cta_before); ?><?php if ($cont_block_promo_cta_target_link && $cont_block_promo_cta_target_text) {?><a href="<?php echo esc_html($cont_block_promo_cta_target_link); ?>"><?php echo esc_html($cont_block_promo_cta_target_text); ?></a><?php } ?><?php echo esc_html($cont_block_promo_cta_after); ?></p>
			<?php } ?>
        </div>
    </div>
</div>
<?php
get_footer();