<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package instapress
 * @since 1.1
 */
?>

<div class="caption">
    <?php
        printf(
            '<h1 class="caption-title">%s</h1>',
            esc_html__( 'Not Found', 'instapress' )
        );

        printf(
            '<h1 class="caption-text">%s</h1>',
            esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'instapress' )
        );
    ?>
</div>