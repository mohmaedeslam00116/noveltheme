<?php get_header(); ?>

<?php
$featured_novel = new WP_Query([
    'post_type' => 'novel',
    'posts_per_page' => 1,
    'meta_key' => '_thumbnail_id',
]);
?>

<section class="hero hero--app">
    <div class="container hero-grid">
        <div class="hero-card hero-card--glass">
            <span class="pill"><?php esc_html_e('الأكثر شهرة', 'noveltheme'); ?></span>
            <?php if ($featured_novel->have_posts()) : ?>
                <?php while ($featured_novel->have_posts()) : $featured_novel->the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <div class="hero-actions">
                        <a class="button" href="<?php the_permalink(); ?>">
                            <?php esc_html_e('ابدأ القراءة', 'noveltheme'); ?>
                        </a>
                        <a class="button button--ghost" href="<?php echo esc_url(get_post_type_archive_link('novel')); ?>">
                            <?php esc_html_e('عرض المزيد', 'noveltheme'); ?>
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <h1><?php esc_html_e('عالم الروايات بين يديك', 'noveltheme'); ?></h1>
                <p><?php esc_html_e('ابدأ بإضافة روايتك الأولى لتظهر هنا كعنوان بارز.', 'noveltheme'); ?></p>
            <?php endif; ?>
        </div>
        <div class="hero-media">
            <?php if ($featured_novel->have_posts()) : ?>
                <?php while ($featured_novel->have_posts()) : $featured_novel->the_post(); ?>
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="notice">
                    <?php esc_html_e('أضف صورة بارزة للرواية لتظهر كبطاقة مميزة.', 'noveltheme'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2><?php esc_html_e('أحدث الفصول', 'noveltheme'); ?></h2>
        <a class="button button--ghost" href="<?php echo esc_url(get_post_type_archive_link('chapter')); ?>">
            <?php esc_html_e('كل الفصول', 'noveltheme'); ?>
        </a>
    </div>

    <div class="chapter-list">
        <?php
        $latest_chapters = new WP_Query([
            'post_type' => 'chapter',
            'posts_per_page' => 5,
        ]);
        if ($latest_chapters->have_posts()) :
            while ($latest_chapters->have_posts()) :
                $latest_chapters->the_post();
                $novel_id = get_post_meta(get_the_ID(), '_noveltheme_novel_id', true);
                ?>
                <article <?php post_class('chapter-card'); ?>>
                    <div class="chapter-card__info">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ($novel_id) : ?>
                            <p class="chapter-card__novel">
                                <span><?php esc_html_e('الرواية:', 'noveltheme'); ?></span>
                                <a href="<?php echo esc_url(get_permalink($novel_id)); ?>">
                                    <?php echo esc_html(get_the_title($novel_id)); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <div class="story-meta">
                            <span><?php echo esc_html(get_the_date()); ?></span>
                            <span><?php echo esc_html(get_the_author()); ?></span>
                        </div>
                    </div>
                    <div class="chapter-card__cover">
                        <?php if ($novel_id && has_post_thumbnail($novel_id)) : ?>
                            <?php echo get_the_post_thumbnail($novel_id, 'thumbnail'); ?>
                        <?php else : ?>
                            <div class="cover-placeholder"><?php esc_html_e('غلاف', 'noveltheme'); ?></div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p><?php esc_html_e('لا توجد فصول بعد.', 'noveltheme'); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2><?php esc_html_e('أضيف حديثاً', 'noveltheme'); ?></h2>
        <a class="button button--ghost" href="<?php echo esc_url(get_post_type_archive_link('novel')); ?>">
            <?php esc_html_e('كل الروايات', 'noveltheme'); ?>
        </a>
    </div>

    <div class="card-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('story-card story-card--app'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php endif; ?>
                    <div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="story-meta">
                            <span><?php echo esc_html(get_the_date()); ?></span>
                            <span><?php echo esc_html(get_the_author()); ?></span>
                        </div>
                    </div>
                    <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <a class="button" href="<?php the_permalink(); ?>">
                        <?php esc_html_e('تابع القراءة', 'noveltheme'); ?>
                    </a>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('لا توجد روايات بعد. ابدأ بنشر أول رواية.', 'noveltheme'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
