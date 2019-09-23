<?php
/* ================================================================================================
# DEFINE CPT - Podcast
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('cap_cpt_podcast') ) {

	function cap_cpt_podcast() {

		$labels = array(
			'name'                  => _x( 'Podcasts', 'Post Type General Name', 'cap' ),
			'singular_name'         => _x( 'Podcast', 'Post Type Singular Name', 'cap' ),
			'menu_name'             => __( 'Podcasts', 'cap' ),
			'name_admin_bar'        => __( 'Podcasts', 'cap' ),
			'archives'              => __( 'Podcasts Archives', 'cap' ),
			'attributes'            => __( 'Podcasts Attributes', 'cap' ),
			'parent_item_colon'     => __( 'Parent Podcast:', 'cap' ),
			'all_items'             => __( 'All Podcasts', 'cap' ),
			'add_new_item'          => __( 'Add New Podcast', 'cap' ),
			'add_new'               => __( 'Add New', 'cap' ),
			'new_item'              => __( 'New Podcast', 'cap' ),
			'edit_item'             => __( 'Edit Podcast', 'cap' ),
			'update_item'           => __( 'Update Podcast', 'cap' ),
			'view_item'             => __( 'View Podcast', 'cap' ),
			'view_items'            => __( 'View Podcasts', 'cap' ),
			'search_items'          => __( 'Search Podcast', 'cap' ),
			'not_found'             => __( 'Not found', 'cap' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cap' ),
			'featured_image'        => __( 'Featured Image', 'cap' ),
			'set_featured_image'    => __( 'Set featured image', 'cap' ),
			'remove_featured_image' => __( 'Remove featured image', 'cap' ),
			'use_featured_image'    => __( 'Use as featured image', 'cap' ),
			'insert_into_item'      => __( 'Insert into podcast', 'cap' ),
			'uploaded_to_this_item' => __( 'Uploaded to this podcast', 'cap' ),
			'items_list'            => __( 'Podcasts list', 'cap' ),
			'items_list_navigation' => __( 'Podcasts list navigation', 'cap' ),
			'filter_items_list'     => __( 'Filter podcasts list', 'cap' ),
			'name_admin_bar' 		=> _x( 'Podcast', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Podcast', 'cap' ),
			'description'           => __( 'CAP - Podcasts', 'cap' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields', 'excerpt', 'author' ),
			'taxonomies'            => array('category', 'post_tag', 'country'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-microphone',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('podcasts', 'cap'), 
									   ),
			'yarpp_support' 		=> true,
		);

		register_post_type( 'podcast', $args );

	}
	add_action( 'init', 'cap_cpt_podcast', 0 );

}

/**
 * Podcast update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function cap_cpt_podcast_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'podcast';
	$post_type_object = get_post_type_object( $post_type );

	$messages['podcast'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Podcast updated.', 'cap' ),
		2  => __( 'Custom field updated.', 'cap' ),
		3  => __( 'Custom field deleted.', 'cap' ),
		4  => __( 'Podcast updated.', 'cap' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Podcast restored to revision from %s', 'cap' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Podcast published.', 'cap' ),
		7  => __( 'Podcast saved.', 'cap' ),
		8  => __( 'Podcast submitted.', 'cap' ),
		9  => sprintf(
			__( 'Podcast scheduled for: <strong>%1$s</strong>.', 'cap' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'cap' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Podcast draft updated.', 'cap' )
	);

	if ( $post_type_object->publicly_queryable && 'podcast' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View podcast', 'cap' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview podcast', 'cap' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'cap_cpt_podcast_update_messages' );

/* ================================================================================================
# Podcast pagination
================================================================================================ */

