<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container site-header__inner">
        <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
            <span>ر</span>
            <strong><?php bloginfo('name'); ?></strong>
        </a>
        <nav class="primary-nav" aria-label="<?php esc_attr_e('القائمة الرئيسية', 'noveltheme'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => false,
                'items_wrap' => '<ul>%3$s</ul>',
            ]);
            ?>
        </nav>
    </div>
</header>
<main class="site-main">
