<?php get_header() ?>
<?php if (have_posts()): ?>
    <main>

        <div class="site-block site-block_light site-block_no-padding-bottom">
            <div class="container">
                <div class="row">

                    <?php if ( in_category('vacancies') ) get_template_part('template_parts/single/vacancy') ?>

                    <div class="col-xl-10 offset-xl-1">
                        <div class="site-block__content">
                            <?php the_content() ?>
                        </div>
                    </div>

                    <div class="col-xl-10 offset-xl-1">
                        <?php the_media_links() ?>
                    </div>

                </div>
            </div>
        </div>

        <?php the_single_nav(false, false) ?>

		<?php query_front_page_and_show_block(function(){
			get_template_part('template_parts/front-page/cta');
		}); ?>

    </main>
<?php endif ?>
<?php get_footer() ?>