function podcast_pagination( $custom_query ) {

    $total_pages = $custom_query->max_num_pages;
    $big = 999999999; // need an unlikely integer

    if ($total_pages > 1) :

        $current_page = max(1, get_query_var('paged'));

        echo paginate_links(array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    
    endif;
}

/* ================================================================================================
# DEFINE CPT - Analysis
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('cap_cpt_analysis') ) {

	function cap_cpt_analysis() {

		$labels = array(
			'name'                  => _x( 'Analysis', 'Post Type General Name', 'cap' ),
			'singular_name'         => _x( 'Analysis', 'Post Type Singular Name', 'cap' ),
			'menu_name'             => __( 'Analysis', 'cap' ),
			'name_admin_bar'        => __( 'Analysis', 'cap' ),
			'archives'              => __( 'Analysis Archives', 'cap' ),
			'attributes'            => __( 'Analysis Attributes', 'cap' ),
			'parent_item_colon'     => __( 'Parent Analysis:', 'cap' ),
			'all_items'             => __( 'All Analysis', 'cap' ),
			'add_new_item'          => __( 'Add New Analysis', 'cap' ),
			'add_new'               => __( 'Add New', 'cap' ),
			'new_item'              => __( 'New Analysis', 'cap' ),
			'edit_item'             => __( 'Edit Analysis', 'cap' ),
			'update_item'           => __( 'Update Analysis', 'cap' ),
			'view_item'             => __( 'View Analysis', 'cap' ),
			'view_items'            => __( 'View Analysis', 'cap' ),
			'search_items'          => __( 'Search Analysis', 'cap' ),
			'not_found'             => __( 'Not found', 'cap' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cap' ),
			'featured_image'        => __( 'Featured Image', 'cap' ),
			'set_featured_image'    => __( 'Set featured image', 'cap' ),
			'remove_featured_image' => __( 'Remove featured image', 'cap' ),
			'use_featured_image'    => __( 'Use as featured image', 'cap' ),
			'insert_into_item'      => __( 'Insert into analysis', 'cap' ),
			'uploaded_to_this_item' => __( 'Uploaded to this analysis', 'cap' ),
			'items_list'            => __( 'Analysis list', 'cap' ),
			'items_list_navigation' => __( 'Analysis list navigation', 'cap' ),
			'filter_items_list'     => __( 'Filter analysis list', 'cap' ),
			'name_admin_bar' 		=> _x( 'Analysis', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Analysis', 'cap' ),
			'description'           => __( 'Diverse voices. Unique Insights.', 'cap' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields', 'excerpt', 'author' ),
			'taxonomies'            => array('category', 'post_tag', 'country'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 21,
			'menu_icon'             => 'dashicons-chart-bar',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('analysis', 'cap'), 
									   ),
			'yarpp_support' 		=> true,			
		);

		register_post_type( 'analysis', $args );

	}
	add_action( 'init', 'cap_cpt_analysis', 0 );

}

/**
 * Analysis update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function cap_cpt_analysis_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'analysis';
	$post_type_object = get_post_type_object( $post_type );

	$messages['analysis'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Analysis updated.', 'cap' ),
		2  => __( 'Custom field updated.', 'cap' ),
		3  => __( 'Custom field deleted.', 'cap' ),
		4  => __( 'Analysis updated.', 'cap' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Analysis restored to revision from %s', 'cap' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Analysis published.', 'cap' ),
		7  => __( 'Analysis saved.', 'cap' ),
		8  => __( 'Analysis submitted.', 'cap' ),
		9  => sprintf(
			__( 'Analysis scheduled for: <strong>%1$s</strong>.', 'cap' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'cap' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Analysis draft updated.', 'cap' )
	);

	if ( $post_type_object->publicly_queryable && 'analysis' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View analysis', 'cap' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview analysis', 'cap' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'cap_cpt_analysis_update_messages' );

/* ================================================================================================
# DEFINE CPT - Student Posts
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('cap_cpt_studentpost') ) {

	function cap_cpt_studentpost() {

		$labels = array(
			'name'                  => _x( 'Student Posts', 'Post Type General Name', 'cap' ),
			'singular_name'         => _x( 'Student Post', 'Post Type Singular Name', 'cap' ),
			'menu_name'             => __( 'Student Posts', 'cap' ),
			'name_admin_bar'        => __( 'Student Posts', 'cap' ),
			'archives'              => __( 'Student Posts Archives', 'cap' ),
			'attributes'            => __( 'Student Posts Attributes', 'cap' ),
			'parent_item_colon'     => __( 'Parent Student Post:', 'cap' ),
			'all_items'             => __( 'All Student Posts', 'cap' ),
			'add_new_item'          => __( 'Add New Student Post', 'cap' ),
			'add_new'               => __( 'Add New', 'cap' ),
			'new_item'              => __( 'New Student Post', 'cap' ),
			'edit_item'             => __( 'Edit Student Post', 'cap' ),
			'update_item'           => __( 'Update Student Post', 'cap' ),
			'view_item'             => __( 'View Student Post', 'cap' ),
			'view_items'            => __( 'View Student Posts', 'cap' ),
			'search_items'          => __( 'Search Student Post', 'cap' ),
			'not_found'             => __( 'Not found', 'cap' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cap' ),
			'featured_image'        => __( 'Featured Image', 'cap' ),
			'set_featured_image'    => __( 'Set featured image', 'cap' ),
			'remove_featured_image' => __( 'Remove featured image', 'cap' ),
			'use_featured_image'    => __( 'Use as featured image', 'cap' ),
			'insert_into_item'      => __( 'Insert into student post', 'cap' ),
			'uploaded_to_this_item' => __( 'Uploaded to this student post', 'cap' ),
			'items_list'            => __( 'Student Posts list', 'cap' ),
			'items_list_navigation' => __( 'Student Posts list navigation', 'cap' ),
			'filter_items_list'     => __( 'Filter student posts list', 'cap' ),
			'name_admin_bar' 		=> _x( 'Student Post', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Student Post', 'cap' ),
			'description'           => __( 'CAP - Student Posts', 'cap' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields', 'excerpt', 'author' ),
			'taxonomies'            => array('category', 'post_tag', 'country'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-welcome-learn-more',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('student-xchange', 'cap'), 
									   ),
			'yarpp_support' 		=> true,			
		);

		register_post_type( 'studentpost', $args );

	}
	add_action( 'init', 'cap_cpt_studentpost', 0 );

}

/**
 * Student Post update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function cap_cpt_studentpost_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'studentpost';
	$post_type_object = get_post_type_object( $post_type );

	$messages['studentpost'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Student Post updated.', 'cap' ),
		2  => __( 'Custom field updated.', 'cap' ),
		3  => __( 'Custom field deleted.', 'cap' ),
		4  => __( 'Student Post updated.', 'cap' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Student Post restored to revision from %s', 'cap' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Student Post published.', 'cap' ),
		7  => __( 'Student Post saved.', 'cap' ),
		8  => __( 'Student Post submitted.', 'cap' ),
		9  => sprintf(
			__( 'Student Post scheduled for: <strong>%1$s</strong>.', 'cap' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'cap' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Student Post draft updated.', 'cap' )
	);

	if ( $post_type_object->publicly_queryable && 'studentpost' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View student post', 'cap' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview student post', 'cap' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'cap_cpt_studentpost_update_messages' );

/* ================================================================================================
# DEFINE CPT - CAP Network Members
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('cap_cpt_nwmembers') ) {

	function cap_cpt_nwmembers() {

		$labels = array(
			'name'                  => _x( 'CAP Network Members', 'Post Type General Name', 'cap' ),
			'singular_name'         => _x( 'CAP Network Member', 'Post Type Singular Name', 'cap' ),
			'menu_name'             => __( 'CAP Network Members', 'cap' ),
			'name_admin_bar'        => __( 'CAP Network Members', 'cap' ),
			'archives'              => __( 'CAP Network Members Archives', 'cap' ),
			'attributes'            => __( 'CAP Network Members Attributes', 'cap' ),
			'parent_item_colon'     => __( 'Parent CAP Network Member:', 'cap' ),
			'all_items'             => __( 'All CAP Network Members', 'cap' ),
			'add_new_item'          => __( 'Add New CAP Network Member', 'cap' ),
			'add_new'               => __( 'Add New', 'cap' ),
			'new_item'              => __( 'New CAP Network Member', 'cap' ),
			'edit_item'             => __( 'Edit CAP Network Member', 'cap' ),
			'update_item'           => __( 'Update CAP Network Member', 'cap' ),
			'view_item'             => __( 'View CAP Network Member', 'cap' ),
			'view_items'            => __( 'View CAP Network Members', 'cap' ),
			'search_items'          => __( 'Search CAP Network Member', 'cap' ),
			'not_found'             => __( 'Not found', 'cap' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cap' ),
			'featured_image'        => __( 'Featured Image', 'cap' ),
			'set_featured_image'    => __( 'Set featured image', 'cap' ),
			'remove_featured_image' => __( 'Remove featured image', 'cap' ),
			'use_featured_image'    => __( 'Use as featured image', 'cap' ),
			'insert_into_item'      => __( 'Insert into CAP network member', 'cap' ),
			'uploaded_to_this_item' => __( 'Uploaded to this CAP network member', 'cap' ),
			'items_list'            => __( 'CAP Network Members list', 'cap' ),
			'items_list_navigation' => __( 'CAP Network Members list navigation', 'cap' ),
			'filter_items_list'     => __( 'Filter CAP network members list', 'cap' ),
			'name_admin_bar' 		=> _x( 'CAP Network Member', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'CAP Network Member', 'cap' ),
			'description'           => __( 'CAP - CAP Network Members', 'cap' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt', 'author' ),
			'taxonomies'            => array('nw-profession-category', 'nw-country', 'nw-lang'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('cap-network-members', 'cap'), 
									   ),
		);

		register_post_type( 'nwmember', $args );

	}
	add_action( 'init', 'cap_cpt_nwmembers', 0 );

}

/**
 * CAP Network Member update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function cap_cpt_nwmembers_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'nwmember';
	$post_type_object = get_post_type_object( $post_type );

	$messages['nwmember'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'CAP Network Member updated.', 'cap' ),
		2  => __( 'Custom field updated.', 'cap' ),
		3  => __( 'Custom field deleted.', 'cap' ),
		4  => __( 'CAP Network Member updated.', 'cap' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'CAP Network Member restored to revision from %s', 'cap' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'CAP Network Member published.', 'cap' ),
		7  => __( 'CAP Network Member saved.', 'cap' ),
		8  => __( 'CAP Network Member submitted.', 'cap' ),
		9  => sprintf(
			__( 'CAP Network Member scheduled for: <strong>%1$s</strong>.', 'cap' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'cap' ), strtotime( $post->post_date ) )
		),
		10 => __( 'CAP Network Member draft updated.', 'cap' )
	);

	if ( $post_type_object->publicly_queryable && 'nwmember' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View CAP network member', 'cap' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview CAP network member', 'cap' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'cap_cpt_nwmembers_update_messages' );

/* ================================================================================================
# Create custom taxonomies
================================================================================================ */

function create_countries_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Countries', 'taxonomy general name', 'cap' ),
		'singular_name'     => _x( 'Country', 'taxonomy singular name', 'cap' ),
		'search_items'      => __( 'Search Countries', 'cap' ),
		'all_items'         => __( 'All Countries', 'cap' ),
		'parent_item'       => __( 'Parent Country', 'cap' ),
		'parent_item_colon' => __( 'Parent Country:', 'cap' ),
		'edit_item'         => __( 'Edit Country', 'cap' ),
		'update_item'       => __( 'Update Country', 'cap' ),
		'add_new_item'      => __( 'Add New Country', 'cap' ),
		'new_item_name'     => __( 'New Country Name', 'cap' ),
		'menu_name'         => __( 'Countries', 'cap' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'country' ),
		'show_in_rest' 		=> true,
		'public' 			=> true,
	);

	register_taxonomy( 'country', array( 'post', 'analysis', 'podcast', 'studentpost' ), $args );

}

