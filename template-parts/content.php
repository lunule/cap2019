<?php
/**
 * Template part for displaying post content on listing templates
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

/* ================================================================================================
===================================================================================================

	--- FORMAT-SPECIFIC CONTENT-SETUP ---

===================================================================================================
================================================================================================ */

$format 	= get_field('cap_post_format'); 
$format 	= ( NULL == $format ) ? 'article' : $format; 
$imgsurl 	= get_template_directory_uri() . "/img/{$format}-f.png";

switch ($format) :

	/* --------------------------------------------------------------------------------------------
	# AUDIO
	-------------------------------------------------------------------------------------------- */
	case 'audio':

		/* SHORT DESC: News feed displays:
			> audio
			> html
		   ... as expected - then an excerpt of any other content type. */
		
		$media_content = '';

		$blocks = parse_blocks( get_the_content() );

		// var_dump( $blocks );
		foreach ( $blocks as $block ) :

			if ( cap_block_is_audio( $block ) ) :

				if ( 'core/html' === $block['blockName'] ) :

					$htmlString = $block['innerHTML'];

					/**
					 * Check if block content has a valid iframe inside - AND LET'S GET 
					 * THAT IFRAME!!
					 *
					 * @see https://stackoverflow.com/questions/6909405/filter-out-iframe-using-preg-match#answer-6909599
					 * 
					 */
					preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $htmlString, $matches);
					$iframeHTML = $matches[0]; //only the <iframe ...></iframe> part
					$iframeUrl 	= $matches[1]; //the src part. (http://www.youtube.com/embed/IIYeKGNNNf4?rel=0)		

					if ( count($matches) >= 2 ) :

						$media_content .= '<div class="cap-blog-iframe">';
						$media_content .= $matches[0];
						$media_content .= '</div>';

					endif;

				else:
				
					$media_content .= render_block($block);
				
				endif;

				//break;
			
			endif;

		endforeach;

		$pre_content = '';
		foreach ( $blocks as $block ) :
			
			if ( cap_block_is_audio( $block ) ) : continue;
			
			else :

				$pre_content .= render_block( $block );

			endif;
				
		endforeach;	

		$pre_content 	= apply_filters( 'the_content', $pre_content );

		//var_dump( $pre_content );

		$pre_content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$pre_content, 
								35, 
								get_permalink(), 
								'' 
						  	)
					  	  );		
		
		$pre_excerpt  	= wpautop( $post->post_excerpt );
		
		$content = has_excerpt() ? $pre_excerpt : $pre_content;

		$content = $media_content . $content;

		break;

	/* --------------------------------------------------------------------------------------------
	# PHOTOS
	-------------------------------------------------------------------------------------------- */
	case 'photos':

		/* SHORT DESC: News feed displays:
			> gallery block
			> image
			> image with text
		   ... as expected - then an excerpt of any other content type. */
		
		$media_content = '';

		$blocks = parse_blocks( get_the_content() );

		//var_dump( $blocks );
		foreach ( $blocks as $block ) :

			if ( cap_block_is_media( $block ) ) :

				$media_content .= render_block($block);
				//break;
			
			endif;

		endforeach;

		$pre_content = '';
		foreach ( $blocks as $block ) :
			
			if ( cap_block_is_media( $block ) ) : continue;
			
			else :

				$pre_content .= render_block( $block );

			endif;
				
		endforeach;	

		$pre_content 	= apply_filters( 'the_content', $pre_content );

		//var_dump( $pre_content );

		$pre_content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$pre_content, 
								35, 
								get_permalink(), 
								'' 
						  	)
					  	  );		
		
		$pre_excerpt  	= wpautop( $post->post_excerpt );
		
		$content = has_excerpt() ? $pre_excerpt : $pre_content;

		$content = $media_content . $content;

		break;

	/* --------------------------------------------------------------------------------------------
	# QUOTE
	-------------------------------------------------------------------------------------------- */
	case 'quote':

		/* SHORT DESC: News feed displays:
			> quote
			> pullquote
		   ... as expected - then an excerpt of any other content type. */
		
		$media_content = '';

		$blocks = parse_blocks( get_the_content() );

		// var_dump( $blocks );
		foreach ( $blocks as $block ) :

			if ( cap_block_is_quote( $block ) ) :

				$media_content .= render_block($block);
				//break;
			
			endif;

		endforeach;

		$pre_content = '';
		foreach ( $blocks as $block ) :
			
			if ( cap_block_is_quote( $block ) ) : continue;
			
			else :

				$pre_content .= render_block( $block );

			endif;
				
		endforeach;	

		$pre_content 	= apply_filters( 'the_content', $pre_content );

		//var_dump( $pre_content );

		$pre_content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$pre_content, 
								35, 
								get_permalink(), 
								'' 
						  	)
					  	  );		
		
		$pre_excerpt  	= wpautop( $post->post_excerpt );
		
		$content = has_excerpt() ? $pre_excerpt : $pre_content;

		$content = $media_content . $content;

		break;

	/* --------------------------------------------------------------------------------------------
	# TWITTER
	-------------------------------------------------------------------------------------------- */
	case 'twitter':

		/* SHORT DESC: News feed displays:
			> twitter
		   ... as expected - then an excerpt of any other content type. */
		
		// We should set up the global to make EMBEDS work in their shortcode format --- for 
		// some reason render_block() doesn't work with embeds
		global $wp_embed;

		$media_content = '';

		$blocks = parse_blocks( get_the_content() );

		// var_dump( $blocks );
		foreach ( $blocks as $block ) :

			if ( cap_block_is_tweet( $block ) ) :

				if ( 'core-embed/twitter' === $block['blockName'] ) :

					$tweet_url 	= $block['attrs']['url'];
					$htmlString = render_block($block);
					
					$has_tweet 		= filter_var( $tweet_url, FILTER_VALIDATE_URL );

					if ( $has_tweet ) :

						$media_content .= '<div class="cap-blog-twitterembed">';
						$media_content .= $wp_embed->run_shortcode( '[embed]' . $tweet_url . '[/embed]' );
						$media_content .= '</div>';

					endif;

				else:
				
					$media_content .= render_block($block);
				
				endif;
			
			endif;

		endforeach;

		$pre_content = '';
		foreach ( $blocks as $block ) :
			
			if ( cap_block_is_tweet( $block ) ) : continue;
			
			else :

				$pre_content .= render_block( $block );

			endif;
				
		endforeach;	

		$pre_content 	= apply_filters( 'the_content', $pre_content );

		//var_dump( $pre_content );

		$pre_content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$pre_content, 
								35, 
								get_permalink(), 
								'' 
						  	)
					  	  );		
		
		$pre_excerpt  	= wpautop( $post->post_excerpt );
		
		$content = has_excerpt() ? $pre_excerpt : $pre_content;

		$content = $media_content . $content;

		break;

	/* --------------------------------------------------------------------------------------------
	# VIDEO
	-------------------------------------------------------------------------------------------- */
	case 'video':

		/* SHORT DESC: News feed displays:
			> video
			> media with text
			> youtube video
			> vimeo video
		   ... as expected - then an excerpt of any other content type. */
		
		// We shold set up the global to make EMBEDS work in their shortcode format --- for 
		// some reason render_block() doesn't work with embeds
		global $wp_embed;

		$media_content = '';

		$blocks = parse_blocks( get_the_content() );

		// var_dump( $blocks );
		foreach ( $blocks as $block ) :

			if ( cap_block_is_video( $block ) ) :

				if (
						( 'core-embed/youtube' === $block['blockName'] ) ||
						( 'core-embed/vimeo' === $block['blockName'] )
					) :

					$video_url 	= $block['attrs']['url'];
					$htmlString = render_block($block);

					$dom = new DOMDocument;
					@$dom->loadHTML($htmlString); // @see https://github.com/wasinger/html-pretty-min/issues/1/#issuecomment-388858167

					$figcaptions = @$dom->getElementsByTagName('figcaption');

					if ( NULL !== $figcaptions['length'] ) :

						$caption = $figcaptions->item(0);
						$caption = $caption->textContent;

					endif;
					
					$has_video 		= filter_var( $video_url, FILTER_VALIDATE_URL );
					$has_caption 	= ( isset( $caption ) && ( '' !== $caption ) );

					//if ( $has_video ) :

						$media_content .= '<div class="cap-blog-videoembed"><div class="videoembed__video">';
						$media_content .= $wp_embed->run_shortcode( '[embed]' . $video_url . '[/embed]' );

						if ( $has_caption ) :
							$media_content .= "<figcaption class='videoembed__caption'>{$caption}</figcaption>";
						endif;

						$media_content .= '</div></div>';

					//endif;

				else:
				
					$media_content .= render_block($block);
				
				endif;
			
			endif;

		endforeach;

		$pre_content = '';
		foreach ( $blocks as $block ) :
			
			if ( cap_block_is_video( $block ) ) : continue;
			
			else :

				$pre_content .= render_block( $block );

			endif;
				
		endforeach;	

		$pre_content 	= apply_filters( 'the_content', $pre_content );

		//var_dump( $pre_content );

		$pre_content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$pre_content, 
								35, 
								get_permalink(), 
								'' 
						  	)
					  	  );		
		
		$pre_excerpt  	= wpautop( $post->post_excerpt );
		
		$content = has_excerpt() ? $pre_excerpt : $pre_content;

		$content = $media_content . $content;

		break;
	
	/* --------------------------------------------------------------------------------------------
	# DEFAULT (article)
	-------------------------------------------------------------------------------------------- */
	default:

		/* SHORT DESC: News feed displays:
			> if there's a featured image: featured image + a 35-words excerpt of the 
			  content OR the excerpt if this latter's specified.
			> if there's no featured image: the full content OR the excerpt if this 
			  latter's specified. THis also means that if there's no excerpt specificed
			  for this scenario, content shortening is possible only by using the CLASSIC 
			  BLOCK'S ( default content with WYSIWYG editor ) "INSERT READ MORE TAG" 
			  BUTTON, OR -more preferably- THE "MORE" BLOCK.
		*/

		if ( has_post_thumbnail() ) :

			$content 	= apply_filters( 'the_content', $post->post_content );

			$content 	= wp_strip_all_tags( 
							cap_custom_excerpt( 
								$content, 
								35, 
								get_permalink(), 
								'' 
							)
						  );

			if ( has_excerpt() ) :
				$content = wpautop( $post->post_excerpt );
			endif;

		else : 

			$content = wpautop( $post->post_content );

			if ( has_excerpt() ) : 
				$content = wpautop( $post->post_excerpt );
			endif;
			?>

		<?php
		endif;

		break;

