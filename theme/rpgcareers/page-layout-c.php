<?php
/**
 * Template Name: Page : Layout C
 */
 get_header(); ?>

<?php 
	$post_id = get_the_ID();

	$bill_logo_text = get_post_meta($post_id,'billboard_logo_text',true);
	$bill_logo_src = intval(get_post_meta($post_id,'billboard_logo',true));
	$bill_heading = get_post_meta($post_id,'billboard_heading',true);
	$bill_intro_para_1 = get_post_meta($post_id,'billboard_paragraph_1',true);
	$bill_intro_para_2 = get_post_meta($post_id,'billboard_paragraph_2',true);
	$bill_intro_para_3 = get_post_meta($post_id,'billboard_paragraph_3',true);
	$bill_intro_para_4 = get_post_meta($post_id,'billboard_paragraph_4',true);
	$bill_image_src = intval(get_post_meta($post_id,'billboard_image',true));
	$bill_image_src_resp = '';

	$media_embed_link = trim(get_post_meta($post_id,'media_media_link',true));
	$media_embed_quote = trim(get_post_meta($post_id,'media_quote',true));
	$media_embed_cta_text = trim(get_post_meta($post_id,'media_cta_text',true));
	$media_embed_cta_link = trim(get_post_meta($post_id,'media_cta_target',true));
	$media_render = true;

	if($media_embed_link === '' || $media_embed_quote === '' || $media_embed_cta_text === '' || $media_embed_cta_link === ''){
		$media_render = false;
	}

	$cont_block_vert_image = intval(get_post_meta($post_id,'content_block_vertical_image',true));
	$cont_block_vert_image_alt = get_post_meta($cont_block_vert_image, '_wp_attachment_image_alt', true);
	$cont_block_vert_quote = get_post_meta($post_id,'content_block_vertical_quote',true);
	$cont_block_vert_forename = get_post_meta($post_id,'content_block_vertical_forename',true);
	$cont_block_vert_surname =get_post_meta($post_id,'content_block_vertical_surname',true);
	$cont_block_vert_role = get_post_meta($post_id,'content_block_vertical_role',true);

	$cont_block_vert_extra_heading = get_post_meta($post_id,'extra_group_extra_heading',true);
	$cont_block_vert_extra_body = get_post_meta($post_id,'extra_group_extra_body',true);
	$cont_block_vert_extra_link_text = get_post_meta($post_id,'extra_group_extra_target_text',true);
	$cont_block_vert_extra_link_target = get_post_meta($post_id,'extra_group_extra_target',true);

	$sub_cont_heading = get_post_meta($post_id,'sub_content_heading',true);
	$sub_cont_body = wpautop(get_post_meta($post_id,'sub_content_body',true));
	$sub_cont_image_1 = intval(get_post_meta($post_id,'sub_content_image_1',true));
	$sub_cont_image_1_alt = get_post_meta($sub_cont_image_1, '_wp_attachment_image_alt', true);
	$sub_cont_image_2 = intval(get_post_meta($post_id,'sub_content_image_2',true));
	$sub_cont_image_2_alt = get_post_meta($sub_cont_image_1, '_wp_attachment_image_alt', true);

	$content_block_with_cta_1_heading = get_post_meta($post_id,'content_block_with_cta_1_heading',true);
	$content_block_with_cta_1_para_1 = get_post_meta($post_id,'content_block_with_cta_1_paragraph_1',true);
	$content_block_with_cta_1_para_2 = get_post_meta($post_id,'content_block_with_cta_1_paragraph_2',true);
	$content_block_with_cta_1_cta_text = get_post_meta($post_id,'content_block_with_cta_1_cta_text',true);
	$content_block_with_cta_1_cta_target = get_post_meta($post_id,'content_block_with_cta_1_cta_target',true);

	$content_block_with_cta_2_heading = get_post_meta($post_id,'content_block_with_cta_2_heading',true);
	$content_block_with_cta_2_para_1 = get_post_meta($post_id,'content_block_with_cta_2_paragraph_1',true);
	$content_block_with_cta_2_para_2 = get_post_meta($post_id,'content_block_with_cta_2_paragraph_2',true);
	$content_block_with_cta_2_cta_text = get_post_meta($post_id,'content_block_with_cta_2_cta_text',true);
	$content_block_with_cta_2_cta_target = get_post_meta($post_id,'content_block_with_cta_2_cta_target',true);

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
	if ($sub_cont_image_1) {
		$sub_cont_image_1 = wp_get_attachment_image_src($sub_cont_image_1, 'full');
	}
	if ($sub_cont_image_2) {
		$sub_cont_image_2 = wp_get_attachment_image_src($sub_cont_image_2, 'full');
	}
?>
<div class="banner">
	<style>.banner {background-image: url(<?php echo $bill_image_src_resp[0]; ?>);}@media (min-width: 768px) {.banner {background-image: url(<?php echo $bill_image_src[0]; ?>);}}</style>
