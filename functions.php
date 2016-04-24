<?php
/**
 * CCW_Countries functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CCW_Countries
 */

if ( ! function_exists( 'ccw_countries_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ccw_countries_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CCW_Countries, use a find and replace
	 * to change 'ccw_countries' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ccw_countries', get_template_directory() . '/languages' );

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
		'primary_navigation' => esc_html__( 'Primary Navigation', 'ccw_countries' ),
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

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ccw_countries_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'ccw_countries_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ccw_countries_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ccw_countries_content_width', 640 );
}
add_action( 'after_setup_theme', 'ccw_countries_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ccw_countries_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ccw_countries' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ccw_countries_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ccw_countries_scripts() {
	// set style guide version, or default to false (see https://developer.wordpress.org/reference/functions/wp_enqueue_style/)
	$styleguide_meta = file_get_contents(get_template_directory() . '/bower_components/code-club/.bower.json');
	$styleguide_meta_json = json_decode($styleguide_meta, true);
	$styleguide_version = $styleguide_meta_json['version'] ?: false;

	// enqueue the Code Club style guide
	wp_enqueue_style( 'ccw-countries-style-guide-style', get_template_directory_uri() . '/bower_components/code-club/dist/stylesheets/code-club.min.css', false, $styleguide_version );
	wp_enqueue_style( 'ccw-countries-style', get_stylesheet_uri() );
	wp_enqueue_script( 'ccw-countries-style-guide-script', get_template_directory_uri() . '/bower_components/code-club/dist/javascripts/code-club.min.js', ['jquery'], $styleguide_version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ccw_countries_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Theme nav overrides
 */
add_filter('nav_menu_css_class', 'primary_nav_li', 1, 3);
function primary_nav_li($classes, $item, $args) {
	$classes[] = 'o-nav__item';
	return $classes;
}

add_filter('wp_nav_menu', 'primary_nav_anchors');
function primary_nav_anchors($anchorclass) {
	return preg_replace('/<a /', '<a class="o-nav__link" ', $anchorclass);
}

add_filter('nav_menu_css_class', 'current_nav_class', 10, 2);
function current_nav_class($classes, $item) {
	if (in_array('current-menu-item', $classes)) {
		$classes[] = 'is-current ';
	}
	return $classes;
}
