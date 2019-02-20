<?php
/**
 * Page template
 *
 * @package instapress
 * @since 1.0
 */

get_header(); ?>

<section class="content">
    <?php
        if ( have_posts() ) {
            while( have_posts() ) {
                the_post();

                // Get page content partial
                get_template_part( 'partials/content', 'page' );
            }
         } else {
            get_template_part( 'partials/content', 'none' );
         }
    ?>
</section>

<?php get_footer();