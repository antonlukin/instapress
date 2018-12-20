<?php
/**
 * The template for displaying the footer
 *
 * @package intsapress
 * @since 1.0
 */
?>

<footer class="footer">
    <?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
        <aside class="footer__widgets" role="complementary">
            <?php
                dynamic_sidebar('sidebar-footer');
            ?>
        </div>
    <?php endif; ?>

    <div class="footer__copy">
        <?php
            echo get_theme_mod( 'footer-description' , '' );
        ?>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>