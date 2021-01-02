<?php get_header() ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

        <div style="background-image: url('<?php the_post_thumbnail_url( 'full' ); ?>')"></div>
        <p><?php the_title() ?></p>
        <p><?php the_content() ?></p>
        <p><?php the_author() ?></p>
        <p><?php the_category() ?></p>

    <?php endwhile; ?>
<?php endif; ?>

<aside>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : ?>
<?php endif; ?>
</aside>


<?php get_footer() ?>
