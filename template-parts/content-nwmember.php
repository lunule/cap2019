<?php
/**
 * Template part for displaying singular post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */
?>

<div class="cap-single__main flex-container">

	<div class="cap-single__hentry flex-item">

		<header class="page-header">

			<?php
			$heading 	= get_field('cap_general_member_heading', 'option');
			$desc 		= get_field('cap_general_member_desc', 'option');

			echo "<h1 class='page-title'>{$heading}</h1>";
			echo "<div class='page-description'>{$desc}</div>";
			?>

		</header><!-- .page-header -->

		<article id="post-<?php the_ID(); ?>" <?php post_class('flex-container'); ?>>

			<div class="member-profile__left flex-item">
				
				<?php 
				the_post_thumbnail('member-thumbnail'); ?>

				<div class="entry-meta--side">
					
					<?php
					$email 			= get_field( 'net_email' );
					$has_email 		= ( $email && ( '' !== $email ) );

					$skype 			= get_field( 'net_skype' );
					$has_skype 		= ( $skype && ( '' !== $skype ) );

					$wechat 		= get_field( 'net_wechat' );
					$has_wechat 	= ( $wechat && ( '' !== $wechat ) );

					$whatsapp 		= get_field( 'net_whatsapp' );
					$has_whatsapp 	= ( $whatsapp && ( '' !== $whatsapp ) );

					$linkedin 		= get_field( 'net_linkedin' );
					$has_linkedin 	= ( $linkedin && filter_var( $linkedin, FILTER_VALIDATE_URL ) );

					$twitter 		= get_field( 'net_twitter' );
					$has_twitter 	= ( $twitter && filter_var( $twitter, FILTER_VALIDATE_URL ) );

					$facebook 		= get_field( 'net_facebook' );
					$has_facebook 	= ( $facebook && filter_var( $facebook, FILTER_VALIDATE_URL ) );

					$youtube 		= get_field( 'net_youtube' );
					$has_youtube 	= ( $youtube && filter_var( $youtube, FILTER_VALIDATE_URL ) );

					$weibo 			= get_field( 'net_weibo' );
					$has_weibo 		= ( $weibo && filter_var( $weibo, FILTER_VALIDATE_URL ) );

					$has_social 	= ( $has_linkedin 	||
										$has_twitter 	||
										$has_facebook 	||
										$has_youtube 	||
										$has_weibo );

					if ( $has_email ) : ?>

						<div class="entry-meta__email">

							<h3><i class="fas fa-envelope"></i><?php _e( 'Email', 'cap' ) ; ?></h3>
							<h4><a href="<?php echo $email; ?>"><?php echo $email; ?></a></h4>
						
						</div>

					<?php 
					endif;		

					if ( $has_social ) : ?>

						<div class="entry-meta__social flex-container">
							
							<?php
							if ( $has_facebook ) :

								echo "<a class='flex-item nw-social__facebook' href='{$facebook}'><i class='fab fa-facebook-f'></i></a>";

							endif;

							if ( $has_twitter ) :

								echo "<a class='flex-item nw-social__twitter' href='{$twitter}'><i class='fab fa-twitter'></i></a>";
								
							endif;

							if ( $has_linkedin ) :

								echo "<a class='flex-item nw-social__linkedin' href='{$linkedin}'><i class='fab fa-linkedin-in'></i></a>";
								
							endif;

							if ( $has_youtube ) :

								echo "<a class='flex-item nw-social__youtube' href='{$youtube}'><i class='fab fa-youtube'></i></a>";
								
							endif;

							if ( $has_weibo ) :

								echo "<a class='flex-item nw-social__weibo' href='{$weibo}'><i class='fab fa-weibo'></i></a>";
								
							endif;
							?>

						</div>
					
					<?php
					endif;

					if ( $has_skype ) : ?>

						<div class="entry-meta__skype">

							<h3><i class="fab fa-skype"></i><?php _e( 'Skype', 'cap' ) ; ?></h3>
							<h4><a href="skype:<?php echo $skype; ?>?chat"><?php echo $skype; ?></a></h4>
						
						</div>

					<?php 
					endif;

					if ( $has_wechat ) : ?>

						<div class="entry-meta__wechat">

							<h3><i class="fab fa-weixin"></i><?php _e( 'WeChat', 'cap' ) ; ?></h3>
							<h4><a href="weixin://contacts/profile/<?php echo $wechat; ?>"><?php echo $wechat; ?></a></h4>
						
						</div>

					<?php 
					endif;

					if ( $has_whatsapp ) : ?>

						<div class="entry-meta__whatsapp">

							<h3><i class="fab fa-whatsapp"></i><?php _e( 'WhatsApp', 'cap' ) ; ?></h3>
							<h4><a href='https://web.whatsapp.com/send?phone=<?php echo $whatsapp; ?>'><?php echo $whatsapp; ?></a></h4>
						
						</div>

					<?php 
					endif; ?>

				</div>

			</div>

			<div class="member-profile__right flex-item">
				
				<header class="entry-header">

					<div class="wrap--entry-title">

						<h1 class="entry-title"><?php echo get_the_title(); ?></h1>

						<div class="entry-meta__post-share">
							<?php cap_post_share(); ?>
						</div>					
	
					</div>

					<?php
					$pos 			= get_field( 'net_position' );
					$has_pos 		= ( $pos && ( '' !== $pos ) );

					$inst 			= get_field( 'net_institution' );
					$has_inst 		= ( $inst && ( '' !== $inst ) );					

					$glue 			= __( 'at', 'cap' );

					$has_subtitle 	= ( $has_pos || $has_inst );
					$needs_glue 	= ( $has_pos && $has_inst );

					if ( $has_subtitle ) : ?>

						<div class="wrap--entry-subtitle">
	
							<h2 class="entry-subtitle">

								<?php
								if ( $has_pos ) 	
									echo "<span='entry-subtitle__pos'>{$pos}</span>";
								
								if ( $needs_glue ) 	
									echo "<span='entry-subtitle__glue'> {$glue} </span>";
								
								if ( $has_inst ) 
									echo "<span='entry-subtitle__inst'>{$inst}</span>";
								?>

							</h2>

						</div>

					<?php 
					endif; ?>

				</header><!-- .entry-header -->

				<div class="wrap--entry-meta">

					<?php 				
					$profcats_Arr 	= get_the_terms( get_the_ID(), 'nw-profession-category' );
					$has_profcat 	= ( $profcats_Arr && !is_wp_error( $profcats_Arr ) );

					$countries_Arr 	= get_the_terms( get_the_ID(), 'nw-country' );
					$has_country 	= ( $countries_Arr && !is_wp_error( $countries_Arr ) );	

					$city 			= get_field( 'net_city' );
					$has_city 		= ( $city && ( '' !== $city ) );

					$press 			= get_field( 'net_press' );
					$has_press 		= ( $press && ( '' !== $press ) );

					$langs_Arr 		= get_the_terms( get_the_ID(), 'nw-lang' );
					$has_lang 		= ( $langs_Arr && !is_wp_error( $langs_Arr ) );

					$has_meta 		= ( $has_profcat 	|| 
										$has_country 	|| 
										$has_lang 		|| 
										$has_city 		|| 
										$has_press );

					if ( $has_meta ) :

						echo '<div class="entry-meta flex-container">';

						if ( $has_profcat ) : 

							$profcat_name = $profcats_Arr[0]->name;
							$profcat_slug = $profcats_Arr[0]->slug;

							$profcat_url  = get_site_url() . '/network-members/?_sft_nw-profession-category=' . $profcat_slug;
							?>

							<div class="entry-meta__profcat flex-item">

								<h3><?php _e( 'Profession', 'cap' ) ; ?></h3>
								<h4><a href="<?php echo $profcat_url; ?>"><?php echo $profcat_name; ?></a></h4>
							
							</div>

						<?php 
						endif;

						if ( $has_country ) : 

							$country_name = $countries_Arr[0]->name;
							$country_slug = $countries_Arr[0]->slug;

							$country_url  = get_site_url() . '/network-members/?_sft_nw-country=' . $country_slug;
							?>

							<div class="entry-meta__country flex-item">

								<h3><?php _e( 'Country', 'cap' ) ; ?></h3>
								<h4><a href="<?php echo $country_url; ?>"><?php echo $country_name; ?></a></h4>
							
							</div>

						<?php 
						endif;

						if ( $has_city ) : ?>

							<div class="entry-meta__city flex-item">

								<h3><?php _e( 'City', 'cap' ) ; ?></h3>
								<h4><?php echo $city; ?></h4>
							
							</div>

						<?php 
						endif;		

						if ( $has_press ) : ?>

							<div class="entry-meta__press flex-item">

								<h3><?php _e( 'Press Accessible', 'cap' ) ; ?></h3>
								<h4><?php echo ucfirst($press); ?></h4>
							
							</div>

						<?php 
						endif;										

						if ( $has_lang ) : ?>

							<div class="entry-meta__lang flex-item">

								<h3><?php _e( 'Language(s) Spoken', 'cap' ) ; ?></h3>
								<h4>

									<?php
									$new_langs_Arr = [];

									foreach ( $langs_Arr as $lang ) :

										$lang_name = $lang->name;
										$lang_slug = $lang->slug;
										$lang_url  = get_site_url() . '/network-members/?_sft_nw-lang=' . $lang_slug;

										$new_langs_Arr[] = "<a href={$lang_url}>{$lang_name}</a>";

									endforeach; 

									echo implode( ', ', $new_langs_Arr );
									?>

								</h4>
							
							</div>

						<?php 
						endif;												 

						echo '</div>';						

					endif; ?>					
	
				</div>
				
				<?php
				$pubtitle1 		= get_field('net_pub1');
				$puburl1 		= get_field('net_pub1_url');
				$has_pub1 		= (
									( $pubtitle1 && ( '' !== $pubtitle1 ) ) &&
									( $puburl1 && filter_var( $puburl1, FILTER_VALIDATE_URL ) )
								  );

				$pubtitle2 		= get_field('net_pub2');
				$puburl2 		= get_field('net_pub2_url');
				$has_pub2 		= (
									( $pubtitle2 && ( '' !== $pubtitle2 ) ) &&
									( $puburl2 && filter_var( $puburl2, FILTER_VALIDATE_URL ) )
								  );

				$pubtitle3 		= get_field('net_pub3');
				$puburl3 		= get_field('net_pub3_url');
				$has_pub3 		= (
									( $pubtitle3 && ( '' !== $pubtitle3 ) ) &&
									( $puburl3 && filter_var( $puburl3, FILTER_VALIDATE_URL ) )
								  );

				$has_pub 		= ( $has_pub1 || $has_pub2 || $has_pub3 );
				?>

				<div class="entry-content <?php echo ( $has_pub ) ? 'has-meta-bottom' : 'no-has-meta-bottom'; ?>">
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

				<?php 
				if ( $has_pub ) : ?>

					<div class="entry-meta--bottom">

						<h3><?php _e( 'Publications', 'cap' ) ?></h3>
						
						<div class="wrap--publications">
							
							<h4>

								<?php
								$pubs_Arr = [];

								for ( $i=1; $i < 4; $i++) :
								
									if ( ${"has_pub" . $i} ) :

										$pubtitle 	= ${"pubtitle" . $i};
										$puburl 	= ${"puburl" . $i};

										$pubs_Arr[] = "<a href='{$puburl}'>{$pubtitle}</a>";

									endif;

								endfor;

								echo implode( ', ', $pubs_Arr );
								?>

							</h4>

						</div>

					</div>

				<?php 
				endif; ?>

			</div>

		</article><!-- #post-<?php the_ID(); ?> -->
			
		<?php
		/* Related Posts
		======================================================================================== */

		yarpp_related(
			array(
			    'post_type' => array( 'nwmember' ),
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
			            'nw-profession-category' 	=> 5,
			            'nw-country' 				=> 5,
			            'nw-lang'  					=> 5,
			        )
			    ),
			    // Specify taxonomies and a number here to require that a certain number be shared:
			    'require_tax' => array(
		            'nw-profession-category' 	=> 0, // for example, this requires all results to have at least one 'nw-profession-category' in common.
		            'nw-country' 				=> 0,
		            'nw-lang'  					=> 0,
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
			$format 				= '<span>' . __( 'Previous Member: ' ) . '</span>' . '%link', 
			$link 					= '%title' 
		);

		$next_post = get_next_post_link( 
			$format 				= '<span>' . __( 'Next Member: ' ) . '</span>' . '%link', 
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
						echo "<div class='infobox__btn'><a href='{ib_btnurl}' class='cap-btn cap-btn--default'>{$ib_btnlabel}</a></div>";
					?>

				</div>

			<?php 
			endif; 

		endif; ?>

	</div>

	<div class="cap-single__sidebar flex-item">
		
		<aside id="secondary" class="widget-area">
			<?php dynamic_sidebar( 'sidebar-12' ); ?>
		</aside><!-- #secondary -->

	</div>

</div>