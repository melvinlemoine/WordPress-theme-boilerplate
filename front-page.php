<?php get_header() ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <header></header>

      <main></main>

    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer() ?>
