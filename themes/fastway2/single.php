<?php get_header(); ?>


<script type="text/javascript">
jQuery(function() {
	jQuery(".a-link-toggle").hide(); //hide dynamic links
	jQuery("object").css('display','block');
});
</script>

<div id="container">

	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>

<?php
$post_class = "post";
$post_short = false; //BB Dev

$tags = wp_get_post_tags( get_the_ID() );
if ( !empty( $tags ) ) {
	foreach ( $tags as $tag ) {
		if (preg_match('/lesson-zoar/', $tag->name)) {
			$post_class = "post-narrow"; //BB Dev: $post_class = "post-zohar"
			$post_short = true; //BB Dev
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
			<!-- BB Dev: repacing H2 to H1 for single posts -->
			<h1 id="post-h1"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
				<?php if (! $post_short) { /*BB Dev: if added*/?> 
				<div class="postinfo">
<span class="postdate"><?php the_time('j.m.Y') ?>, <?php the_time('H:i') ?> [#<?php the_ID(); ?>]</span>
				</div>
				<?php } /*BB Dev*/ ?>

			<div class="entry">

				<?php the_content(); ?>
				<?php wp_link_pages('<p><strong>Страницы:</strong>','</p>','номер'); ?>
				
				<?php if (! $post_short) { /*BB Dev: if added*/?>
				<p class="postmetadata">
<?php _e('Тема &#58;'); ?> <?php the_category(', ') ?>
				</p>
				<?php } /*BB Dev*/?>
				
<p class="postmetadata_links">
<?php if (! $post_short) { /*BB Dev if/else added*/?>	
<span class="left">
<?php edit_post_link('Редактировать', '', ''); ?>
</span>
<span class="right"> 
<a class="thickbox" href="/wp-content/themes/fastway2/share_this.php?url=<?php echo get_permalink() ?>&amp;title=<?php echo get_the_title() ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=300" title="" target="blank">
Закладки</a>
<?php if(function_exists('wp_email')) { email_link(); } ?>
<?php if(function_exists('wp_print')) { print_link(); } ?>
</span>
<?php } else { ?>
		<span class="right">
		<?php edit_post_link('Редактировать', '', ''); ?>
		<?php comments_popup_link('Комментировать', 'Комментариев: 1', 'Комментариев: %'); ?>
		<span class="email_link"><?php if(function_exists('wp_email')) { email_link(); } ?>
		</span>
		</span>
		<?php } ?>
</p>
			
			</div>
<?php if (! $post_short) { /*BB Dev: if/else added*/?>
				<p class="clear bborder"></p>
			<?php } else { ?>
				<p class="clear"></p>
			<?php } ?>
		
		<div class="comments-template">
			<?php comments_template(); ?>
		</div>

		</div>
		
	<?php endwhile; ?>

		<div class="postnav">
			<?php previous_post_link('&laquo; %link') ?> &#8212; <?php next_post_link('%link &raquo;') ?>
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
