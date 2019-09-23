<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package cap2019
 */

get_header();

// $paged - number of the current page
global $paged, $wp_query;
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
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
							printf( esc_html__( 'Search Results for: %s', 'cap' ), '<span>' . get_search_query() . '</span>' );
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

								if ( has_excerpt( $ID ) ) :

									if ( ctype_space( get_the_excerpt( $ID ) ) ) :
									
									$excerpt = $post->post_content;
									// Remove iframe from string
									$excerpt = preg_replace('/<iframe.*?\/iframe>/i','', $excerpt); 

									$excerpt = wp_strip_all_tags( 
													cap_custom_excerpt( 
														$excerpt, 
														50, 
														get_permalink(), 
														'' 
													)
												  );
									
									else: 

										$excerpt = wpautop( $post->post_excerpt );
									
									endif;

								else :

									$excerpt = $post->post_content;
									// Remove iframe from string
									$excerpt = preg_replace('/<iframe.*?\/iframe>/i','', $excerpt); 

									$excerpt = wp_strip_all_tags( 
													cap_custom_excerpt( 
														$excerpt, 
														50, 
														get_permalink(), 
														'' 
													)
												  );

								endif;


								$permalink = get_the_permalink( $ID );

								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'cap-single-thumbnail' ); 

								$thumb = filter_var( $thumb[0], FILTER_VALIDATE_URL )
											? $thumb[0]
											: get_template_directory_uri() . '/img/default-thumb.jpg';

								$countries 		= get_the_terms( $ID, 'country' );
								$has_countries 	= ( $countries && !is_wp_error( $countries ) );

								$list_cats_Arr 	= wp_get_post_categories( 
									$ID,
									array(
										'fields' => 'names',
									) 
								);
								$has_cats 		= ( 
													( count( $list_cats_Arr ) > 1 ) || 
													( 
														( count( $list_cats_Arr ) == 1 ) && 
														( 'Uncategorized' !== $list_cats_Arr[0] ) 
													) 
												  );
								?>

								<div class="no-sticky flex-container <?php echo 'no-sticky--' . $unst_i; ?> <?php echo 'pt--' . get_post_type(); ?>">
								
									<div class="no-sticky__left flex-item">

										<div class="no-sticky__meta flex-container">

												<?php if ( $has_cats ) : ?>

													<div class="entry-meta__post-cats flex-item flex-container">
														<?php cap_post_cats( 3, false ); ?>
													</div>										
												<?php endif;

												if ( $has_countries ) : ?>

													<div class="entry-meta__post-countries flex-item flex-container">
														<?php cap_post_countries(); ?>
													</div>				

												<?php 
												endif; ?>									
										</div>

										<h2 class="no-sticky__title"><a href="<?php echo $permalink; ?>"><?php echo relevanssi_get_the_title( $ID ); ?></a></h2>

										<div class="no-sticky__excerpt"><?php echo $excerpt; ?></div>

									</div>

									<div class="no-sticky__right flex-item">

										<div class="wrap--no-sticky__thumb">

											<div class="no-sticky__thumb">
											
												<a href="<?php echo $permalink; ?>" style="background: transparent url('<?php echo $thumb; ?>') center center/cover no-repeat;"></a>

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
				else: ?>

					<header class="page-header">

						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'cap' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
						
						<div class="page-description"><?php esc_html_e( 'Nothing Found :(', 'cap' ); ?></div>						

					</header><!-- .page-header -->
				
				<?php
				endif; ?>
			
			</div>

			<div class="cap-search__sidebar flex-item">

				<?php get_sidebar('cpt-archive'); ?>

			</div>

		</div>

	</main><!-- #main -->

</div><!-- #primary -->

<?php
get_footer();
