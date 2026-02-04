<?php

function noveltheme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

    register_nav_menus([
        'primary' => __('القائمة الرئيسية', 'noveltheme'),
        'footer' => __('قائمة التذييل', 'noveltheme'),
    ]);
}
add_action('after_setup_theme', 'noveltheme_setup');

function noveltheme_enqueue_assets() {
    wp_enqueue_style('noveltheme-style', get_stylesheet_uri(), [], '1.0.0');
    wp_enqueue_style('noveltheme-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap', [], null);
}
add_action('wp_enqueue_scripts', 'noveltheme_enqueue_assets');

function noveltheme_excerpt_length($length) {
    return 28;
}
add_filter('excerpt_length', 'noveltheme_excerpt_length');

function noveltheme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'noveltheme_excerpt_more');

function noveltheme_widgets_init() {
    register_sidebar([
        'name' => __('الشريط الجانبي للروايات', 'noveltheme'),
        'id' => 'noveltheme-sidebar',
        'description' => __('عناصر جانبية لعرض التصنيفات وأحدث الفصول.', 'noveltheme'),
        'before_widget' => '<section class="widget">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'noveltheme_widgets_init');

function noveltheme_register_custom_content() {
    register_post_type('novel', [
        'labels' => [
            'name' => __('الروايات', 'noveltheme'),
            'singular_name' => __('رواية', 'noveltheme'),
            'add_new' => __('إضافة رواية', 'noveltheme'),
            'add_new_item' => __('إضافة رواية جديدة', 'noveltheme'),
            'edit_item' => __('تعديل الرواية', 'noveltheme'),
            'new_item' => __('رواية جديدة', 'noveltheme'),
            'view_item' => __('عرض الرواية', 'noveltheme'),
            'search_items' => __('بحث في الروايات', 'noveltheme'),
            'not_found' => __('لم يتم العثور على روايات', 'noveltheme'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-book',
        'has_archive' => true,
        'rewrite' => ['slug' => 'novels'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
        'show_in_rest' => true,
    ]);

    register_post_type('chapter', [
        'labels' => [
            'name' => __('الفصول', 'noveltheme'),
            'singular_name' => __('فصل', 'noveltheme'),
            'add_new' => __('إضافة فصل', 'noveltheme'),
            'add_new_item' => __('إضافة فصل جديد', 'noveltheme'),
            'edit_item' => __('تعديل الفصل', 'noveltheme'),
            'new_item' => __('فصل جديد', 'noveltheme'),
            'view_item' => __('عرض الفصل', 'noveltheme'),
            'search_items' => __('بحث في الفصول', 'noveltheme'),
            'not_found' => __('لم يتم العثور على فصول', 'noveltheme'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-media-text',
        'has_archive' => true,
        'rewrite' => ['slug' => 'chapters'],
        'supports' => ['title', 'editor', 'author'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('novel_genre', ['novel'], [
        'labels' => [
            'name' => __('التصنيفات', 'noveltheme'),
            'singular_name' => __('تصنيف', 'noveltheme'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'genres'],
    ]);
}
add_action('init', 'noveltheme_register_custom_content');

function noveltheme_add_chapter_meta_box() {
    add_meta_box(
        'noveltheme_chapter_novel',
        __('ربط الفصل بالرواية', 'noveltheme'),
        'noveltheme_render_chapter_meta_box',
        'chapter',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'noveltheme_add_chapter_meta_box');

function noveltheme_render_chapter_meta_box($post) {
    wp_nonce_field('noveltheme_save_chapter_meta', 'noveltheme_chapter_nonce');
    $selected_novel = get_post_meta($post->ID, '_noveltheme_novel_id', true);
    $novels = get_posts([
        'post_type' => 'novel',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    ?>
    <label for="noveltheme-novel-select">
        <?php esc_html_e('اختر الرواية المرتبطة', 'noveltheme'); ?>
    </label>
    <select name="noveltheme_novel_id" id="noveltheme-novel-select" style="width: 100%; margin-top: 8px;">
        <option value=""><?php esc_html_e('بدون ربط', 'noveltheme'); ?></option>
        <?php foreach ($novels as $novel) : ?>
            <option value="<?php echo esc_attr($novel->ID); ?>" <?php selected($selected_novel, $novel->ID); ?>>
                <?php echo esc_html($novel->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function noveltheme_save_chapter_meta($post_id) {
    if (!isset($_POST['noveltheme_chapter_nonce']) || !wp_verify_nonce($_POST['noveltheme_chapter_nonce'], 'noveltheme_save_chapter_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $novel_id = isset($_POST['noveltheme_novel_id']) ? absint($_POST['noveltheme_novel_id']) : 0;
    if ($novel_id) {
        update_post_meta($post_id, '_noveltheme_novel_id', $novel_id);
    } else {
        delete_post_meta($post_id, '_noveltheme_novel_id');
    }
}
add_action('save_post_chapter', 'noveltheme_save_chapter_meta');

function noveltheme_filter_home_query($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_home()) {
        $query->set('post_type', ['novel']);
    }
}
add_action('pre_get_posts', 'noveltheme_filter_home_query');
