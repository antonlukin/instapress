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
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#21252b">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="header">
    <?php
        if ( has_nav_menu( 'primary' ) ) :
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'echo' => true,
                'items_wrap' => '<ul class="navbar">%3$s</ul>',
                'container_class' => 'navbar__menu'
            ) );
        endif;
    ?>
</header>