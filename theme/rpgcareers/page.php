<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package RPG_Careers
 * @since 1.0
 * @version 1.0
 */
 get_header(); ?>

<?php 
	$post_id = get_the_ID();
	$image_ids = array();

	$main_cont_img_1_alt = '';
	$main_cont_img_2_alt = '';
	$cont_block_vert_image_alt = '';
	$cont_block_hori_image_alt = '';

	$bill_logo_text = get_post_meta($post_id,'billboard_logo_text',true);
	$bill_logo_src = intval(get_post_meta($post_id,'billboard_logo',true));
	$image_ids[] = $bill_logo_src;
	$bill_heading = get_post_meta($post_id,'billboard_heading',true);
	$bill_intro = get_post_meta($post_id,'billboard_intro_text',true);
	$bill_image_src = intval(get_post_meta($post_id,'billboard_image',true));
	$bill_image_src_resp = '';
	$image_ids[] = $bill_image_src;

	$main_cont_heading = get_post_meta($post_id,'main_content_heading',true);
	$main_cont_para_1 = get_post_meta($post_id,'main_content_paragraph_1',true);
	$main_cont_para_2 = get_post_meta($post_id,'main_content_paragraph_2',true);
	$main_cont_para_3 = get_post_meta($post_id,'main_content_paragraph_3',true);
	$main_cont_img_1 = intval(get_post_meta($post_id,'main_content_image_1',true));
	$main_cont_img_2 = intval(get_post_meta($post_id,'main_content_image_2',true));
	$image_ids[] = $main_cont_img_1;
	$image_ids[] = $main_cont_img_2;

	$cont_block_vert_image = intval(get_post_meta($post_id,'content_block_vertical_image',true));
	$cont_block_vert_image_alt = get_post_meta($cont_block_vert_image, '_wp_attachment_image_alt', true);
	$cont_block_vert_quote = get_post_meta($post_id,'content_block_vertical_quote',true);
	$cont_block_vert_forename = get_post_meta($post_id,'content_block_vertical_forename',true);
	$cont_block_vert_surname =get_post_meta($post_id,'content_block_vertical_surname',true);
	$cont_block_vert_role = get_post_meta($post_id,'content_block_vertical_role',true);
	$image_ids[] = $cont_block_vert_image;

	$cont_block_hori_image = intval(get_post_meta($post_id,'content_block_horizontal_image',true));
	$cont_block_hori_quote = get_post_meta($post_id,'content_block_horizontal_quote',true);
	$cont_block_hori_forename = get_post_meta($post_id,'content_block_horizontal_forename',true);
	$cont_block_hori_surname = get_post_meta($post_id,'content_block_horizontal_surname',true);
	$cont_block_hori_role = get_post_meta($post_id,'content_block_horizontal_role',true);
	$cont_block_hori_extra_txt = get_post_meta($post_id,'content_block_horizontal_extra_text',true);
	$image_ids[] = $cont_block_hori_image;

	$sub_cont_heading = get_post_meta($post_id,'sub_content_heading',true);
	$sub_cont_intro = get_post_meta($post_id,'sub_content_intro',true);

	//GET ALL POSTS - SAVES DB CALLS VIA wp_get_attachment_image_src
	$cache = get_posts(array('post_type' => 'attachment', 'numberposts' => -1, 'post__in' => $image_ids));

	//STORE ALT TEXTS
	foreach ($cache as $item) {
		if($item->ID === $main_cont_img_1){
			$main_cont_img_1_alt = $item->post_title;
		}
		if($item->ID === $main_cont_img_2){
			$main_cont_img_2_alt = $item->post_title;
		}
		if($item->ID === $cont_block_vert_image){
			$cont_block_vert_image_alt = $item->post_title;
		}
		if($item->ID === $cont_block_hori_image){
			$cont_block_hori_image_alt = $item->post_title;
		}
	}

	//GET IMAGE SRC
	if ($bill_logo_src) {
		$bill_logo_src = wp_get_attachment_image_src($bill_logo_src, 'full');
	}
	if ($bill_image_src) {
		$bill_image_src_resp = $bill_image_src;
		$bill_image_src = wp_get_attachment_image_src($bill_image_src, 'full');
		$bill_image_src_resp = wp_get_attachment_image_src($bill_image_src_resp, 'medium_large');
	}
	if ($cont_block_vert_image) {
		$cont_block_vert_image = wp_get_attachment_image_src($cont_block_vert_image, 'medium_large');
	}
	if ($cont_block_hori_image) {
		$cont_block_hori_image = wp_get_attachment_image_src($cont_block_hori_image, 'medium_large');
	}
	if ($main_cont_img_1) {
		$main_cont_img_1 = wp_get_attachment_image_src($main_cont_img_1, 'large');
	}
	if ($main_cont_img_2) {
		$main_cont_img_2 = wp_get_attachment_image_src($main_cont_img_2, 'large');
	}

	//THEMING
	$post_theme = get_post_meta($post_id,'rpg-theme',true);

	if($post_theme !==''){
		$theme_colour = get_term_meta($post_theme, 'content_team_theme_colour', true);
	}