add_action( 'init', 'create_countries_taxonomies', 0 );

function create_nw_profcat_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Profession Categories', 'taxonomy general name', 'cap' ),
		'singular_name'              => _x( 'Profession Category', 'taxonomy singular name', 'cap' ),
		'search_items'               => __( 'Search Profession Categories', 'cap' ),
		'popular_items'              => __( 'Popular Profession Categories', 'cap' ),
		'all_items'                  => __( 'All Profession Categories', 'cap' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Profession Category', 'cap' ),
		'update_item'                => __( 'Update Profession Category', 'cap' ),
		'add_new_item'               => __( 'Add New Profession Category', 'cap' ),
		'new_item_name'              => __( 'New Profession Category Name', 'cap' ),
		'separate_items_with_commas' => __( 'Separate profession categories with commas', 'cap' ),
		'add_or_remove_items'        => __( 'Add or remove profession categories', 'cap' ),
		'choose_from_most_used'      => __( 'Choose from the most used profession categories', 'cap' ),
		'not_found'                  => __( 'No profession categories found.', 'cap' ),
		'menu_name'                  => __( 'Profession Categories', 'cap' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'member-profession-category' ),
		'show_in_rest'	 		=> true,
		'has_archive'           => true,		
		'public'				=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
	);

	register_taxonomy( 'nw-profession-category', array( 'nwmember' ), $args );

}

