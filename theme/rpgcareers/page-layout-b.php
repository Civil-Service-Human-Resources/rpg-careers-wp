<?php
/**
 * Template Name: Page : Layout B
 */
 get_header(); ?>

<?php 
	$post_id = get_the_ID();
	$image_ids = array();

	$main_cont_img_1_alt = '';
	$main_cont_img_2_alt = '';
	$cont_block_vert_image_alt = '';
	$bill_image_src_alt = '';

	$bill_heading = get_post_meta($post_id,'billboard_heading',true);
	$bill_intro = get_post_meta($post_id,'billboard_intro_text',true);
	$bill_image_src = intval(get_post_meta($post_id,'billboard_image',true));
	$bill_image_src_alt = get_post_meta($bill_image_src, '_wp_attachment_image_alt', true);
	$bill_image_src_resp = '';
	$image_ids[] = $bill_image_src;

	$main_cont_heading = get_post_meta($post_id,'main_content_heading',true);
	$main_cont_para_1 = get_post_meta($post_id,'main_content_paragraph_1',true);
	$main_cont_para_2 = get_post_meta($post_id,'main_content_paragraph_2',true);
	$main_cont_para_3 = get_post_meta($post_id,'main_content_paragraph_3',true);
	$main_cont_para_4 = get_post_meta($post_id,'main_content_paragraph_4',true);
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

	$sub_content = get_post_meta($post_id, 'sub_content_multiple', true);

	if($sub_content){
		for ($i=0;$i<$sub_content;$i++){
			$cont_block_simple_heading[$i] = get_post_meta($post_id, 'sub_content_multiple_'.$i.'_content_area_content_block_with_3_paras_heading', true);
			$cont_block_simple_para_1[$i] = get_post_meta($post_id, 'sub_content_multiple_'.$i.'_content_area_content_block_with_3_paras_paragraph_1', true);
			$cont_block_simple_para_2[$i] = get_post_meta($post_id, 'sub_content_multiple_'.$i.'_content_area_content_block_with_3_paras_paragraph_2', true);
			$cont_block_simple_para_3[$i] = get_post_meta($post_id, 'sub_content_multiple_'.$i.'_content_area_content_block_with_3_paras_paragraph_3', true);
		}
	}

	$cont_listing_heading = get_post_meta($post_id,'listing_heading',true);

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
		if($item->ID === $bill_image_src){
			$bill_image_src_alt = $item->post_title;
		}
	}

	//GET IMAGE SRC
	if ($bill_image_src) {
		$bill_image_src = wp_get_attachment_image_src($bill_image_src, 'large');
		$bill_image_src_resp = wp_get_attachment_image_src($bill_image_src, 'medium');
	}
	if ($main_cont_img_1) {
		$main_cont_img_1 = wp_get_attachment_image_src($main_cont_img_1, 'medium');
	}
	if ($main_cont_img_2) {
		$main_cont_img_2 = wp_get_attachment_image_src($main_cont_img_2, 'medium');
	}
	if ($cont_block_vert_image) {
		$cont_block_vert_image = wp_get_attachment_image_src($cont_block_vert_image, 'medium');
	}

?>
 <div class="hero-text">
    <div class="hero-text__inner">
        <div class="hero-text__img">
            <img src="<?php echo $bill_image_src[0]; ?>" alt="<?php echo $bill_image_src_alt; ?>" />
        </div>
        <h1 class="hero-text__title hero-text__title--alt oversized"><?php echo esc_html($bill_heading); ?></h1>
        <p class="hero-text__text intro"><?php echo esc_html($bill_intro); ?></p>
    </div>
</div>
<main id="content" role="main">
<div class="content-two-col content-two-col--stacked-left">
	<div class="content-two-col__inner">
		<div class="content-two-col__first">
			<h2><?php echo esc_html($main_cont_heading); ?></h2>
			<p><?php echo esc_html($main_cont_para_1); ?></p>
			<p><?php echo esc_html($main_cont_para_2); ?></p>
		</div>
		<?php if($main_cont_img_1) { ?>
		<div class="content-two-col__last">
			<img src="<?php echo $main_cont_img_1[0]; ?>" alt="<?php echo $main_cont_img_1_alt; ?>">
		</div>
		<?php } ?>
	</div>