?>
<div class="banner">
	<style>.banner {background-image: url(<?php echo $bill_image_src_resp[0]; ?>);}@media (min-width: 768px) {.banner {background-image: url(<?php echo $bill_image_src[0]; ?>);}}</style>
</div>
<main id="content" role="main">
<div class="department-intro">
    <div class="department-intro__inner">
        <div class="department-intro__head">
            <div class="department-intro__head-logo">
                <span class="logo" style="background-image:url(<?php echo $bill_logo_src[0]; ?>);">
                <span class="logo__text"><?php echo esc_html($bill_logo_text); ?></span>
            </span>
            </div>
            <h1 class="department-intro__head-title"><?php echo esc_html($bill_heading); ?></h1>
        </div>
		<div class="department-intro__content">
			<p class="intro"><?php echo esc_html($bill_intro); ?></p>
		</div>
    </div>
</div>
<div class="content-two-col content-two-col--stacked-left">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
            <h2><?php echo esc_html($main_cont_heading); ?></h2>
            <p><?php echo esc_html($main_cont_para_1); ?></p>
			<p><?php echo esc_html($main_cont_para_2); ?></p>
            <?php if($main_cont_img_1) { ?>
			<div class="image image--spaced"><img src="<?php echo $main_cont_img_1[0]; ?>" alt="<?php echo $main_cont_img_1_alt; ?>"></div>
			<?php } ?>
            <p><?php echo esc_html($main_cont_para_3); ?></p>
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
			<?php if($main_cont_img_2) { ?>
			<div class="image"><img src="<?php echo $main_cont_img_2[0]; ?>" alt="<?php echo $main_cont_img_2_alt; ?>"></div>
			<?php } ?>
        </div>
    </div>
