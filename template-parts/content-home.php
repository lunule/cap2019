<?php
/**
 * Template part for displaying page content on the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */
?>

<div class="wrap--content-showcase">
	
	<div class="content-showcase container flex-container">
		
		<div class="wrap--home-newsfeed flex-item">
			
			<div class="home-newsfeed">
				
				<?php
				$title 			= get_field('home_news_title');
				$numposts 		= get_field('home_news_no');
				$more_label 	= get_field('home_news_more_label');						
				$more_url 		= get_field('home_news_more');				
				$numposts 		= (int)$numposts;	
				$stickies_Arr 	= get_option('sticky_posts');			

				// If there are sticky posts, the number of stickies should be 
				// subtracted from the $numberposts value BECAUSEeach sticky 
				// adds +1 to the number of displayed posts.
				// So for instance if $numposts is 10, and there are 3 stick 
				// posts, the final $numposts value should be 7 because we still
				// want to display max. 10 posts even as stickies are added to 
				// the listing.
				// And how do we get 7? We subtract the number of stickies from 
				// the $numposts value. 
				if ( !empty($stickies_Arr) )
					$numposts = (int)( $numposts - count( $stickies_Arr ) );

				if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'><a href='{$more_url}'>{$title}</a></h2></div>";

				$news_args = array(
					'post_type' 			=> array('post'),
					'posts_per_page' 		=> $numposts ? $numposts : 7,
					'post_status' 			=> array('publish'),
					'ignore_sticky_posts' 	=> 0,
				);

				$query_news = new WP_Query( $news_args );

				if ( $query_news->have_posts() ) :

					echo '<div class="home-news__blocks">';

					while ( $query_news->have_posts() ) : $query_news->the_post(); 
					
						$list_cats 		= get_the_category_list();
						$newstitle 		= get_the_title();
						$date_Str 		= cap_posted_on('return');
						
						if ( $newstitle && ( '' !== $newstitle ) ) :
						?>

							<div class="home-news__block">
								
								<div class="wrap--home-news__terms wrap--cap-termlists">

									<div class="home-news__terms cap-termlists">
										
										<div class="wrap--home-news__cats wrap--cap-catlist">
											<div class="home-news__cats cap-catlist">
												<?php echo $list_cats; ?>
											</div>
										</div>
										
										<?php
										/**
										 * Output post country flag list
										 * @var [type]
										 */
										$countries = get_the_terms( get_the_ID(), 'country' );
						
										if ( $countries && !is_wp_error( $countries ) ) : ?>

											<div class="wrap--home-news__countries wrap--cap-countrylist">
												
												<div class="home-news__countries cap-countrylist">

													<ul class="countries flex-container">

														<?php
														foreach ( $countries as $country ) :

															$term_name 	= $country->name; 
															$term_ID 	= $country->term_id;
															$term_arch 	= get_term_link( $country );
															$flag_Obj 	= get_field( 'tax_country_flag', 'country_' . $term_ID );

															$flag_size 	= 'thumbnail';
															$flag_thumb = $flag_Obj['sizes'][$flag_size];

															if ( !empty($flag_Obj) )
																echo "<li class='flex-item'><a href='{$term_arch}' title='{$term_name}'><img src='{$flag_thumb}' width='' height='' alt='' /></a></li>";

														endforeach;
														?>

													</ul>

												</div>

											</div>

										<?php
										endif; 						
										?>

									</div>
								
								</div>

								<div class="wrap--home-news__title"><h2 class="home-news__title"><a href="<?php the_permalink(); ?>"><?php echo $newstitle; ?></a></h2></div>

								<div class="wrap--home-news__postedon"><div class="home-news__postedon"><?php echo $date_Str; ?></div></div>								

							</div>

						<?php
						endif;

					endwhile;
					
					wp_reset_postdata();

					echo '</div>';
				
				endif;
				?>

				<div class="wrap--home-news__more wrap--home__more">
					
					<?php
					if ( filter_var( $more_url, FILTER_VALIDATE_URL) ) : ?>

						<a href="<?php echo $more_url; ?>" class="home-news__more home__more"><?php echo $more_label; ?></a>

					<?php 
					endif; 
					?>

				</div>

			</div>

		</div>

		<div class="wrap--home-analysis flex-item">
			
			<div class="home-analysis">
				
				<?php
				$title 		= get_field('home_analysis_title');
				$more_label = get_field('home_analysis_more_label');
				$more_url 	= get_field('home_analysis_more');

				if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'><a href='{$more_url}'>{$title}</a></h2></div>";

				$dsticky 	= get_field('home_analysis_dsticky');
				$sticky_ID 	= get_field('home_analysis_sticky');

				// Create array to store all displayed analysis BEFORE the You Might Like block
				$already_shown_ids 	= [];
				// Add the ID to the exclude-collection for the You Might Like block
				$already_shown_ids[] = $sticky_ID;
				
				if ( $dsticky && $sticky_ID ) :

					$sticky_Obj 	= get_post( $sticky_ID );

					$list_cats 		= get_the_category_list( '', '', $sticky_ID );
					$analysistitle 	= get_the_title( $sticky_ID );

					$sticky_class 	= has_post_thumbnail( $sticky_ID ) ? 'has-thumb' : 'no-has-thumb';
					?>

					<div class="wrap--home-analysis__sticky">

						<div class="home-analysis__sticky <?php echo $sticky_class; ?>">
							
							<div class="wrap--home-analysis__terms wrap--cap-termlists">

								<div class="home-analysis__terms cap-termlists">
									
									<div class="wrap--home-analysis__cats wrap--cap-catlist">
										<div class="home-analysis__cats cap-catlist">
											<?php echo $list_cats; ?>
										</div>
									</div>
									
									<?php
									/**
									 * Output post country flag list
									 * @var [type]
									 */
									$countries = get_the_terms( get_the_ID(), 'country' );
					
									if ( $countries && !is_wp_error( $countries ) ) : ?>

										<div class="wrap--home-analysis__countries wrap--cap-countrylist">
											
											<div class="home-analysis__countries cap-countrylist">

												<ul class="countries flex-container">

													<?php
													foreach ( $countries as $country ) :

														$term_name 	= $country->name; 
														$term_ID 	= $country->term_id;
														$term_arch 	= get_term_link( $country );
														$flag_Obj 	= get_field( 'tax_country_flag', 'country_' . $term_ID );

														$flag_size 	= 'thumbnail';
														$flag_thumb = $flag_Obj['sizes'][$flag_size];

														if ( !empty($flag_Obj) )
															echo "<li class='flex-item'><a href='{$term_arch}' title='{$term_name}'><img src='{$flag_thumb}' width='' height='' alt='' /></a></li>";

													endforeach;
													?>

												</ul>

											</div>

										</div>

									<?php
									endif; 						
									?>

								</div>
							
							</div>

							<div class="wrap--home-analysis__title">

								<h2 class="home-analysis__title"><a href="<?php the_permalink( $sticky_ID ); ?>"><?php echo $analysistitle; ?></a></h2>

							</div>
					
							<?php 
							if ( has_post_thumbnail( $sticky_ID ) ) : 
							
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $sticky_ID ), 'cap-home-analysis-sticky' ); 
								?>

								<div class="wrap--home-analysis__thumb">

									<div class="home-analysis__thumb">
									
										<img src="<?php echo $thumb[0]; ?>" width="" height="" alt="">

										<?php 
										$caption = get_the_post_thumbnail_caption( $sticky_ID );

										if ( $caption && ( '' !== $caption ) )
											echo "<div class='home-analysis__caption'>{$caption}</div>";
										?>

									</div>

								</div>

							<?php 
							endif; 
							?>		

							<div class="wrap--home-analysis__content">

								<div class="home-analysis__content flex-container">

									<div class="wrap--home-analysis__author flex-item">
										
										<div class="home-analysis__author">

											<?php
											$a_ID 		= $sticky_Obj->post_author;

											$avatar 	= get_avatar( $a_ID, 90 );
											
											$fname 		= get_the_author_meta( 
															'first_name', 
															$a_ID 
															);
											$lname 		= get_the_author_meta( 
															'last_name', 
															$a_ID 
															);
											$nick 		= get_the_author_meta( 
															'display_name', 
															$a_ID 
															);

											$a_name 	= ( $fname && $lname ) 
															? $fname . ' ' . $lname
															: $nick;

											$a_url 		= get_author_posts_url( $a_ID );
											?>

											<div class="avatar">
												
												<a href="<?php echo $a_url; ?>"><?php echo $avatar; ?></a>

											</div>

											<div class="author-name">
												<a href="<?php echo $a_url; ?>"><?php echo $a_name; ?></a>
											</div>
			
										</div>

									</div>

									<div class="wrap--home-analysis__excerpt flex-item">
										
										<div class="home-analysis__excerpt">
											
											<?php
											$content = apply_filters( 'the_content', $sticky_Obj->post_content );

											if ( has_excerpt( $sticky_ID ) ) :
											
												echo $sticky_Obj->post_excerpt;
											
											else :
												
												echo wp_strip_all_tags( 
														cap_custom_excerpt( 
															$content, 
															54, 
															get_permalink( $sticky_ID ), 
															'' 
														)
													);
											
											endif;
											?>

										</div><!-- EOF .home-analysis__excerpt -->

									</div><!-- EOF .wrap--home-analysis__excerpt -->

								</div><!-- EOF .home-analysis__content -->

							</div><!-- EOF .wrap--home-analysis__content -->

						</div><!-- EOF .home-analysis__sticky -->

					</div><!-- EOF .wrap--home-analysis__sticky -->

				<?php
				endif;
				
				$numposts 	= get_field('home_analysis_no');
				$numposts 	= (int)$numposts;

				$analysis_args = array(
					'post_type' 		=> array('analysis'),
					'posts_per_page' 	=> $numposts ? $numposts : 7,
					'post_status' 		=> array('publish'),
					'post__not_in' 		=> array( $sticky_ID ),
				);

				$query_analysis = new WP_Query( $analysis_args );

				if ( $query_analysis->have_posts() && ( 0 !== $numposts ) ) :

					echo '<div class="home-analysis__blocks">';

					while ( $query_analysis->have_posts() ) : $query_analysis->the_post(); 
					
						// Add the ID to the exclude-collection for the You Might Like block
						$already_shown_ids[] = get_the_ID();

						$list_cats 		= get_the_category_list();
						$analysistitle 	= get_the_title();
						$date_Str 		= cap_posted_on();
						
						if ( $analysistitle && ( '' !== $analysistitle ) ) :
						?>

							<div class="home-analysis__block">

								<div class="wrap--home-analysis__content">

									<div class="home-analysis__content flex-container">

										<div class="wrap--home-analysis__author flex-item">
											
											<div class="home-analysis__author">

												<?php
												$a_ID 		= $post->post_author;

												$avatar 	= get_avatar( $a_ID, 90 );
												
												$fname 		= get_the_author_meta( 
																'first_name', 
																$a_ID 
																);
												$lname 		= get_the_author_meta( 
																'last_name', 
																$a_ID 
																);
												$nick 		= get_the_author_meta( 
																'display_name', 
																$a_ID 
																);

												$a_name 	= ( $fname && $lname ) 
																? $fname . ' ' . $lname
																: $nick;

												$a_url 		= get_author_posts_url( $a_ID );
												?>

												<div class="avatar">
													
													<a href="<?php echo $a_url; ?>"><?php echo $avatar; ?></a>

												</div>

												<div class="author-name">
													<a href="<?php echo $a_url; ?>"><?php echo $a_name; ?></a>
												</div>
				
											</div>

										</div>

										<div class="wrap--home-analysis__excerpt flex-item">
											
											<div class="home-analysis__excerpt">

												<div class="wrap--home-analysis__terms wrap--cap-termlists">

													<div class="home-analysis__terms cap-termlists">
														
														<div class="wrap--home-analysis__cats wrap--cap-catlist">
															<div class="home-analysis__cats cap-catlist">
																<?php echo $list_cats; ?>
															</div>
														</div>
														
														<?php
														/**
														 * Output post country flag list
														 * @var [type]
														 */
														$countries = get_the_terms( get_the_ID(), 'country' );
										
														if ( $countries && !is_wp_error( $countries ) ) : ?>

															<div class="wrap--home-analysis__countries wrap--cap-countrylist">
																
																<div class="home-analysis__countries cap-countrylist">

																	<ul class="countries flex-container">

																		<?php
																		foreach ( $countries as $country ) :

																			$term_name 	= $country->name; 
																			$term_ID 	= $country->term_id;
																			$term_arch 	= get_term_link( $country );
																			$flag_Obj 	= get_field( 'tax_country_flag', 'country_' . $term_ID );

																			$flag_size 	= 'thumbnail';
																			$flag_thumb = $flag_Obj['sizes'][$flag_size];

																			if ( !empty($flag_Obj) )
																				echo "<li class='flex-item'><a href='{$term_arch}' title='{$term_name}'><img src='{$flag_thumb}' width='' height='' alt='' /></a></li>";

																		endforeach;
																		?>

																	</ul>

																</div>

															</div>

														<?php
														endif; 						
														?>

													</div>
												
												</div>

												<div class="wrap--home-analysis__title"><h2 class="home-analysis__title"><a href="<?php the_permalink(); ?>"><?php echo $analysistitle; ?></a></h2></div>
												
												<?php
												$content = apply_filters( 'the_content', $sticky_Obj->post_content );

												if ( has_excerpt( $sticky_ID ) ) :
												
													echo $sticky_Obj->post_excerpt;
												
												else :
													
													echo wp_strip_all_tags( 
															cap_custom_excerpt( 
																$content, 
																54, 
																get_permalink( $sticky_ID ), 
																'' 
															)
														);
												
												endif;
												?>

											</div><!-- EOF .home-analysis__excerpt -->

										</div><!-- EOF .wrap--home-analysis__excerpt -->

									</div><!-- EOF .home-analysis__content -->

								</div><!-- EOF .wrap--home-analysis__content -->
							
							</div>

						<?php
						endif;

					endwhile;
					
					wp_reset_postdata();

					echo '</div>';
				
				endif;
				?>

				<div class="wrap--home-analysis__more wrap--home__more">
					
					<?php
					if ( filter_var( $more_url, FILTER_VALIDATE_URL) ) : ?>

						<a href="<?php echo $more_url; ?>" class="home-analysis__more home__more"><?php echo $more_label; ?></a>

					<?php 
					endif; 
					?>

				</div>
				
			</div>

		</div>

		<div class="wrap--home-others flex-item">
			
			<div class="home-others">
				
				<?php
				$title 	= get_field('home_others_title');

				if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'>{$title}</h2></div>";

				$others_args = array(
					'post_type' 		=> array('post', 'analysis', 'studentpost', 'podcast'),
					'posts_per_page' 	=> 4,
					'post_status' 		=> array('publish'),
					'post__not_in' 		=> $already_shown_ids,
				    // query posts with post thumbnail
				    'meta_query' => array( 
				        array(
				            'key' => '_thumbnail_id'
				        ) 
				    )						
				);

				$query_others = new WP_Query( $others_args );

				if ( $query_others->have_posts() ) :

					echo '<div class="home-others__blocks flex-container">';

					while ( $query_others->have_posts() ) : $query_others->the_post(); 
					
						// Add the ID to the exclude-collection for the You Might Like block
						$already_shown_ids[] = get_the_ID();

						$otherstitle = get_the_title();
						
						if ( $otherstitle && ( '' !== $otherstitle ) ) :
						?>

							<div class="home-others__block flex-item">

								<?php 									
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'cap-home-analysis-others' ); 
								?>

								<div class="wrap--home-others__thumb">

									<div class="home-others__thumb">
									
										<a href="<?php the_permalink(); ?>"><img src="<?php echo $thumb[0]; ?>" width="" height="" alt=""></a>

									</div>

								</div>

								<div class="wrap--home-others__title"><h2 class="home-others__title"><a href="<?php the_permalink(); ?>"><?php echo $otherstitle; ?></a></h2></div>															
							</div>

						<?php
						endif;

					endwhile;
					
					wp_reset_postdata();

					echo '</div>';
				
				endif;
				?>

			</div>

		</div>

		<div class="wrap--home-perspective flex-item">
			
			<div class="home-perspective">
				
				<?php
				$title 	= get_field('home_perspective_title');

				if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'>{$title}</h2></div>";

				$quote 			= get_field('home_perspective_quote');
				$src 			= get_field('home_perspective_qsrc');

				$name 			= rtrim( $src['name'] );
				$name_Str 		= "<span class='qname'>{$name}</span>";
				
				$pos 			= rtrim( $src['position'] );
				$pos_Str 		= "<span class='qpos'>{$pos}</span>";

				$country 		= rtrim( $src['country'] );
				$country_Str 	= "<span class='qcountry'>{$country}</span>";

				$cite 			= [];

				if ( $name && ( '' !== $name ) ) 		array_push( $cite, $name_Str );
				if ( $pos && ( '' !== $pos ) ) 			array_push( $cite, $pos_Str );	
				if ( $country && ( '' !== $country ) ) 	array_push( $cite, $country_Str );

				if ( $quote && ( '' !== $quote ) ) :

					echo "<div class='wrap--home-perspective__quote'><blockquote class='home-perspective__quote'>{$quote}";

						if ( !empty($cite) )
							echo '<footer>' . implode( ', ', $cite ) . '</footer>';

					echo "</blockquote></div>";					

				endif;
				?>

			</div>

		</div>

		<div class="wrap--home-bigones flex-item">
			
			<div class="home-bigones">
				
				<?php
				$title 	= get_field('home_bigones_title');

				if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'>{$title}</h2></div>";

				/* ================================================================================
				↓↓↓ LOGIC COURSE 1 - DISPLAY SELECTED CATEGORIES THE FOLLOWING WAY:
					- IMAGE OF A POST IN THE DISPLAYED CATEGORY
					- CATEGORY NAME
					- BOTH OF THE ABOVE LINKED - WHERE THE LINK POINTS TO THE CATEGORY ARCHIVE 
				================================================================================ */

				$selected_cats_Arr = get_field('home_bigones_cats');

				// Get all categories
				$categories = get_categories( array(
				    'orderby' 		=> 'name',
				    'order'   		=> 'ASC',
				    'hide_empty' 	=> true,
				    'include' 	=> $selected_cats_Arr,
				) );

				// Create and fill multidimensional array - we need category name, 
				// archive url, and the post thumbnail of the first post having the 
				// specific/queried/displayed category
				$cats = array();
				$i = 0;
				foreach( $categories as $category ) :

					// Get all posts associated with the queried category
					$args = array(
						'post_type' 		=> array('post', 'analysis', 'studentpost', 'podcast'),
						'cat' 				=> $category->term_id,
						'post_status' 		=> array('publish'),
						'posts_per_page' 	=> 50,
					);

					$all_cat_posts = get_posts( $args ); 

				    if ( $all_cat_posts ) :

				    	// Create an array to hold all post ids where the category is the 
				    	// queried category and where a post thumbnail is specified/available
				    	$posts_w_img_Arr = array();

				        foreach ( $all_cat_posts as $post ) : setup_postdata( $post );

							/*if ( $i == 0 ) :
								var_dump( $post->post_title );
								var_dump( $category->name );
							endif;*/

				    		if ( has_post_thumbnail( $post->ID ) )
				    			$posts_w_img_Arr[] = $post->ID; 

				        endforeach;
				        wp_reset_postdata();

					endif;	

					// Setup the array items only if there's at least one item in 
					// the array created for the posts with category and post thumbnail
					if ( count( $posts_w_img_Arr ) >= 1 ) :

						// Create variable to hold the ID of the first 
						// post having both a featured image available, 
						// and the queried category associated/specified
						$firstpost_w_img = $posts_w_img_Arr[0];

						// Now we have all the items for the current item of 
						// the multidimensional/associative array
						$cats[$i]['name'] 	= $category->name;
						$cats[$i]['url'] 	= get_category_link( $category->term_id );
						$cats[$i]['img'] 	= wp_get_attachment_image_src( 
												get_post_thumbnail_id( $firstpost_w_img ), 
												'cap-home-analysis-others' 
											  ); 

					endif;

					$i++;

				endforeach; 

				// Randomize the multidimensional/associative array
				shuffle( $cats );
				// Keep only the first 3 items of the array
				$cats = array_slice( $cats, 0, 3);

				/* ================================================================================
				↑↑↑ EOF LOGIC COURSE --------------------------------------------------------------
				================================================================================ */

				if ( !empty( $cats ) ) :

					echo '<div class="home-bigones__blocks flex-container">';

					foreach ( $cats as $cat ) : 
					
						// Add the ID to the exclude-collection for the You Might Like block
						$already_shown_ids[] = get_the_ID();
						?>

						<div class="home-bigones__block flex-item">

							<div class="wrap--home-bigones__thumb">

								<div class="home-bigones__thumb">
								
									<a href="<?php echo $cat['url']; ?>"><img src="<?php echo $cat['img'][0]; ?>" width="" height="" alt=""></a>

								</div>

							</div>

							<div class="wrap--home-bigones__title"><h2 class="home-bigones__title"><a href="<?php echo $cat['url']; ?>"><?php echo $cat['name']; ?></a></h2></div>															

						</div>

					<?php
					endforeach;
					
					echo '</div>';
				
				endif;
				?>

			</div>

		</div>		

	</div>

