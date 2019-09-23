<?php
/**
 * The template for displaying member search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package cap2019
 */

get_header();

// $paged - number of the current page
global $paged, $wp_query; 
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$s = cap_return_member_search_query();
?>

<div id="primary" class="primary primary--search content-area">
	
	<main id="main" class="site-main container">

		<div class="cap-search__main flex-container">

			<div class="cap-search__posts flex-item">

				<?php
				if ( have_posts() ) : ?>

					<header class="page-header">

						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'cap' ), '<span>' . $s . '</span>' );
							?>
						</h1>

					</header><!-- .page-header -->

					<div class="wrap--no-stickies <?php if ( empty( $search_stickies ) ) echo ' beware-no-stickies'; ?>">
						
						<div class="no-stickies">
				
							<?php
							$unst_i = 0;
							while ( have_posts() ) : 

								the_post();

								$ID 		= get_the_ID();
								$title 		= get_the_title( $ID );

								$content 	= $post->post_content;

								$excerpt 	= wp_strip_all_tags( 
												cap_custom_excerpt( 
													$content, 
													50, 
													get_permalink(), 
													'' 
												)
											  );

								$permalink = get_the_permalink( $ID );

								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'cap-single-thumbnail' ); 

								$thumb = filter_var( $thumb[0], FILTER_VALIDATE_URL )
											? $thumb[0]
											: get_template_directory_uri() . '/img/default-thumb.jpg';

								$country 		= get_field('net_country');
								$has_country 	= ( $country && ( '' !== $country ) );

								$prof 			= get_field('net_profcat');
								$has_prof 		= ( $prof && ( '' !== $prof ) );
								?>

								<div class="no-sticky flex-container">
								
									<div class="no-sticky__left flex-item">

										<?php 
										if ( $has_prof || $has_country ) : ?>

											<div class="no-sticky__meta flex-container">

													<?php if ( $has_prof ) : ?>

														<div class="member-meta__prof flex-item flex-container">
															<?php echo $prof; ?>
														</div>										
													<?php endif;

													if ( $has_country ) : ?>
		
														<div class="member-meta__country flex-item flex-container">
															<span>from</span>
															<?php echo $country; ?>
														</div>				

													<?php 
													endif; ?>	

											</div>

										<?php 
										endif; ?>	

										<h2 class="no-sticky__title"><a href="<?php echo $permalink; ?>"><?php echo relevanssi_get_the_title( $ID ); ?></a></h2>

										<div class="no-sticky__excerpt"><?php echo $excerpt; ?></div>

									</div>

									<div class="no-sticky__right flex-item">

										<div class="wrap--no-sticky__thumb">

											<div class="no-sticky__thumb">
											
												<a href="<?php echo $permalink; ?>"><?php the_post_thumbnail( 'member-thumbnail' ); ?></a>

											</div>

										</div>

									</div>

								</div>
							
								<?php 
								if ( ( $unst_i == 4 ) && ( $paged == 1 ) ) : 

									$btitle = get_field( 'cap_general_arch_cobustitle', 'option' );
									$btitle = ( $btitle && ( '' !== $btitle ) )
												? $btitle
												: false;

									cap_blog_inbetween_cobus( false, $btitle );

								endif;

								$unst_i++;
							
							endwhile; 
							wp_reset_postdata();

							/**
							 * PAGINATION
							 * ==========
							 */

							// $paged is defined at the top - it's needed elsewhere as 
							// well in the code, not only in pagination. 

						    $total_pages 	= $wp_query->max_num_pages;
						    $big 			= 999999999; // need an unlikely integer
						    $current_page 	= max(1, $paged);

							$pargs = array(
								'prev_text' => '<span class="cap-pagination__prev dashicons dashicons-arrow-left-alt2"></span>',
								'next_text' => '<span class="cap-pagination__prev dashicons dashicons-arrow-right-alt2"></span>',
								// Using 'type' => 'array' you can remove 
								// page links by using unset().
								// E.g.: unset( $links[ $total_pages - 7 ] ) - 
								// just don't forget to set the $total_pages 
								// value, see below;
								'type' 		=> 'array',
								'mid_size' 	=> 1,
								'end_size' 	=> 1,

					            'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					            'format' 	=> '?paged=%#%',
					            'current' 	=> $current_page,
					            'total' 	=> $total_pages,
					            'paged' 	=> $paged,
	            			);

						    if ($total_pages > 1) :

								$links = paginate_links($pargs); 

								echo '<div class="wrap--cap-pagination"><div class="cap-pagination">';
								
								// Add "Page x of X" info span
								echo "<span class='page-numbers pageof-info'>Page {$paged} of {$total_pages}</span>";

								foreach ( $links as $link ) echo $link;

								echo '</div></div>';

							endif;
							?>

						</div>

					</div>

				<?php
				else:

					get_template_part( 'template-parts/content', 'none-nw' );
				
				endif; ?>
			
			</div>

			<div class="cap-search__sidebar flex-item">
				
				<?php
				if ( is_active_sidebar( 'sidebar-11' ) ) : ?>

					<aside id="secondary" class="widget-area">
						<?php dynamic_sidebar( 'sidebar-11' ); ?>
					</aside><!-- #secondary -->

				<?php 
				endif; ?>

			</div>

		</div>

	</main><!-- #main -->

</div><!-- #primary -->

<?php

get_footer();

