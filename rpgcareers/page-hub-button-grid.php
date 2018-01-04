<?php
/**
 * Template Name: Hub : Button Grid
 */
get_header(); ?>
	<h1 class="heading-xlarge"><?php the_title(); ?></h1>
	<?php if( have_rows('hub_buttons') ): ?>
		<?php while( have_rows('hub_buttons') ): the_row(); 
			$image = get_sub_field('hub_button_image');
		?>
			<div class="component-button-grid__button">
				<div class="component-button-grid__image" style="background-image: url(<?php echo $image['url']; ?>)"></div>
				<a href="<?php the_sub_field('hub_button_target'); ?>" class="component-button-grid__inner">
				<h2 class="component-button-grid__text"><?php the_sub_field('hub_button_text'); ?></h2>
				</a>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
<?php
get_footer();