<?php
/**
 * The sidebar containing the Single page template's sidebar content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

$pt_Obj 		= get_queried_object();

$i = 4;

if ( isset( $pt_Obj->name ) ) :

	if ( 'analysis' == $pt_Obj->name ) 		$i = 4;
	if ( 'podcast' == $pt_Obj->name ) 		$i = 5;
	if ( 'studentpost' == $pt_Obj->name ) 	$i = 6;

endif;

if ( is_author() )	 		$i = 7;
if ( is_category() ) 		$i = 8;
if ( is_tax( 'country' ) ) 	$i = 9;
if ( is_date() ) 			$i = 10;

if ( is_active_sidebar( "sidebar-{$i}" ) ) : ?>

	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( "sidebar-{$i}" ); ?>
	</aside><!-- #secondary -->

<?php endif; 