<?php
/**
 * Template part for displaying singular post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

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

$exp_class = ( $has_countries && $has_cats ) ? 'explode-it' : 'no-explode-it'; 
?>

<div class="cap-single__main flex-container <?php echo $exp_class; ?>">

	<div class="cap-single__hentry flex-item">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<header class="entry-header">

				<h1 class="entry-title"><?php echo get_the_title(); ?></h1>

				<div class="entry-meta flex-container">
	
					<?php 
					if ( $has_countries ) : ?>

						<div class="entry-meta__post-countries flex-item flex-container">
							<?php cap_post_countries(); ?>
						</div>

					<?php 
					endif;

					if ( $has_cats ) : ?>

						<div class="entry-meta__post-cats flex-item flex-container">
							<?php cap_post_cats(); ?>
						</div>

					<?php 
					endif; ?>
					
					<div class="entry-meta__posted-on flex-item flex-container">
						<?php cap_posted_on(); ?>
					</div>
					
					<div class="entry-meta__posted-by flex-item flex-container">
						<?php cap_posted_by(); ?>
					</div>

					<div class="entry-meta__post-share flex-item flex-container">
						<?php cap_post_share(); ?>
					</div>					

				</div><!-- .entry-meta -->
			
			</header><!-- .entry-header -->

			<?php cap_post_thumbnail(); ?>

			<div class="entry-content">
				<?php
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cap' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );
				?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->
			
		<?php
		/* Related Posts
		======================================================================================== */

		yarpp_related(
			array(
			    'post_type' => array( get_post_type() ),
			    'show_pass_post' => false, // show password-protected posts
			    'past_only' => false, // show only posts which were published before the reference post
			    'exclude' => array(), // a list of term_taxonomy_ids. entities with any of these terms will be excluded from consideration.
			    'recent' => false, // to limit to entries published recently, set to something like '15 day', '20 week', or '12 month'.
			    // Relatedness options: these determine how "relatedness" is computed
			    // Weights are used to construct the "match score" between candidates and the reference post
			    'weight' => array(
			        'body' => 0,
			        'title' => 0, // larger weights mean this criteria will be weighted more heavily
			        'tax' => array(
			            'category' => 10,
			            'post_tag' => 0,
			            'country'  => 7,
			        )
			    ),
			    // Specify taxonomies and a number here to require that a certain number be shared:
			    'require_tax' => array(
			        'post_tag' => 0, // for example, this requires all results to have at least one 'post_tag' in common.
			        'category' => 1,
			        'country' => 0,
			    ),
			    // The threshold which must be met by the "match score"
			    'threshold' => 5,

			    // Display options:
			    'template' => 'yarpp-template-cap.php', // either the name of a file in your active theme or the boolean false to use the builtin template
			    'limit' => 3, // maximum number of results
			    'order' => 'score DESC'
			),
			get_the_ID(), // second argument: (optional) the post ID. If not included, it will use the current post.
			true
		); // third argument: (optional) true to echo the HTML block; false to return it
		
		/* The Post Navigation
		======================================================================================== */

		$prev_post = get_previous_post_link(
			$format 				= '<span>' . __( 'Previous Story: ' ) . '</span>' . '%link', 
			$link 					= '%title' 
		);

		$next_post = get_next_post_link( 
			$format 				= '<span>' . __( 'Next Story: ' ) . '</span>' . '%link', 
			$link 					= '%title' 
		);		
		?>

		<div class="cap-singular-navigation flex-container">
			
			<div class="prev-post flex-item"><?php echo $prev_post; ?></div>
			<div class="next-post flex-item"><?php echo $next_post; ?></div>

		</div>
		
		<?php
		/* Infobox
		======================================================================================== */

		$curr_user_has_paid_mship 	= current_user_can( 'mepr-active', 'rule: 7000' );

		// Display banner if
		// 1. 	banner ad slot is active --- AND
		// 2A. 	user is not logged in OR is logged in but doesn't have the 
		// 		paid membership --- OR
		// 2B. 	MEMBERPRESS_TESTING constant is set to true
		if ( is_active_sidebar( 'adslot-3' ) &&
			(
		 		( !is_user_logged_in() || !$curr_user_has_paid_mship ) ||
		 		( true === MEMBERPRESS_TESTING )
		 	)
		) : 

			$ib_title 		= get_field('cap_general_single_infobox_title', 'option');
			$ib_btnlabel 	= get_field('cap_general_single_infobox_btnlabel', 'option');
			$ib_btnurl 		= get_field('cap_general_single_infobox_btnurl', 'option');

			if ( have_rows('cap_general_single_infobox_blocks', 'option') ) : ?>

				<div class="wrap--infobox">

					<div class="infobox">

						<?php if ( $ib_title && ( '' !== $ib_title ) ) echo "<h3>{$ib_title}</h3>"; ?>

						<div class="infobox__blocks flex-container">

							<?php
							while ( have_rows('cap_general_single_infobox_blocks', 'option') ) : the_row();
							
							$btitle 	= get_sub_field('title');
							$bcontent 	= get_sub_field('content');
							?>

								<div class="infobox__block flex-item">
									
									<?php
									if ( $btitle && ( '' !== $btitle ) ) echo "<h4 class='btitle'>{$btitle}</h4>";

									if ( $bcontent && ( '' !== $bcontent ) ) echo "<div class='bcontent'>{$bcontent}</div>";
									?>

								</div>

							<?php
							endwhile; ?>

						</div>
					
					</div>

					<?php 
					if ( 
						( $ib_btnlabel && ( '' !== $ib_btnlabel ) ) &&
						( $ib_btnurl && ( filter_var( $ib_btnurl, FILTER_VALIDATE_URL ) ) )
					   )
						echo "<div class='infobox__btn'><a href='{$ib_btnurl}' class='cap-btn cap-btn--default'>{$ib_btnlabel}</a></div>";
					?>

				</div>

			<?php 
			endif; 

		endif; ?>

	</div>

	<div class="cap-single__sidebar flex-item">
		
		<?php get_sidebar('single'); ?>

	</div>

</div>