<?php get_header(); ?>

<div class="container layout">
    <article class="post-content">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </article>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
