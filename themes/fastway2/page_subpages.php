<?php
/*
Template Name: Sub-pages
*/
?>

<?php get_header(); ?>
<?php
	global $post;
//echo "<pre>";
//print_r($post);
//echo "</pre>";

	$guid = $post->guid;
	$id = $post->ID;
	$title = $post->post_title;
	$content = $post->post_content;
	$post_name = $post->post_name;
	$post_ref = get_bloginfo('url')."/$post_name";
?>
<div id="container">

	<div class="post">
		<center><div id="div-h2-post"><a href="<?php echo $post_ref; ?>"><?php echo $title; ?></a></div></center>
		<div class="postinfo">&nbsp;</div>
		<div class="entry sub-pages">
			<ul>
				<?php
				if (preg_match('/\[links (.*)\]/', $content, $matches)) {
					if (preg_match("/[\d]+/", $matches[1], $pageno)) {
						$query = "category=" . $matches[1];
					} else {
						$query = "category_name=" . $matches[1];
					}
					wp_list_bookmarks($query."&title_li=&categorize=0&title_before=&title_after=");
				} else {
					wp_list_pages("depth=1&child_of=$id&title_li=&sort_column=menu_order,post_title");
				}
				?>
			</ul>
		</div>
</div>

</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div></body>
</html>
