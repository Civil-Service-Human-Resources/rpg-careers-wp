<?php
/**
 * Template Name: Hub : Standard
 */
get_header(); ?>
	<div class="page-homepage">
			<h1 class="heading-xlarge"><?php the_title(); ?></h1>

			<?php 
				$hub = get_field('component_hub_intro');
				echo $hub['component_hub_intro_content'];
			?>
	</div>
<?php
get_footer();