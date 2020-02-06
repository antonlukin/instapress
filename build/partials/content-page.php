<?php
/**
 * Page content template part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
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