<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cap2019
 */

/* if ( ! function_exists( 'cap_posted_on' ) ) :

	// Prints HTML with meta information for the current post-date/time.
	function cap_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			// translators: %s: post date.
			esc_html_x( 'Posted on %s', 'post date', 'cap' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif; */

if ( ! function_exists( 'cap_posted_on' ) ) :

	function cap_posted_on( $echo_or_not = 'echo' ) {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		$posted_on = '<span class="posted-on"><a href="' . esc_url( get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')) ) . '" rel="bookmark">' . $time_string . '</a></span>';

		if ( $echo_or_not == 'return' ) : 
			return $posted_on;
		else:
			echo $posted_on;
		endif;

	}

endif;

if ( ! function_exists( 'cap_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function cap_posted_by() {

		$fname 		= ( get_the_author_meta( 'first_name' ) );
		$has_fname 	= ( $fname && ( '' !== $fname ) );

		$lname 		= ( get_the_author_meta( 'last_name' ) );
		$has_lname 	= ( $lname && ( '' !== $lname ) );		

		$author_name = ( $has_fname && $has_lname )
							? $fname . ' ' . $lname
							: get_the_author();

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'cap' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( $author_name ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'cap_post_countries' ) ) :

	function cap_post_countries( $id = false ) {

		/**
		 * Output post country flag list
		 * @var [type]
		 */
		$id = ( false == $id ) ? get_the_ID() : $id;

		$countries = get_the_terms( $id, 'country' );

		if ( $countries && !is_wp_error( $countries ) ) : ?>

			<div class="wrap--meta__countries wrap--cap-countrylist">
				
				<div class="meta__countries cap-countrylist">

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

	}

endif;

if ( ! function_exists( 'cap_post_cats' ) ) :

	function cap_post_cats( $catnumber = 3, $show_ellipsis = true ) {

		$list_cats_Arr = wp_get_post_categories( 
			get_the_ID(),
			array(
				'fields' => 'names',
			) 
		);

		// Remove 'Uncategorized'
		if ( ( $key = array_search( 'Uncategorized', $list_cats_Arr ) ) !== false )
		    unset( $list_cats_Arr[$key] );

		if ( empty( $list_cats_Arr ) )
			return;		

		echo '<ul class="flex-container post-categories">';

			$more_cats 	= false;
			$allowed 	= $catnumber;

			if ( count( $list_cats_Arr ) > $allowed ) 								// !!!
				$more_cats = true;

			$list_cats_Arr = array_slice( $list_cats_Arr, 0, $allowed ); 			// !!!

			$cat_i = 0;
			foreach ( $list_cats_Arr as $cat ) :

				$cat_url 	= get_category_link( get_cat_ID( $cat ) );
				$cat_class 	= ( $cat_i == ( $allowed - 1 ) ) ? 'last-cat' : 'cat'; 		// !!!
				echo "<li class='flex-item {$cat_class}'><a href='{$cat_url}'>{$cat}</a></li>";

				$cat_i ++;

			endforeach;

			if ( ( true == $more_cats ) && $show_ellipsis )
				echo '<li class="flex-item more-cats"><span>... </span></li>';

		echo '</ul>';

		//echo $list_cats;	

	}

endif;

/* Social Meta
-------------- */

// Add social meta tags to header
if ( ! function_exists( 'cap_social_meta' ) ) :

	function cap_social_meta() {
	 
		global $post;
	 
		$pt_Arr = array(
					'post',
					'page',
					'attachment',
					'podcast',
					'analysis',
					'studentpost',
					'nwmember',
				  );

		if( is_singular( $pt_Arr ) ) :
	 
			$sitename 		= get_bloginfo( 'name' );

	   		$permalink 		= get_permalink();
	   		$title 			= get_the_title();
	 
	   		$content 		= has_excerpt() ? get_the_excerpt() : get_the_content();
	   		$content 		= wp_trim_words( $content, $num_words = 50, ' ...' );
	 
			$thumbnail_ID 	= get_post_thumbnail_id( $post->ID );
			$thumbnail_Obj 	= wp_get_attachment_image_src( $thumbnail_ID, 'medium_large', false );
			$thumbnail 		= $thumbnail_Obj[0];

			$via 			= get_field('cap_general_via', 'option');
			$via 			= ( $via && ( '' !== $via ) ) ? $via : 'eolander';

			if ( is_front_page() ) : 

		   		$content 		= get_the_excerpt( 4862 ); 	// Get the excerpt from the About page
		   		$content 		= wp_trim_words( $content, $num_words = 50, ' ...' );
		 
				$thumbnail 		= get_template_directory_uri() . '/img/default-thumb.jpg';

			endif;
			?>
	 
			<!-- Facebook & LinkedIn -->
			<meta property="og:url" content="<?php echo $permalink; ?>" />
			<meta property="og:type" content="article" />
			<meta property="og:title" content="<?php echo $title; ?>" />
			<meta property="og:description" content="<?php echo $content; ?>" />
			<meta property="og:site_name" content="<?php echo $sitename; ?>" />
		 
			<meta property="og:image" content="<?php echo $thumbnail; ?>" />
			<meta property="og:image:type" content="image/jpeg" />
			<meta property="og:image:width" content="320" />
			<meta property="og:image:height" content="208" />
		 
			<!-- <meta property="fb:app_id" content="996878443771257" />			
			<meta property="fb:admins" content="your-facebook-user-id" /> -->
	 
	   		<!-- Twitter card properties -->
			<meta name="twitter:card" content="summary_large_image" />
			<meta name="twitter:site" content="@<?php echo $via; ?>" />
			<meta name="twitter:creator" content="@<?php echo $via; ?>" />
					
			<meta name="twitter:title" content="<?php echo $title; ?>" />
			<meta name="twitter:description" content="<?php echo $content; ?>" />
			<meta name="twitter:image" content="<?php echo $thumbnail; ?>" />
	 
		<?php		   
	   	endif;
	 
	}
	add_action( 'wp_head', 'cap_social_meta' );

endif;

if ( ! function_exists( 'cap_post_share' ) ) :

	function cap_post_share( $template = 'singular' ) {
	 
		ob_start();
		
		if ( 'singular' == $template ) :
		?>
			
			<ul>

				<li>

					<a href="#"><i class="fas fa-share"></i>Share</a>

					<ul>
						<li>
							<div class="jssocials"></div>
						</li>
					</ul>

				</li>

			</ul>

		<?php
		elseif ( 'blog' == $template ) :
		?>

			<div class="jssocials"></div>

		<?php
		endif;

		echo ob_get_clean();
	 
	}

endif;

/* ------------------ 
   EOF Social Meta */

if ( ! function_exists( 'cap_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function cap_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'cap' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'cap' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'cap' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'cap' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'cap' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'cap' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'cap_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function cap_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">

				<?php
				$pturl = get_field('cap_single_featimg_url');
				$pturl = filter_var( $pturl, FILTER_VALIDATE_URL)
							? $pturl
							: false;

				$target = get_field('cap_single_featimg_url_target');
				$target = ( $target ) ?  $target : '_self';							

				if ( false !== $pturl ) echo "<a href='{$pturl}' target='{$target}'>";

				the_post_thumbnail('cap-single-thumbnail');

				if ( false !== $pturl ) echo "</a>";

				if ( false !== wp_get_attachment_caption( get_post_thumbnail_id() ) ) 
					echo '<figcaption class="post-thumbnail__caption">' . wp_get_attachment_caption( get_post_thumbnail_id() ) . '</caption>';
				?>	
			
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<div class="post-thumbnail">		

				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php
					the_post_thumbnail( 'cap-single-thumbnail', array(
						'alt' => the_title_attribute( array(
							'echo' => false,
						) ),
					) );
					?>
				</a>
					
				<?php
				if ( false !== wp_get_attachment_caption( get_post_thumbnail_id() ) ) 
					echo '<figcaption class="post-thumbnail__caption">' . wp_get_attachment_caption( get_post_thumbnail_id() ) . '</caption>';				
				?>

			</div>

		<?php
		endif; // End is_singular().
	}
endif;

if ( class_exists('acf') && !function_exists( 'cap_nav_follow' ) ) :

	function cap_nav_follow() { echo do_shortcode('[cap-follow]'); }

endif;

if ( !function_exists( 'cap_nav_listen' ) ) :

	function cap_nav_listen() { echo do_shortcode('[cap-listen]'); }

endif;

if ( !function_exists( 'cap_nav' ) ) :

	function cap_nav() {

		ob_start();
		?>

			<nav class="wrap--menu-1 flex-item">

				<?php
				wp_nav_menu( array(
					'theme_location' 	=> 'menu-1',
					'menu_id'        	=> 'primary-menu',
					'container' 	 	=> '',
				) );
				?>

			</nav>

			<nav class="wrap--menu-2 flex-item">

				<?php
				wp_nav_menu( array(
					'theme_location' 	=> 'menu-2',
					'menu_id'        	=> 'primary-menu',
					'container' 	 	=> '',
				) );
				?>

			</nav>					

			<div class="wrap--search flex-item">
				
				<a href="#"><i class="fas fa-search"></i></a>

				<div class="searchform"><?php get_search_form(); ?></div>

			</div>

		<?php
		echo ob_get_clean();

	}

endif;

function cap_custom_excerpt( $content, $wordlimit, $url, $readmore = 'Read More' ) {
 
	// Let's remove Visual Composer shorttags.
	$content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content );

	if ( '' == $readmore ) :
		$readmore_Str = ' ...';
	else :
		$readmore_Str = ' ... <a class="readmore-link" href="' . $url . '">' . $readmore . '</a>';
	endif;

	if ( false !== $wordlimit ) :

		$excerpt = force_balance_tags( 
			html_entity_decode( 
				wp_trim_words( 
					htmlentities( 
						wpautop( $content ) 
					), 
					$wordlimit, 
					$readmore_Str			 
				) 
			) 
		); 

	else:
	// If $wordlimit is set to false, there's no trimming. Might come in handy 
	// in case of handcrafted excerpts input in the Excerpt field.		

		$excerpt = force_balance_tags( 
			html_entity_decode( 
				htmlentities( 
					wpautop( $content . $readmore_Str ) 
				)			
			) 
		);		

	endif;
 
	return $excerpt; 
 
}
 
/* ================================================================================================
# Pagination
================================================================================================ */

function cap_pagination( $custom_query ) {

    $total_pages = $custom_query->max_num_pages;
    $big = 999999999; // need an unlikely integer

    if ($total_pages > 1) :

        $current_page = max(1, get_query_var('paged'));

        echo paginate_links(array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    
    endif;
}

/* ================================================================================================
# Blog page in-between content
================================================================================================ */

if ( ! function_exists( 'cap_blog_inbetween_ad' ) ) :

	function cap_blog_inbetween_ad() {

		ob_start();
		?>

			<div class="wrap--adslot wrap--adslot-5">

				<aside class="adslot adslot-5 container">
					<?php dynamic_sidebar( 'adslot-5' ); ?>
				</aside>

			</div>	
		
		<?php
		echo ob_get_clean();

	}

endif;

if ( ! function_exists( 'cap_blog_inbetween_cobus' ) ) :

	function cap_blog_inbetween_cobus( $is_slider = true, $title = false ) {

		$blog_ID 	= get_option('page_for_posts');
		$author_ID 	= 12;
		
		if ( false == $title ) :
		
			$cobus_on_blog_title = get_field('cap_blog_cobustitle', $blog_ID );

			$btitle = ( $cobus_on_blog_title && ( '' !== $cobus_on_blog_title ) ) 
						? $cobus_on_blog_title
						: __( 'Analysis from Cobus Van Staden', 'cap' );

		else:

			$btitle = $title;

		endif;
		
		$avatar 	= get_avatar( $author_ID, 100 );
		$author_url = get_author_posts_url( $author_ID );

		$numposts 	= get_field('cap_blog_cobusno', $blog_ID );	
		$numposts 	= $numposts ? $numposts : 10;
		$numposts 	= $is_slider ? $numposts : 1;

		$ana_args = array(
			'post_type' 			=> array('analysis'),
			'posts_per_page' 		=> $numposts,
			'post_status' 			=> array('publish'),
			'author' 				=> $author_ID,
		);

		$query_ana = new WP_Query( $ana_args );

		if ( $query_ana->have_posts() ) : ?>

			<div class="wrap--cobus-showcase <?php if ( false == $is_slider ) echo 'no-slider'; ?>">
				
				<div class="cobus-showcase">

					<header class="cobus__header flex-container">

						<div class="cobus__avatar flex-item">
							
							<a href="<?php echo $author_url; ?>"><?php echo $avatar; ?></a>

						</div>											

						<?php 
						if ( $btitle && ( '' !== $btitle ) ) echo "<h2 class='cobus__blocktitle flex-item'><a href='{$author_url}'>{$btitle}</a></h2>";
						?>

					</header>

					<div class="<?php echo ( $is_slider ) ? 'anaslides' : 'anablocks'; ?>">
			
						<?php
						while ( $query_ana->have_posts() ) : $query_ana->the_post(); ?>

							<div class='<?php echo ( $is_slider ) ? 'anaslide' : 'anablock'; ?>'><article>

								<?php
								global $post;

								/* Socials */

								$thumbnail_ID 	= get_post_thumbnail_id( $post->ID );
								$thumbnail_Obj 	= wp_get_attachment_image_src( $thumbnail_ID, 'medium_large', false );
								$thumbnail 		= $thumbnail_Obj[0];

								/* EOF Socials */

								$content 	= apply_filters( 'the_content', $post->post_content );
								$content 	= cap_custom_excerpt( 
												wp_strip_all_tags( $content ), 
												70, 
												get_permalink(), 
												'' 
											  );

								$countries 		= get_the_terms( get_the_ID(), 'country' );
								$has_countries 	= ( $countries && !is_wp_error( $countries ) );
								?>

								<form class="content4socials" action="#" style="display: none;">
									<input type="text" class="input--title" value="<?php echo get_the_title(); ?>" />
									<input type="text" class="input--permalink" value="<?php echo get_permalink(); ?>" />
									<input type="text" class="input--thumbnail" value="<?php echo $thumbnail; ?>" />
									<textarea><?php echo $content; ?></textarea>
								</form>

								<?php
								if ( ( false == $is_slider ) && $has_countries ) : ?>

									<div class="anablock__inner-wrap flex-container">

										<div class="anablock__left flex-item">

											<?php cap_post_countries( $id = false ); ?>

										</div>

										<div class="anablock__right flex-item">

								<?php 
								endif; ?>
								
								<h3 class="<?php echo ( $is_slider ) ? 'anaslide' : 'anablock'; ?>__entry-title">

									<a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>

								</h3>

								<div class="<?php echo ( $is_slider ) ? 'anaslide' : 'anablock'; ?>__entry-content">

									<?php echo $content; ?>

								</div>

								<div class="jssocials"></div>

								<?php
								if ( ( false == $is_slider ) && $has_countries ) : ?>

										</div>

									</div>

								<?php 
								endif; ?>								

							<?php
							echo '</article></div>';

						endwhile; 
			
						wp_reset_postdata();
						?>
					
					</div>
	
				</div>

			</div>
		
		<?php	
		endif;

	}

endif;

if ( ! function_exists( 'cap_blog_inbetween_nw' ) ) :

	function cap_blog_inbetween_nw() {

		$blog_ID 	= get_option('page_for_posts');
		$btitle 	= get_field('cap_blog_nwtitle', $blog_ID );

		$nwurl 		= get_field('cap_blog_nwurl', $blog_ID );		

		$blogo_Arr 	= get_field('cap_blog_nwlogo', $blog_ID );
		$blogo_size = 'medium';
		$blogo 		= $blogo_Arr['sizes'][$blogo_size];

		$nw_args = array(
			'post_type' 			=> array('nwmember'),
			'posts_per_page' 		=> 8,
			'post_status' 			=> array('publish'),
			'orderby' 				=> 'rand',
		);

		$query_nw = new WP_Query( $nw_args );

		if ( $query_nw->have_posts() ) : ?>

			<div class="wrap--nw-showcase">
				
				<div class="nw-showcase">

					<header class="nw__header flex-container">

						<div class="nw__logo flex-item">
							
							<a href="<?php echo $nwurl; ?>"><img src="<?php echo $blogo; ?>" width="" height="" alt="" /></a>

						</div>											

						<?php 
						if ( $btitle && ( '' !== $btitle ) ) echo "<h2 class='flex-item'><a href='{$nwurl}'>{$btitle}</a></h2>";
						?>

					</header>

					<div class="members flex-container">
			
						<?php
						while ( $query_nw->have_posts() ) : $query_nw->the_post(); 

							echo '<div class="member flex-item"><article>';

								$title 	= get_the_title();
								$pos 	= get_field('net_position');
								$inst 	= get_field('net_institution');

								// Get thumbnail
								if ( has_post_thumbnail( get_the_ID() ) ) : 

									$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' ); 
									?>
										
									<div class="wrap--member-img">
										<img src="<?php echo $thumb[0]; ?>" width="" height="" alt="" class="member-img" />
									</div>

								<?php
								endif;

								if ( $title && ( '' !== $title ) ) 	echo "<h4 class='member-name'>{$title}</h4>";
								if ( $pos 	&& ( '' !== $pos ) ) 	echo "<h5 class='member-pos'>{$pos}</h5>";
								if ( $inst 	&& ( '' !== $inst ) ) 	echo "<h6 class='member-inst'>{$inst}</h6>";

							echo '</article></div>';

						endwhile; 
			
						wp_reset_postdata();
						?>
					
					</div>
	
				</div>

			</div>
		
		<?php	
		endif;

	}

endif;

if ( ! function_exists( 'cap_blog_inbetween_stud' ) ) :

	function cap_blog_inbetween_stud() {

		$blog_ID 	= get_option('page_for_posts');
		
		$btitle 	= get_field('cap_blog_studtitle', $blog_ID );
		$btitle 	= str_replace(
			'XCHANGE', 
			'xCHANGE', 
			strtoupper( $btitle ) 
		);

		$numposts 	= get_field('cap_blog_studno', $blog_ID );

		$blogo_Arr 	= get_field('cap_blog_studlogo', $blog_ID );
		$blogo_size = 'medium';
		$blogo 		= $blogo_Arr['sizes'][$blogo_size];				

		$stud_args = array(
			'post_type' 			=> array('studentpost'),
			'posts_per_page' 		=> $numposts ? $numposts : 12,
			'post_status' 			=> array('publish'),
		);

		$query_stud = new WP_Query( $stud_args );

		if ( $query_stud->have_posts() ) : ?>

			<div class="wrap--studslider">
				
				<div class="studslider">

					<header class="stud__header flex-container">

						<div class="stud__logo flex-item">
							
							<img src="<?php echo $blogo; ?>" width="" height="" alt="" />

						</div>											

						<?php 
						if ( $btitle && ( '' !== $btitle ) ) echo "<h2 class='flex-item'>{$btitle}</h2>";
						?>

					</header>

					<div class="studslides">
			
						<?php
						while ( $query_stud->have_posts() ) : $query_stud->the_post(); 

							echo '<div class="studslide"><article>';

								global $post;

								$title 			= get_the_title();
								$permalink 		= get_permalink();
								$student_ID 	= get_the_author_meta('ID');
								$student_url 	= get_author_posts_url( $student_ID );

								$avatar 		= get_avatar( $student_ID, 75 );
								
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

								<header class="studentpost__header flex-container">

									<div class="studentpost__avatar flex-item">
										
										<a href="<?php echo $student_url; ?>"><?php echo $avatar; ?></a>

									</div>

									<div class="studentpost__hentry flex-item">

										<?php 
										$studentmeta = [];

										if ( $age && ( '' !== $age ) ) 			array_push( $studentmeta, $age );
										if ( $inst && ( '' !== $inst ) ) 		array_push( $studentmeta, $inst );
										if ( $country && ( '' !== $country ) ) 	array_push( $studentmeta, $country );
										
										$name_Str 	= !empty($studentmeta) ? $name . ',' : $name;
										?>

										<h3><?php echo $name_Str; ?></h3>

										<?php
										if ( !empty($studentmeta) ) : ?>

											<div class="studentpost__meta">

												<h4><?php echo implode( ', ', $studentmeta ); ?></h4>

											</div>
										
										<?php
										endif;
									
										if ( $title && ( '' !== $title ) ) 	echo "<div class='studentpost__title'><a href='{$permalink}'><h3>{$title}</h3></a></div>";	
										?>
										
									</div>

								</header>
								
								<?php
								$content 	= apply_filters( 'the_content', $post->post_content );
								$content 	= cap_custom_excerpt( 
												wp_strip_all_tags( $content ), 
												50, 
												get_permalink(), 
												'' 
											  );
								?>

								<div class="studentpost__content"><?php echo $content; ?></div>	

							<?php
							echo '</article></div>';

						endwhile; 
			
						wp_reset_postdata();
						?>
					
					</div>
	
				</div>

			</div>
		
		<?php	
		endif;

	}

endif;