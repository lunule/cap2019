<?php
/*
YARPP Template: CAP
Description: Requires a theme which supports post thumbnails
Author: lunule
*/ 

if ( have_posts() ) : ?>

	<div class="wrap--related-posts">

		<?php
		$blocktitle 			= get_field('cap_general_related_title', 'option');
		$blocktitle_nwmember 	= get_field('cap_general_related_title_nwmember', 'option'); 

		$blocktitle 			= ( is_singular('nwmember') ) 
									? $blocktitle_nwmember 
									: $blocktitle;

		if ( $blocktitle && ( '' !== $blocktitle ) ) echo "<h3>{$blocktitle}</h3>";
		?>

		<div class="related-posts flex-container">
			
			<?php while (have_posts()) : the_post(); ?>

				<div class="related-post flex-item">

					<div class="entry-meta flex-container">
		
						<?php 
						$countries 		= get_the_terms( get_the_ID(), 'country' );
						$has_countries 	= ( $countries && !is_wp_error( $countries ) );

						$list_cats_Arr 	= wp_get_post_categories( 
							get_the_ID(),
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

						if ( $has_cats ) : ?>

							<div class="entry-meta__post-cats flex-item flex-container">
								<?php cap_post_cats( 2, false ); ?>
							</div>

						<?php 
						endif; 

						if ( $has_countries ) : ?>

							<div class="entry-meta__post-countries flex-item flex-container">
								<?php cap_post_countries(); ?>
							</div>

						<?php 
						endif;
						?>
					
					</div>						

					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					
					<?php if (has_post_thumbnail()) : ?>
	
						<div class="related-post__thumbnail">

							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail('yarpp-thumbnail'); ?>
							</a>

						</div>
					
					<?php endif; ?>

					<?php
					$content = apply_filters( 'the_content', $post->post_content );
					$content = has_excerpt() ? $post->post_excerpt : $content;
					$content = wp_strip_all_tags( 
									cap_custom_excerpt( 
										$content, 
										35, 
										get_permalink(), 
										'' 
									)
								);
					
					echo "<div class='related-post__content'>{$content}</div>";					
					?>


				</div>
			
			<?php endwhile; ?>
			
		</div>

	</div>

<?php endif; ?>
