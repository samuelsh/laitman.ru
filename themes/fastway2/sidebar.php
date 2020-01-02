<!-- Start Sidebar -->

<div class="sidebar">
<form class="sidebar-form" action="https://www.feedburner.com/fb/a/emailverify" method="post" target="popupwindow" onsubmit="window.open('https://www.feedburner.com/fb/a/emailverifySubmit?feedId=1892440', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
	<div class="form-input">
		<input name="email" id="email" value="  Запись на рассылку" type="text" style="width:130px;" onclick="javascript:resetVal('email', '  Запись на рассылку')" /> 
	</div>
	<input value="https://feeds.feedburner.com/~e?ffid=1892440" name="url" type="hidden" /> <input value="Rav Michael Laitman blog" name="title" type="hidden" /> <input name="loc" value="ru_RU" type="hidden" />
	<div class="form-submit">
		<input value="Подписаться" type="image" src="/wp-content/themes/fastway2/images/submit.gif" alt="[Подписаться]" name="submit" />
	</div>
	<div style="clear:both;"></div>
</form>

<form class="sidebar-form" method="get" action="<?php bloginfo('url'); ?>/search-results">
	<div class="form-input">
		<input type="hidden" name="cx" value="009476949152162131478:e8s1qvvqlo0" />
		<input type="hidden" name="cof" value="FORID:11" />
      <input type="hidden" name="ie" value="UTF-8" />
		<input type="text" value="  Поиск по сайту" name="q" id="q" onclick="javascript:resetVal('q', '  Поиск по сайту')" style="border: 1px solid silver; width:131px;" />
	</div>
	<div class="form-submit">
		<input value="<?php _e('Search'); ?>" type="image" src="/wp-content/themes/fastway2/images/submit.gif" alt="[<?php _e('Search'); ?>]" name="submit" />
	</div>
	<div style="clear:both;"></div>
</form>
<script type="text/javascript" src="https://www.google.com/coop/cse/brand?form=cse-search-box&lang=ru"></script>

<!--style>

table.gsc-search-box{width: 150px !important}
.gsc-control-cse {padding: 0 0 0 6px !important;}
.gsc-input{padding-right: 0 !important;width: 126px !important;}

input#gsc-i-id1{background: url(http://laitman.ru/wp-content/themes/fastway2/images/searchBg.gif) 8% 11% no-repeat rgb(255, 255, 255) !important; width: 126px !important; margin-left: -2px !important;border: 1px solid silver !important; padding: 0 4px; font-size: 10px; font-family: Verdana; height: 14px;}

input#gsc-i-id1:focus, input#gsc-i-id1:active,  input#gsc-i-id1:hover{background: none !important}

.gsc-results .gsc-cursor-box .gsc-cursor-current-page{background: none !important}

