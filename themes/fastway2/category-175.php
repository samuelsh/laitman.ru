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
	<div class="archive_head_h2">  '<?php echo $title; ?>'</div>
	<?php } ?>
    
	<script type="text/javascript">
		jQuery(function() {
			jQuery(".toggle").click(function(event){
				event.preventDefault();
				$this = jQuery(this);
				$ul = $this.siblings("ul");
				if ($ul.css("display") == "none"){
					$this.removeClass("x-plus").addClass("x-minus");
					$ul.show("slow");
				} else {
					$this.removeClass("x-minus").addClass("x-plus");
					$ul.hide("slow");
				}
			});
		});
  </script>
  
  <script type="text/javascript">
		jQuery(function() { // putting the date of the post and the post in the same line
			jQuery(".podPress_imgicon").each(function(){
				var date = jQuery(this).parents(".post").find(".postdate").text();
				var date = date + " ";
				jQuery(this).before(jQuery(this).parents(".post").find(".postdate").text(date));
			});
		});
  </script>

<?php 
	// BB Develop: TOC of all posts in category
	$cat = get_query_var('cat');
	$sql = "
		select
			ID, post_title
		from
			wp_posts
		where ID in (
			SELECT
				object_id
			FROM `wp_term_relationships`
			WHERE `term_taxonomy_id` in (
					SELECT
						term_taxonomy_id
					FROM
						`wp_term_taxonomy`
					WHERE
						`term_id` = ".$cat."
						and taxonomy = 'category'
			)
		)
		and post_status = 'publish'
		order by post_date desc
	";
	#ZZZ There are old posts when there were no categories
	if ($cat != "") {
		$posts_in_category = $wpdb->get_results($sql);
		
		echo '<div id="content">';
		echo '<a class="toggle x-plus" href="#">Содержание</a>';
		echo '<ul style="display: none;">';
/*
		foreach ($posts_in_category as $one_post)
		{
			$post_status = get_post_status($one_post->ID);
			if ($post_status != 'publish')
				continue;
			$post_link = apply_filters('the_permalink', get_permalink($one_post->ID));
			$post_title = get_the_title($one_post->ID);
			echo '<li><a href="'.$post_link.'" title = "'.$post_title.'">'.$post_title.'</a></li>';
			
		}*/
		// Som: foreach optimisation
		$size_of_objects = sizeof($posts_in_category);
		for ($i=0;$i<$size_of_objects;$i++) {
			$post_link = apply_filters('the_permalink', get_permalink($posts_in_category[$i]->ID));
			echo '<li><a href="'.$post_link.'" title = "'.$posts_in_category[$i]->post_title.'">'.$posts_in_category[$i]->post_title.'</a></li>';
		}
		echo '</ul>';
		echo '</div>';

	}
?>


<?php while(have_posts()):the_post(); ?>
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
