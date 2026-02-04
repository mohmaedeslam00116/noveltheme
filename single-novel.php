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
                <?php the_content(); ?>

                <section>
                    <h2><?php esc_html_e('الفصول المتاحة', 'noveltheme'); ?></h2>
                    <ul>
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
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
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
