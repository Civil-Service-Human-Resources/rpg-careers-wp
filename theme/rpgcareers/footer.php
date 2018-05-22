<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package RPG_Careers
 */

?>
</main>
<span class="mobile-overlay"></span>
<footer class="footer" role="contentinfo"><div class="footer__inner"><?php echo do_shortcode("[rpg_snippet tagcode='53']"); ?></div></footer>
<!--[if gt IE 8]>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/modernizr-custom.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/rem.js"></script>
<![endif]-->
<!--[if IE 8]>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/tiny-slider.helper.ie8.js"></script>
<![endif]-->
<?php wp_footer(); ?>
</body>
</html>