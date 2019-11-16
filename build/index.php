<?php
/**
 * The main template file
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

                // Include default content partial
                get_template_part( 'partials/content' );
            }

            // Show navigation
            the_posts_pagination();
        } else {
            // If no content, include the "No posts found" template
            get_template_part( 'partials/content', 'none' );
        }
    ?>
</section>

<?php get_footer();