<?php
/**
 * Search archive caption part
 *
 * @package instapress
 * @since 1.1
 */
?>

<div class="caption">
    <?php
        printf(
            '<h1 class="caption-title">%s &laquo;%s&raquo;</h1>',
            esc_html__( 'Search results for:', 'instapress' ),
            get_search_query()
        );
    ?>
</div>
