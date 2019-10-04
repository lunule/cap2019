<?php
/**
 * cap2019 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cap2019
 */

if ( ! function_exists( 'cap_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cap_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on cap2019, use a find and replace
		 * to change 'cap' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'cap', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Header Navigation 1', 'cap' ),
			'menu-2' => esc_html__( 'Header Navigation 2', 'cap' ),			
			'menu-3' => esc_html__( 'Footer 1', 'cap' ),
			'menu-4' => esc_html__( 'Footer 2', 'cap' ),						
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'cap_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 89,
			'width'       => 260,
			'flex-width'  => false,
			'flex-height' => true,
		) );

		/* Gutenberg related
		======================================================================================== */

		// Adding support for core block visual styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		//add_theme_support( 'responsive-embeds' );

		// Add support for Editor Styles
		add_editor_style( 'assets/css/editor-style.css' );		

		// Add support for Wide Images
		add_theme_support( 'align-wide' );

		// Add support for Font Styles
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name'      => __( 'small', 'cap' ),
				'shortName' => __( 'S', 'cap' ),
				'size'      => 12,
				'slug'      => 'small'
			),
			array(
				'name'      => __( 'regular', 'cap' ),
				'shortName' => __( 'M', 'cap' ),
				'size'      => 16,
				'slug'      => 'regular'
			),
			array(
				'name'      => __( 'large', 'cap' ),
				'shortName' => __( 'L', 'cap' ),
				'size'      => 20,
				'slug'      => 'large'
			),
			array(
				'name'      => __( 'larger', 'cap' ),
				'shortName' => __( 'XL', 'cap' ),
				'size'      => 24,
				'slug'      => 'larger'
			)

		) );

		// Disable Custom Colors
		//add_theme_support( 'disable-custom-colors' );

		// Add support for Editor Color Palette
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => __( 'Light Grey', 'cap' ),
				'slug'  => 'light-gray',
				'color'	=> '#f5f5f5',
			),
			array(
				'name'  => __( 'Medium Grey', 'cap' ),
				'slug'  => 'medium-gray',
				'color' => '#9E9E9E',
			),
			array(
				'name'  => __( 'Dark Grey', 'cap' ),
				'slug'  => 'dark-gray',
				'color' => '#424242',
			   ),
		) );

	}
