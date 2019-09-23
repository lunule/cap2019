<?php
/**
 * The sidebar containing the Single page template's sidebar content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

$blog_ID 				= get_option('page_for_posts');

$ls 					= get_field('single_libsyn', $blog_ID);
$ls_default 			= get_field('cap_general_single_libsyn_ep', 'option');

$lsthumb 				= get_field('single_libsyn_thumb', $blog_ID);
$lsthumb_default 		= get_field('cap_general_single_libsyn_epthumb', 'option');

$d_ls 					= get_field('single_libsyn_display', $blog_ID);

$has_ls 				= ( $ls && ( '' !== $ls ) );
$has_ls_default 		= ( $ls_default && ( '' !== $ls_default ) );
$has_lsthumb 			= !empty( $lsthumb );
$has_lsthumb_default 	= !empty( $lsthumb_default );	

if ( !$ls && !$lsthumb && !$has_ls_default && !$has_lsthumb_default ) 
	return;

if ( !$ls && !$has_ls_default ) 
	return;

if ( !$lsthumb && !$has_lsthumb_default ) 
	return;			

$ls_final 				= !$has_ls ? $ls_default : $ls;
$lsthumb_final 			= !$has_lsthumb ? $lsthumb_default : $lsthumb;		

// thumbnail
$size 					= 'libsyn-thumbnail';
$lsthumb_final 			= $lsthumb_final['sizes'][ $size ];

if ( $ls_final && ( '' !== $ls_final ) && !$d_ls ) : ?>

	<div class="pod">				

		<?php echo "<div class='podthumb'><img src='{$lsthumb_final}' width='' height='' alt='' /></div>"; ?>

		<div class="podplayer"><?php echo $ls_final; ?></div>

	</div>

<?php
endif;

if ( is_active_sidebar( 'sidebar-2' ) ) : ?>

	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</aside><!-- #secondary -->

<?php endif; 