<?php
/**
 * The template for displaying all single posts
 *
 * @package instapress
 * @since 1.0
 */

get_header(); ?>

<section class="content">
    <?php
        if ( have_posts() ) :
            while( have_posts() ) : the_post();
                get_template_part( 'partials/content', 'single' );

                // If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
            endwhile;
        else :
            get_template_part( 'partials/content', 'none' );
        endif;
    ?>
</section>

<?php get_footer();