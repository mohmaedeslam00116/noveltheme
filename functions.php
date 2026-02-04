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

function noveltheme_add_novel_meta_boxes() {
    add_meta_box(
        'noveltheme_novel_details',
        __('تفاصيل الرواية', 'noveltheme'),
        'noveltheme_render_novel_meta_box',
        'novel',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'noveltheme_add_novel_meta_boxes');

function noveltheme_render_novel_meta_box($post) {
    wp_nonce_field('noveltheme_save_novel_meta', 'noveltheme_novel_nonce');
    $author_name = get_post_meta($post->ID, '_noveltheme_author_name', true);
    $status = get_post_meta($post->ID, '_noveltheme_status', true);
    $views = get_post_meta($post->ID, '_noveltheme_views', true);
    $rating = get_post_meta($post->ID, '_noveltheme_rating', true);
    ?>
    <p>
        <label for="noveltheme-author-name"><?php esc_html_e('اسم المؤلف', 'noveltheme'); ?></label>
        <input type="text" id="noveltheme-author-name" name="noveltheme_author_name" value="<?php echo esc_attr($author_name); ?>" class="widefat">
    </p>
    <p>
        <label for="noveltheme-status"><?php esc_html_e('حالة الرواية', 'noveltheme'); ?></label>
        <select id="noveltheme-status" name="noveltheme_status" class="widefat">
            <?php
            $statuses = [
                'ongoing' => __('مستمرة', 'noveltheme'),
                'completed' => __('مكتملة', 'noveltheme'),
                'hiatus' => __('متوقفة', 'noveltheme'),
            ];
            foreach ($statuses as $key => $label) :
                ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
        <p style="margin: 0;">
            <label for="noveltheme-views"><?php esc_html_e('عدد المشاهدات', 'noveltheme'); ?></label>
            <input type="number" min="0" id="noveltheme-views" name="noveltheme_views" value="<?php echo esc_attr($views); ?>" class="widefat">
        </p>
        <p style="margin: 0;">
            <label for="noveltheme-rating"><?php esc_html_e('التقييم', 'noveltheme'); ?></label>
            <input type="number" step="0.1" min="0" max="5" id="noveltheme-rating" name="noveltheme_rating" value="<?php echo esc_attr($rating); ?>" class="widefat">
        </p>
    </div>
    <?php
}

function noveltheme_save_novel_meta($post_id) {
    if (!isset($_POST['noveltheme_novel_nonce']) || !wp_verify_nonce($_POST['noveltheme_novel_nonce'], 'noveltheme_save_novel_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $author_name = isset($_POST['noveltheme_author_name']) ? sanitize_text_field($_POST['noveltheme_author_name']) : '';
    $status = isset($_POST['noveltheme_status']) ? sanitize_text_field($_POST['noveltheme_status']) : 'ongoing';
    $views = isset($_POST['noveltheme_views']) ? absint($_POST['noveltheme_views']) : 0;
    $rating = isset($_POST['noveltheme_rating']) ? floatval($_POST['noveltheme_rating']) : 0;

    update_post_meta($post_id, '_noveltheme_author_name', $author_name);
    update_post_meta($post_id, '_noveltheme_status', $status);
    update_post_meta($post_id, '_noveltheme_views', $views);
    update_post_meta($post_id, '_noveltheme_rating', $rating);
}
add_action('save_post_novel', 'noveltheme_save_novel_meta');

function noveltheme_add_chapter_details_meta_box() {
    add_meta_box(
        'noveltheme_chapter_details',
        __('تفاصيل الفصل', 'noveltheme'),
        'noveltheme_render_chapter_details_meta_box',
        'chapter',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'noveltheme_add_chapter_details_meta_box');

function noveltheme_render_chapter_details_meta_box($post) {
    wp_nonce_field('noveltheme_save_chapter_details', 'noveltheme_chapter_details_nonce');
    $chapter_number = get_post_meta($post->ID, '_noveltheme_chapter_number', true);
    $reading_time = get_post_meta($post->ID, '_noveltheme_reading_time', true);
    ?>
    <p>
        <label for="noveltheme-chapter-number"><?php esc_html_e('رقم الفصل', 'noveltheme'); ?></label>
        <input type="number" min="1" id="noveltheme-chapter-number" name="noveltheme_chapter_number" value="<?php echo esc_attr($chapter_number); ?>" class="widefat">
    </p>
    <p>
        <label for="noveltheme-reading-time"><?php esc_html_e('مدة القراءة (بالدقائق)', 'noveltheme'); ?></label>
        <input type="number" min="1" id="noveltheme-reading-time" name="noveltheme_reading_time" value="<?php echo esc_attr($reading_time); ?>" class="widefat">
    </p>
    <?php
}

function noveltheme_save_chapter_details($post_id) {
    if (!isset($_POST['noveltheme_chapter_details_nonce']) || !wp_verify_nonce($_POST['noveltheme_chapter_details_nonce'], 'noveltheme_save_chapter_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $chapter_number = isset($_POST['noveltheme_chapter_number']) ? absint($_POST['noveltheme_chapter_number']) : '';
    $reading_time = isset($_POST['noveltheme_reading_time']) ? absint($_POST['noveltheme_reading_time']) : '';

    if ($chapter_number) {
        update_post_meta($post_id, '_noveltheme_chapter_number', $chapter_number);
    } else {
        delete_post_meta($post_id, '_noveltheme_chapter_number');
    }

    if ($reading_time) {
        update_post_meta($post_id, '_noveltheme_reading_time', $reading_time);
    } else {
        delete_post_meta($post_id, '_noveltheme_reading_time');
    }
}
add_action('save_post_chapter', 'noveltheme_save_chapter_details');

function noveltheme_novel_columns($columns) {
    $columns['noveltheme_status'] = __('الحالة', 'noveltheme');
    $columns['noveltheme_views'] = __('المشاهدات', 'noveltheme');
    $columns['noveltheme_rating'] = __('التقييم', 'noveltheme');
    return $columns;
}
add_filter('manage_novel_posts_columns', 'noveltheme_novel_columns');

function noveltheme_novel_columns_content($column, $post_id) {
    if ($column === 'noveltheme_status') {
        $status = get_post_meta($post_id, '_noveltheme_status', true);
        $labels = [
            'ongoing' => __('مستمرة', 'noveltheme'),
            'completed' => __('مكتملة', 'noveltheme'),
            'hiatus' => __('متوقفة', 'noveltheme'),
        ];
        echo esc_html($labels[$status] ?? __('غير محدد', 'noveltheme'));
    }
    if ($column === 'noveltheme_views') {
        echo esc_html((string) get_post_meta($post_id, '_noveltheme_views', true));
    }
    if ($column === 'noveltheme_rating') {
        echo esc_html((string) get_post_meta($post_id, '_noveltheme_rating', true));
    }
}
add_action('manage_novel_posts_custom_column', 'noveltheme_novel_columns_content', 10, 2);

function noveltheme_chapter_columns($columns) {
    $columns['noveltheme_chapter_number'] = __('رقم الفصل', 'noveltheme');
    $columns['noveltheme_reading_time'] = __('مدة القراءة', 'noveltheme');
    return $columns;
}
add_filter('manage_chapter_posts_columns', 'noveltheme_chapter_columns');

function noveltheme_chapter_columns_content($column, $post_id) {
    if ($column === 'noveltheme_chapter_number') {
        echo esc_html((string) get_post_meta($post_id, '_noveltheme_chapter_number', true));
    }
    if ($column === 'noveltheme_reading_time') {
        $time = get_post_meta($post_id, '_noveltheme_reading_time', true);
        if ($time) {
            echo esc_html(sprintf(__('%s دقيقة', 'noveltheme'), $time));
        }
    }
}
add_action('manage_chapter_posts_custom_column', 'noveltheme_chapter_columns_content', 10, 2);
