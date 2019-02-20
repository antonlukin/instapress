<?php
/**
 * The template for displaying 404 pages
 *
 * @package instapress
 * @since 1.0
 */

get_header(); ?>

<section class="content">
    <?php
        get_template_part( 'partials/content', 'none' );
    ?>
</section>

<?php get_footer();