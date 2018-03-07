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
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="robots" content="index,follow">
	<meta name="google" content="nositelinkssearchbox" />
	<title>Civil service careers - <?php the_title(); ?></title>
	<?php wp_head(); ?>
	<style id="fKill">body{display:none!important;}</style>
	<script type="text/javascript">if(self===top){var f=document.getElementById('fKill');f.parentNode.removeChild(f);}else{top.location=self.location;}</script>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'body_open' ); ?>
<div id="skiplink-container"><div><a href="#content" class="skiplink">Skip to main content</a></div></div>
<div id="global-cookie-message"<?php global $cookie_banner_set; if($cookie_banner_set){ echo ' style="display:block;"';}?>><p>GOV.UK uses cookies to make the site simpler. <a href="<?php echo get_site_url(); ?>/cookies">Find out more about cookies</a></p></div>
<header role="banner">
	<nav role="navigation" aria-label="site" class="main-nav">
	<input type="checkbox" id="menu-toggle" /><label for="menu-toggle" class="label-menu-toggle"></label><label for="menu-toggle" class="label-menu-toggle-off"></label><?php
	  wp_nav_menu(array(
		'container'			=> false,
		'items_wrap'		=> '<ul class="main-nav-items">%3$s</ul>',
		'theme_location'	=> 'main-nav',
		'walker' => new RPG_Walker_Nav_Menu(),
	  ));
	?></nav>
</header>
<div id="main">
	<main id="content" role="main">