add_action( 'init', 'create_nw_profcat_taxonomies', 0 );
 
function create_nw_country_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Countries (of Residence)', 'taxonomy general name', 'cap' ),
		'singular_name'              => _x( 'Country (of Residence)', 'taxonomy singular name', 'cap' ),
		'search_items'               => __( 'Search Countries', 'cap' ),
		'popular_items'              => __( 'Popular Countries', 'cap' ),
		'all_items'                  => __( 'All Countries', 'cap' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Country', 'cap' ),
		'update_item'                => __( 'Update Country', 'cap' ),
		'add_new_item'               => __( 'Add New Country', 'cap' ),
		'new_item_name'              => __( 'New Country Name', 'cap' ),
		'separate_items_with_commas' => __( 'Separate Countries with commas', 'cap' ),
		'add_or_remove_items'        => __( 'Add or remove Countries', 'cap' ),
		'choose_from_most_used'      => __( 'Choose from the most used Countries', 'cap' ),
		'not_found'                  => __( 'No Countries found.', 'cap' ),
		'menu_name'                  => __( 'Countries (of Residence)', 'cap' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'member-country' ),
		'show_in_rest'	 		=> true,
		'has_archive'           => true,		
		'public'				=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,		
	);

	register_taxonomy( 'nw-country', array( 'nwmember' ), $args );

}

