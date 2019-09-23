<?php
/**
 * Output the address specified on the CAP Theme Settings options panel's Contact tab
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */

/**
 * Helper function - Unnamed (aka No-Value) WordPress shortcode attributes
 *
 * @see https://richjenks.com/unnamed-wordpress-shortcode-attributes/
 * 
 * @param  [type]  $flag The queried attrbiute
 * @param  [type]  $atts The attributes array
 * @return boolean       true if flag exists ( meaning the no-value 
 *                       attribute is specified )
 */
function is_flag( $flag, $atts ) {

	if ( $atts ) :

		foreach ( $atts as $key => $value )
			if ( $value === $flag && is_int( $key ) ) return true;
	
	endif;

	return false;

}

/**
 * Output verious CAP contact data
 * @param  [type] $atts    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function cap_sc_output_contact( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'address-title'	=> __('Address', 'cap'),
		'phone-title'	=> __('Call', 'cap'),
		'email-title'	=> __('Write', 'cap'),
		'social-title'	=> __('Follow us on:', 'cap'),
		'social-icon' 	=> 'light',
		'exclude' 		=> '',
	), $atts );

	// implement shortcode attribute with no-value,
	// see helper function is_flag() above.
	$hide_titles	= is_flag( 'hide-titles', $atts ) ? true : false;	
	$hide_address 	= is_flag( 'hide-address', $atts ) ? true : false;
	$hide_phone 	= is_flag( 'hide-phone', $atts ) ? true : false;
	$hide_email 	= is_flag( 'hide-email', $atts ) ? true : false;
	$hide_social 	= is_flag( 'hide-social', $atts ) ? true : false;
	$custom_labels  = is_flag( 'custom-labels', $atts ) ? true : false;

	$excludes 		= explode(",", $a['exclude'] );

	$has_acf 			= class_exists('ACF');

	$address 			= get_field('opt_contact_address', 'option');
	$has_address 		= ( $address && ( '' !== $address ) && ( false == $hide_address ) );

	$phone1 			= get_field('opt_contact_phone1', 'option');
	$has_phone1 		= ( $phone1 && ( '' !== $phone1 ) && ( false == $hide_phone ) );

	$phone2 			= get_field('opt_contact_phone2', 'option');
	$has_phone2 		= ( $phone2 && ( '' !== $phone2 ) && ( false == $hide_phone ) );

	$email1 			= get_field('opt_contact_email1', 'option');
	$has_email1 		= ( $email1 && ( '' !== $email1 ) && ( false == $hide_email ) );

	$email2 			= get_field('opt_contact_email2', 'option');
	$has_email2 		= ( $email1 && ( '' !== $email2 ) && ( false == $hide_email ) );

	$has_social 		= ( have_rows( 'opt_contact_social', 'option' ) && ( false == $hide_social ) );

	if ( $has_acf ) :

		ob_start(); ?>

			<div class="wrap--cap-sc-contact">

				<div class="cap-sc-contact">

					<?php
					/* Address 
					---------- */
					if ( $has_address ) : ?>			

						<div class="cap-sc-contact__address">

							<?php
							if ( false == $hide_titles ) 
								echo "<h4>{$a['address-title']}</h4>";

							echo "<address>{$address}</address>";
							?>

						</div>

					<?php
					endif; ?>

					<?php
					/* Phone 
					-------- */
					if ( $has_phone1 || $has_phone2 ) : ?>			

						<div class="cap-sc-contact__phone">

							<?php							
							if ( false == $hide_titles ) 
								echo "<h4>{$a['phone-title']}</h4>";
							?>

							<ul>

								<?php
								if ( $has_phone1 ) echo "<li>{$phone1}</li>";
								if ( $has_phone2 ) echo "<li>{$phone2}</li>";
								?>

							</ul>

						</div>

					<?php
					endif; ?>

					<?php
					/* Email 
					-------- */					
					if ( $has_email1 || $has_email2 ) : ?>			

						<div class="cap-sc-contact__email">

							<?php							
							if ( false == $hide_titles ) 
								echo "<h4>{$a['email-title']}</h4>";
							?>

							<ul>

								<?php
								if ( $has_email1 ) echo "<li><a class='undeco' href='mailto:{$email1}'>{$email1}</a></li>";
								if ( $has_email2 ) echo "<li><a class='undeco' href='mailto:{$email2}'>{$email2}</a></li>";
								?>

							</ul>

						</div>

					<?php
					endif; ?>
					
					<?php
					/* Social 
					--------- */					
					if ( have_rows( 'opt_contact_social', 'option' ) && $has_social ) : 			
						echo '<div class="cap-sc-contact__social wrap--social">';

						if ( false == $hide_titles ) 
							echo "<h4>{$a['social-title']}</h4>";

						echo '<ul>';

						while ( have_rows( 'opt_contact_social', 'option' ) ) : the_row();

							$name 			= get_sub_field('name');

							$clabel 		= get_sub_field('clabel');
							$has_clabel 	= ( $clabel && ( '' !== $clabel ) );

							$name 			= ( ( true == $custom_labels ) && $has_clabel ) 
												? $clabel 
												: $name;

							$name_lc 		= strtolower( $name );
							$url 			= get_sub_field('url');
							
							$icon 			= ( 'light' == $a['social-icon'] ) 
													? get_sub_field('icon')
													: get_sub_field('icon_dark');

							$icon_url 		= $icon['url'];							

							if ( !in_array( get_row_index(), $excludes ) ) 
								echo "<li class='cap-social--{$name_lc}'><span class='icon-holder' style='background: transparent url({$icon_url}) center center/contain no-repeat;'></span><a href='{$url}' target='_blank' class='undeco'>{$name}</a></li>";  

						endwhile;

						echo '</ul></div>';

					endif; ?>

				</div>

			</div>
		
		<?php
		$output = ob_get_clean();

	else :

		$output = __('Please install/activate the Advanced Custom Fields Pro plugin', 'cap');

	endif;

	return $output;

}
add_shortcode( 'cap-contact', 'cap_sc_output_contact' );

