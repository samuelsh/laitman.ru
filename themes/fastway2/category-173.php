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
	<div class="archive_head_h2"> '<?php echo $title; ?>'</div>
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



	// BB Develop: Show the posts	

	// Init a variable to store the values of the original WP Query
	global $post;
	
	// Init a variable for a new WP Query to search for posts tagged with 'supporter'
	
	$args = array (
		//'post__in' => $post_ids,
                'cat' => 173, 
		'paged' => get_query_var('paged'), 
		'order' => DESC,
		'posts_per_page' => 50,
		'orderby' => 'meta_value', 
		'meta_key' => 'postdate'
        );
	// Instantiate a new query
	$radio_posts = new WP_Query($args); 
?> 

<?php if ($radio_posts->have_posts()) : ?>
<?php while($radio_posts->have_posts()):$radio_posts->the_post(); ?>
<?php
		$post_title = get_the_title();
		preg_match_all('/[0-9]|\./', $post_title, $post_title_splitted);
		$post_title = implode($post_title_splitted[0]);
?>		
	<div class="special_video_post">
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="postinfo">
				<span class="postdate"><?php echo $post_title; ?></span>
			</div>
			<div class="entry">
				<?php the_content("[Read more &rarr;]"); ?>
			</div><!-- entry -->
		</div><!-- post -->
	</div>
<?php
	endwhile;
?>
<?php 
	endif; 
?>
	<div class="post">
	<div class="postinfo" ></div>
	</div>
	
	<div class="postnav">  <!--BB Dev: Added wp-navi-->
			<?php if(function_exists('wp_pagenavi')) {                                                           
                                        wp_pagenavi(array('query' => $radio_posts));                                                                       
                                } 
				else {                                                                                     
                                        posts_nav_link(' &#8212; ', '&laquo; Предыдущая страница', 'Следующая страница &raquo;');
                                }                                                                                            
	// Restore the $wp_query back to its original state
	$wp_query = $temp;
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

</body>
</html>
