<?php
/**
 * Common content part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <div class="card link-card">
        <?php
            printf(
                '<a class="card-permalink" href="%s"></a>',
                esc_url( get_permalink() )
            );
        ?>

        <figure class="card-thumbnail">
            <?php
                the_post_thumbnail( 'instapress-featured',
                    array( 'class' => 'card-thumbnail-image' )
                );
            ?>
        </figure>

        <ul class="card-summary">
            <?php
                if ( function_exists( 'instapress_show_summary' ) ) {
                    instapress_show_summary();
                }
            ?>
        </ul>
    </div>
</article>