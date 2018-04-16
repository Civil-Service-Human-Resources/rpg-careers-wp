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
        <h1 class="hero-text__title hero-text__title--alt oversized"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'rpgcareers' ); ?></h1>
    </div>
</div>
<main id="content" role="main">
<?php
get_footer();