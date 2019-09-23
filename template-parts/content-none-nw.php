<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cap2019
 */

?>

<section class="no-results not-found">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'cap' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cap' ); ?></p>
			
			<button onclick="window.location.href = '<?php the_permalink(4860); ?>';">Back to the Experts Network</button>

	</div><!-- .page-content -->
</section><!-- .no-results -->
