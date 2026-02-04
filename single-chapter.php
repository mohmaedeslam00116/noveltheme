<?php get_header(); ?>

<div class="container layout">
    <article class="post-content chapter-reader">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <div class="story-meta">
                    <?php
                    $chapter_number = get_post_meta(get_the_ID(), '_noveltheme_chapter_number', true);
                    $reading_time = get_post_meta(get_the_ID(), '_noveltheme_reading_time', true);
                    ?>
                    <?php if ($chapter_number) : ?>
                        <span><?php echo esc_html(sprintf(__('الفصل %s', 'noveltheme'), $chapter_number)); ?></span>
                    <?php endif; ?>
                    <?php if ($reading_time) : ?>
                        <span><?php echo esc_html(sprintf(__('%s دقيقة', 'noveltheme'), $reading_time)); ?></span>
                    <?php endif; ?>
                    <span><?php echo esc_html(get_the_date()); ?></span>
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
