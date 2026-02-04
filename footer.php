</main>
<footer class="site-footer">
    <div class="container">
        <p><?php echo esc_html(get_bloginfo('name')); ?> &copy; <?php echo esc_html(date('Y')); ?> — منصة قراءة روايات ويب.</p>
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
</footer>
<?php wp_footer(); ?>
</body>
</html>
