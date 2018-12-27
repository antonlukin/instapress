<?php
/**
 * Template part for displaying posts
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <figure class="post__thumbnail">
		<?php the_post_thumbnail(); ?>
	</figure>

    <div class="post__content">
        <ul class="post__content-summary summary">
            <?php
                printf(
                    '<p class="summary__field"><span>"%s"</span>: <strong>"%s"</strong>,</p>',
                    __( 'date', 'instapress' ),
                    get_the_date()
                );

                printf(
                    '<p class="summary__field"><span>"%s"</span>: <strong>"%s"</strong>,</p>',
                    __( 'author', 'instapress' ),
                    get_the_author()
                );

                printf(
                    '<p class="summary__field"><span>"%s"</span>: <strong>"%s"</strong></p>',
                    __( 'title', 'instapress' ),
                    get_the_title()
                );
            ?>
        </ul>
    </div>
</article>