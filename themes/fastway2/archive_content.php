<?php 
	include_once('/sites/laitman.ru/public/wp-load.php');
	
	// BB Develop: TOC of all posts in category
	$cat = intval($_GET["cat"]);
	$sql = "
		select
			ID, post_title, post_status
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
		) and ID NOT IN (
			SELECT post_id FROM `wp_postmeta` WHERE meta_key = '_wplp_post_category'		
		)
		and post_status = 'publish'		
		order by post_date desc
	"; 
	#ZZZ There are old posts when there were no categories
	if ($cat != "") {
		$posts_in_category = $wpdb->get_results($sql);	
		echo '<ul style="display: none;">';
		// Som: foreach optimisation
		$size_of_objects = sizeof($posts_in_category);
		
		for ($i=0;$i<$size_of_objects;$i++)
		{
			$post_link = apply_filters('the_permalink', get_permalink($posts_in_category[$i]->ID));
			echo '<li><a href="'.$post_link.'" title = "'.$posts_in_category[$i]->post_title.'">'.$posts_in_category[$i]->post_title.'</a></li>';
			update_post_caches($posts);	
		}
		echo '</ul>';
		echo '</div>';
	}
?>