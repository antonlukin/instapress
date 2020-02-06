<?php
/**
 * Archive caption part
 *
 * @package instapress
 * @since 1.1
 */
?>

<div class="caption">
    <?php
        printf(
            '<h1 class="caption-title">%s</h1>',
            wp_kses_post( get_the_archive_title() )
        );
    ?>
</div>
