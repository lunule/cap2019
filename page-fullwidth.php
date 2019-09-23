<?php
/**
 * Template Name: Full Width Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

get_header();

$main_class = is_front_page() ? '' : ' container';
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main<?php echo $main_class; ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'fullwidth' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