</div>

<div class="wrap--adslot wrap--adslot-2">

	<aside class="adslot adslot-2 container">
		<?php dynamic_sidebar( 'adslot-2' ); ?>
	</aside>

</div>	

<div class="wrap--network-showcase">
	
	<div class="network-showcase container">

		<?php
		$title 		= get_field('home_network_title');
		$intro 		= get_field('home_network_intro');
		$btn_label 	= get_field('home_network_btn_label');
		$btn_url 	= get_field('home_network_btn_url');				

		if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'><a href='{$btn_url}'>{$title}</a></h2></div>"; ?>

		<div class="flex-container">

			<div class="network-showcase__left flex-item">

				<?php
				if ( $intro && ( '' !== $intro ) ) :

					echo "<div class='network-showcase__intro'>{$intro}</div>";

				endif;

				if ( 
					filter_var( $btn_url, FILTER_VALIDATE_URL) 	&& 
					( $btn_label && ( '' !== $btn_label ) ) 
				   ) :

					echo "<div class='wrap--network-showcase__btn'><a href='{$btn_url}' class='network-showcase__btn'>{$btn_label}</a></div>";					

				endif;
				?>

			</div>

			<div class="network-showcase__right flex-item">		

				<div class="wrap--network-showcase__memslider">
					
					<div class="network-showcase__memslider">
						
						<?php				
						$numposts = get_field('home_network_memno', 'option');

						$mem_args = array(
							'post_type' 		=> array('nwmember'),
							'posts_per_page' 	=> $numposts ? $numposts : -1,
							'post_status' 		=> array('publish'),
						);

						$query_mems = new WP_Query( $mem_args );

						if ( $query_mems->have_posts() ) :

							echo '<div class="memslides">';

							while ( $query_mems->have_posts() ) : $query_mems->the_post(); 

								$title 		= get_the_title();
								$pos 		= get_field('net_position');
								$inst 		= get_field('net_institution');
								$profile 	= get_permalink();

								echo "<a href='{$profile}' class='link--wrap'><div class='memslide'>";

									// Get thumbnail
									if ( has_post_thumbnail( get_the_ID() ) ) : 

										$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' ); 
										?>
											
										<div class="wrap--member-img" style="background: transparent url(<?php echo $thumb[0]; ?>) center center/cover no-repeat;"></div>

									<?php
									endif;

									if ( $title && ( '' !== $title ) ) 	echo "<h4 class='member-name'>{$title}</h4>";
									if ( $pos 	&& ( '' !== $pos ) ) 	echo "<h5 class='member-pos'>{$pos}</h5>";
									if ( $inst 	&& ( '' !== $inst ) ) 	echo "<h6 class='member-inst'>{$inst}</h6>";

								echo '</div></a>';

							endwhile;

							wp_reset_postdata();

							echo '</div>';

						endif;
						?>

					</div>

				</div>

			</div>

		</div>			

	</div>

