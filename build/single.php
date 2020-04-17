<?php
/**
 * Single post template
 *
 * @package instapress
 * @since 1.0
 */

get_header(); ?>

<section class="content" id="content">
    <?php
        if ( have_posts() ) {
            while( have_posts() ) {
                the_post();

                // Get single partial
                get_template_part( 'partials/content', 'single' );

                // If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
            }
        } else {
            get_template_part( 'partials/caption', 'none' );
        }
    ?>
</section>

<?php get_footer();