</div>
	<?php $factoids = get_post_meta($post_id, 'factoid_factoid', true);
	if($factoids): ?>
	<div class="slider">
	<?php for ($i=0;$i<$factoids;$i++) { ?>
		<div class="slider__item">
            <div class="slider__item-content">
                <div class="slider__item-icon">
                    <svg version="1.1" viewBox="0 0 47 66" xmlns="http://www.w3.org/2000/svg">
                        <title>lightbulb</title>
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(-300 -2406)">
                                <g transform="translate(300 2405)">
                                    <g transform="translate(.30405 .58503)">
                                        <ellipse cx="23.099" cy="31.058" rx="15.832" ry="15.847" class="fill-override" />
                                        <path d="m23.099 0.66378c-12.737 0-23.099 10.389-23.099 23.153 0 12.309 9.6364 22.374 21.74 23.088v-11.628l-1.7555 1.7624c-0.53264 0.52844-1.3914 0.52844-1.924 0l-4.0763-4.0859c-0.52992-0.53117-0.52992-1.3946 0-1.9258 0.53264-0.53389 1.3914-0.53389 1.924 0l3.1143 3.1216 3.1143-3.1216c0.12229-0.12258 0.27447-0.22609 0.44296-0.29146 0.33154-0.13892 0.70384-0.13892 1.0354 0 0.16849 0.065374 0.32067 0.16888 0.44567 0.29146l3.1143 3.1216 3.1143-3.1216c0.53264-0.53389 1.3914-0.53389 1.924 0 0.52992 0.53117 0.52992 1.3946 0 1.9258l-4.0763 4.0859c-0.26632 0.26422-0.61416 0.39769-0.962 0.39769-0.34784 0-0.69569-0.13347-0.962-0.39769l-1.7555-1.7624v11.628c12.101-0.71367 21.74-10.779 21.74-23.088 0-12.764-10.362-23.153-23.099-23.153" fill="#fff"/>
                                        <path d="m29.804 49.503h-13.41c-0.74021 0-1.341 0.58191-1.341 1.2989 0 0.717 0.60075 1.2989 1.341 1.2989h13.41c0.73752 0 1.341-0.58191 1.341-1.2989 0-0.717-0.60343-1.2989-1.341-1.2989" class="fill-override"/>
                                        <path d="m29.804 54.699h-13.41c-0.74021 0-1.341 0.58191-1.341 1.2989s0.60075 1.2989 1.341 1.2989h13.41c0.73752 0 1.341-0.58191 1.341-1.2989s-0.60343-1.2989-1.341-1.2989" class="fill-override"/>
                                        <path d="m29.804 59.894h-13.41c-0.74021 0-1.341 0.6401-1.341 1.4288s0.60075 1.4288 1.341 1.4288h5.3638v1.4288c0 0.7887 0.60075 1.4288 1.341 1.4288 0.73752 0 1.341-0.6401 1.341-1.4288v-1.4288h5.3638c0.73752 0 1.341-0.6401 1.341-1.4288s-0.60343-1.4288-1.341-1.4288" class="fill-override"/>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <p><?php echo esc_html(get_post_meta($post_id, 'factoid_factoid_'.$i.'_fact', true)); ?></p>
            </div>
        </div>
	<?php } ?>
	</div>
<?php endif; ?>
    <div class="content-two-col content-two-col--two-thirds-left">
        <div class="content-two-col__inner">
            <div class="content-two-col__first">
                <div class="aside aside--img-right aside--dark">
                    <div class="aside__inner">
                        <div class="aside__img">
							<img src="<?php echo $cont_block_hori_image[0]; ?>" alt="<?php echo $cont_block_hori_image_alt; ?>">
                        </div>
                        <blockquote class="aside__content">
							<p><?php echo esc_html($cont_block_hori_quote); ?></p>
							<footer><?php echo esc_html($cont_block_hori_forename); ?> <?php echo esc_html($cont_block_hori_surname); ?><strong><?php echo esc_html($cont_block_hori_role); ?></strong></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="content-two-col__last">
				<?php echo wpautop($cont_block_hori_extra_txt); ?>
            </div>
        </div>
    </div>
	<div class="cta-boxes">
        <div class="section-intro">
            <h2><?php echo esc_html($sub_cont_heading); ?></h2>
            <p><?php echo esc_html($sub_cont_intro); ?></p>
        </div>
        <div class="cta-boxes__inner">
			<?php $drivers = get_post_meta($post_id, 'sub_content_driver', true);
			if($drivers):
				for ($j=0;$j<$drivers;$j++) { ?>
				<div class="cta-boxes__item">
					<div class="cta">
						<img class="cta__img" src="<?php echo wp_get_attachment_image_src(get_post_meta($post_id, 'sub_content_driver_'.$j.'_image', true), 'large')[0]; ?>" alt="<?php echo get_post_meta(get_post_meta($post_id, 'sub_content_driver_'.$j.'_image', true), '_wp_attachment_image_alt', true); ?>">
						<div class="cta__content">
							<h3 class="cta__title"><a href="<?php echo esc_html(get_post_meta($post_id, 'sub_content_driver_'.$j.'_link', true)); ?>"><?php echo esc_html(get_post_meta($post_id, 'sub_content_driver_'.$j.'_headline', true)); ?></a></h3>
							<p><?php echo esc_html(get_post_meta($post_id, 'sub_content_driver_'.$j.'_text', true)); ?></p>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php endif; ?>
        </div>
    </div>
<?php
get_footer();
