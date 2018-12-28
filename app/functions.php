<?php
/**
 * Important functions and definitions
 *
 * Set up the theme and provides some helper classes and functions,
 * which are attached to hooks in WordPress to change core functionality.
 *
 * @package instapress
 * @since 1.0
 */

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * @global int $content_width Content width.
 */
function instapress_content_width() {
    global $content_width;

	$content_width = apply_filters( 'instapress_content_width', 640 );
}
add_action( 'after_setup_theme', 'instapress_content_width', 0 );


/**
 * Insert required js files
 *
 * We can easily clear static cache on theme version update.
 * While debug is true, use timestamp as script curren version.
 */
function instapress_scripts() {
    if( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
        $version = date( 'U' );
    } else {
        $version = wp_get_theme()->get( 'Version' );
    }

    wp_enqueue_script('instapress-script', get_template_directory_uri() . '/assets/scripts.min.js', [], $version, true);
}
add_action( 'wp_enqueue_scripts', 'instapress_scripts' );


/**
 * Insert required css files
 *
 * We can easily clear static cache on theme version update.
 * While debug is true, use timestamp as style curren version.
 */
function instapress_styles() {
    if( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
        $version = date( 'U' );
    } else {
        $version = wp_get_theme()->get( 'Version' );
    }

    wp_enqueue_style('instapress-style', get_template_directory_uri() . '/assets/styles.min.css', [], $version);
}
add_action( 'wp_enqueue_scripts', 'instapress_styles' );


/**
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Can be override by child theme function by action removing
 */
function instapress_setup() {
    // Make theme available for translation.
    load_theme_textdomain( 'instapress', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Enable post formats
    add_theme_support( 'post-formats',
        array(
            'image',
            'video'
        )
    );

    // Set post thumbnail default size
    set_post_thumbnail_size( 1280, 9999 );

    // This theme uses wp_nav_menu() in header and footer.
    register_nav_menus(
        array(
            'primary' => __( 'Primary menu', 'instapress' ),
        )
    );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        )
    );
}
add_action( 'after_setup_theme', 'instapress_setup' );


/**
 * Replace useless menu classes with custom ones
 *
 * Applies to menu in primary theme location only
 */
function instapress_menu_classes( $classes, $item, $args ) {
    // Redefine classes array
    $classes = array();

    if( $args->theme_location === 'primary' ) {
        $classes[] = 'menu__item';
    }

    if( $item->current === true ) {
        $classes[] = 'menu__item--current';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'instapress_menu_classes', 10, 3 );


/**
 * Remove stupid menu id attribute
 *
 * Just remove this filter if you want to use menu item id
 * Applies to menu in primary theme location only
 */
function instapress_menu_item_id() {
    return '';
}
add_filter( 'nav_menu_item_id', 'instapress_menu_item_id' );


/**
 * Add class to link menu items
 *
 * Applies to menu in primary theme location only
 */
function instapress_menu_link_class( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $atts['class'] = 'menu__item-link';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'instapress_menu_link_class', 10, 3 );


/**
 * Replace annoying post classes
 */
function instapress_post_class( $classes, $class, $post_id ) {
    $classes = array();

    if ( $class ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
        }

		$classes = array_map( 'esc_attr', $class );
    }

    if ( is_singular( 'page' ) ) {
        $classes[] = 'post--page';
    }

    if ( is_singular( 'post' ) && has_post_format() ) {
        $classes[] = 'post--' . get_post_format();
    }

    return $classes;
}
add_filter( 'post_class', 'instapress_post_class', 10, 3 );


/**
 * Update annoying body classes
 */
function instapress_body_class( $wp_classes, $extra_classes ) {
    $classes = array();

    if ( is_archive() ) {
        $classes[] = 'is-archive';
    }

    if ( is_admin_bar_showing() ) {
        $classes[] = 'is-adminbar';
    }

    if ( is_front_page() ) {
        $classes[] = 'is-front';
    }

    if ( is_singular( 'page' ) && ! is_front_page() ) {
        $classes[] = 'is-page';
    }

    if ( is_singular( 'post' ) ) {
        if ( has_post_format() ) {
            $classes[] = 'is-' . get_post_format();
        } else {
            $classes[] = 'is-post';
        }
    }

    return $classes;
}
add_filter( 'body_class', 'instapress_body_class', 10, 2 );