<?php
/**
 * Page content template part
 *
 * @package instapress
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
    <header class="entry-header">
        <?php
            the_title('<h1 class="entry-header-title">', '</h1>');
        ?>
    </header>

    <div class="entry-article">
        <?php
            the_content();
        ?>
    </div>
</article>