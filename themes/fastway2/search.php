<?php get_header(); ?>

<div id="container">

	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>

<?php
$post_class = "post";

$tags = wp_get_post_tags( get_the_ID() );
if ( !empty( $tags ) ) {
	foreach ( $tags as $tag ) {
		if (preg_match('/lesson-zoar/', $tag->name)) {
			$post_class = "post-zoar";
			break;
		}
	}
}
?>

		<div class="<?php echo $post_class; ?>">

			<div id="div-h2-post"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
                        <div class="postinfo">
<span class="postdate"><?php the_time('j.m.Y') ?>, <?php the_time('H:i') ?> [#<?php the_ID(); ?>]</span>
                        </div>

			<div class="entry">

				<?php the_content(); ?>

				<?php wp_link_pages('<p><strong>Страницы:</strong>','</p>','номер'); ?>

				<p class="postmetadata">
<?php _e('Тема &#58;'); ?> <?php the_category(', ') ?>
				</p>
<p class="postmetadata_links">
<span class="left">
<?php edit_post_link('Редактировать', '', ''); ?>
<?php comments_popup_link('Комментировать', 'Комментариев: 1', 'Комментариев: %'); ?>
</span>
<span class="right"> 
<a class="thickbox" href="/wp-content/themes/fastway2/share_this.php?url=<?php echo get_permalink() ?>&amp;title=<?php echo get_the_title() ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=300" title="" target="blank">
Закладки</a>
<?php if(function_exists('wp_email')) { email_link(); } ?> 
<?php if(function_exists('wp_print')) { print_link(); } ?>
</span>
</p>

			</div>
<p class="clear"></p>
		</div>

	<?php endwhile; ?>

		<div class="postnav">
			<?php posts_nav_link(' &#8212; ', '&laquo; Предыдущая страница', 'Следующая страница &raquo;'); ?>
		</div>

	<?php else: ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<div id="div-h2-post"><?php _e('Не найдено'); ?></div>

		</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div></body>
</html>
