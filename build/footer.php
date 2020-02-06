<?php
/**
 * The template for displaying the footer
 *
 * @package intsapress
 * @since 1.0
 */
?>

<footer class="footer">
    <?php
        if ( get_theme_mod( 'instapress_footer_copy' ) ) {
            printf(
                '<div class="footer-copy">%s</div>',
                wp_kses_post( get_theme_mod( 'instapress_footer_copy', '' ) )
            );
        }
    ?>
</footer>

<?php wp_footer(); ?>

</body>
</html>