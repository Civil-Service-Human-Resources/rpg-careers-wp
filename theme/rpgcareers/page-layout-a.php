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
	
?>
<div class="banner">
	<picture>
        <source srcset="<?php echo $bill_image_src['url']; ?>" media="(min-width: 680px)">
        <source srcset="<?php echo $bill_image_src['url']; ?>">
        <img src="<?php echo $bill_image_src['url']; ?>" alt="<?php echo $bill_image_src['alt']; ?>">
    </picture>
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
<?php
get_footer();