<?php get_header(); ?>

<div id="container">

	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>
		<div class="post">

			<center><div id="div-h2-post"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></div></center>

			<div class="postinfo">&nbsp;</div>

			<div class="entry">

				<?php the_content(); ?>
				<?php wp_link_pages('<p><strong>Страницы:</strong>','</p>'); ?>
				<?php edit_post_link('Edit','<p>','</p>'); ?>

			</div>

		</div>

	<?php endwhile; ?>

	<?php else: ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<div id="div-h2-post"><?php _e('Не найден'); ?></div>

		</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div>
</body>
</html>