.cse input.gsc-search-button, input.gsc-search-button{display: inline-block; background: transparent url(/wp-content/themes/fastway2/images/submit.gif) 0 0 no-repeat; width: 17px; min-width: 17px; height: 16px; border: 1px solid silver; padding: 0;margin: 0;font-size: 10px;font-family: Verdana, Arial, sans-serif; margin-left: -4px; color: white; overflow: hidden; text-indent: -9999px; cursor: pointer; position: relative; left: -2px}
#bgresponse{width: 0 !important}
.gsc-clear-button{display: none} 
input::-webkit-input-placeholder { /* WebKit browsers */
    color:    #fff;
	opacity: 0;
}
input:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
   color:    #fff;
   opacity:  0;
}
input::-moz-placeholder { /* Mozilla Firefox 19+ */
   color:    #fff;
   opacity:  0;
}
input:-ms-input-placeholder { /* Internet Explorer 10+ */
   color:    #fff;
   opacity:0;
}
.gsc-branding{display: none !important;}
</style>
<script>
  (function() {
    var cx = '009476949152162131478:e8s1qvvqlo0';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search-->


<div class="separator"></div>

<div class="banner">
<a href="https://laitman.ru/topics/radio-versiya-bloga/"  title="Радио-версия блога" alt="Радио-версия блога"><img style="width:152px; margin:3px 5px; border:none;" src="/wp-content/gallery/banners/picblog.gif" align="middle" border="0"/></a>
</div>

<div class="separator" style="margin-bottom: 5px;"></div>
<?php echo get_scp_widget(); ?>
<div class="separator" style="margin-bottom: 5px;"></div>
<script src="https://apis.google.com/js/platform.js"></script>
<div class="g-ytsubscribe" data-channel="kabvideo125" data-layout="full" data-count="default"></div>
<div class="separator" style="margin-top:5px;"></div>

<!-- Start Twitter Block -->
<!--
<div class="separator" style="margin-bottom: 5px;"></div>
<a href="https://twitter.com/Michael_Laitman" class="twitter-follow-button" data-show-count="false" data-lang="ru" data-size="large" data-show-screen-name="false" data-dnt="true">Читать @Michael_Laitman</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<div class="separator" style="margin-top:5px;"></div>
-->
<!-- End Twitter Block -->

<!-- Start AWSOM News Announcement Block -->
<?php if (function_exists('display_my_news_announcement')) { display_my_news_announcement(''); } ?>

<!-- bbdev: banner promo sections -->

<div class="separator"></div>
<div class="banner">
<a href="http://kabacademy.com/webinar/" title='Вебинар - Программа духовного развития' target="blank"><img src="/wp-content/gallery/banners/webinar-170x170.jpg" alt='Вебинар - Программа духовного развития' align="middle" border="0"/></a>
</div> 
<div class="separator"></div>

<div class="banner">
<a href="http://www.kab.tv/rus#/stream" title='Michael Laitman | КАББАЛА ТВ' target="blank"><img src="/wp-content/gallery/banners/RAV_v3.png" alt='Michael Laitman | КАББАЛА ТВ' align="middle" border="0"/></a>
</div> 
<div class="separator"></div>

<div class="banner">
<a href="https://soundcloud.com/michael_laitman" title='Michael Laitman | Free Listening on SoundCloud' target="blank"><img src="/wp-content/gallery/banners/170x170soundcloud-banners.jpg" alt='Michael Laitman | Free Listening on SoundCloud' align="middle" border="0"/></a>
</div> 
<div class="separator"></div>


<!--div class="banner">

<div class="banner">
<a href="http://www.kab.net.ua/" title="Новый семестр очного курса изучения Каббалы в Киеве" alt="Новый семестр очного курса изучения Каббалы в Киеве" target="blank"><img src="/wp-content/gallery/banners/laitman_ua_november2012.jpg" align="middle" border="0"/></a>
</div>
<div class="separator"></div>
<a href="https://www.kabbalah.info/donate/ru/projects/new_building?utm_source=http%3A%2F%2Fwww.laitman.ru%2F&utm_medium=banner&utm_campaign=ourhome" title="Наш новый дом" alt="Наш новый дом" target="blank"><img src="/wp-content/gallery/banners/170x170rus.gif" align="middle" border="0"/></a>
</div>
<div class="separator"></div>

<!--div class="banner">
	<a href="<?php get_bloginfo('url') ?>/video-yarkie-momenty">
<img title="Яркие моменты" alt="Яркие моменты" src="/wp-content/gallery/banners/banner_yarkie_momenty.jpg" align="middle" border="0" /></a>
</div>
<div class="separator"></div-->

<!-- php  get_flv_objects(); don't uncomment this-->

<!--div class="banner">
<a href="http://kabbala.fm/short-lesson.html" title="Каббала FM" alt="Каббала FM" target="blank"><img src="/wp-content/gallery/banners/kabfm_banner_175x51.jpg" align="middle" border="0"/></a>
</div>
<div class="separator"></div-->

<!--div class="banner">
<a href="<?php get_bloginfo('url') ?>/category/klipy-filmy">
<img title="Клипы-фильмы" alt="Клипы-фильмы" src="/wp-content/gallery/banners/banner_klipy_filmy.jpg" align="middle" border="0" /></a>
</div>
<div class="separator"></div-->

<!-- End AWSOM News Announcement Block -->

<!--script>
	$(function() {
   	$(".toggle-poll").click(function(event){
			event.preventDefault();
			$this = $(this);
			$poll = $this.parent().next(".wp-polls-ans");
			if ($poll.css("display") == "none"){
				$poll.show("slow");
			} else {
				$poll.hide("slow");
			}
			return false;
		});
	});
</script-->
<?php 
	if (function_exists('vote_poll') && !in_pollarchive()) {
		get_poll();
		display_polls_archive_link();
	}; 
?>

<ul>

	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2)) : else : ?>
	
	<?php wp_list_bookmarks('title_before=<div class="sidebar_header">&category=13&title_after=</div>'); ?>
	<?php wp_list_bookmarks('title_before=<div class="sidebar_header">&category=144&title_after=</div>'); ?>

