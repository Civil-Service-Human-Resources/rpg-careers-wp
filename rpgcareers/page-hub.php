<?php
/**
 * Template Name: Hub : Standard
 */
get_header(); ?>
	<div class="page-homepage">
			<h1 class="heading-xlarge"><?php the_title(); ?></h1>

			<?php 
				// STAT BANNER COMPONENT
				$banner = get_field('departmenthome_banner');
				$data = array(
					'heading' => $banner['banner_heading'],
					'bgimage' => $banner['banner_background_image']
				);

				include(locate_template('template-parts/components/stat-banner.php'));
			?>

			<?php 
				// SEARCH BOX COMPONENT
				$search = get_field('departmenthome_search_box');
				$data = array(
					'label' => $search['departmenthome_search_label'],
					'explainer' => $search['departmenthome_search_explanation_text']
				);

				include(locate_template('template-parts/components/search.php'));
			?>

			<?php 
				// TEXT / IMAGE COMPONENT
				$textimage1 = get_field('departmenthome_text_and_image_component_1');
				$data = array(
					'heading' => $textimage1['departmenthome_text_and_image_component_1_heading'],
					'image' => $textimage1['departmenthome_text_and_image_component_1_image'],
					'intro' => $textimage1['departmenthome_text_and_image_component_1_intro_text'],
					'content' => $textimage1['departmenthome_text_and_image_component_1_content']
				);

				include(locate_template('template-parts/components/text-image.php'));
			?>

			<?php 
				// DEPARTMENT LIST COMPONENT
				$depts = get_field('dept_info');
				$data = array(
					'heading' => $depts['dept_info_heading'],
					'intro' => $depts['dept_info_sub_heading'],
					'department_fieldname' => 'dept_info_department_list',
					'departments' => $depts['dept_info_department_list'],
					'departments_link' => $depts['link_to_list_of_departments']
				);

				include(locate_template('template-parts/components/department-list.php'));
			?>

	</div>
<?php
get_footer();