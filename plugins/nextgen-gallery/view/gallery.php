<?php 
/**
Template Page for the gallery overview

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/
?>

<script type="text/javascript">
var today = new Date();
today.setTime(today.getTime());
var expires_date = new Date(today.getTime() + 60 * 60 * 24 * 365 * 10);

document.cookie = "postwidth=" + jQuery('.post:first').width() + ";expires=" + expires_date.toGMTString() + ";path=/;";
</script>

<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<div class="ngg-galleryoverview" id="<?php echo $gallery->anchor ?>">
<!--center><h2><?php echo $gallery->title ?></h2></center--> <!-- BB Dev: Show gallery title -->

<?php if ($gallery->show_piclens) { ?>
	<!-- Piclense link -->
	<div class="piclenselink">
		<a class="piclenselink" href="<?php echo $gallery->piclens_link ?>">
			<?php _e('[View with PicLens]','nggallery'); ?>
		</a>
	</div>
<?php } ?>

<?php if ($gallery->show_slideshow) { ?>
	<!-- Slideshow link -->
	<div class="slideshowlink">
		<a class="slideshowlink" href="<?php echo $gallery->slideshow_link ?>">
			<?php echo $gallery->slideshow_link_text ?>
		</a>
	</div>
<?php } ?>

	<!-- Thumbnails -->
	<?php //BB Dev: here we calculating how many pics can be fit in the row -->
		$imgwidth = -1;
		$i = 0;
		foreach ( $images as $image ){
			list($w) = getimagesize($image->thumbnailURL);
			if(!$w) $w = 100; //if no image found in DB, it will be default size
			if($w > $imgwidth) $imgwidth = $w;
			
		}
		$postwidth = preg_replace("/[^a-zA-Z0-9\s]/","", isset($_COOKIE['postwidth']) ? $_COOKIE['postwidth'] : NULL); //strips quotes
		if(!$postwidth) 
			$postwidth = 520;
		$maxinraw = $postwidth / $imgwidth; 
	//BB Dev <--?>
	
	<!-- BB Dev (new gallery format): we're wrapping table around the gallery -->

	<?php $gtable = '<table width="'.$postwidth.'"><tr>';?> <!-- BB Dev: rewriting gallery cycle in order to dispaly gallery on complete -->
	<?php foreach ( $images as $image ) {
	list($w) = getimagesize($image->thumbnailURL);
	if(!$w) $w = 100; 
	$gtable .= '<td width="'.$w.'">
	<div id="ngg-image-'.$image->pid.'" class="ngg-gallery-thumbnail-box" '.$image->style.' >
		<div class="ngg-gallery-thumbnail" >
			<a href="'.$image->imageURL.'" title="'.$image->description.'" '.$image->thumbcode.'>';
				if ( !$image->hidden ) { 
				$gtable .= '<img title="'.$image->alttext.'" alt="'.$image->alttext.'" src="'.$image->thumbnailURL.'" width="'.$w.'" />';
				}
			$gtable .= '</a></div></div></td>';
	if ( $image->hidden ) continue;
	if ( /*$gallery->columns > 0 && */ ++$i % $maxinraw == 0 ) {
		$gtable .= '</tr><tr>';
	}
	
 	} ?>
 	<?php $gtable .= '</tr></table>'; ?>
 	<?php echo $gtable; ?>
	<!-- Pagination -->
 	<?php echo $pagination ?>
</div>
<?php endif; ?>
