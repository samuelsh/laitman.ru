<?php get_header(); ?>

<!-- swfobject library for cross-browser flash support -->
<script type="text/javascript" src="/myscripts/jquery.swfobject.1-1-1.min.js"></script>
<!-- BB Dev: Following script implements dynamic slideUp/slideDown for post or part of the post -->
<script type="text/javascript" src="/myscripts/dynamicpost.js"></script>
<!-- BB Dev: Following script creates thumbs from youtube videos -->
<script type="text/javascript" src="/myscripts/ytbthumb.js"></script>

<div id="container">

	<?php if(have_posts()): ?>

	<?php $title = single_cat_title("", false); ?>

	<?php if (!empty($title)){ ?>
	<div class="archive_head_h2">Записи в разделе '<?php echo $title; ?>'</div>
	<?php } ?>

<?php
	echo '<div id="content">';
	echo '<li><a href="http://www.laitman.ru/useful-links/kniga-zoar/">Книга Зоар</a></li>';
	echo '<li><a href="http://files.kab.co.il/files/rus-t-bb-hoveret_zohar-laam_le-pesah.doc">Зоар. К празднику Песах</a></li>';
	echo '<li><a href="http://www.laitman.ru/useful-links/kniga-zoar-konspekt/">Предисловие книги Зоар (конспект)</a></li>';	
	echo '</div>';
	
	$curdir='/wp-content/themes/fastway2'; ?>
    
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

				<?php the_content("[Read more &rarr;]"); ?>



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
Закладки</a>;
<?php if(function_exists('wp_email')) { email_link(); } ?> 
<?php if(function_exists('wp_print')) { print_link(); } ?>
</span>
</p>

			</div>
<p class="clear bborder"></p>

		</div>

	<?php endwhile; ?>

		<div class="postnav">
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