add_action( 'init', 'create_nw_country_taxonomies', 0 );

function create_nw_lang_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Languages', 'taxonomy general name', 'cap' ),
		'singular_name'              => _x( 'Language', 'taxonomy singular name', 'cap' ),
		'search_items'               => __( 'Search Languages', 'cap' ),
		'popular_items'              => __( 'Popular Languages', 'cap' ),
		'all_items'                  => __( 'All Languages', 'cap' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Language', 'cap' ),
		'update_item'                => __( 'Update Language', 'cap' ),
		'add_new_item'               => __( 'Add New Language', 'cap' ),
		'new_item_name'              => __( 'New Language Name', 'cap' ),
		'separate_items_with_commas' => __( 'Separate languages with commas', 'cap' ),
		'add_or_remove_items'        => __( 'Add or remove languages', 'cap' ),
		'choose_from_most_used'      => __( 'Choose from the most used languages', 'cap' ),
		'not_found'                  => __( 'No languages found.', 'cap' ),
		'menu_name'                  => __( 'Languages', 'cap' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'member-language' ),
		'show_in_rest'	 		=> true,
		'has_archive'           => true,		
		'public'				=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
	);

	register_taxonomy( 'nw-lang', array( 'nwmember' ), $args );

}

add_action( 'init', 'create_nw_lang_taxonomies', 0 );

