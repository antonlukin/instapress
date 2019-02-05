<?php
/**
 * Common content part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <figure class="entry-thumbnail">
        <?php
            the_post_thumbnail(
                'post-thumbnail',
                array( 'class' => 'entry-thumbnail__image' )
            );
        ?>
    </figure>

    <footer class="entry-summary">
        <?php
            printf(
                '<p class="entry-summary-field"><span>"%s"</span>: <strong>"%s"</strong>,</p>',
                __( 'date', 'instapress' ),
                get_the_date()
            );

            printf(
                '<p class="entry-summary-field"><span>"%s"</span>: <strong>"%s"</strong>,</p>',
                __( 'author', 'instapress' ),
                get_the_author()
            );

            printf(
                '<p class="entry-summary-field"><span>"%s"</span>: <strong>"%s"</strong></p>',
                __( 'title', 'instapress' ),
                get_the_title()
            );
        ?>
    </footer>
</article>