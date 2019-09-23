<?php
/**
 * The sidebar containing the Single page template's sidebar content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

$ls 					= get_field('single_libsyn');
$ls_default 			= get_field('cap_general_single_libsyn_ep', 'option');

$lsthumb 				= get_field('single_libsyn_thumb');
$lsthumb_default 		= get_field('cap_general_single_libsyn_epthumb', 'option');

$has_ls 				= ( $ls && ( '' !== $ls ) );
$has_ls_default 		= ( $ls_default && ( '' !== $ls_default ) );
$has_lsthumb 			= !empty( $lsthumb );
$has_lsthumb_default 	= !empty( $lsthumb_default );	

$showit = true;

if ( 'podcast' == get_post_type() )
	$showit = false;

if ( !$ls && !$lsthumb && !$has_ls_default && !$has_lsthumb_default ) 
	$showit = false;

if ( !$ls && !$has_ls_default ) 
	$showit = false;

if ( !$lsthumb && !$has_lsthumb_default ) 
	$showit = false;

$ls_final 				= !$has_ls ? $ls_default : $ls;
$lsthumb_final 			= !$has_lsthumb ? $lsthumb_default : $lsthumb;		

// thumbnail
$size 					= 'libsyn-thumbnail';
$lsthumb_final 			= $lsthumb_final['sizes'][ $size ];

if ( $ls_final && ( '' !== $ls_final ) && $showit ) : ?>

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