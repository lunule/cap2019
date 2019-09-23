<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

get_header();
?>

	<div id="primary" class="content-area">
		
		<main id="main" class="site-main container">

			<div class="cap-listing__main flex-container">

				<div class="cap-listing__posts flex-item">

					<header>
						
						<h1 class="page-title"><?php single_post_title(); ?></h1>

						<?php 
						$pdesc = get_field('cap_listing_page_description', get_option('page_for_posts') );

						if ( $pdesc && ( '' !== $pdesc ) )
							echo "<div class='page-description'>{$pdesc}</div>";
						?>

					</header>

					<?php
					if ( have_posts() ) :

						$i = 1;

						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_type() );

							if ( ( $i - 7 ) % 28 == 0 ) : 		cap_blog_inbetween_ad();
							elseif ( ( $i - 14 ) % 28 == 0 ) : 	cap_blog_inbetween_cobus();
							elseif ( ( $i - 21 ) % 28 == 0 ) :	cap_blog_inbetween_nw();
							elseif ( ( $i - 28 ) % 28 == 0 ) :	cap_blog_inbetween_stud();
							endif;																					
							$i++;

						endwhile;

						/**
						 * PAGINATION
						 * ==========
						 */
						global $paged, $wp_query;

						$paged 			= ( get_query_var( 'paged' ) ) 
												? absint( get_query_var( 'paged' ) ) 
												: 1;
					    $total_pages 	= $wp_query->max_num_pages;
					    $big 			= 999999999; // need an unlikely integer
					    $current_page 	= max(1, get_query_var('paged'));

						$pargs = array(
							'prev_text' => '<span class="cap-pagination__prev dashicons dashicons-arrow-left-alt2"></span>',
							'next_text' => '<span class="cap-pagination__prev dashicons dashicons-arrow-right-alt2"></span>',
							// Using 'type' => 'array' you can remove page links by using unset().
							// E.g.: 	unset( $links[ $total_pages - 7 ] ) - just don't forget to 
							// set the $total_pages value, see below;
							'type' 		=> 'array',
							'mid_size' 	=> 1,
							'end_size' 	=> 1,

				            'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				            'format' 	=> '?paged=%#%',
				            'current' 	=> $current_page,
				            'total' 	=> $total_pages,
				            'paged' 	=> $paged
            			);

					    if ($total_pages > 1) :

							$links = paginate_links($pargs); 

							echo '<div class="wrap--cap-pagination"><div class="cap-pagination">';
							
							// Add "Page x of X" info span
							echo "<span class='page-numbers pageof-info'>Page {$paged} of {$total_pages}</span>";

							foreach ( $links as $link ) echo $link;

							echo '</div></div>';

						endif;

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>

				</div>

				<div class="cap-listing__sidebar flex-item">

					<?php get_sidebar('post-archive'); ?>

				</div>

			</div>

		</main><!-- #main -->

	</div><!-- #primary -->

<?php
get_footer();