</div>

<div class="wrap--podcast-showcase">
	
	<div class="podcast-showcase container">

		<?php
		$title 			= get_field('home_podcast_title');
		$posts_per_page = 4;
		$more_label 	= get_field('home_podcast_more_label');						
		$more_url 		= get_field('home_podcast_more');

		if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'><a href='{$more_url}'>{$title}</a></h2></div>"; ?>

		<div class="flex-container">

			<div class="podcast-showcase__left flex-item">

				<?php				
				$pod_args = array(
					'post_type' 		=> array('podcast'),
					'posts_per_page' 	=> $posts_per_page,
					'post_status' 		=> array('publish'),
				);

				$query_pods = new WP_Query( $pod_args );

				if ( $query_pods->have_posts() ) :

					echo '<div class="podposts flex-container">';

					while ( $query_pods->have_posts() ) : $query_pods->the_post(); 

						echo '<div class="podpost flex-item"><div class="flex-container">';

							$title 			= get_the_title();
							$permalink 		= get_permalink();
							$author_ID 		= get_the_author_meta('ID');
							$defthumb 		= get_template_directory_uri() . '/img/cap-defaults-guestimg.jpg';

							$pos 			= get_field(
												'user_position', 
												'user_' . $author_ID 
											  );
							$inst 			= get_field(
												'user_institution', 
												'user_' . $author_ID 
											  );

							$avatar 		= get_avatar( $author_ID, 150 );

							$fname 			= get_the_author_meta( 
												'first_name', 
												$author_ID 
											  );
							$lname 			= get_the_author_meta( 
												'last_name', 
												$author_ID 
											  );
							$nick 			= get_the_author_meta( 
												'display_name', 
												$author_ID 
											  );

							$author_name 	= ( $fname && $lname ) 
												? $fname . ' ' . $lname
												: $nick;

							$author_url 	= get_author_posts_url( $author_ID );

							$guest 			= get_field('cap_pod_single_guest');

							$guest_img_Obj 	= get_field('cap_pod_single_guestimg');
							$guest_thumb 	= $guest_img_Obj['sizes']['thumbnail'];

							$guest_pos 		= get_field('cap_pod_single_guestpos');
							$guest_inst 	= get_field('cap_pod_single_guestinst');

							$has_guest 		= false;

							if ( 
								$guest 												&& 
								( '' !== $guest ) 									&&
								filter_var( $guest_thumb, FILTER_VALIDATE_URL )
								) :

								$has_guest = true;

							endif;

							if ( $has_guest ) :

								$d_name 		= $guest;
								$d_avatar 		= "<img src={$guest_thumb} width='' height='' alt='' />";	

								$d_pos 			= ( $guest_pos && ( '' !== $guest_pos ) )
													? $guest_pos
													: false;
								
								$d_inst 		= ( $guest_inst && ( '' !== $guest_inst ) )
													? $guest_inst
													: false;

								$d_authorlink 	= $permalink;

							else:

								$d_name 		= $author_name;

								$d_avatar 		= ( false !== $avatar )
													? $avatar
													: "<img src={$defthumb} width='' height='' alt='' />";	

								$d_pos 			= ( $pos && ( '' !== $pos ) )
													? $pos
													: false;
								
								$d_inst 		= ( $inst && ( '' !== $inst ) )
													? $inst
													: false;

								$d_authorlink 	= $author_url;
							
							endif;
							?>

							<div class="author__avatar flex-item">
								
								<a href="<?php echo $d_authorlink; ?>"><?php echo $d_avatar; ?></a>

							</div>

							<div class="author__meta flex-item">

								<div class="author__name">
									<h4><a href="<?php echo $d_authorlink; ?>"><?php echo $d_name; ?></a></h4>
								</div>

								<?php 
								if ( $d_pos && ( '' !== $d_pos ) ) 
									echo "<div class='author__pos'><h5>{$d_pos}</h5></div>";

								if ( $d_inst && ( '' !== $d_inst ) ) 
									echo "<div class='author__inst'><h6>{$d_inst}</h6></div>";

								?>

							</div>
							
							<?php
							if ( $title && ( '' !== $title ) ) 	echo "<div class='podcast__title flex-item'><a href='{$permalink}'><h3>{$title}</h3></a></div>";

						echo '</div></div>';

					endwhile;

					wp_reset_postdata();

					echo '</div>'; // EOF .podposts.flex-container

				endif;
				?>

				<div class="wrap--home-podcast__more wrap--home__more">
					
					<?php
					if ( filter_var( $more_url, FILTER_VALIDATE_URL) ) : ?>

						<a href="<?php echo $more_url; ?>" class="home-podcast__more home__more"><?php echo $more_label; ?></a>

					<?php 
					endif; 
					?>

				</div>				


			</div>

			<div class="podcast-showcase__right flex-item">		

				<div class="wrap--adslot wrap--adslot-3">

					<aside class="adslot adslot-3 container">
						<?php dynamic_sidebar( 'adslot-3' ); ?>
					</aside>

				</div>	

			</div>

		</div><!-- EOF .flex-container -->	

	</div>

