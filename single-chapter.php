<?php get_header(); ?>

<div class="container layout">
    <article class="post-content">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <div class="story-meta">
                    <span><?php echo esc_html(get_the_date()); ?></span>
                    <span><?php echo esc_html(get_the_author()); ?></span>
                </div>
                <?php the_content(); ?>

                <?php
                $novel_id = get_post_meta(get_the_ID(), '_noveltheme_novel_id', true);
                if ($novel_id) :
                    ?>
                    <p>
                        <strong><?php esc_html_e('الرواية:', 'noveltheme'); ?></strong>
                        <a href="<?php echo esc_url(get_permalink($novel_id)); ?>">
                            <?php echo esc_html(get_the_title($novel_id)); ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="chapter-nav">
            <div><?php previous_post_link('%link', '← ' . esc_html__('الفصل السابق', 'noveltheme')); ?></div>
            <div><?php next_post_link('%link', esc_html__('الفصل التالي', 'noveltheme') . ' →'); ?></div>
        </div>
    </article>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