endswitch;

/* ================================================================================================
===================================================================================================

	--- EOF format-specific content setup ---

===================================================================================================
================================================================================================ */

$thumb_class = has_post_thumbnail() ? 'has-thumb' : 'has-no-thumb';

/* Socials */

$thumbnail_ID 	= get_post_thumbnail_id( $post->ID );
$thumbnail_Obj 	= wp_get_attachment_image_src( $thumbnail_ID, 'medium_large', false );
$thumbnail 		= $thumbnail_Obj[0];

/* EOF Socials */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cap-format-' . $format . ' ' . $thumb_class ); ?>>

	<form class="content4socials" action="#" style="display: none;">
		<input type="text" class="input--title" value="<?php echo get_the_title(); ?>" />
		<input type="text" class="input--permalink" value="<?php echo get_permalink(); ?>" />
		<input type="text" class="input--thumbnail" value="<?php echo $thumbnail; ?>" />
		<textarea><?php echo wp_strip_all_tags( $content ); ?></textarea>
	</form>

	<header class="entry-header">

		<?php
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		?>

	</header><!-- .entry-header -->

	<div class="entry-body flex-container">

		<div class="entry-meta flex-item flex-container">

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
			?>

			<div class="entry-meta__posted-on flex-item flex-container">
				<?php cap_posted_on(); ?>
			</div>

			<?php
			if ( $has_countries ) : ?>

				<div class="entry-meta__post-countries flex-item flex-container">
					<?php cap_post_countries(); ?>
				</div>

			<?php 
			endif;

			if ( $has_cats ) : ?>

				<div class="entry-meta__post-cats flex-item flex-container">
					<?php cap_post_cats( 3, false ); ?>
				</div>

			<?php 
			endif; ?>
						
			<div class="entry-meta__post-share flex-item flex-container">

				<?php cap_post_share('blog'); ?>
	
			</div>					

		</div><!-- .entry-meta -->

		<div class="wrap--entry-content flex-item">
	
			<?php 
			echo "<div class='wrap--iformat-{$format}'><span class='iformat-{$format}'><img src='{$imgsurl}' width='' height='' alt='' /></span></div>";

			cap_post_thumbnail();			

			echo $content; 
			?>

		</div><!-- .entry-content -->

	</div>

</article><!-- #post-<?php the_ID(); ?> -->