</div>

<div class="wrap--adslot wrap--adslot-4">

	<aside class="adslot adslot-4 container">
		<?php dynamic_sidebar( 'adslot-4' ); ?>
	</aside>

</div>	

<div class="wrap--studentpost-showcase">
	
	<div class="studentpost-showcase container">

		<?php
		$title 		= str_replace(
			'XCHANGE', 
			'xCHANGE', 
			strtoupper( get_field('home_student_title') ) 
		);
		$intro 		= get_field('home_student_intro');
		$numposts 	= get_field('home_student_no');
		$more_label = get_field('home_student_more_label');						
		$more_url 	= get_field('home_student_more');
		
		if ( $title && ( '' !== $title ) ) echo "<div class='wrap--home-block-title'><h2 class='home-block-title'><a href='{$more_url}'>{$title}</a></h2></div>";

		if ( $intro && ( '' !== $intro ) ) echo "<div class='home-block__intro'>{$intro}</div>"; ?>

		<?php				
		$sposts_args = array(
			'post_type' 		=> array('studentpost'),
			'posts_per_page' 	=> $numposts ? $numposts : 6,
			'post_status' 		=> array('publish'),
		);

		$query_sposts = new WP_Query( $sposts_args );

		if ( $query_sposts->have_posts() ) :

			echo '<div class="studentposts flex-container">';

			while ( $query_sposts->have_posts() ) : $query_sposts->the_post(); 

				echo '<div class="studentpost flex-item"><div class="flex-container">';

					$title 			= get_the_title();
					$permalink 		= get_permalink();
					$student_ID 	= get_the_author_meta('ID');
					$student_url 	= get_author_posts_url( $student_ID );

					$avatar 		= get_avatar( $student_ID, 100 );
					
					$fname 			= get_the_author_meta( 
										'first_name', 
										$student_ID 
									  );
					$lname 			= get_the_author_meta( 
										'last_name', 
										$student_ID 
									  );
					$nick 			= get_the_author_meta( 
										'display_name', 
										$student_ID 
									  );

					$name 			= ( $fname && $lname ) 
										? $fname . ' ' . $lname
										: $nick;

					$name_Str 		= ( $name && ( '' !== $name ) )
										? "<a href='{$student_url}'>{$name}</a>"
										: '';

					$age 			= get_field(
										'student_age', 
										'user_' . $student_ID 
									  );
					$inst 			= get_field(
										'student_inst', 
										'user_' . $student_ID 
									  );
					$country 		= get_field(
										'student_country', 
										'user_' . $student_ID 
									  );					
					?>

					<div class="studentpost__avatar flex-item">
						
						<a href="<?php echo $student_url; ?>"><?php echo $avatar; ?></a>

					</div>

					<div class="studentpost__hentry flex-item">

						<?php 
						$studentmeta = [];

						if ( $name && ( '' !== $name ) ) 		array_push( $studentmeta, $name_Str );
						if ( $age && ( '' !== $age ) ) 			array_push( $studentmeta, $age );
						if ( $inst && ( '' !== $inst ) ) 		array_push( $studentmeta, $inst );
						if ( $country && ( '' !== $country ) ) 	array_push( $studentmeta, $country );

						if ( !empty($studentmeta) )
							echo '<div class="studentpost__meta"><h4>' . implode( ', ', $studentmeta ) . '</h4></div>';
					
						if ( $title && ( '' !== $title ) ) 	echo "<div class='studentpost__title'><a href='{$permalink}'><h3>{$title}</h3></a></div>";					
						?>

					</div>

				<?php					
				echo '</div></div>';

			endwhile;

			wp_reset_postdata();

			echo '</div>'; // EOF .studentposts.flex-container

		endif;
		?>

		<div class="wrap--home-studentpost__more wrap--home__more">
			
			<?php
			if ( filter_var( $more_url, FILTER_VALIDATE_URL) ) : ?>

				<a href="<?php echo $more_url; ?>" class="home-studentpost__more home__more"><?php echo $more_label; ?></a>

			<?php 
			endif; 
			?>

		</div>				

	</div>

</div>