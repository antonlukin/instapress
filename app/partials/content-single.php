<?php
/**
 * Single content template part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <figure class="entry-thumbnail">
        <?php
            the_post_thumbnail( 'featured',
                array( 'class' => 'entry-thumbnail-image' )
            );
        ?>
    </figure>

    <footer class="entry-summary">
        <?php
            if ( function_exists( 'instapress_show_summary' ) ) {
                instapress_show_summary();
            }
        ?>
    </footer>
</article>