/* ================================================================================================
# DEFINE CPT - Migrated Posts
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('cap_cpt_temptype') ) {

	function cap_cpt_temptype() {

		$labels = array(
			'name'                  => _x( 'Migrated Posts', 'Post Type General Name', 'cap' ),
			'singular_name'         => _x( 'Migrated Post', 'Post Type Singular Name', 'cap' ),
			'menu_name'             => __( 'Migrated Posts', 'cap' ),
			'name_admin_bar'        => __( 'Migrated Posts', 'cap' ),
			'archives'              => __( 'Migrated Posts Archives', 'cap' ),
			'attributes'            => __( 'Migrated Posts Attributes', 'cap' ),
			'parent_item_colon'     => __( 'Parent Migrated Post:', 'cap' ),
			'all_items'             => __( 'All Migrated Posts', 'cap' ),
			'add_new_item'          => __( 'Add New Migrated Post', 'cap' ),
			'add_new'               => __( 'Add New', 'cap' ),
			'new_item'              => __( 'New Migrated Post', 'cap' ),
			'edit_item'             => __( 'Edit Migrated Post', 'cap' ),
			'update_item'           => __( 'Update Migrated Post', 'cap' ),
			'view_item'             => __( 'View Migrated Post', 'cap' ),
			'view_items'            => __( 'View Migrated Posts', 'cap' ),
			'search_items'          => __( 'Search Migrated Post', 'cap' ),
			'not_found'             => __( 'Not found', 'cap' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cap' ),
			'featured_image'        => __( 'Featured Image', 'cap' ),
			'set_featured_image'    => __( 'Set featured image', 'cap' ),
			'remove_featured_image' => __( 'Remove featured image', 'cap' ),
			'use_featured_image'    => __( 'Use as featured image', 'cap' ),
			'insert_into_item'      => __( 'Insert into migrated post', 'cap' ),
			'uploaded_to_this_item' => __( 'Uploaded to this migrated post', 'cap' ),
			'items_list'            => __( 'Migrated Posts list', 'cap' ),
			'items_list_navigation' => __( 'Migrated Posts list navigation', 'cap' ),
			'filter_items_list'     => __( 'Filter migrated posts list', 'cap' ),
			'name_admin_bar' 		=> _x( 'Migrated Post', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Migrated Post', 'cap' ),
			'description'           => __( 'CAP - Migrated Posts', 'cap' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt', 'author' ),
			'taxonomies'            => array('category', 'post_tag'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('migrated-posts', 'cap'), 
									   ),
		);

		register_post_type( 'temptype', $args );

	}
	add_action( 'init', 'cap_cpt_temptype', 0 );

}

/**
 * Migrated Post update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function cap_cpt_temptype_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'temptype';
	$post_type_object = get_post_type_object( $post_type );

	$messages['temptype'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Migrated Post updated.', 'cap' ),
		2  => __( 'Custom field updated.', 'cap' ),
		3  => __( 'Custom field deleted.', 'cap' ),
		4  => __( 'Migrated Post updated.', 'cap' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Migrated Post restored to revision from %s', 'cap' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Migrated Post published.', 'cap' ),
		7  => __( 'Migrated Post saved.', 'cap' ),
		8  => __( 'Migrated Post submitted.', 'cap' ),
		9  => sprintf(
			__( 'Migrated Post scheduled for: <strong>%1$s</strong>.', 'cap' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'cap' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Migrated Post draft updated.', 'cap' )
	);

	if ( $post_type_object->publicly_queryable && 'temptype' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View migrated post', 'cap' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview migrated post', 'cap' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'cap_cpt_temptype_update_messages' );