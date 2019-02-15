<?php
/**
 * Common content part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <?php
        if ( get_theme_mod( 'instapress_internal_pages' ) === 'enable' ) :
            printf(
                '<a class="entry-permalink" href="%s"></a>',
                esc_url( get_permalink() )
            );
        endif;
    ?>

    <figure class="entry-thumbnail">
        <?php
            the_post_thumbnail( 'featured',
                array( 'class' => 'entry-thumbnail-image' )
            );
        ?>
    </figure>

    <footer class="entry-summary">
        <?php
            if ( function_exists( 'instapress_show_summary' ) ) :
                instapress_show_summary();
            endif;
        ?>
    </footer>
</article>