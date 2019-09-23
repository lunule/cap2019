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
		
		<header class="entry-header">
			
			<?php 
			the_title( '<h1 class="entry-title">', '</h1>' ); 

			$subtitle = get_field('cap_page_subtitle');
			if ( $subtitle && ( '' !== $subtitle ) ) echo "<h2 class='entry-subtitle'>{$subtitle}</h2>";
			?>
		
		</header><!-- .entry-header -->

		<?php cap_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
			the_content(); ?>

			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cap' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

	</article><!-- #post-<?php the_ID(); ?> -->

	<div class="cap-page__sidebar flex-item">
		
		<?php get_sidebar('nw'); ?>

	</div>

</div>