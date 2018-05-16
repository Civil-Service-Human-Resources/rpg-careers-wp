<?php
/**
 * Template Name: Page : Simple Text Only
 */
 get_header(); ?>

<?php 
	$post_id = get_the_ID();
	$page_heading = get_post_meta($post_id,'heading',true);
	$page_content = get_post_meta($post_id,'content',true);
?>
<div class="hero-text">
    <div class="hero-text__inner">
        <h1 class="hero-text__title hero-text__title--alt oversized"><?php echo $page_heading; ?></h1>
    </div>
</div>
<main id="content" role="main">
<div class="content-two-col">
<?php echo $page_content; ?>
</div>
<?php
get_footer();