endif;
add_action( 'after_setup_theme', 'cap_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cap_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'cap_content_width', 1260 );
}
add_action( 'after_setup_theme', 'cap_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cap_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Default Sidebar', 'cap' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Single Template Sidebar', 'cap' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Posts Page Sidebar', 'cap' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );		

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Analysis', 'cap' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Podcast', 'cap' ),
		'id'            => 'sidebar-5',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Student Post', 'cap' ),
		'id'            => 'sidebar-6',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Author Archive', 'cap' ),
		'id'            => 'sidebar-7',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Category Archive', 'cap' ),
		'id'            => 'sidebar-8',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Country Archive', 'cap' ),
		'id'            => 'sidebar-9',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Archive Sidebar - Date Archive', 'cap' ),
		'id'            => 'sidebar-10',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );								

	register_sidebar( array(
		'name'          => esc_html__( 'Member Search Results Sidebar', 'cap' ),
		'id'            => 'sidebar-11',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Member Single Template Sidebar', 'cap' ),
		'id'            => 'sidebar-12',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );		

	register_sidebar( array(
		'name'          => esc_html__( 'Member Directory Sidebar', 'cap' ),
		'id'            => 'sidebar-13',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	

	register_sidebar( array(
		'name'          => esc_html__( 'Membership Product Sidebar', 'cap' ),
		'id'            => 'sidebar-14',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );		

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 1', 'cap' ),
		'id'            => 'footbar-1',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 2', 'cap' ),
		'id'            => 'footbar-2',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 3', 'cap' ),
		'id'            => 'footbar-3',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 4', 'cap' ),
		'id'            => 'footbar-4',
		'description'   => esc_html__( 'Add widgets here.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Ad Slot - Main Banner', 'cap' ),
		'id'            => 'adslot-1',
		'description'   => esc_html__( 'Exlusively for AdRotate widget.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Ad Slot - Home, Full Width Ad_01', 'cap' ),
		'id'            => 'adslot-2',
		'description'   => esc_html__( 'Exlusively for AdRotate widget.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	

	register_sidebar( array(
		'name'          => esc_html__( 'Ad Slot - Home, Podcast Block, Vertical', 'cap' ),
		'id'            => 'adslot-3',
		'description'   => esc_html__( 'Exlusively for AdRotate widget.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</add_image>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Ad Slot - Home, Full Width Ad_02', 'cap' ),
		'id'            => 'adslot-4',
		'description'   => esc_html__( 'Exlusively for AdRotate widget.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Ad Slot - Blog Page In-Between Ad', 'cap' ),
		'id'            => 'adslot-5',
		'description'   => esc_html__( 'Exlusively for AdRotate widget.', 'cap' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	

}
add_action( 'widgets_init', 'cap_widgets_init' );

/* ------------------------------------------------------------------------------------------------
# Enqueues
------------------------------------------------------------------------------------------------ */

/**
 * Enqueue scripts and styles.
 */
function cap_scripts() {

	/* ============================================================================================
	# Styles
	============================================================================================ */

	wp_enqueue_style('dashicons');

	wp_enqueue_style( 'cap-googlefonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|PT+Sans+Narrow:400,700|Fira+Mono:400,500,700|PT+Serif:400,400i,700,700i|Roboto+Condensed&display=swap', null, false, 'all' );

	wp_enqueue_style( 'cap-slick', get_template_directory_uri() . '/css/slick.css', null,  false, 'all' );

	wp_enqueue_style( 'cap-slick-theme', get_template_directory_uri() . '/css/slick-theme.css', null,  false, 'all' );

	wp_enqueue_style( 'cap-colorbox', get_template_directory_uri() . '/css/colorbox.css', null,  false, 'all' );

	wp_enqueue_style( 'cap-magnipop', get_template_directory_uri() . '/css/magnific-popup.css', null,  false, 'all' );	

	wp_enqueue_style( 'cap-jssocials', get_template_directory_uri() . '/css/jssocials-theme-minima.css', null, false, 'all' );

	wp_enqueue_style( 'cap-selectize', get_template_directory_uri() . '/css/selectize.default.css', null, false, 'all' );		

	wp_enqueue_style( 
		'cap-style', 
		get_stylesheet_uri(), 
		array( 
			'cap-slick', 
			'cap-slick-theme',
			'cap-colorbox',
			'cap-magnipop',
			'cap-jssocials',
			'cap-selectize',
		),
		false,
		'all' 
	);

	/* ============================================================================================
	# Scripts
	============================================================================================ */

	wp_enqueue_script( 'cap-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'cap-fontawesome', 'https://kit.fontawesome.com/65f3d19268.js', null, false, true );

	wp_enqueue_script( 'cap-slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'cap-colorbox', get_template_directory_uri() . '/js/jquery.colorbox-min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'cap-magnipop', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), false, true );	

	wp_enqueue_script( 'cap-jssocials', get_template_directory_uri() . '/js/jssocials.min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'cap-nicefileinput', get_template_directory_uri() . '/js/jquery.nicefileinput.min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'cap-selectize', get_template_directory_uri() . '/js/selectize.min.js', array( 'jquery' ), false, true );	

	wp_enqueue_script( 'cap-matchheight', get_template_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'cap-stickykit', get_template_directory_uri() . '/js/jquery.sticky-kit.min.js', array( 'jquery' ), false, true );					

	wp_register_script( 
		'cap-global', 
		get_template_directory_uri() . '/js/global.js', 
		array( 
			'jquery', 
			'cap-fontawesome',
			'cap-slick',
			'cap-colorbox',
			'cap-magnipop',			
			'cap-jssocials',
			'cap-nicefileinput',
			'cap-selectize',
			'cap-matchheight',
			'cap-stickykit',
		), 
		false, 
		true 
	);	

	global $post;
	setup_postdata($post);
 
	$pt_Arr = array(
				'post',
				'page',
				'attachment',
				'podcast',
				'analysis',
				'studentpost',
				'nwmember',
				'memberpressproduct'
			  );

	$is_paid_subscriber = current_user_can( 'mepr-active', 'rule: 7000' );

	if ( is_singular( $pt_Arr ) ) :

		$id 			= $post->ID;

		$content 		= has_excerpt( $id ) ? get_the_excerpt( $id ) : get_the_content( $id );
		$content 		= wp_trim_words( $content, $num_words = 50, ' ...' );

		$thumbnail_ID 	= get_post_thumbnail_id( $id );
		$thumbnail_Obj 	= wp_get_attachment_image_src( $thumbnail_ID, 'medium_large', false );
		$thumbnail 		= $thumbnail_Obj[0];

		$via 			= get_field('cap_general_via', 'option');
		$via 			= ( $via && ( '' !== $via ) ) ? $via : 'eolander';

		$translation_array = array(
			'permalink' 		=> get_permalink(),
			'content' 			=> $content,
			'title' 			=> get_the_title(),
			'thumbnail' 		=> $thumbnail,
			'via' 				=> $via,
			'is_singular' 		=> true,
			'is_single_member' 	=> ( is_singular( 'nwmember' ) ), 
			'inpostSubscribe' 	=> get_field('cap_general_single_inpost', 'option')
									? get_field('cap_general_single_inpost', 'option')
									: false,
			'showBox' 			=> get_field('cap_single_disable_email_box', $id),
			'is_paid_subscriber'=> $is_paid_subscriber,
			'templatedir' 		=> get_template_directory_uri(),
		);
		
		wp_localize_script( 'cap-global', 'cap', $translation_array );

	elseif ( is_home() || is_archive() || is_search() ) :

		$translation_array = array(
			'is_home' 				=> is_home(),
			'is_archive' 			=> is_archive(),
			'is_search' 			=> is_search(),
			'is_paid_subscriber'	=> $is_paid_subscriber,
			'is_nwmember_archive' 	=> is_post_type_archive( 'nwmember' ),
		);
		
		wp_localize_script( 'cap-global', 'cap', $translation_array );		

	else :

		$translation_array = array(
			'is_404' 				=> is_404(),
			'is_paid_subscriber'	=> $is_paid_subscriber,
		);
		
		wp_localize_script( 'cap-global', 'cap', $translation_array );		

	endif;

	wp_enqueue_script( 'cap-global' );	

}
add_action( 'wp_enqueue_scripts', 'cap_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function cap_admin_scripts() {

	/* ============================================================================================
	# Styles
	============================================================================================ */

	wp_enqueue_style( 
		'cap-admin-style', 
		get_template_directory_uri() . '/css/admin-style.css', 
		array(),
		false,
		'all' 
	);

	/* ============================================================================================
	# Scripts
	============================================================================================ */

	wp_register_script( 
		'cap-global-admin', 
		get_template_directory_uri() . '/js/global-admin.js', 
		array( 
			'jquery', 
		), 
		false, 
		true 
	);	

	$translation_array = array(
		'siteurl' 			=> get_site_url(),
	);
	
	wp_localize_script( 'cap-global-admin', 'cap', $translation_array );

	wp_enqueue_script( 'cap-global-admin' );	


}
add_action( 'admin_enqueue_scripts', 'cap_admin_scripts' );

/* ------------------------------------------------------------------------------------------------
# Enqueues - Gutenberg
------------------------------------------------------------------------------------------------ */

/**
 * Theme Fonts URL
 *
 */
function cap_theme_fonts_url() {

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Noto Serif, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$notoserif = esc_html_x( 'on', 'Noto Serif font: on or off', 'cap' );

	$font_families = apply_filters( 
						'cap_theme_fonts', 
						array( 
							'Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i',
							'PT+Sans+Narrow:400,700',
							'Fira+Mono:400,500,700',
							'PT+Serif:400,400i,700,700i',
							'Roboto+Condensed'
						) 
					 );

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);

	$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	return esc_url_raw( $fonts_url ) . '&display=swap';

}

/**
 * Gutenberg scripts and styles
 *
 */
function cap_gutenberg_scripts() {

	/* ============================================================================================
	# Styles
	============================================================================================ */

	wp_enqueue_style( 'cap-fonts', cap_theme_fonts_url() );
	wp_enqueue_style( 'cap-editor', get_template_directory_uri() . '/css/editor-style.css', array(), false, 'all' );

	/* ============================================================================================
	# Scripts
	============================================================================================ */

}
add_action( 'enqueue_block_editor_assets', 'cap_gutenberg_scripts' );

/* ------------------------------------------------------------------------------------------------
# Requires
------------------------------------------------------------------------------------------------ */

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom post types.
 */
require get_template_directory() . '/inc/custom-post-types.php';

/**
 * Custom shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Custom shortcodes.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Require Mobile Detect
 */
require get_template_directory() . '/inc/Mobile_Detect.php';
 
/* ------------------------------------------------------------------------------------------------
# Mobile Detect
------------------------------------------------------------------------------------------------ */

$detect = new Mobile_Detect;

// Any mobile device (phones or tablets).
// if ( $detect->isMobile() ) {}

// Any tablet device.
// if( $detect->isTablet() ){}

// Exclude tablets.
// if( $detect->isMobile() && !$detect->isTablet() ){}

/* ------------------------------------------------------------------------------------------------
# Images
------------------------------------------------------------------------------------------------ */

/* Deactivate WP's default image optimization behaviour */
add_filter( 'jpeg_quality', function( $arg ) { return 100; } );

/* Custom image sizes */
add_image_size( 'cap-logo', 260, 9999, false );
add_image_size( 'cap-home-analysis-sticky', 720, 396, array( 'center', 'center' ) );
add_image_size( 'cap-home-analysis-others', 540, 297, array( 'center', 'center' ) );
add_image_size( 'cap-home-memslider', 200, 200, array( 'center', 'center' ) );
add_image_size( 'cap-single-thumbnail', 1000, 650, array( 'center', 'center' ) );
add_image_size( 'yarpp-thumbnail', 360, 201, true );
add_image_size( 'libsyn-thumbnail', 300, 263, true );
add_image_size( 'member-thumbnail', 640, 9999, false );

// Register custom image sizes for backend use
add_filter( 'image_size_names_choose', 'cap_imgsize_names' );
function cap_imgsize_names( $sizes ) {

	return array_merge( $sizes, array(
		'cap-logo' 					=> __( 'Logo Size (w=260px)' ),		
		'cap-home-analysis-sticky' 	=> __( 'Home Page Featured Analysis Image (720x396)' ),
		'cap-home-analysis-others' 	=> __( 'Home Page You Might Like Block Image (540x297)' ),
		'cap-home-memslider' 		=> __( 'Home Page Member Slider Image (200x200)' ),
		'cap-single-thumbnail' 		=> __( 'Single Template Featured Image (800x520)' ),
		'yarpp-thumbnail' 			=> __( 'Related Posts Thumbnail Size (360x201)' ),
		'libsyn-thumbnail' 			=> __( 'Libsyn Player Thumbnail Size (300x263)' ),
		'member-thumbnail' 			=> __( 'Member Thumbnail Size (640px width, auto height)' ),
	) );

}

define('ALLOW_UNFILTERED_UPLOADS', true);

/* ------------------------------------------------------------------------------------------------
# Dev helpers
------------------------------------------------------------------------------------------------ */

/**
 * Enable page excerpts
 */
add_post_type_support( 'page', 'excerpt' );

/**
 * Enable shortcodes in text widgets
 */
add_filter('widget_text','do_shortcode');

/**
 * Custom body classes
 */
function cap_custom_body_classes( $classes ) {
	
	global $post; 

	$parent_ID = wp_get_post_parent_id( get_the_ID() );	

	if ( is_singular() ) :

		$classes[] = str_replace(
						' ', 
						'-', 
						strtolower( 
							strip_tags( 
								get_the_title() 
							) 
						)
					);

	endif;

	if ( $parent_ID && 
		 ( 'page-resource-center.php' == get_post_meta( $parent_ID, '_wp_page_template', true ) )
		) :

		$classes[] = 'resource-page';		 

	endif;

	$is_search_nw = ( is_search() && !get_search_query() );

	if ( is_search() ) 		$classes[] = 'archive';
	if ( $is_search_nw ) 	$classes[] = 'search-nw';

	if ( 
			!current_user_can( 'mepr-active', 'rule: 7000' ) &&
			( 
				is_page_template('page-nw.php') 		||
				$is_search_nw							||
				is_post_type_archive('nwmember')
			)
	   )
		$classes[] = 'freeze-overflow';

	return $classes;

}
add_filter( 'body_class', 'cap_custom_body_classes' );

/**
 * Custom post classes
 */
function cap_custom_post_classes( $classes ) {
	
	global $post;

	if ( is_singular() ) :

		$classes[] = 'chocolat-parent';

	endif;
	
	return $classes;

}
add_filter( 'post_class', 'cap_custom_post_classes' );

/**
 * Custom title tags
 */
function cap_return_member_search_query() {

	/* 	The only way to get the whole search query string is using parse_str() with the 
		$_SERVER[] array.

		Based on the Search & Filter plugin documentation there's another way:

		    global $searchandfilter;

			$s = $searchandfilter->get(6614)->current_query();
			$s = $s->get_search_term();

		But, as it came out, THIS ONLY WORKS WITH THE TEXT (KEYWORD) SEARCH FIELD.
		If the user submits the search form with a query based on other form input types (select, multiselect), $searchandfilter doesn't return shit.
	*/


	$queries = array();
	parse_str($_SERVER['QUERY_STRING'], $queries);

	/* 	At first we need to consider and handle the scenario where there are multiple 
		queries!! For instance the user submits the form with search query for City, First Name and Institution.

		To handle this scenario we need to parse at first the whole query, and create a 
		multidimensional array where the keys will be BASED ON the $queries array's keys, 
		and the values will be the $queries array's search term values imploded into a 
		string after some cleaning.
	*/
	$endval_Arr = [];
	$i = 0;
	foreach ( $queries as $key=>$value ) :

		/* 	Let's make the key HUMAN-READABLE - then add it as KEY of 
			the new $endval_Arr array's CURRENT ITEM. 
		*/
		if ( '_sf_s' == $key )
			$endval_Arr[$i]['key'] = __( 'Keyword', 'cap' );

		if ( '_sft_nw-profession-category' == $key )
			$endval_Arr[$i]['key'] = __( 'Profession', 'cap' );

		if ( '_sft_nw-country' == $key )
			$endval_Arr[$i]['key'] = __( 'Country', 'cap' );
		
		if ( '_sft_nw-lang' == $key )
			$endval_Arr[$i]['key'] = __( 'Language(s) Spoken', 'cap' );
		
		if ( '_sfm_net_press' == $key )
			$endval_Arr[$i]['key'] = __( 'Press Accessibility', 'cap' );
		
		if ( '_sfm_net_fname' == $key )
			$endval_Arr[$i]['key'] = __( 'First Name', 'cap' );
		
		if ( '_sfm_net_lname' == $key )
			$endval_Arr[$i]['key'] = __( 'Last Name', 'cap' );
		
		if ( '_sfm_net_city' == $key )
			$endval_Arr[$i]['key'] = __( 'City', 'cap' );
		
		if ( '_sfm_net_institution' == $key )
			$endval_Arr[$i]['key'] = __( 'Institution', 'cap' );
		
		/* 	Let's clean and handle the values. 
			Originally these values look like 'Barcelona-,-Berlin-,-Beijing'.
			To get rid of the slashes we need to explode the string at first, 
			then once we have an array, we can do the cleaning by using ltrim 
			and rtrim for the removals, and ucfirst to capitalize the values.
			Finally - we create the new string type value with implode(), and
			we add it as VALUE of the new $endval_Arr array's CURRENT ITEM.;
		*/
		$val_Arr = explode(',', $value);

		$valnew_Arr = [];

		foreach( $val_Arr as $val ) :

			$valnew_Arr[] = ucfirst( 
							ltrim( 
								rtrim($val, '-'), 
								'-'
							)
						  );

		endforeach;

		$endval_Arr[$i]['value'] = implode(', ', $valnew_Arr);			

		$i++;

	endforeach;

	/* 	The resulting multidimensional array includes arrays of key - value pairs. We need 
		a final string sortof 

		"array1[key] - array1[value]; array2[key] - array2[value]; (...)" 

		which, with a real world example, would look something like 

		"City - Beijing, Berlin, Parlis; Institution - Bill & Melinda Gates Foundation, Freelance; (...)". 
	*/
	$endendval_Arr = []; 

	foreach ( $endval_Arr as $v ) :

		if ( isset( $v['key'] ) )
			$endendval_Arr[] = $v['key'] . ' - ' . $v['value'];

	endforeach;

	$s = implode( '; ', $endendval_Arr );

	return $s;

}

function cap_custom_title_tags($data) {
    
	$is_search_nw = ( is_search() && !get_search_query() );

    if ( $is_search_nw ) :

    	$s = cap_return_member_search_query();
    	if ( is_array($s) ) $s = implode(', ', $s);

        return sprintf( __( 'Search Results for: %s - The China-Africa Project', 'cap'), ucfirst($s) );

    else :

        return $data;

    endif;

}
add_filter('wp_title','cap_custom_title_tags');
add_filter('wpseo_title','cap_custom_title_tags');

/**
 * str_replace modified - replace LAST OCCURRENCE ONLY
 * @see  https://www.jonefox.com/blog/2011/09/26/php-str_replace_last-function/comment-page-1/
 * 
 * @param  [type] $search  [description]
 * @param  [type] $replace [description]
 * @param  [type] $subject [description]
 * @return [type]          [description]
 */
function str_replace_last( $search, $replace, $subject ) {
    if ( !$search || !$replace || !$subject )
        return false;
    
    $index = strrpos( $subject, $search );
    if ( $index === false )
        return $subject;
    
    // Grab everything before occurence
    $pre = substr( $subject, 0, $index );
    
    // Grab everything after occurence
    $post = substr( $subject, $index );
    
    // Do the string replacement
    $post = str_replace( $search, $replace, $post );
    
    // Recombine and return result
    return $pre . $post;
}
/* Usage: 

function some_custom_filter_function( $content ) {

	// Replace the last character ( meaning if the last character is a dot, this custom function will replace ONLY THE LAST DOT OCCURENCE - while the default str_replace function would replace all dots in $value!!! ).

	$readmore_Str 	= __('Read More', 'cap');
	$last_char 		= substr($value, -1, 1);

	return str_replace_last( $last_char, $last_char . ' ' . $readmore_Str, $content);

}
add_filter( 'some_filter_hook', 'some_custom_filter_function' );
*/

/**
 * Get first image in post
 * @return [type] [description]
 *
 * @see  https://css-tricks.com/snippets/wordpress/get-the-first-image-from-a-post/
 */
function catch_that_image() {

	global $post, $posts;
	
	$first_img = '';
	
	ob_start();
	ob_end_clean();
	
	$output = preg_match_all('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];

	if(empty($first_img)) {
		$first_img = "/path/to/default.png";
	}
	return $first_img;

}

/**
 * Remove pages from Search results
 */

function wpb_remove_pages($query) {

  if ( 
  		!is_admin() 			&& 
  		$query->is_main_query() &&
  		$query->is_search
  	) :

	$query->set( 
				'post_type', 
				array(
					'post', 
					'podcast', 
					'analysis', 
					'studentpost',
				) 
			);

  endif;

}

add_action('pre_get_posts','wpb_remove_pages');

/**
 * Custom class for next and/or previous posts link
 */
add_filter('next_posts_link_attributes', 'posts_link_attributes');
//add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="pagination__next"';
}

/**
 * Display custom post types on author archives
 */
function post_types_author_archives($query) {

	if ( !is_admin() ) :

	    if ( $query->is_archive() && $query->is_main_query() && !is_post_type_archive() )
	        $query->set( 'post_type', array(
	        							'podcast', 
	        							'analysis', 
	        							'studentpost', 
	        							'post',
			) );

	    remove_action( 'pre_get_posts', 'custom_post_author_archive' );

	endif;

}
add_action('pre_get_posts', 'post_types_author_archives');

/* ============================================================================================
# GUTENBERG HELPERS 
============================================================================================ */

function cap_block_is_media( $block ) {

	$is_def_gallery 	= ( 'jetpack/tiled-gallery' === $block['blockName'] );
	$is_jetpack_gallery = ( 'core/gallery' === $block['blockName'] );
	$is_image 			= ( 'core/image' === $block['blockName'] );
	$is_imagetext 		= ( 'core/media-text' === $block['blockName'] );

	return (	$is_def_gallery 	|| 
				$is_jetpack_gallery || 
				$is_image 			|| 
				$is_imagetext 
		   );
}

function cap_block_is_video( $block ) {

	$is_video 		= ( 'core/video' === $block['blockName'] );
	$is_youtube 	= ( 'core-embed/youtube' === $block['blockName'] );
	$is_vimeo 		= ( 'core-embed/vimeo' === $block['blockName'] );
	$is_imagetext 	= ( 'core/media-text' === $block['blockName'] );

	return (	$is_video 		|| 
				$is_youtube 	|| 
				$is_vimeo 		|| 
				$is_imagetext 
		   );
}

function cap_block_is_audio( $block ) {

	$is_audio 	= ( 'core/audio' === $block['blockName'] );
	$is_html 	= ( 'core/html' === $block['blockName'] );

	return (	$is_audio 	|| 
				$is_html
		   );	

}

function cap_block_is_tweet( $block ) {

	return ( 'core-embed/twitter' === $block['blockName'] );

}

function cap_block_is_quote( $block ) {

	$is_quote 		= ( 'core/quote' === $block['blockName'] );
	$is_pullquote 	= ( 'core/pullquote' === $block['blockName'] );

	return (	$is_quote 		|| 
				$is_pullquote
		   );
}

/* Excerpt & Read More
----------------------

/* ---------------------- part 1 - from twentyeleven ---------------------- */

/**
 * Set the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove
 * the filter and add your own function tied to
 * the excerpt_length filter hook.
 */
function cap_excerpt_length( $length ) {
	return 45;
}
add_filter( 'excerpt_length', 'cap_excerpt_length' );

/**
 * Return a "Continue Reading" link for excerpts
 */
if ( ! function_exists( 'cap_continue_reading_link' ) ) :

	function cap_continue_reading_link() {
		return ' <a class="cap-readmore" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cap' ) . '</a>';
	}

endif; // cap_continue_reading_link

/**
 * Add a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function cap_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() && ! is_admin() ) {
		$output .= cap_continue_reading_link();
	}
	return $output;
}
//add_filter( 'get_the_excerpt', 'cap_custom_excerpt_more' );

/**
 * Replace "[...]" in the Read More link with an ellipsis.
 *
 * The "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function cap_auto_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return ' &hellip;' . cap_continue_reading_link();
	}
	return $more;
}
add_filter( 'excerpt_more', 'cap_auto_excerpt_more' );

/* ---------------------- part 2 - collected ---------------------- */

/**
 * Customize Read More link - CONTENT
 */
function modify_read_more_link() {
    //return '<a class="more-link" href="' . get_permalink() . '">Your Read More Link Text</a>';
    return false;
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

/**
 * Customize Read More link - EXCERPT
 */
function new_excerpt_more($more) {
       global $post;
	// return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read the full article...</a>';
	return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');

/** 
 * Allow links in excerpts
 *
 * WordPress strips out tags in wp_trim_words(), which is called by get_the_excerpt(); 
 * so we have to filter 'wp_trim_words', basically copying that function with one 
 * change: replace wp_strip_all_tags() with strip_tags(). 
 * 
 * We don't want other functions that run wp_trim_words to be modified, so we add our 
 * filter while get_the_excerpt() is running, and remove it when we're done.
 * 
 * @see  https://gist.github.com/swinggraphics/4ca551447bec03da281424c4ff85dcfd
 * 
 */
function cap_trim_words( $text, $num_words, $more, $original_text ) {
	
	$text = strip_tags( $original_text, '<a>' );
	
	// @See wp_trim_words in wp-includes/formatting.php
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) :

		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep = '';

	else :

		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep = ' ';

	endif;
	
	if ( count( $words_array ) > $num_words ) :

		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;

	else :

		$text = implode( $sep, $words_array );

	endif;
	
	// Remove self so we don't affect other functions that use wp_trim_words
	remove_filter( 'wp_trim_words', 'cap_trim_words' );
	
	return $text;
}

// Be sneaky: add our wp_trim_words filter during excerpt_more filter, which is called 
// immediately prior
function cap_add_trim_words_filter( $excerpt_length ) {
	add_filter( 'wp_trim_words', 'cap_trim_words', 10, 4 );
	return $excerpt_length;
}

add_filter( 'excerpt_more', 'cap_add_trim_words_filter', 1 );

/**
 * Remove WPBakery shortcodes from excerpt content
 */
if(!function_exists('remove_wpbpb_from_excerpt')) {

	function remove_wpbpb_from_excerpt( $excerpt ) {
	
		$patterns 		= array("/\[[\/]?vc_[^\]]*\]/","/\{[\{] ?vc_[^\}]*\}\}/");
		$replacements 	= "";
		$clean_excerpt 	= preg_replace($patterns, $replacements, $excerpt);
		
		return $clean_excerpt;
	
	}

}
add_filter( 'the_excerpt', 'remove_wpbpb_from_excerpt' , 11, 1 );

/* ================================================================================================
# Fixing WordPress Bugs !!!!!!!!! ;(
================================================================================================ */

/**
 * Wordpress has a known bug with the posts_per_page value and overriding it using
 * query_posts. The result is that although the number of allowed posts_per_page
 * is abided by on the first page, subsequent pages give a 404 error and act as if
 * there are no more custom post type posts to show and thus gives a 404 error.
 *
 * This fix is a nicer alternative to setting the blog pages show at most value in the
 * WP Admin reading options screen to a low value like 1.
 *
 */

/**
 * IN ONE WORD - THERE'S A WORDPRESS BUG CAUSING CUSTOM QUERY PAGINATION ON AN 
 * ARCHIVE TEMPLATE NOT WORKING IF THE NUMBER OF POSTS SETTING IN THE BACKEND'S 
 * SETTINGS > READING SECTION DIFFERS FROM THE NUMBER OF POSTS SETTING APPLIED 
 * IN THE CUSTOM QUERY ARGS ARRAY.
 */

function custom_posts_per_page( $query ) {

	// $q_ana 	= $query->is_archive('analysis');
	// $q_pod 	= $query->is_archive('podcast');
	// $q_stud = $query->is_archive('studentpost');	

	if ( 
			( $query->is_archive() || $query->is_search() ) &&
			$query->is_main_query()
		)
        set_query_var('posts_per_page', 10);

}
add_action( 'pre_get_posts', 'custom_posts_per_page' );

/**
 * Create constant to hold the array of ALL sticky posts.
 * 
 * @return 		array 		merged array of sticky and "custom sticky" ids  
 */
$stickies_ID_Arr = get_option( 'sticky_posts' );

$args = array(
	'post_type' => array('analysis', 'podcast', 'studentpost'),
	'post_status' => 'publish',
	'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'OR',
        'ana_clause' => array(
            'key'     => 'cap_ana_single_sticky',
            'value'   => true,
        ),
        'pod_clause' => array(
            'key'     => 'cap_pod_single_sticky',
            'value'   => true,
        ), 
        'stud_clause' => array(
            'key'     => 'cap_stud_single_sticky',
            'value'   => true,
        ), 	        
    ),		
);

$cstickies_Arr 		= get_posts( $args );
$cstickies_ID_Arr 	= [];

foreach ( $cstickies_Arr as $post ) :

	setup_postdata( $post ); 
	$cstickies_ID_Arr[] = $post->ID;

endforeach;
wp_reset_postdata();

$stickies = array_merge( $stickies_ID_Arr, $cstickies_ID_Arr );

define('ALL_STICKIES', $stickies);

/**
 * Remove sticky posts from the main query results on archives.
 * 
 * @param  object $query [description]
 * @return [type]        [description]
 */
function cap_remove_stickies_from_archive_query( $query ) {

	if ( $query->is_archive() && $query->is_main_query() ) :

		$query->set( 'post__not_in', ALL_STICKIES );

	endif;

}
add_action( 'pre_get_posts', 'cap_remove_stickies_from_archive_query' );

/* ================================================================================================
# Plugins
================================================================================================ */

/* Relevanssi
------------------------------------------------------------------------------------------------ */

add_filter( 'relevanssi_block_one_letter_searches', '__return_false' );

/* MemberPress 
------------------------------------------------------------------------------------------------ */

function memberpress_get_all_memberships( $user_id = false ){
    
    if( class_exists('MeprUser') ){
        
        if( ! $user_id ){
            $user_id = get_current_user_id();
        }
        
        $user            = new MeprUser( $user_id );
        $get_memberships = $user->active_product_subscriptions();
        
        if( !empty( $get_memberships ) ){
            $user_memberships = array_values( array_unique( $get_memberships ) );
        } else {
            $user_memberships = array();
        }
        
        return $user_memberships;
        
    } else {
        return false;
    }

}

define( 'MEMBERPRESS_TESTING', false );

/* Yoast SEO
------------------------------------------------------------------------------------------------ */

// Move Yoast Meta Box to bottom
function yoasttobottom() {
	return 'low';
}

add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

/* Gravity Forms
------------------------------------------------------------------------------------------------ */

/**
 * Remove/replace Gravity Forms default Submit ajax spinner
 */
// With a fake value the ugly default spinner gets hidden at least.
function spinner_url( $image_src, $form ) {
    return "http://www.somedomain.com/spinner.png";
}
add_filter( 'gform_ajax_spinner_url', 'spinner_url', 10, 2 );

/* WPBakery Page Builder
------------------------------------------------------------------------------------------------ */

/* Advanced Custom Fields
------------------------------------------------------------------------------------------------ */

/**
 * Options page
 * ------------
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'CAP Theme Settings',
		'menu_title'	=> 'CAP Theme Settings',
		'menu_slug' 	=> 'cap-theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> true
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'General',
		'parent_slug'	=> 'cap-theme-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'cap-theme-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Settings',
		'menu_title'	=> 'Contact',
		'parent_slug'	=> 'cap-theme-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Popup Settings',
		'menu_title'	=> 'Popups',
		'parent_slug'	=> 'cap-theme-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'cap-theme-settings',
	));
		
}

/**
 * Remove 'parent country' setters
 * @return [type] [description]
 */
function remove_tax_parent_dropdown() {
    $screen = get_current_screen();

    if ( 'country' == $screen->taxonomy ) {
        if ( 'edit-tags' == $screen->base ) {
            $parent = "$('label[for=parent]').parent()";
        } elseif ( 'term' == $screen->base ) {
            $parent = "$('label[for=parent]').parent().parent()";
        }
    } /* elseif ( 'post' == $screen->post_type ) {
        $parent = "$('#newcategory_parent')";
    } else {
        return;
    } */
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {     
            <?php echo $parent; ?>.remove();       
        });
    </script>

    <?php 
}
add_action( 'admin_head-edit-tags.php', 'remove_tax_parent_dropdown' );
add_action( 'admin_head-term.php', 'remove_tax_parent_dropdown' );
add_action( 'admin_head-post.php', 'remove_tax_parent_dropdown' );
add_action( 'admin_head-post-new.php', 'remove_tax_parent_dropdown' ); 

/**
 * Populate field value on save post based on saved custom taxonomy terms
 */

function my_acf_save_post( $post_id ) {
    
	if ( 'nwmember' == get_post_type( $post_id ) ) :

		$profcats_Arr 	= get_the_terms( $post_id, 'nw-profession-category' );

		if ( !empty( $profcats_Arr ) ) :
			
			$new_profcat = $profcats_Arr[0];
			$new_profcat = $new_profcat->slug;

			update_field('net_profcat', $new_profcat, $post_id);

		endif;

		$countries_Arr 	= get_the_terms( $post_id, 'nw-country' );

		if ( !empty( $countries_Arr ) ) :
			
			$new_country = $countries_Arr[0];
			$new_country = $new_country->name;

			update_field('net_country', $new_country, $post_id);

		endif;

		$langs_Arr 		= get_the_terms( $post_id, 'nw-lang' );
		$new_langs_Arr 	= [];

		if ( !empty( $langs_Arr ) ) : 

			foreach	( $langs_Arr as $lang ) :

				$new_langs_Arr[] = $lang->slug;

			endforeach; print_r($new_langs_Arr);

			update_field('net_lang', $new_langs_Arr, $post_id);

		endif;

	endif;
	
}

add_action('acf/save_post', 'my_acf_save_post', 20);

/**
 * ACF Blocks
 * ===================================================================
 */

/**
 * Add custom block category
 * @param  [type] $categories [description]
 * @param  [type] $post       [description]
 * @return [type]             [description]
 */
function cap_block_categories( $categories, $post ) {

    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'cap-blocks',
                'title' => __( 'CAP Blocks', 'cap' ),
                'icon'  => 'layout',
            ),
        )
    );

}
add_filter( 'block_categories', 'cap_block_categories', 10, 2 );

function cap_register_acf_block_types() {

    acf_register_block_type(array(
        'name'              => 'cap-subscribe-box',
        'title'             => __('CAP Subscribe Box'),
        'description'       => __('Outputs the CAP email subscription box.'),
        'render_template'   => 'template-parts/blocks/block-cap-subscribe-box.php',
        'category'          => 'cap-blocks',
        'icon'              => 'buddicons-pm',
        'keywords'          => array( 'box', 'cap', 'email', 'subscribe' ),
        'align' 			=> '',
        'mode' 				=> 'preview',
        'supports' 			=> array(
        	'align' 		=> false,
		),        
    ));

    acf_register_block_type(array(
        'name'              => 'cap-feature-grid',
        'title'             => __('CAP Feature Grid'),
        'description'       => __('Outputs a set of features.'),
        'render_template'   => 'template-parts/blocks/block-cap-feature-grid.php',
        'category'          => 'cap-blocks',
        'icon'              => 'screenoptions',
        'keywords'          => array( 'cap', 'feature' ),
        'align' 			=> '',
        'mode' 				=> 'preview',
        'supports' 			=> array(
        	'align' 		=> false,
		),        
    ));

    acf_register_block_type(array(
        'name'              => 'cap-description-list',
        'title'             => __('CAP Description List'),
        'description'       => __('Outputs a description list such as the list of Frequently Asked Questions.'),
        'render_template'   => 'template-parts/blocks/block-cap-description-list.php',
        'category'          => 'cap-blocks',
        'icon'              => 'book-alt',
        'keywords'          => array( 'cap', 'description', 'dl', 'list' ),
        'align' 			=> '',
        'mode' 				=> 'preview',
        'supports' 			=> array(
        	'align' 		=> false,
		),
    ));

}

// Check if function exists and hook into setup.
if( function_exists('cap_register_acf_block_types') ) {

    add_action('acf/init', 'cap_register_acf_block_types');

}

/* Posts Table Pro
------------------------------------------------------------------------------------------------ */

if ( class_exists( 'Abstract_Posts_Table_Data' ) ) :

	/**
	 * Gets data for the 'profile_btn' column to use in the posts table.
	 *
	 * @license GPL-3.0
	 */
	class Posts_Table_Data_Profile_Btn extends Abstract_Posts_Table_Data {

	    public function get_data() {
	        
	        // Retrieve the media type from somewhere. In this example, we get it from the post meta table.
	        $permalink 	= get_permalink( $this->post->ID );
	        $label 		= __( 'View', 'cap' );
	        $btn = "<a href='{$permalink}'>{$label}</a>";

	        // Return the media type and run through a filter.
			return apply_filters( 'posts_table_data_profile_btn', $btn, $this->post );
	    
	    }

	}

	add_filter( 'posts_table_custom_table_data_profile_btn', function( $data_obj, $post, $args ) { 
	    return new Posts_Table_Data_Profile_Btn( $post ); 
	}, 10, 3 );	

endif;

/**
 * Override Posts Table Pro labels
 */
add_filter( 'posts_table_language_defaults', function( $defaults ) {

    $defaults['search'] 	= 'Search'; 
    $defaults['filterBy'] 	= 'Filter';     
    return $defaults; 

} );