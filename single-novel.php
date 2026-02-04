<?php get_header(); ?>

<div class="container layout">
    <article class="post-content">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <?php if (has_post_thumbnail()) : ?>
                    <div>
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                <div class="novel-meta">
                    <div class="novel-meta__item">
                        <span><?php esc_html_e('الفصول', 'noveltheme'); ?></span>
                        <strong>
                            <?php
                            $chapter_count = new WP_Query([
                                'post_type' => 'chapter',
                                'posts_per_page' => -1,
                                'fields' => 'ids',
                                'meta_key' => '_noveltheme_novel_id',
                                'meta_value' => get_the_ID(),
                            ]);
                            echo esc_html($chapter_count->found_posts);
                            wp_reset_postdata();
                            ?>
                        </strong>
                    </div>
                    <div class="novel-meta__item">
                        <span><?php esc_html_e('الكاتب', 'noveltheme'); ?></span>
                        <strong><?php echo esc_html(get_the_author()); ?></strong>
                    </div>
                </div>

                <?php the_content(); ?>

                <?php
                $genres = get_the_terms(get_the_ID(), 'novel_genre');
                if ($genres && !is_wp_error($genres)) :
                    ?>
                    <div class="genre-tags">
                        <?php foreach ($genres as $genre) : ?>
                            <span class="genre-tag"><?php echo esc_html($genre->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <section class="chapter-section">
                    <h2><?php esc_html_e('الفصول المتاحة', 'noveltheme'); ?></h2>
                    <ul class="chapter-links">
                        <?php
                        $chapters = new WP_Query([
                            'post_type' => 'chapter',
                            'posts_per_page' => -1,
                            'meta_key' => '_noveltheme_novel_id',
                            'meta_value' => get_the_ID(),
                            'orderby' => 'date',
                            'order' => 'ASC',
                        ]);
                        if ($chapters->have_posts()) :
                            while ($chapters->have_posts()) :
                                $chapters->the_post();
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </li>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            ?>
                            <li><?php esc_html_e('لا توجد فصول بعد.', 'noveltheme'); ?></li>
                        <?php endif; ?>
                    </ul>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>
    </article>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
