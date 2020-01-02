<?php
/**
 * @package WordPress
 * @subpackage Fastway2_Theme
 */

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
	echo 'This post is password protected. Enter the password to view comments.';
	return;
}

?>

<div id="comments"><?php comments_number(__('No Comments'), __('1 Comment'), __('% Comments')); ?>
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php _e("Leave a comment"); ?>">&raquo;</a>
<?php endif; ?>
</div>

<?php if ( have_comments() ) : ?>
  <ul class="commentlist">
    <?php wp_list_comments('callback=mytheme_comment'); ?>
  </ul>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<div id='comment-warning'>
<p><?php _e('The blog is moderated.', 'fastway2')?></p>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'fastway2'), wp_login_url( get_permalink() ) );?></p>
<p><?php printf(__('Otherwise please <a href="%s">register</a>.', 'fastway2'), '/wp-login.php?action=register' );?></p>
</div>

<?php else : ?>
<div id="respond">
<div id="postcomment"><?php comment_form_title( __('Leave a comment'), __('Leave a comment') .  ' %s' ); ?></div>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p><?php printf(__('Logged in as %s.'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>'); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account') ?>"><?php _e('Log out &raquo;'); ?></a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)');?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website'); ?></small></label></p>

<?php endif; ?>

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p>
<input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Submit Comment'); ?>" />
<?php cancel_comment_reply_link() ?>

<?php comment_id_fields(); ?>
</p>
<div style="clear:both"> </div>
<?php do_action('comment_form', $post->ID); ?>

</form>
</div>

<?php endif; // If registration required and not logged in ?>

<?php else : // Comments are closed ?>
<p><?php _e('Sorry, the comment form is closed at this time.'); ?></p>
<?php endif; ?>
