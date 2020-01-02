<?php
/*
Template Name: lesson_video_page
*/
?>

<?php get_header(); ?>
<!-- BB Dev: swfobject library for cross-browser flash support -->
<script type="text/javascript" src="/myscripts/jquery.swfobject.1-1-1.min.js"></script>
<!-- BB Dev: Following script creates thumbs from youtube videos -->
<script type="text/javascript" src="/myscripts/ytbthumb.js"></script>
<?php
?>
<div id="container">

	<div class="archive_head_h2">Видео - яркие моменты</div>
    
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

<?php
	// BB Develop: Get all posts with given special post type
	$sql = "
		SELECT wposts.* 
		FROM $wpdb->posts wposts
		WHERE (wposts.post_title like '%Вы думали, каббала - это сложно%' 
		OR wposts.post_title like '%Каббала скрытой камерой%')
		AND wposts.post_status = 'publish' 
		AND wposts.post_type = 'post' 
		ORDER BY wposts.post_date DESC
	";

	$posts_in_category = $wpdb->get_results($sql);

	// BB Develop: TOC of all posts
	echo '<div id="content">';
	echo '<a class="toggle x-plus" href="#">Содержание</a>';
	echo '<ul style="display: none;">';
	$post_ids = array();
	foreach ($posts_in_category as $one_post)
	{
		$post_status = get_post_status($one_post->ID);
		$post_link = apply_filters('the_permalink', get_permalink($one_post->ID));
		$post_title = get_the_title($one_post->ID);
		$post_title_splitted = explode('\(|\)', $post_title);
		$post_title = $post_title_splitted[1];
		echo '<li><a href="'.$post_link.'" title = "'.$post_title.'">'.$post_title.'</a></li>';
		array_push($post_ids, $one_post->ID);
	}
	echo '</ul>';
	echo '</div>';
	echo '<div id="content">';
	echo '<span style="color: #800000;"><strong>Самые яркие моменты ежедневного урока:</strong></span>';
	echo '</div>';
	// BB Develop: Show the posts	

	// Init a variable to store the values of the original WP Query
	$temp = $wp_query;

	// Reset the $wp_query global variable
	$wp_query= null;
	
	// Init a variable for a new WP Query to search for posts tagged with 'supporter'
	
	$args = array (
		'post__in' => $post_ids,
		'paged' => $paged,
		'order' => DESC,
    );

	// Instantiate a new query
	$wp_query = new WP_Query($args); 

	if ($wp_query->have_posts()) :

	while($wp_query->have_posts()):$wp_query->the_post();
		$post_title = get_the_title();
		$post_title_splitted = explode('\(|\)', $post_title);
		$post_title = $post_title_splitted[1];
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
	<div class="postnav">
		<?php posts_nav_link(' &#8212; ', '&laquo; Предыдущая страница', 'Следующая страница &raquo;'); ?>
	</div>

<?php else: ?>

	<div id="div-h2-post"><?php _e('Not Found'); ?></div>

<?php 

	endif;

	// Restore the $wp_query back to its original state
	$wp_query = $temp;
?>


</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div></body>
</html>
