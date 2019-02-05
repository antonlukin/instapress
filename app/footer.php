<?php
/**
 * The template for displaying the footer
 *
 * @package intsapress
 * @since 1.0
 */
?>

<footer class="footer">
    <div class="footer-copy">
        <?php
            echo get_theme_mod( 'footer-copy', '' );
        ?>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>