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
                get_template_part( 'template-parts/content', 'single' );
            endwhile;
        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
    ?>
</section>

<?php get_footer();