<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package RPG_Careers
 */
get_header(); ?>
<div class="hero-text">
    <div class="hero-text__inner">
        <h1 class="hero-text__title hero-text__title--alt oversized"><?php esc_html_e( 'Oops!', 'rpgcareers' ); ?></h1>
    </div>
</div>
<main id="content" role="main">
<div class="content-two-col">
<p>We can't seem to find the page you're looking for.</p>
<p>If you typed in the web address, please check that you entered it correctly. If you followed a link, we may have moved or deleted the page.</p>
<p><a href="/">Back to the home page</a></p>
</div>
<?php
get_footer();