<?php
/**
 * Template Name: Home
 */
get_header(); ?>
	<?php $image = get_field('hero_image'); ?>
	<div style="width:100%;height:500px;position:absolute;top:86px;left:0;background-image:url(<?php echo $image['url']; ?>);background-size: cover;background-position:0 -300px;"></div>

	<?php if( have_rows('hero_headline') ): ?>
	<div style="position:relative;margin-top:440px;">
		<?php while( have_rows('hero_headline') ): the_row();  ?>
			<h1 class="heading-xlarge"><?php the_sub_field('headline'); ?></h1>
		<?php endwhile; ?>
	</div>
	<?php endif; ?>

	<?php if( have_rows('callouts') ): ?>
	<ul class="component-callouts__list">
		<?php while( have_rows('callouts') ): the_row(); 
			$imageCallOut = get_sub_field('image');
		?>
		<li class="component-callouts__list-item">
			<img src="<?php echo $imageCallOut['url']; ?>" alt="<?php echo $imageCallOut['alt']; ?>" />
            <h3 class="heading-medium"><?php the_sub_field('title'); ?></h3>
            <p><?php the_sub_field('text'); ?></p>
        </li>
		<?php endwhile; ?>
	</ul>
	<?php endif; ?>
<?php
get_footer();