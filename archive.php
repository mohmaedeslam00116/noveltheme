<?php get_header(); ?>

<div class="container">
    <header class="section-title">
        <h2><?php the_archive_title(); ?></h2>
        <?php the_archive_description('<p>', '</p>'); ?>
    </header>

    <div class="card-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('story-card'); ?>>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="story-meta">
                        <span><?php echo esc_html(get_the_date()); ?></span>
                        <span><?php echo esc_html(get_the_author()); ?></span>
                    </div>
                    <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <a class="button" href="<?php the_permalink(); ?>"><?php esc_html_e('قراءة المزيد', 'noveltheme'); ?></a>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('لا توجد نتائج.', 'noveltheme'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
