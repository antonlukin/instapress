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
    set_post_thumbnail_size( 1200, 900, true );

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
 * Update default media sizes on theme setup
 */
function instapress_media_size() {
    // Thumbnail size
	update_option( 'thumbnail_size_w', 1200 );
	update_option( 'thumbnail_size_h', 900 );
    update_option( 'thumbnail_crop', 1 );
}
add_action( 'switch_theme', 'instapress_media_size' );


/**
 * Replace useless menu classes with custom ones
 *
 * Applies to menu in primary theme location only
 */
function instapress_menu_classes( $classes, $item, $args ) {
    if( $args->theme_location === 'primary' ) {
        // Redefine classes array
        $classes = array( 'menu-item' );

        if( $item->current === true ) {
            $classes[] = 'menu-item-current';
        }
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
function instapress_menu_item_id( $classes, $item, $args ) {
    if( $args->theme_location === 'primary' ) {
        return '';
    }
}
add_filter( 'nav_menu_item_id', 'instapress_menu_item_id', 10, 3 );


/**
 * Add class to link menu items
 *
 * Applies to menu in primary theme location only
 */
function instapress_menu_link_class( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $atts['class'] = 'menu-item-link';
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

    $post_type = get_post_type( $post_id );

    if( 'post' === $post_type ) {
        $classes[] = 'post-image';
    } else {
        $classes[] = 'post-' . $post_type;
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
        // Skip no-customize-support class
        if ( 'no-customize-support' !== $body_class ) {
            $body_class = 'is-' . $body_class;
        }
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
function instapress_remove_post_editor() {
    remove_post_type_support( 'post', 'editor');
}
add_action( 'init', 'instapress_remove_post_editor' );


/**
 * Remove thumbnail support from posts
 */
function instapress_remove_post_thumbnail() {
    remove_post_type_support( 'post', 'thumbnail' );
}
add_action( 'init', 'instapress_remove_post_thumbnail' );


/**
 * Remove default taxes support from posts
 */
function instapress_unregister_post_taxes() {
    // Unregister tags
    unregister_taxonomy_for_object_type( 'post_tag', 'post' );

    // Unregister categories
    unregister_taxonomy_for_object_type( 'category', 'post' );
}
add_action( 'init', 'instapress_unregister_post_taxes' );


/**
 * Enqueue admin side thumbnail metabox assets
 */
function instapress_thumbnail_assets() {
    $screen = get_current_screen();

    if ( is_object( $screen ) && 'post' === $screen->post_type ) {
        $include = get_template_directory_uri() . '/include';

        if( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
            $version = date( 'U' );
        } else {
            $version = wp_get_theme()->get( 'Version' );
        }

        // Insert wp media scripts
        wp_enqueue_media();

        // Insert admin styles
        wp_enqueue_style( 'instapress-thumbnail', $include . '/styles/thumbnail-metabox.css', array(), $version );

        // Insert admin scripts
        wp_enqueue_script( 'instapress-thumbnail', $include . '/scripts/thumbnail-metabox.js', array( 'jquery' ), $version );
    }
}
add_action( 'admin_enqueue_scripts', 'instapress_thumbnail_assets' );


/**
 * Add custom thumbnail metabox
 */
function instapress_thumbnail_metabox() {
    $label = _x( 'Featured Image', 'custom thumbnail metabox title', 'instapress' );

    add_meta_box( 'instapress-thumbnail-metabox', $label, 'instapress_thumbnail_callback', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'instapress_thumbnail_metabox', 20 );


/**
 * Custom thumnbnail metabox callback
 */
function instapress_thumbnail_callback( $post, $meta ) {
    $thumbnail = get_post_meta( $post->ID, '_thumbnail_id', true );

    if ( $thumbnail && get_post( $thumbnail ) ) {
        printf( '<div class="thumbnail-image"><img src="%s" alt="%s"></div>',
            wp_get_attachment_image_url( $thumbnail, 'post-thumbnail' ),
            esc_html( $post->post_title )
        );
    }

    printf( '<div class="thumbnail-placeholder">%3$s <a href="%1$s" class="button">%2$s</a></div>',
        esc_url( get_upload_iframe_src( 'image', $post->ID ) ),
        __( 'Set featured image', 'instapress' ),
        __( 'No image selected', 'instapress' )
    );

    printf( '<input type="hidden" name="_thumbnail_id" value="%s">',
        esc_attr( $thumbnail ? $thumbnail : '-1' )
    );
}


/**
 * Sabe custom thumbnail metabox
 */
function instapress_thumbnail_save( $post_id ) {
    $nonce = 'instapress_thumbnail_nonce';

    if ( empty( $_POST[$nonce] ) ) {
        return;
    }

    if( ! wp_verify_nonce( $_POST[ $nonce ], 'metabox' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

   // update_post_meta( $post_id, self::$meta_options, $_REQUEST[self::$meta_options]);
}
add_action( 'save_post', 'instapress_thumbnail_save' );


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


/**
 * Disable post attachment pages and redirect to post parent if exists
 */
function instapress_attachment_template() {
    global $post;

    if ( is_attachment() ) {
        if ( isset( $post->post_parent ) && absint( $post->post_parent ) > 0 ) {
            $url = get_permalink( $post->post_parent );
        } else {
            $url = home_url( '/' );
        }

        wp_redirect( esc_url( $url ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'instapress_attachment_template' );


/**
 * Add article options to customizer
 */
function instapress_customizer_settings( $wp_customize ) {
    $wp_customize->add_section( 'instapress_settings',
        array(
            'title' => __( 'Theme settings', 'instapress' ),
            'priority' => 50
        )
    );

    // Use internal pages
    $wp_customize->add_setting( 'instapress_internal_pages', array(
        'default' => 'enable'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize, 'instapress_internal_pages', array(
            'label' => __( 'Use post internal pages', 'instapress' ),
            'section' => 'instapress_settings',
            'type' => 'radio',
            'choices' => array(
                'enable' => __( 'Enable', 'instapress' ),
                'disable' => __( 'Disable', 'instapress' )
            )
        )
    ) );

    // Show summary meta
    $wp_customize->add_setting( 'instapress_summary_meta', array(
        'default' => 'disable'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize, 'instapress_summary_meta', array(
            'label' => __( 'Display summary custom fields', 'instapress' ),
            'section' => 'instapress_settings',
            'type' => 'radio',
            'choices' => array(
                'enable' => __( 'Enable', 'instapress' ),
                'disable' => __( 'Disable', 'instapress' )
            )
        )
    ) );

    // Author field in summary
    $wp_customize->add_setting( 'instapress_summary_author', array(
        'default' => 'disable'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize, 'instapress_summary_author', array(
            'label' => __( 'Display summary author', 'instapress' ),
            'section' => 'instapress_settings',
            'type' => 'radio',
            'choices' => array(
                'enable' => __( 'Enable', 'instapress' ),
                'disable' => __( 'Disable', 'instapress' )
            )
        )
    ) );

    // Footer copy text
    $wp_customize->add_setting( 'instapress_footer_copy' );

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
        __( 'Leave a Reply' )
    );

    $fields['author'] = sprintf(
        '<p><input id="author" name="author" type="text" value="%s" placeholder="%s" maxlength="245"%s></p>',
        esc_attr( $commenter['comment_author'] ),
        __( 'Name' ), $requred
    );

    $fields['email'] = sprintf(
        '<p><input id="email" name="email" type="email" value="%s" placeholder="%s" maxlength="100"%s></p>',
        esc_attr( $commenter['comment_author_email'] ),
        __( 'Email' ), $requred
    );

    $fields['url'] = sprintf(
        '<p><input id="url" name="url" type="url" value="%s" placeholder="%s" maxlength="200"></p>',
        esc_attr( $commenter['comment_author_url'] ),
        __( 'Website' )
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
        __( 'Cancel reply' ), $display
    );

    return $submit_button . $cancel_link;
}
add_filter( 'comment_form_submit_button', 'instapress_comment_form_submit_button', 10, 2 );


/**
 * Remove admin bar styles
 */
function instapress_remove_adminbar_styles() {
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}
add_action( 'get_header', 'instapress_remove_adminbar_styles' );


/**
 * Template function: show post summary
 */
if( ! function_exists( 'instapress_show_summary' ) ) :
    function instapress_show_summary() {
        $fields = array(
            'date' => get_the_date(),
            'title' => get_the_title(),
        );

        if ( get_theme_mod( 'instapress_summary_author' ) === 'enable' ) {
            $fields['author'] = get_the_author();
        }

        if ( get_theme_mod( 'instapress_summary_meta') === 'enable' ) {
            foreach ( (array) get_post_custom_keys() as $key ) {
                if ( is_protected_meta( trim( $key ), 'post' ) ) {
                    continue;
                }

                $values = array_map( 'trim', get_post_custom_values( $key ) );
                $fields[ $key ] = implode( ', ', $values );
            }
        }

        if( count( $fields ) > 0 ) {
            foreach ( $fields as $label => $value ) {
                printf(
                    '<p class="entry-summary-field"><span>"%s"</span>: <strong>"%s"</strong></p>',
                    esc_html( $label ),
                    esc_html( $value )
                );
            }
        }
    }
endif;