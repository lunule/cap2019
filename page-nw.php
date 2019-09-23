<?php
/**
 * Template Name: The CAP Expert Network
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

get_header();
?>

	<div id="temporary">
		<div></div>
	</div>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'nw' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
