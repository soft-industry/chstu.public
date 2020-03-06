<?php get_header() ?>

<?php if ( have_posts() ): ?>
	<?php while (have_posts()): the_post(); ?>
		<article>
			<h3>
				<?php the_title() ?>
			</h3>
			<time datetime="<?= get_the_date('Y-m-d') ?>">
				<?= get_the_date('d.m.Y') ?>
			</time>
			<a href="<?php the_permalink() ?>">
				<?php the_localization_string('Read more') ?>
			</a>
		</article>
	<?php endwhile;?>
<?php else: ?>
	<h3>
		<?php the_localization_string('No results for this query') ?>
	</h3>
<?php endif ?>

<?php get_footer() ?>