<?php
/**
 * Archive template file
 *
 * @package instapress
 * @since 1.1
 */

get_header(); ?>

<section class="content" id="content">
    <?php
        if ( have_posts() ) {
            if ( get_the_archive_title() ) {
                get_template_part( 'partials/caption' );
            }

            while( have_posts() ) {
                the_post();

                // Include default content partial
                get_template_part( 'partials/content' );
            }

            // Show navigation
            the_posts_pagination();
        } else {
            // If no content, include the "No posts found" template
            get_template_part( 'partials/caption', 'none' );
        }
    ?>
</section>

<?php get_footer();