</div>
 <div class="content-two-col content-two-col--stacked-left">
    <div class="content-two-col__inner">
		<?php if($main_cont_img_2) { ?>
			<div class="content-two-col__first no-padding">
				<img src="<?php echo $main_cont_img_2[0]; ?>" alt="<?php echo $main_cont_img_2_alt; ?>">
			</div>
		<?php } ?>
        <div class="content-two-col__last">
            <p><?php echo esc_html($main_cont_para_3); ?></p>
			<?php if($main_cont_para_4) { ?>
			<p><?php echo esc_html($main_cont_para_4); ?></p>
			<?php } ?>
        </div>
    </div>
</div>
<div class="wrapper wrapper--secondary">
    <div class="wrapper__inner">
        <div class="content-two-col content-two-col--reversed">
            <div class="content-two-col__inner">
                <div class="content-two-col__first">
					<h2><?php echo esc_html($cont_block_simple_heading[0]); ?></h2>
					<p><?php echo esc_html($cont_block_simple_para_1[0]); ?></p>
					<p><?php echo esc_html($cont_block_simple_para_2[0]); ?></p>
					<p><?php echo esc_html($cont_block_simple_para_3[0]); ?></p>
                </div>
                <div class="content-two-col__last">
					<div class="aside aside--tertiary aside--img-top">
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
    </div>
</div>
<div class="content-two-col content-two-col--two-thirds-left">
	<div class="content-two-col__inner">
		<div class="content-two-col__first">
		<?php if (isset($cont_block_simple_heading[1]) || array_key_exists('1', $cont_block_simple_heading)): ?>
		<h2><?php echo esc_html($cont_block_simple_heading[1]); ?></h2>
		<p><?php echo esc_html($cont_block_simple_para_1[1]); ?></p>
		<p><?php echo esc_html($cont_block_simple_para_2[1]); ?></p>
		<p><?php echo esc_html($cont_block_simple_para_3[1]); ?></p>
		<?php endif; ?>
		</div>
        <div class="content-two-col__last">
            <div class="item-list">
                <h3 class="item-list__heading"><?php echo esc_html($cont_listing_heading); ?></h3>
                <?php $cont_listing_items = get_post_meta($post_id, 'listing_items', true);
				if($cont_listing_items): ?>
				<ul class="item-list__list">
				<?php for ($i=0;$i<$cont_listing_items;$i++) { ?>
                    <li class="item-list__item"><?php echo esc_html(get_post_meta($post_id, 'listing_items_'.$i.'_item', true)); ?></li>
				<?php } ?>
                </ul>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="content-three-col">
	<div class="content-three-col__inner">
	<?php $sub_items = get_post_meta($post_id, 'sub_repeater_items', true);
	if($sub_items):
		for ($j=0;$j<$sub_items;$j++) { 
			switch($j){
				case 0:
					echo '<div class="content-three-col__first">';
					break;
				case 1:
					echo '<div class="content-three-col__middle">';
					break;
				default:
					echo '<div class="content-three-col__last">';
					break;
			}
		?>
		<div class="cta">
			<img class="cta__img" src="<?php echo wp_get_attachment_image_src(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_image', true), 'large')[0]; ?>" alt="<?php echo get_post_meta(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_image', true), '_wp_attachment_image_alt', true); ?>">
			<div class="cta__content">
				<h3 class="cta__title"><a href="<?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_heading_target', true)); ?>"><?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_heading', true)); ?></a></h3>
				<p><?php echo esc_html(get_post_meta($post_id, 'sub_repeater_items_'.$j.'_body', true)); ?></p>
			</div>
		</div>
		</div>
	<?php } ?>
	<?php endif; ?>
	</div>
</div>
<?php
get_footer();