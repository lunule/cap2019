<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

?>

<div class="cap-page__main flex-container">

	<article id="post-<?php the_ID(); ?>" <?php post_class('cap-page__hentry flex-item'); ?>>
		
		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cap' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

	</article><!-- #post-<?php the_ID(); ?> -->

</div>