<?php endif; ?>

<?php if (function_exists('aktt_sidebar_tweets')) {?>
	<li><div id="div-h2-post">ПОСЛЕДНЕЕ НА ТВИТТЕРЕ</div></li>
	<?php aktt_sidebar_tweets(); ?>
<?php } ?>

</ul>

</div>

<div style="clear:both"></div>
</div><!-- blog-body -->

<!-- End Sidebar -->
<?php
function get_flv_objects()
{
	$sidebar_flash_page = get_page_by_path('sidebar_flash');
	if ($sidebar_flash_page == NULL)
		return;
	$sidebar_flash_content = $sidebar_flash_page->post_content;

	/*** a new dom object ***/
	$dom = new domDocument;

	/*** load the html into the object ***/
	$dom->loadHTML($sidebar_flash_content);

	/*** discard white space ***/
	$dom->preserveWhiteSpace = false;

	/*** the table by its tag name ***/
	$tables = $dom->getElementsByTagName('table');

	/*** get all rows from the table ***/
	$rows = $tables->item(0)->getElementsByTagName('tr');

	/*** loop over the table rows ***/
	$flv_objs = array();
	foreach ($rows as $row)
	{
		/*** get each column by tag name ***/
		$cols = $row->getElementsByTagName('td');
		if ($cols->length == 0)
			continue;
		/*** get the flv objects ***/
		$images = $cols->item(0)->getElementsByTagName('img');

		if ($images->length == 0)
			continue;

		$img_src = $images->item(0)->getAttribute('src');
		$flv_src = $cols->item(1)->nodeValue;
		$title = $cols->item(2)->nodeValue;
		if ($title == "null")
			$title = "";
		if (!empty($title) && mb_detect_encoding($title) == "UTF-8")
			$title = utf8_decode($cols->item(2)->nodeValue);

		if ($flv_src == "")
			continue;

		array_push($flv_objs, new FlvObject($title, $img_src, $flv_src));
	}

	foreach ($flv_objs as $flv_obj)
	{
		echo '<div class="flv-obj">';
		$flv_obj->show_link();
		echo "</div>\n";
	}

	if (count($flv_objs))
		echo "<div class=\"separator\"></div>";

//	if (count($flv_objs))
//		echo "<a id=\"others\" href=\"" . get_bloginfo('url') . "/topics/klipy-filmy\">И другие...</a>\n";
}

class FlvObject
{
	private $title, $img_src, $flv_src; 

	function __construct($ititle, $iimg_src, $iflv_src)
	{
		$this->title = $ititle;
		$this->img_src = $iimg_src;
		$this->flv_src = $iflv_src;
	}

	function show_link()
	{
		$title = empty($this->title) ? "Изучаем ЗОАР" : $this->title;
		$link = get_bloginfo('url') . "/wp-content/themes/fastway2/sidebar_flash.php?flv=$this->flv_src";
		echo "<a class=\"thickbox\" href=\"$link&amp;KeepThis=true&amp;TB_iframe=true&amp;height=285&amp;width=330\" title=\"$title\" target=\"blank\">";
		echo "<img src=\"$this->img_src\" title=\"$title\" alt=\"$title\" />";
		if (!empty($this->title))
			echo "<div class=\"flv-title\">" . $title . "</div></a>\n";
	}
}
?>
