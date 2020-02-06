<?php
/**
 * Single content template part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <div class="card">
        <figure class="card-thumbnail">
            <?php
                the_post_thumbnail( 'featured',
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

    <div class="entry">
        <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">',
                    'after'  => '</div>',
                    'next_or_number' => 'next'
                )
            );
        ?>
    </div>
</article>