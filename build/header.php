<?php
/**
 * The header file
 *
 * @package instapress
 * @since 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#21252b">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
    wp_body_open();
?>

<header class="header">
    <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'header-menu',
                'items_wrap' => '<ul class="menu">%3$s</ul>',
                'echo' => true,
                'depth' => 1
            ) );
        }
    ?>
</header>