<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package cap2019
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'cap' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content flex-container">
	
					<div class="search-404 flex-item">

						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'cap' ); ?></p>

						<?php
						get_search_form(); ?>

					</div>

					<div class="recent-posts-404 flex-item">

						<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					</div>

					<div class="widget widget_categories categories-404 flex-item">

						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'cap' ); ?></h2>
						<ul>
							<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
							?>
						</ul>
					</div><!-- .widget -->

					<div class="tagcloud-404 flex-item">

						<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

					</div>

					<div class="monthly-archives-404 flex-item">

						<?php
						/* translators: %1$s: smiley */
						$cap_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'cap' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$cap_archive_content" );
						?>

					</div>					

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
