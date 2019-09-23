<?php
/**
 * Member Directory Sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

if ( is_active_sidebar( 'sidebar-13' ) ) : ?>

	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-13' ); ?>
	</aside><!-- #secondary -->

<?php endif; 