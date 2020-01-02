<?php get_header(); ?>

<!-- swfobject library for cross-browser flash support -->
<script type="text/javascript" src="/myscripts/jquery.swfobject.1-1-1.min.js?v=03092017"></script>
<!-- BB Dev: Following script implements dynamic slideUp/slideDown for post or part of the post -->
<script type="text/javascript" src="/myscripts/dynamicpost.js?v=03092017"></script>
<!-- BB Dev: Following script creates thumbs from youtube videos -->
<script type="text/javascript" src="/myscripts/ytbthumb.js?v=03092017"></script>
<script type="text/javascript" src="/myscripts/ytbthumb_iframe.js?v=03092017"></script>

<div id="container">

	<?php if(have_posts()): ?>

	<?php $title = single_cat_title("", false); ?>

	<?php if (!empty($title)){ ?>
	<div class="archive_head_h2">Записи в разделе '<?php echo $title; ?>'</div>
	<?php } ?>
    
<?php $curdir='/wp-content/themes/fastway2'; ?>
    
	<script type="text/javascript">
		var content_loaded = false;
		jQuery(function() {
			jQuery(".toggle").click(function(event){
				event.preventDefault();
				$this = jQuery(this);				
				var ajax_load = "<img src='<?php echo $curdir;?>/images/ajax-loader.gif' alt='loading...' />"; 
				var loadUrl = "<?php echo $curdir;?>/archive_content.php?cat=<?php echo get_query_var('cat');?>";
			
				if (content_loaded == false)
					jQuery('#content_container').html(ajax_load)  
						.load(loadUrl, function() {
							content_loaded = true;
							toggle();
						});
				else
					toggle();
			});
		});
		
		function toggle()
		{
			$ul = jQuery("#content_container").children("ul");
			if ($ul.css("display") == "none"){
				$this.removeClass("x-plus").addClass("x-minus");
				$ul.show("slow");
			} else {
				$this.removeClass("x-minus").addClass("x-plus");
				$ul.hide("slow");
			}
		}

  </script>
<?php 
	// BB Develop: TOC of all posts in category
	$cat = get_query_var('cat');
	if ($cat != "") {
		echo '<div id="content">';
		echo '<a id="content_link" class="toggle x-plus" href="#">Содержание</a>';
		echo '<div id="content_container">';
		echo '</div>';
		echo '</div>';
	}
?> 


<?php while(have_posts()):the_post(); ?>
<?php
$post_class = "post";
$post_short = false;

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

			<div id="div-h2-post"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
			<?php if (! $post_short) { /*BB Dev: if added*/?> 
			<div class="postinfo">
<span class="postdate"><?php the_time('j.m.Y') ?>, <?php the_time('H:i') ?> [#<?php the_ID(); ?>]</span>
			</div>
			<?php } /*BB Dev*/ ?>
			
			<div class="entry">

				<?php the_content("[Read more &rarr;]"); ?>


				<?php if (! $post_short) { /*BB Dev: if added*/?>
				<p class="postmetadata">
<?php _e('Тема &#58;'); ?> <?php the_category(', ') ?>
				</p>
				<?php } /*BB Dev*/?>
				
<p class="postmetadata_links">
<?php if (! $post_short) { /*BB Dev if/else added*/?>				
	<span class="left">
	<?php edit_post_link('Редактировать', '', ''); ?>
	<?php comments_popup_link('Комментировать', 'Комментариев: 1', 'Комментариев: %'); ?>
	</span>
	<span class="right"> 
	<a class="thickbox" href="/wp-content/themes/fastway2/share_this.php?url=<?php echo get_permalink() ?>&amp;title=<?php echo get_the_title() ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=300" title="" target="blank">Закладки</a>
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

		</div>

	<?php endwhile; ?>

		<div class="postnav">  <!--BB Dev: Added wp-navi-->
			<?php if(function_exists('wp_pagenavi')) {                                                           
                                        wp_pagenavi();                                                                       
                                } 
				else {                                                                                     
                                        posts_nav_link(' &#8212; ', '&laquo; Предыдущая страница', 'Следующая страница &raquo;');
                                }                                                                                            
                        ?>        
		</div>

	<?php else: ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<div id="div-h2-post"><?php _e('Not Found'); ?></div>

		</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div>
</body>
</html>