/**
 * Output the CAP social networks icon block
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function cap_sc_follow( $atts, $content = null ) {

	$a = shortcode_atts( array(
		// 'address-title'	=> __('Address', 'cap'),
	), $atts );

	// implement shortcode attribute with no-value,
	// see helper function is_flag() above.
	$hide_title = is_flag( 'hide-title', $atts ) ? true : false;
	$has_title 	= ( false == $hide_title );	

	$has_acf 	= class_exists('ACF');

	if ( $has_acf ) :

		ob_start();

		echo '<div class="ib">';

			$flabel = get_field('cap_header_follow_label', 'option');

			if ( $flabel && ( '' !== $flabel ) && $has_title ) echo "<h2>{$flabel}</h2>";

			if ( have_rows('cap_header_follow_nw', 'option') ) :

				while ( have_rows('cap_header_follow_nw', 'option') ) : the_row();

					$nw_name 	= get_sub_field('nw_name', 'option');
					$nw_url 	= get_sub_field('nw_url', 'option');
					$nw_icon 	= get_sub_field('nw_icon', 'option');

					$nw_newtab 	= get_sub_field('nw_newtab', 'option');
					$nw_newtab 	= ( TRUE == $nw_newtab ) ? '_blank' : '_self';

					if ( ( filter_var( $nw_url, FILTER_VALIDATE_URL ) !== FALSE ) && isset( $nw_icon ) ) :

						echo "<a href='{$nw_url}' target='{$nw_newtab}'><i class='{$nw_icon}'></i></a>";

					endif;

				endwhile;

			endif;

		echo '</div>';

		$output = ob_get_clean();

	else :

		$output = __('Please install/activate the Advanced Custom Fields Pro plugin', 'cap');

	endif;

	return $output;

}
add_shortcode( 'cap-follow', 'cap_sc_follow' );

/**
 * Output the CAP podcast services icon block
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function cap_sc_listen( $atts, $content = null ) {

	$a = shortcode_atts( array(
		// 'address-title'	=> __('Address', 'cap'),
	), $atts );

	// implement shortcode attribute with no-value,
	// see helper function is_flag() above.
	$hide_title = is_flag( 'hide-title', $atts ) ? true : false;
	$has_title 	= ( false == $hide_title );	

	$has_acf 	= class_exists('ACF');

	if ( $has_acf ) :

		ob_start();

		echo '<div class="ib">';

			$llabel = get_field('cap_header_listen_label', 'option');

			if ( $llabel && ( '' !== $llabel ) && $has_title ) echo "<h2>{$llabel}</h2>";

			if ( have_rows('cap_header_listen_pods', 'option') ) :

				while ( have_rows('cap_header_listen_pods', 'option') ) : the_row();

					$pod_name 	= get_sub_field('pod_name', 'option');
					$pod_url 	= get_sub_field('pod_url', 'option');
					$pod_icon 	= get_sub_field('pod_icon', 'option');

					$pod_newtab 	= get_sub_field('pod_newtab', 'option');
					$pod_newtab 	= ( TRUE == $pod_newtab ) ? '_blank' : '_self';

					if ( ( filter_var( $pod_url, FILTER_VALIDATE_URL ) !== FALSE ) && isset( $pod_icon ) ) :

						echo "<a href='{$pod_url}' target='{$pod_newtab}'><i class='{$pod_icon}'></i></a>";

					endif;

				endwhile;

			endif;

		echo '</div>';

		$output = ob_get_clean();

	else :

		$output = __('Please install/activate the Advanced Custom Fields Pro plugin', 'cap');

	endif;

	return $output;

}
add_shortcode( 'cap-listen', 'cap_sc_listen' );

/**
 * Output the CAP podcast services icon block
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function cap_sc_subscribe_box( $atts, $content = null ) {

	$has_acf 		= class_exists('ACF');
	$formcontent 	= get_field('cap_general_single_inpost', 'option');

	if ( 
		$has_acf &&
		( $formcontent && ( '' !== $formcontent ) )
		) :

		ob_start();
		?>

			<div class="wrap--in-post wrap--in-post-1">

				<div class="in-post">

					<?php echo $formcontent; ?>

				</div>

			</div>

	<?php
	endif;

	return ob_get_clean();

}
add_shortcode( 'cap-subscribe-box', 'cap_sc_subscribe_box' );

/**
 * Output the CAP category search section (member directory feature)
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function cap_sc_category_search( $atts, $content = null ) {

	$has_acf 		= class_exists('ACF');

	if ( $has_acf ) :

		$members = get_posts( array(
			'post_type' 		=> 'nwmember',
			'posts_per_page' 	=> -1,
			'post_status' 		=> 'publish',
		) );

		$profcat_vals = [];

		$i = 0;
		if ( !empty($members) ) :

			foreach ( $members as $post ) : setup_postdata( $post );

				$profcats_Arr = get_the_terms( $post->ID, 'nw-profession-category' );
				
				if ( !empty( $profcats_Arr ) ) :
					
					$profcat = $profcats_Arr[0];

					$profcat_vals[$i]['name'] = $profcat->name;
					$profcat_vals[$i]['slug'] = $profcat->slug;
					$profcat_vals[$i]['count'] = $profcat->count;

				endif;

				$i++;
			endforeach;
			wp_reset_postdata();

		endif;

		/**
		 * SORT MULTIDIMENSIONAL ARRAY BY VALUE
		 * -------------------------------------
		 *
		 * @see https://stackoverflow.com/questions/2699086/how-to-sort-multi-dimensional-array-by-value
		 */
		usort($profcat_vals, function($a, $b) {

			// the SPACESHIP OPERATOR!!! :D
		    return $a['name'] <=> $b['name'];

		});

		$profcat_vals = array_map( 
							'unserialize', 
							array_unique( 
								array_map( 'serialize', $profcat_vals ) 
							) 
						);

		if ( !empty($profcat_vals) ) :

			ob_start();
			?>

				<div class="profcatsearch">

					<ul class="profcats flex-container">

						<?php
						foreach ( $profcat_vals as $profcat ) : 

							$profcat_url = get_site_url() . '/network-members/?_sft_nw-profession-category=' . $profcat['slug'];
							?>

							<li class="profcat flex-item"><a href="<?php echo $profcat_url; ?>"><?php echo $profcat['name']; ?> <span>(<?php echo $profcat['count']; ?>)</span></a></li>
						
						<?php
						endforeach; ?>

					</ul>

				</div>

			<?php
			return ob_get_clean();

		endif;

	endif;

}
add_shortcode( 'cap-category-search', 'cap_sc_category_search' );
