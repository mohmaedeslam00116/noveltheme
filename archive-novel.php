<?php get_header(); ?>

<div class="container">
    <header class="section-title section-title--split">
        <h2><?php esc_html_e('أرشيف الروايات المترجمة', 'noveltheme'); ?></h2>
        <a class="button" href="<?php echo esc_url(get_post_type_archive_link('chapter')); ?>">
            <?php esc_html_e('أرشيف الفصول', 'noveltheme'); ?>
        </a>
    </header>

    <div class="card-grid card-grid--dense">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('story-card story-card--horizontal'); ?>>
                    <div class="story-card__cover">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php else : ?>
                            <div class="cover-placeholder"><?php esc_html_e('غلاف', 'noveltheme'); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="story-card__body">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <div class="story-card__actions">
                            <a class="button" href="<?php the_permalink(); ?>"><?php esc_html_e('عرض التفاصيل', 'noveltheme'); ?></a>
                            <a class="button button--ghost" href="<?php the_permalink(); ?>"><?php esc_html_e('ابدأ القراءة', 'noveltheme'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('لا توجد روايات مترجمة بعد.', 'noveltheme'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
