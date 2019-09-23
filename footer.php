<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cap2019
 */

?>

	</div><!-- #content -->

	<div class="wrap--site-foot">

		<div class="site-foot">

			<div class="site-branding--footer">
				<div class="container"></div>
			</div>

			<footer id="colophon" class="site-footer">

				<div class="footer-widgets container flex-container">

					<?php
					for ( $i = 0; $i <= 5; $i++ ) :

						$callmesam = 'footbar-' . $i; 

						if ( is_active_sidebar( $callmesam ) ) : ?>

							<aside class="widget-area flex-item widget-area--<?php echo $i; ?>">
								<?php dynamic_sidebar( $callmesam ); ?>
							</aside>

						<?php 
						endif;

					endfor;
					?>

				</div>

				<div class="footer-copyright container">
					
					<?php
					$copy = get_field('cap_footer_copyright', 'option');

					if ( $copy && ( '' !== $copy ) )
						echo $copy;
					?>

				</div>

			</footer><!-- #colophon -->

		</div>

	</div>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
