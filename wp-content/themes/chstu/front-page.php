<?php get_header() ?>

<?php if ( have_posts() ): the_post(); ?>
    <main>
        <h2>Front page</h2>
        <?php //get_front_page_blocks() ?>
    </main>
<?php endif ?>

<?php get_footer() ?>


