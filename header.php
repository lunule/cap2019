<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="Pragma" content="no-cache">

	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-24489529-1"></script>
	<script>
		
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-24489529-1');
	
	</script>

</head>

<body <?php body_class(); ?>>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'lead' ); ?></a>

	<?php 
	if ( class_exists('acf') ) :

		$form_ID = get_field('cap_topsub_form', 'option');

		if ( is_numeric( $form_ID ) ) : ?>

			<div class="wrap--top-subscribe">

				<div class="top-subscribe container">

					<?php 
					echo do_shortcode('[gravityform id=' . (int)$form_ID . ' title=false description=true ajax=true]');
					?>

					<a href="#" title="<?php _e('Hide this form for 30 days', 'cap'); ?>"><i class="fas fa-times"></i></a>

				</div>

			</div>

		<?php
		endif;

	endif;
	?>

	<div id="mobile-navigation" class="mobile-navigation">
	
		<div class="flex-container"><?php cap_nav(); ?></div>

		<div>
			<?php
			if ( class_exists('acf') ) :
				cap_nav_follow();							
				cap_nav_listen();
			endif;
			?>
		</div>

	</div><!-- #mobile-navigation -->

	<div class="wrap--site-head">

		<div class="hamburger hamburger--spin">
			<div class="hamburger-box">
				<div class="hamburger-inner"></div>
			</div>
		</div>					

		<div class="site-head">

			<header id="masthead" class="site-header">

				<div class="wrap--site-branding">

					<div class="site-branding flex-container container">

						<div class="flex-item cap-follow wrap--ib">
							
							<?php
							if ( class_exists('acf') )
								cap_nav_follow();							
							?>

						</div>

						<div class="flex-item cap-branding"><?php the_custom_logo(); ?></div>

						<div class="flex-item cap-listen wrap--ib">
							
							<?php
							if ( class_exists('acf') )
								cap_nav_listen();
							?>

						</div>

					</div><!-- .site-branding -->

				</div>

				<div class="wrap--site-navigation">

					<div id="site-navigation" class="main-navigation flex-container container">

						<?php cap_nav(); ?>

					</div><!-- #site-navigation -->

				</div>

			</header><!-- #masthead -->			

		</div>

	</div>

		<?php
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
		) : ?> 

		<div class="wrap--adslot wrap--adslot-1">

			<aside class="adslot adslot-1 container">
				<?php dynamic_sidebar( 'adslot-1' ); ?>
			</aside>

		</div>	

	<?php 
	endif;
	?>

	<div id="content" class="site-content">
