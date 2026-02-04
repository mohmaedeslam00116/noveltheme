<aside class="sidebar">
    <?php if (is_active_sidebar('noveltheme-sidebar')) : ?>
        <?php dynamic_sidebar('noveltheme-sidebar'); ?>
    <?php else : ?>
        <section class="widget">
            <h3><?php esc_html_e('ابحث عن رواية مترجمة', 'noveltheme'); ?></h3>
            <?php get_search_form(); ?>
        </section>
        <section class="widget">
            <h3><?php esc_html_e('تصنيفات الروايات', 'noveltheme'); ?></h3>
            <ul>
                <?php wp_list_categories(['title_li' => '']); ?>
            </ul>
        </section>
        <section class="widget">
            <h3><?php esc_html_e('أرشيف الفصول', 'noveltheme'); ?></h3>
            <ul>
                <?php wp_get_archives(); ?>
            </ul>
        </section>
    <?php endif; ?>
</aside>
