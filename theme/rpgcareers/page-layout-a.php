<?php
/**
 * Template Name: Page : Layout A
 */
get_header(); ?>

<?php 
	$bill_logo_text = get_field('billboard_logo_text');
	$bill_logo_src = get_field('billboard_logo');
	$bill_heading = get_field('billboard_heading');
	$bill_intro = get_field('billboard_intro_text');
	$bill_image_src = get_field('billboard_image');

	$main_cont_heading = get_field('main_content_heading');
	$main_cont_para_1 = get_field('main_content_paragraph_1');
	$main_cont_para_2 = get_field('main_content_paragraph_2');
	$main_cont_para_3 = get_field('main_content_paragraph_3');
	$main_cont_img_1 = get_field('main_content_image_1');
	$main_cont_img_2 = get_field('main_content_image_2');
	
	$cont_block_vert_image = get_field('content_block_vertical_image');
	$cont_block_vert_quote = get_field('content_block_vertical_quote');
	$cont_block_vert_forename = get_field('content_block_vertical_forename');
	$cont_block_vert_surname = get_field('content_block_vertical_surname');
	$cont_block_vert_role = get_field('content_block_vertical_role');

	$cont_block_hori_image = get_field('content_block_horizontal_image');
	$cont_block_hori_quote = get_field('content_block_horizontal_quote');
	$cont_block_hori_forename = get_field('content_block_horizontal_forename');
	$cont_block_hori_surname = get_field('content_block_horizontal_surname');
	$cont_block_hori_role = get_field('content_block_horizontal_role');
	$cont_block_hori_extra_txt = get_field('content_block_horizontal_extra_text');

	$sub_cont_heading = get_field('sub_content_heading');
	$sub_cont_intro = get_field('sub_content_intro');



?>
<div class="banner">
	<style>.banner {background-image: url<?php echo $bill_image_src['url']; ?>);}@media (min-width: 768px) {.banner {background-image: url(<?php echo $bill_image_src['url']; ?>);}}</style>
</div>
<main id="content" role="main">
<div class="department-intro">
    <div class="department-intro__inner">
        <div class="department-intro__head">
            <div class="department-intro__head-logo">
                <span class="logo" style="background-image:url(<?php echo $bill_logo_src['url']; ?>);">
                <span class="logo__text"><?php echo $bill_logo_text; ?></span>
            </span>
            </div>
            <h1 class="department-intro__head-title"><?php echo $bill_heading; ?></h1>
        </div>
		<div class="department-intro__content">
			<p class="intro"><?php echo $bill_intro; ?></p>
		</div>
    </div>
</div>
<div class="content-two-col content-two-col--stacked-left">
    <div class="content-two-col__inner">
        <div class="content-two-col__first">
            <h2><?php echo $main_cont_heading; ?></h2>
            <p><?php echo $main_cont_para_1; ?></p>
			<p><?php echo $main_cont_para_2; ?></p>
            <?php if($main_cont_img_1) { ?>
			<div class="image image--spaced"><img src="<?php echo $main_cont_img_1['url']; ?>" alt="<?php echo $main_cont_img_1['alt']; ?>"></div>
			<?php } ?>
            <p><?php echo $main_cont_para_3; ?></p>
        </div>
        <div class="content-two-col__last">
            <div class="aside aside--img-top">
                <div class="aside__inner">
                    <div class="aside__img">
                        <img src="<?php echo $cont_block_vert_image['url']; ?>" alt="<?php echo $cont_block_vert_image['alt']; ?>">
                    </div>
                    <blockquote class="aside__content">
                        <p><?php echo $cont_block_vert_quote; ?></p>
                        <footer><?php echo $cont_block_vert_forename; ?> <?php echo $cont_block_vert_surname; ?><strong><?php echo $cont_block_vert_role; ?></strong></footer>
                    </blockquote>
                </div>
            </div>
			<?php if($main_cont_img_2) { ?>
			<div class="image"><img src="<?php echo $main_cont_img_2['url']; ?>" alt="<?php echo $main_cont_img_2['alt']; ?>"></div>
			<?php } ?>
        </div>
    </div>
</div>
	<?php if(have_rows('factoid_factoid')): ?>
	<div class="slider">
	<?php while (have_rows('factoid_factoid')) : the_row(); ?>
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
                <p><?php the_sub_field('fact'); ?></p>
            </div>
        </div>
	<?php endwhile; ?>
	</div>
<?php endif; ?>
    <div class="content-two-col content-two-col--two-thirds-left">
        <div class="content-two-col__inner">
            <div class="content-two-col__first">
                <div class="aside aside--img-right aside--dark">
                    <div class="aside__inner">
                        <div class="aside__img">
							<img src="<?php echo $cont_block_hori_image['url']; ?>" alt="<?php echo $cont_block_hori_image['alt']; ?>">
                        </div>
                        <blockquote class="aside__content">
							<p><?php echo $cont_block_hori_quote; ?></p>
							<footer><?php echo $cont_block_hori_forename; ?> <?php echo $cont_block_hori_surname; ?><strong><?php echo $cont_block_hori_role; ?></strong></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="content-two-col__last">
				<p><?php echo $cont_block_hori_extra_txt; ?></p>
            </div>
        </div>
    </div>
	<div class="content-two-col">
        <div class="section-intro">
            <h2><?php echo $sub_cont_heading; ?></h2>
            <p><?php echo $sub_cont_intro; ?></p>
        </div>
        <div class="content-two-col__inner">
			<?php if(have_rows('sub_content_driver')):
				$sub_row_cnt = 0;
				while (have_rows('sub_content_driver')) : the_row(); ?>
				<div class="content-two-col__<?php echo (($sub_row_cnt === 0) ? 'first' : 'last'); ?>">
					<div class="cta">
						<img class="cta__img" src="https://placebear.com/900/600" alt="Image alt here">
						<div class="cta__content">
							<h3 class="cta__title"><a href="<?php the_sub_field('link'); ?>"><?php the_sub_field('headline'); ?></a></h3>
							<p><?php the_sub_field('text'); ?></p>
						</div>
					</div>
				</div>
				<?php $sub_row_cnt++; endwhile; ?>
			<?php endif; ?>
        </div>
    </div>
<?php
get_footer();