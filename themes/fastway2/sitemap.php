<?php
/*
Template Name: Sitemap
*/
function get_post_comments_number($post_id = 0, $zero = -1, $one = -1, $more = -1, $deprecated = '' ) 
{
	$number = get_comments_number($post_id);
	if ( $number > 1 )
		$output = str_replace('%', $number, ( $more == -1 ) ? __('% Comments') : $more);
	elseif ( $number == 0 )
		$output = ( $zero == -1 ) ? __('No Comments') : $zero;
	else // must be one
		$output = ( $one == -1 ) ? __('1 Comment') : $one;
	return apply_filters('comments_number', $output, $number);
}

?>

<?php get_header(); ?>

<div id="container">

	<?php if(have_posts()): ?>
	
<?php // Thanks to Chris Pearson of http://pearsonified.com for the instructions on setting this up. ?>
	
<div class="sitemap">		

<div class="sitemap_title"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a> | <?php the_title(); ?></div>

<!--h3>Все посты блога:</h3>
<ul>
<!--?php
	$sql = "
		select ID, post_title from $wpdb->posts where post_status = 'publish' order by post_date desc
	";
	$all_posts = $wpdb->get_results($sql);
	foreach ($all_posts as $one_post)
	{
		$post_link = apply_filters('the_permalink', get_permalink($one_post));
		$post_title = apply_filters('the_title', get_the_title($one_post));
		$str = '<li>';
		$str .= '<a href="'.$post_link.'" rel="bookmark" title = "'.$post_title.'">'.$post_title.'</a> ';
		//$str .= '<strong>' . get_post_comments_number($one_post->ID, '0', '1', '%') . '</strong>';
		$str .= '</li>' . "\n";
		echo $str;
	}
?>
</ul-->

<div id="div-h3">Архивы по месяцам:</div>
<ul>
	<?php wp_get_archives('type=monthly'); ?>
</ul>

<div id="div-h3">Архивы по категориям:</div>
<ul>
	<?php wp_list_categories('title_li=0'); ?>
</ul>

</div>

	<?php else: ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<div id="div-h2-post"><?php _e('Not Found'); ?></div>

		</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer() ?>

</div></body>
</html>
