<?php get_header(); ?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-card">
            <h1><?php esc_html_e('عالم الروايات بين يديك', 'noveltheme'); ?></h1>
            <p><?php esc_html_e('استكشف أحدث الإصدارات، تابع الفصول الجديدة، وشارك آراءك مع مجتمع القرّاء.', 'noveltheme'); ?></p>
            <a class="button" href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">
                <?php esc_html_e('ابدأ القراءة', 'noveltheme'); ?>
            </a>
        </div>
        <div>
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
                <div class="notice">
                    <?php esc_html_e('أضف صورة بارزة لتمنح صفحتك الرئيسية طابعاً مميزاً.', 'noveltheme'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2><?php esc_html_e('أحدث الروايات', 'noveltheme'); ?></h2>
        <a class="button" href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">
            <?php esc_html_e('كل الروايات', 'noveltheme'); ?>
        </a>
    </div>

    <div class="card-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('story-card'); ?>>
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
