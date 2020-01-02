<?php get_header(); ?>

<!-- swfobject library for cross-browser flash support -->
<script type="text/javascript" src="/myscripts/jquery.swfobject.1-1-1.min.js"></script>
<!-- BB Dev: Following script implements dynamic slideUp/slideDown for post or part of the post -->
<script type="text/javascript" src="/myscripts/dynamicpost.js"></script>
<!-- BB Dev: Following script creates thumbs from youtube videos -->
<script type="text/javascript" src="/myscripts/ytbthumb.js"></script>
<script type="text/javascript" src="/myscripts/ytbthumb_iframe.js"></script>

<div id="container">
	
	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>

		<?php
			$post_class = "post";
			$post_short = false;

			$tags = wp_get_post_tags( get_the_ID() );
			if ( !empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					if (preg_match('/lesson-zoar/', $tag->name)) {
						$post_class = "post-narrow";
						$post_short = true;
						break;
					}elseif (preg_match('/simulator/', $tag->name)) {
						$post_class = "post-narrow";
						$post_short = true;
						break;
					}
				}
			}
		?>
		<div class="<?php echo $post_class; ?>">

			<?php $post_id = "post-" . get_the_ID(); ?>
			
			<div id="div-h2-post"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" name="<?php echo $post_id; ?>"><?php the_title(); ?></a></div>

			<?php if (! $post_short) { ?>
			<div class="postinfo">
				<span class="postdate"><?php the_time('j.m.Y') ?>, <?php the_time('H:i') ?> [#<?php the_ID(); ?>]</span>
			</div>
			<?php } ?>

			<div class="entry">

				<?php the_content("Click here to continue reading &rarr;"); ?>

				<?php if (! $post_short) { ?>
				<p class="postmetadata">
					<?php _e('Тема &#58;'); ?> <?php the_category(', ') ?>
				</p>
				<?php } ?>

				<p class="postmetadata_links">
					<?php if (! $post_short) { ?>
						<span class="left">
							<?php edit_post_link('Редактировать', '', ''); ?>
							<?php comments_popup_link('Комментировать', 'Комментариев: 1', 'Комментариев: %'); ?>
						</span>
						<span class="right">
							<a class="thickbox" href="/wp-content/themes/fastway2/share_this.php?url=<?php echo get_permalink() ?>&amp;title=<?php echo get_the_title() ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=300" title="" target="blank">Закладки</a>
							<span class="email_link"><?php if(function_exists('wp_email')) { email_link(); } ?></span>
							<span class="print_link"><?php if(function_exists('wp_print')) { print_link(); } ?></span>
						</span>
					<?php } else { ?>
						<span class="right">
							<?php edit_post_link('Редактировать', '', ''); ?>
							<?php comments_popup_link('Комментировать', 'Комментариев: 1', 'Комментариев: %'); ?>
							<span class="email_link"><?php if(function_exists('wp_email')) { email_link(); } ?></span>
						</span>
					<?php } ?>
				</p>
			</div>

			<?php if (! $post_short) { ?>
				<p class="clear bborder"></p>
			<?php } else { ?>
				<p class="clear"></p>
			<?php } ?>

		</div>

	<?php endwhile; ?>

		<div class="postnav">
			<?php if(function_exists('wp_pagenavi')) {
			  		wp_pagenavi();
				} else {
					posts_nav_link(' &#8212; ', '&laquo; Предыдущая страница', 'Следующая страница &raquo;');
				}
			?>
		</div>

	<?php else: ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<div id="div-h2-post"><?php _e('Не найдено'); ?></div>

		</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer() ?>

</div>
</body>
</html>