</div>
<main id="content" role="main">
<div class="about-intro">
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
			<div class="department-intro__content"></div>
		</div>
	</div>
	<div class="content-two-col content-two-col--stacked-left">
		<div class="content-two-col__inner">
			<div class="content-two-col__first">
				<p class="intro"><?php echo esc_html($bill_intro_para_1); ?></p>
				<p class="intro"><?php echo esc_html($bill_intro_para_2); ?></p>
			</div>
			<div class="content-two-col__last">
				<p class="intro"><?php echo esc_html($bill_intro_para_3); ?></p>
				<?php if($bill_intro_para_4 !== ''){ ?>
					<p class="intro"><?php echo esc_html($bill_intro_para_4); ?></p>
				<?php } ?>
			</div>
		</div>
	</div>	
</div>
<?php if($media_render) { ?>
<div class="wrapper wrapper--white">
	<div class="content-two-col content-two-col--middle-align">
		<div class="content-two-col__inner">
			<div class="content-two-col__first">
				<div class="image image--spaced">
					<iframe width="480" height="235" src="<?php echo esc_html($media_embed_link); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</div>
			</div>
			<div class="content-two-col__last">
				<blockquote><p class="intro"><?php echo esc_html($media_embed_quote); ?></p></blockquote>
				<p class="intro"><a href="<?php echo esc_html($media_embed_cta_link); ?>" class="cta--arrow-link"><?php echo esc_html($media_embed_cta_text); ?></a></p>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="content-two-col content-two-col--stacked-left"<?php if(!$media_render) { ?> style="margin-top:70px;"<?php } ?>>
    <div class="content-two-col__inner">
        <div class="content-two-col__first">

<?php $main_repeaters = get_post_meta($post_id, 'main_content_repeater_content', true);
if($main_repeaters): ?>
	<?php for ($i=0;$i<$main_repeaters;$i++) { ?>
	<h2><?php echo esc_html(get_post_meta($post_id, 'main_content_repeater_content_'.$i.'_heading', true)); ?></h2>
	<?php echo get_post_meta($post_id, 'main_content_repeater_content_'.$i.'_body', true); ?>
	<?php $repeater_image_src = intval(get_post_meta($post_id, 'main_content_repeater_content_'.$i.'_image',true));

	if ($repeater_image_src) {
		$repeater_image_alt = get_post_meta($repeater_image_src, '_wp_attachment_image_alt', true);
		$repeater_image_src = wp_get_attachment_image_src($repeater_image_src, 'full'); ?>
		<div class="image image--spaced"><img src="<?php echo $repeater_image_src[0]; ?>" alt="<?php echo $repeater_image_alt; ?>"></div>
	<?php } 
	$repeater_image_src = null;
	} ?>
<?php endif; ?>
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
					<?php if($cont_block_vert_extra_heading !== ''){ ?>
					<div class="aside--sub">
					<h3 class="intro"><?php echo esc_html($cont_block_vert_extra_heading); ?></h3>
					<?php echo $cont_block_vert_extra_body; ?>
					<a href="<?php echo esc_html($cont_block_vert_extra_link_target); ?>" class="cta--arrow-link"><?php echo esc_html($cont_block_vert_extra_link_text); ?></a>
					</div>
					<?php } ?>
                </div>
			</div>
			<div class="image">
				<img src="<?php echo $sub_cont_image_1[0]; ?>" alt="<?php echo esc_html($sub_cont_image_1_alt); ?>">
            </div>
			<div class="aside aside--white">
				<div class="aside__inner">
					<h3 class="intro"><?php echo esc_html($sub_cont_heading); ?></h3>
					<?php echo $sub_cont_body; ?>
					<div class="image">
						<img src="<?php echo $sub_cont_image_2[0]; ?>" alt="<?php echo esc_html($sub_cont_image_2_alt); ?>">
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<div class="wrapper wrapper--grey">
	<div class="content-two-col content-two-col--middle-align">
		<div class="content-two-col__inner">
			<div class="content-two-col__equal">
				<h3 class="intro"><?php echo esc_html($content_block_with_cta_1_heading); ?></h3>
				<p><?php echo esc_html($content_block_with_cta_1_para_1); ?></p>
				<p><?php echo esc_html($content_block_with_cta_1_para_2); ?></p>
				<a href="<?php echo esc_html($content_block_with_cta_1_cta_target); ?>" class="cta--arrow-link"><?php echo esc_html($content_block_with_cta_1_cta_text); ?></a>
			</div>
			<div class="content-two-col__equal">
				<h3 class="intro"><?php echo esc_html($content_block_with_cta_2_heading); ?></h3>
				<p><?php echo esc_html($content_block_with_cta_2_para_1); ?></p>
				<p><?php echo esc_html($content_block_with_cta_2_para_2); ?></p>
				<a href="<?php echo esc_html($content_block_with_cta_2_cta_target); ?>" class="cta--arrow-link"><?php echo esc_html($content_block_with_cta_2_cta_text); ?></a>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();