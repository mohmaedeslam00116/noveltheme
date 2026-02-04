</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <p class="footer-brand">
                <?php echo esc_html(get_bloginfo('name')); ?>
                <span><?php esc_html_e('منصة روايات مترجمة', 'noveltheme'); ?></span>
            </p>
            <p><?php esc_html_e('نوفر لك أحدث الروايات المترجمة، جداول إصدار واضحة، وتجربة قراءة مريحة على كل الأجهزة.', 'noveltheme'); ?></p>
        </div>
        <div>
            <h3><?php esc_html_e('روابط سريعة', 'noveltheme'); ?></h3>
            <nav aria-label="<?php esc_attr_e('قائمة التذييل', 'noveltheme'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container' => false,
                    'fallback_cb' => false,
                    'items_wrap' => '<ul>%3$s</ul>',
                ]);
                ?>
            </nav>
        </div>
        <div>
            <h3><?php esc_html_e('ابدأ الآن', 'noveltheme'); ?></h3>
            <a class="button" href="<?php echo esc_url(get_post_type_archive_link('novel')); ?>">
                <?php esc_html_e('تصفح الروايات', 'noveltheme'); ?>
            </a>
        </div>
    </div>
    <div class="container footer-bottom">
        <p><?php echo esc_html(get_bloginfo('name')); ?> &copy; <?php echo esc_html(date('Y')); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
