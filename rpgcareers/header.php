<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Civil_Service_Careers
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
<!--<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'civil-service-careers' ); ?></a>-->
<header class="masthead">
	<div class="masthead__first">
		<div class="masthead__inner">
			<a class="masthead__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</div>
	</div>

	<div class="masthead__last">
		<div class="masthead__inner">
			<div class="navigation">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'main-nav',
						'menu_id' => false,
						'container' => 'nav',
					) );
				?>
			</div>
		</div>
	</div>

</header>

<main class="content">
	<div class="content__inner">
