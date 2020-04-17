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
 * Instpress only works in WordPress 4.2 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.2', '<' ) ) {
    wp_die( 'Instpress theme requires WordPress 4.2 or greater' );
}


/**
 * Set the content width in pixels.
 *
 * To support retina featured image size, we should use increased width
 */
function instapress_content_width() {
    global $content_width;

    $content_width = apply_filters( 'instapress_content_width', 600 );
}
add_action( 'after_setup_theme', 'instapress_content_width', 0 );


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
    load_theme_textdomain( 'instapress' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Set post thumbnail default size
    set_post_thumbnail_size( 300, 300, true );

    // Add custom thumbnail image size
    add_image_size( 'instapress-featured', 1200, 900, true );

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
            'gallery',
            'caption',
            'comment-list'
        )
    );
}
add_action( 'after_setup_theme', 'instapress_setup' );


/**
 * Add custom intermediate size
 */
function instapress_image_size_names( $size_names ) {
    $size_names = array_merge( $size_names, array(
        'featured' => __( 'Featured image', 'instapress' )
    ) );

    return $size_names;
}
add_filter( 'image_size_names_choose', 'instapress_image_size_names' );


/**
 * Enqueue comment-reply script
 */
function instapress_comments_scripts() {
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'instapress_comments_scripts' );


/**
 * Upgrade comment form args
 *
 * Remove information strings and reply title
 */
function instapress_comment_form_defaults( $defaults ) {
    $args = array(
        'logged_in_as'         => '',
        'must_log_in'          => '',
        'title_reply_before'   => '',
        'title_reply_after'    => '',
        'title_reply'          => '',
        'title_reply_to'       => '',
        'comment_notes_before' => '',
        'cancel_reply_before'  => '',
        'cancel_reply_after'   => '',
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
        'submit_field'         => '<div class="comments-submit">%1$s %2$s</div>',
    );

    return wp_parse_args( $args, $defaults );
}
add_filter( 'comment_form_defaults', 'instapress_comment_form_defaults' );


/**
 * Remove labels from comment fields
 */
function instapress_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();

    $requred = (string) null;
    if ( get_option( 'require_name_email' ) ) {
        $requred = ' required="required"';
    }

    $fields['comment'] = sprintf(
        '<p><textarea id="comment" name="comment" placeholder="%s" required></textarea></p>',
        esc_attr__( 'Leave a Reply', 'instapress' )
    );

    $fields['author'] = sprintf(
        '<p><input id="author" name="author" type="text" value="%s" placeholder="%s" maxlength="245"%s></p>',
        esc_attr( $commenter['comment_author'] ),
        esc_attr__( 'Name', 'instapress' ), $requred
    );

    $fields['email'] = sprintf(
        '<p><input id="email" name="email" type="email" value="%s" placeholder="%s" maxlength="100"%s></p>',
        esc_attr( $commenter['comment_author_email'] ),
        esc_attr__( 'Email', 'instapress' ), $requred
    );

    $fields['url'] = sprintf(
        '<p><input id="url" name="url" type="url" value="%s" placeholder="%s" maxlength="200"></p>',
        esc_attr( $commenter['comment_author_url'] ),
        esc_attr__( 'Website', 'instapress' )
    );

    return $fields;
}
add_filter( 'comment_form_fields', 'instapress_comment_form_fields' );


/**
 * Remove comment reply link if user not logged in and comment registration required
 */
function instapress_comment_reply_link( $link ) {
    if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
        $link = (string) null;
    }

    return $link;
}
add_filter( 'comment_reply_link', 'instapress_comment_reply_link' );


/**
 * Delete cancel comment reply link to recreate it below
 */
add_filter( 'cancel_comment_reply_link', '__return_empty_string' );


/**
 * Delete cancel comment reply link to recreate it below
 */
