<?php // Do not delete these lines
if ('ask_question.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
if (!empty($post->post_password)) { // if there's a password
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

<div id="div-h2-post"><?php _e('Password Protected'); ?></div>
<p><?php _e('Enter the password to view comments.'); ?></p>

<?php return;
	}
}

	/* This variable is for alternating comment background */

$oddcomment = 'alt';

?>

<!-- You can start editing here. -->

<div id="ask_question-form">

<?php if ('open' == $post->comment_status) : ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<div id="comment-warning">
Задавать вопросы могут только зарегистрированные пользователи.		
</div>		
<p><strong>Если Вы зарегистрированный пользователь, то <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=login&redirect_to=<?php the_permalink(); ?>">войдите</a> в систему.</strong>
</p>
<p><strong>Если нет - <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=register&redirect_to=<?php the_permalink(); ?>">зарегистрируйтесь</a></strong>.
</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="ask_question">
<?php if ( $user_ID ) : ?>

<p>Зарегистрирован как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Выйти &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" tabindex="1" />
<label for="author"><small>Имя <?php if ($req) echo "(обязательно)"; ?></small></label></p><br/>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" tabindex="2" />
<label for="email"><small>Е-Mail (не будет опубликован) <?php if ($req) echo "(обязательно)"; ?></small></label></p><br/>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" tabindex="3" />
<label for="url"><small>Website</small></label></p><br/>

<?php endif; ?>

<p><textarea name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea></p>

<p class="submit"><input name="submit" type="submit" id="submit" tabindex="7" value="Отправить" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>

</div>

<?php
$comments_num = count($comments);
$pagenum = (int)($comments_num/20) + ($comments_num%20 ? 1 : 0);

// current page
//$page = (int)($_GET['page']);
// BBDev added on 30/10/2012
$page = get_query_var( 'page' );
$page = min(max(1, $page), $pagenum);

// offset and length of comments array to show
$off = ($page-1)*20;
$length = min($comments_num - $off, 20);
$curr_comments = array_slice(array_reverse($comments), $off, $length);
?>

<?php if ($comments) : ?>
	<div id="div-h3" style="margin: 25px 0 0;">Заданные вопросы</div>	
	<ol class="asklist">
	<?php $count_pings = 1; foreach ($curr_comments as $comment) : ?>
		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
			<div class="askmetadata">
				<strong><?php comment_author_link() ?></strong>
				<span class="ask_time">  ~  <?php comment_date('F j, Y') ?> в <?php comment_time() ?></span>
				
				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php _e('Ваш комментарий ожидает разрешения.'); ?></em>
				<?php endif; ?>
			</div>
			<div class="askentry">
				<?php comment_text() ?> 
				<?php if ($comment->comment_approved == '0') : ?>
					<p><strong>Ваш комментарий ожидает разрешени.</strong></p>
				<?php endif; ?>
				<p>
					<?php comment_reply_link( array('add_below' => "comment-".$comment->comment_ID, 'depth' => 2, 'max_depth' => 5),$comment->comment_ID,$page->ID ); ?>
				</p>
			</div>
		</li>

	<?php /* Changes every other comment to a different class */
		if ('alt' == $oddcomment) $oddcomment = 'even';
		else $oddcomment = 'alt';
	?>

	<?php endforeach; /* end for each comment */ ?>
	</ol>

	<p class="clear"></p>
	
	<div class="comment-pagenavi">

	<span class="pages">Страница <?php echo $page; ?> из <?php echo $pagenum; ?></span>

	<?php if ($page > 1) {?>
		<a href="<?php bloginfo('url'); ?>/ask-kabbalist?page=1">&laquo; Первая</a>
		<span class="extend">...</span>
		<a href="<?php bloginfo('url'); ?>/ask-kabbalist?page=<?php echo $page-1; ?>">&laquo;</a>
	<?php }

	$entries = $pagenum >= 1000 ? 1 : $pagenum >= 100 ? 3 : 5;
	$start = max(1, min($pagenum - $entries+1, $page - $entries + 2));
	$end = min($pagenum+1, $start + $entries);
	for ($i = $start; $i < $end; $i++)
	{
		if ($i == $page) {
	?>
		<span class="current"><?php echo $i; ?></span>
	<?php
		} else {
	?>
		<a href="<?php bloginfo('url'); ?>/ask-kabbalist?page=<?php echo $i; ?>"><?php echo $i; ?></a>
	<?php
		}
	}

	if ($page < $pagenum) {?>
		<a href="<?php bloginfo('url'); ?>/ask-kabbalist?page=<?php echo $page+1; ?>">&raquo;</a>
		<span class="extend">...</span>
		<a href="<?php bloginfo('url'); ?>/ask-kabbalist?page=<?php echo $pagenum; ?>">Последняя &raquo;</a>
	<?php } ?>

	</div><!-- end comment-pagenavi -->

	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Временно нельзя задавать вопросы.</p>
	<?php endif; ?>

<?php endif; ?>
