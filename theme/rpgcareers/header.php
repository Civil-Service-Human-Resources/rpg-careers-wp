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
	<meta name="google" content="nositelinkssearchbox" />
	<title><?php echo (is_404() ? 'Page not found' : the_title()); ?> | Civil Service Careers</title>
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<?php do_action('gtm_head'); ?>
	<?php wp_head(); ?>
	<!--[if lt IE 9]>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/respond.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/respond.matchmedia.addListener.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/matchmedia.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/flexibility.js"></script>
    <![endif]-->
</head>
<body <?php body_class(); ?>>
<?php do_action('gtm_body'); ?>
<div class="skiplink"><a href="#content">Skip to main content</a></div>
<div id="global-cookie-message"<?php global $cookie_banner_set; if($cookie_banner_set){ echo ' style="display:block;"';}?>><p>GOV.UK uses cookies to make the site simpler. <a href="<?php echo get_site_url(); ?>/cookies">Find out more about cookies</a></p></div>
<header class="masthead" role="banner">
	<div class="masthead__brand">
		<a href="<?php echo get_site_url(); ?>">
		<span class="logo logo--horizontal">
			<span class="logo__text">Civil Service</span>
		</span>
		</a>
		<span class="masthead__brand-text">Civil Service careers</span>
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
<?php echo do_shortcode("[rpg_snippet tagcode='489']"); ?>