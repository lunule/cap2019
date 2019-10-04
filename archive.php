<?php
/**
 * The template for displaying Analysis archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */
get_header();
$sticky_posts = ALL_STICKIES;

// $paged - number of the current page
global $paged, $wp_query;
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

/**
 * GET THE ARRAY OF IDS OF ALL POSTS RETURNED BY THIS QUERY
 * ========================================================
 */
$pt_Arr = false;
$pt_Obj = false;

if ( is_date() || is_author() ) :

	$pt_Arr = $wp_query->query_vars; 

else :

	$pt_Obj = $wp_query->get_queried_object();

endif;

// Custom taxonomy archive
if ( is_tax() ) :
	$postlist = get_posts( array(
		'post_type' 		=> array( 'post', 'analysis', 'podcast', 'studentpost', 'nwmember' ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	    'tax_query' => array(
	        array (
	            'taxonomy' => $pt_Obj->taxonomy,
	            'field' => 'slug',
	            'terms' => $pt_Obj->slug,
	        )
	    ),							
	) );
// Category archive
elseif ( is_category() ) :

	$postlist = get_posts( array(
		'post_type' 		=> array( 'post', 'analysis', 'podcast', 'studentpost' ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	    'category_name' 	=> $pt_Obj->slug,							
	) );

// Category archive
elseif ( is_tag() ) :

	$postlist = get_posts( array(
		'post_type' 		=> array( 'post', 'analysis', 'podcast', 'studentpost' ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	    'tag' 				=> $pt_Obj->slug,							
	) );					

// Author archive
elseif ( is_author() ) :

	$postlist = get_posts( array(
		'post_type' 		=> array( 'post', 'analysis', 'podcast', 'studentpost' ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	    'author' 			=> $pt_Arr['author'],							
	) );

// Date archive
elseif ( is_date() ) :

	$year 		= $pt_Arr['year'];
	$month_raw 	= $pt_Arr['monthnum'];
	$date_Obj   = DateTime::createFromFormat( '!m', $month_raw );
	$month 		= $date_Obj->format('F');
	$day 		= $pt_Arr['day'];	

	$postlist = get_posts( array(
		'post_type' 		=> array( 'post', 'analysis', 'podcast', 'studentpost' ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	    'year' 				=> $pt_Arr['year'],
	    'month' 			=> $pt_Arr['monthnum'],
	    'day' 				=> $pt_Arr['day'],							
	) );					

// Custom post type archive
else : 

	$pt = $pt_Obj->name;

	$postlist = get_posts( array(
		'post_type' 		=> array( $pt ),
		'posts_per_page' 	=> -1,
		'post_status' 		=> array('publish'),
	) );

endif;

// Check if the $sticky_posts array and the array of the ids of the
// archive main query result posts have common elements.
$mainquery_res_ids = array();

if ( $postlist ) :
    foreach ( $postlist as $post ) : setup_postdata( $post );

		$mainquery_res_ids[] = $post->ID;

	endforeach;
    wp_reset_postdata();
    wp_reset_query();
endif;		

$thisarch_stickies = array_intersect( $sticky_posts, $mainquery_res_ids );				

// We only need the first 5 elements
$thisarch_stickies = array_slice( $thisarch_stickies, 0, 5 );

/**
 * ============================================================
 * EOF get the array of ids of all posts returned by this query
 */

?>

<div id="primary" class="primary primary--archive content-area">
	
	<main id="main" class="site-main container">

		<div class="cap-cpt-archive__main flex-container">

			<div class="cap-cpt-archive__posts flex-item">

				<header class="page-header">

					<?php

					if ( is_post_type_archive( array('podcast', 'analysis', 'studentpost') ) ) :

						$pt_Obj = get_queried_object();
						$pt 	= $pt_Obj->name;

						switch ( $pt ) :

							case 'analysis': 	$pt_shortname 	= 'ana'; 	break;
							case 'podcast': 	$pt_shortname 	= 'pod'; 	break;
							case 'studentpost': $pt_shortname 	= 'stud'; 	break;				
							
						endswitch;						

						$archtitle 	= get_field(
										'cap_general_arch_' . $pt_shortname . 'title', 
										'option'
									  );
						$archdesc 	= get_field(
										'cap_general_arch_' . $pt_shortname . 'desc', 
										'option'
									  );

						$archtitle = ( $archtitle && ( '' !== $archtitle ) )
										? $archtitle
										: post_type_archive_title( '', false );

						echo "<h1 class='page-title'>{$archtitle}</h1>";

						if ( $archdesc && ( '' !== $archdesc ) ) :
							echo "<div class='archive-description'>{$archdesc}</div>";
						else:
							the_archive_description( '<div class="archive-description">', '</div>' );
						endif;

					else :

						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );

					endif;
					?>

				</header><!-- .page-header -->

				<?php
				if ( !empty( $thisarch_stickies && ( $paged == 1 ) ) ) : 

					$sticky_class = ( count( $thisarch_stickies ) > 1 ) 
										? 'multi-sticky multi--' . count( $thisarch_stickies ) 
										: 'single-sticky';
					?>

					<div class="wrap--stickies <?php echo $sticky_class; ?>">
						
						<div class="stickies flex-container">
				
							<?php
							foreach ( $thisarch_stickies as $ID ) : 

								$post_Obj 	= get_post( $ID );
								$title 		= get_the_title( $post_Obj->ID );

								if ( has_excerpt( $post_Obj->ID ) ) :

									if ( ctype_space( get_the_excerpt( $post_Obj->ID ) ) ) :
									
										$excerpt = $post_Obj->post_content;
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

										$excerpt = wpautop( $post_Obj->post_excerpt );
									
									endif;

								else :

									$excerpt = $post_Obj->post_content; 
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

								$permalink 	= get_the_permalink( $post_Obj->ID );

								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_Obj->ID ), 'cap-single-thumbnail' ); 

								$thumb = filter_var( $thumb[0], FILTER_VALIDATE_URL )
											? $thumb[0]
											: get_template_directory_uri() . '/img/default-thumb.jpg';
								?>

								<div class="sticky flex-item <?php echo 'sticky--' . count( $thisarch_stickies ); ?> <?php echo 'pt--' . get_post_type( $post_Obj->ID ); ?>">
								
									<div class="wrap--sticky__thumb">

										<div class="sticky__thumb">
										
											<a href="<?php echo $permalink; ?>" style="background: transparent url('<?php echo $thumb; ?>') center center/cover no-repeat;"></a>

										</div>

									</div>

									<h2 class="sticky__title"><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>

									<div class="sticky__excerpt"><?php echo $excerpt; ?></div>

								</div>
							
							<?php
							endforeach; 
							?>

						</div>

					</div>

				<?php
				endif;

				if ( !empty( $thisarch_stickies && ( $paged == 1 ) ) ) : 

					$btitle = get_field( 'cap_general_arch_cobustitle', 'option' );
					$btitle = ( $btitle && ( '' !== $btitle ) )
								? $btitle
								: false;

					cap_blog_inbetween_cobus( false, $btitle );

				endif;

				if ( have_posts() ) : ?>

					<div class="wrap--no-stickies <?php if ( empty( $thisarch_stickies ) ) echo ' beware-no-stickies'; ?>">
						
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

								<div class="no-sticky flex-container <?php echo 'no-sticky--' . $unst_i; ?>">
								
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

										<h2 class="no-sticky__title"><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>

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
								if ( empty( $thisarch_stickies ) && ( $unst_i == 4 ) && ( $paged == 1 ) ) : 

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
				endif;
				?>
			
			</div>

			<div class="cap-cpt-archive__sidebar flex-item">

				<?php get_sidebar('cpt-archive'); ?>

			</div>

		</div>

	</main><!-- #main -->

</div><!-- #primary -->

<?php
get_footer();