function instapress_comment_form_submit_button( $submit_button, $args ) {
    $link = remove_query_arg( array( 'replytocom', 'unapproved', 'moderation-hash' ) );

    $display = (string) null;
    if ( empty( $_GET['replytocom'] ) ) {
        $display = ' style="display: none;"';
    }

    $cancel_link = sprintf(
        '<a id="cancel-comment-reply-link" class="comment-reply-cancel" href="%1s"rel="nofollow"%3$s>%2$s</a>',
        esc_html( $link ) . '#respond',
        __( 'Cancel reply', 'instapress' ), $display
    );

    return $submit_button . $cancel_link;
}
add_filter( 'comment_form_submit_button', 'instapress_comment_form_submit_button', 10, 2 );


/**
 * Slightly upgrade password protected form
 */
function instapress_password_form( $output ) {
    $output = sprintf(
        '<form class="post-password-form" action="%3$s" method="post">%1$s %2$s</form>',

        sprintf(
            '<input name="post_password" type="password" placeholder="%s">',
            esc_attr__( 'Your page password', 'instapress' )
        ),

        sprintf(
            '<button type="submit" class="submit">%s</button>',
            __( 'Enter', 'instapress' )
        ),

        esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) )
    );

    return $output;
}
add_filter( 'the_password_form', 'instapress_password_form' );


/**
 * Add theme options to customizer
 */
function instapress_customizer_settings( $wp_customize ) {
    $wp_customize->add_section( 'instapress_settings',
        array(
            'title' => __( 'Theme settings', 'instapress' ),
            'priority' => 50,
        )
    );

    // Show author in summary
    $wp_customize->add_setting( 'instapress_summary_author', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'instapress_summary_author', array(
        'type' => 'checkbox',
        'section' => 'instapress_settings',
        'priority' => 10,
        'label' => __( 'Show author in summary', 'instapress' ),
    ) );

    // Show custom post meta in summary
    $wp_customize->add_setting( 'instapress_summary_meta', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'instapress_summary_meta', array(
        'type' => 'checkbox',
        'section' => 'instapress_settings',
        'priority' => 10,
        'label' => __( 'Show custom meta in summary', 'instapress' ),
    ) );

    // Footer copy text
    $wp_customize->add_setting( 'instapress_footer_copy', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( new WP_Customize_Code_Editor_Control(
        $wp_customize, 'instapress_footer_copy', array(
            'label' => __( 'Footer description', 'instapress' ),
            'section' => 'instapress_settings',
            'code_type' => 'text/html',
            'priority' => 25
        )
    ) );
}
add_action( 'customize_register', 'instapress_customizer_settings' );


/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 */
function instapress_skip_link() {
	printf(
        '<a class="skip screen-reader-text" href="#content">%s</a>',
        __( 'Skip to the content', 'instapress' )
    );
}

add_action( 'wp_body_open', 'instapress_skip_link', 5 );


/**
 * Shim for wp_body_open, ensuring backwards compatibility with versions of WordPress older than 5.2.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}


/**
 * Template function: show post summary
 */
if( ! function_exists( 'instapress_show_summary' ) ) {
    function instapress_show_summary() {
        $fields = array(
            'date' => esc_html( get_the_date() ),
            'title' => esc_html( get_the_title() )
        );

        if ( get_theme_mod( 'instapress_summary_author' ) === 1 ) {
            $fields['author'] = get_the_author_posts_link();
        }

        if ( get_theme_mod( 'instapress_summary_meta' ) === 1 ) {
            foreach ( (array) get_post_custom_keys() as $key ) {
                if ( is_protected_meta( $key, 'post' ) ) {
                    continue;
                }

                $values = array_map( 'esc_html', get_post_custom_values( $key ) );
                $fields[ $key ] = implode( ', ', $values );
            }
        }

        if ( get_the_tags() ) {
            $fields['tags'] = get_the_tag_list( null, ', ' );
        }

        $fields = apply_filters( 'instapress_summary_fields', $fields );

        foreach ( $fields as $label => $value ) {
            printf(
                '<li><span>"%s"</span>: <strong>"%s"</strong></li>',
                esc_attr( $label ),
                wp_kses_post( $value )
            );
        }
    }
}
