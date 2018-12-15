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

    wp_enqueue_script('instapress-style', get_template_directory_uri() . '/assets/scripts.min.js', [], $version, true);
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

    // Set post thumbnail default size
    set_post_thumbnail_size( 1280, 9999 );

    // Switch default core markup to output valid HTML5
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        )
    );

    // Add theme support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action( 'after_setup_theme', 'instapress_setup' );