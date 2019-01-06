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

    wp_enqueue_script('instapress-scripts', get_template_directory_uri() . '/assets/scripts.min.js', array(), $version, true);
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

    wp_enqueue_style('instapress-styles', get_template_directory_uri() . '/assets/styles.min.css', array(), $version);
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

    return $classes;
}
add_filter( 'post_class', 'instapress_post_class', 10, 3 );


/**
 * Add is- prefix to all body classes
 */
function instapress_body_class( $wp_classes, $extra_classes ) {
    $body_classes = $wp_classes + $extra_classes;

    foreach ( $body_classes as &$body_class ) {
        $body_class = 'is-' . $body_class;
    }

    // Remove link to avoid unexpected behavior
    unset( $body_class );

    return $body_classes;
}
add_filter( 'body_class', 'instapress_body_class', 10, 2 );


/**
 * Disable block editor for default posts type
 */
function instapress_block_editor( $post_type ) {
    if ( in_array( $post_type, array( 'post', 'page') ) ) {
        return false;
    }

    return true;
}
add_filter( 'use_block_editor_for_post_type', 'instapress_block_editor', 10, 2 );


/**
 * Remove default editor for posts
 */
function instapress_post_editor() {
    remove_post_type_support( 'post', 'editor');
}
add_action( 'init', 'instapress_post_editor' );


/**
 * Move thumbnail metabox to main section for posts
 */
function instapress_thumbnail_metabox( $post_type ) {
    if ( 'post' === $post_type && post_type_supports( $post_type, 'thumbnail' ) ) {
        // Remove thumbnail metabox to re-create it below
        remove_meta_box( 'postimagediv', $post_type, 'side' );

        $post_type_object = get_post_type_object( $post_type );
        $metabox_title = esc_html( $post_type_object->labels->featured_image );

        // Add thumbnail metabox instead of post editor
        add_meta_box( 'postimagediv', $metabox_title, 'post_thumbnail_meta_box', $post_type, 'normal', 'high' );
    }
}
add_action( 'do_meta_boxes', 'instapress_thumbnail_metabox' );


/**
 * Enqueue admin side postbox image styles
 */
function instapress_thumbnail_styles() {
    if( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
        $version = date( 'U' );
    } else {
        $version = wp_get_theme()->get( 'Version' );
    }

    wp_enqueue_style('instapress-admin-styles', get_template_directory_uri() . '/include/postbox-image.css', array(), $version);
}
add_action( 'admin_enqueue_scripts', 'instapress_thumbnail_styles' );


/**
 * Remove useless emojis styles
 */
function instapress_remove_emoji() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'instapress_remove_emoji' );


/**
 * Remove wordpress meta for security reasons
 */
function instapress_remove_meta() {
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'init', 'instapress_remove_meta' );


/**
 * We don't use gutenberg for now so it would be better to remove useless styles
 */
function instapress_remove_block_styles() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'instapress_remove_block_styles', 11 );