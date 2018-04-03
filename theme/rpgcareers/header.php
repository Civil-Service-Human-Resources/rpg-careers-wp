<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package RPG_Careers
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="robots" content="index,follow">
	<meta name="google" content="nositelinkssearchbox" />
	<title><?php the_title(); ?> | Civil Service Careers</title>
	<?php wp_head(); ?>
	<style id="fKill">body{display:none!important;}</style>
	<script>if(self===top){var f=document.getElementById('fKill');f.parentNode.removeChild(f);}else{top.location=self.location;}</script>
	<!--[if lt IE 9]>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/modernizr-custom.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/respond.js"></script>
    <![endif]-->
</head>
<body <?php body_class(); ?>>
<?php do_action( 'body_open' ); ?>
<div class="skiplink"><a href="#content">Skip to main content</a></div>
<div id="global-cookie-message"<?php global $cookie_banner_set; if($cookie_banner_set){ echo ' style="display:block;"';}?>><p>GOV.UK uses cookies to make the site simpler. <a href="<?php echo get_site_url(); ?>/cookies">Find out more about cookies</a></p></div>
<header class="masthead" role="banner">
	<div class="masthead__brand">
		<span class="logo logo--horizontal">
			<span class="logo__text">Civil Service</span>
		</span>
		<span class="masthead__brand-text">Careers</span>
	</div>
	<nav role="navigation" aria-label="site" class="masthead__nav">
	<?php
	  wp_nav_menu(array(
		'container'			=> false,
		'items_wrap'		=> '<ul class="masthead__menu" id="nav">%3$s</ul>',
		'theme_location'	=> 'main-nav',
		'walker' => new RPG_Walker_Nav_Menu(),
	  ));
	?></nav>
</header>