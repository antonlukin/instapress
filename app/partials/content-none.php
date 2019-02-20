<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package instapress
 * @since 1.0
 */
?>

<article class="post">
    <div class="entry-article">
        <?php
            printf(
                '<p>%s %s</p>',
                __( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'instapress' ),

                sprintf(
                    __( 'Try to find something interesting on <a href="%s">the front page</a>.', 'instapress'),
                    home_url( '/' )
                )
            );
        ?>
    </div>
</article>