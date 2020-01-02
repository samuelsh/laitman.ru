<?php
/*
Plugin Name: BB Analytics
Description: Plugin for Analytics with ability to get admin defined ID.
Version: 1.0
Author: Michael Cherniakhovsky
*/
function bb_analytics()
{
	if (!current_user_can('edit_posts'))
	{
		//generate_GA_call();
		//generate_YS_call();
	}
}

// Google Analytics call
function generate_GA_call()
{
	$id = "UA-3408443-4"; // get_option('GA_ID'); 
	echo <<< END

<!-- Google Analytics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("$id");
pageTracker._initData();
pageTracker._trackPageview();
</script>
END;
}

// Yandex Statistics call
function generate_YS_call()
{
	$id = "91293"; // get_option('YS_ID');
	echo <<< END
<!-- Yandex.Metrika informer -->
<a href="https://metrica.yandex.com/stat/?id=28638316&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/28638316/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Yandex.Metrica" title="Yandex.Metrica: data for today (page views, visits and unique users)" onclick="try{Ya.Metrika.informer({i:this,id:28638316,lang:'en'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter28638316 = new Ya.Metrika({id:28638316,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/28638316" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
END;
}

/* stuff that goes in the HTML footer */
add_action('wp_footer', 'bb_analytics', 20);

/* options treatment */
// Left side bar themes
// C - category, P - page
add_option('LB_SUPER', 'C149', "");
add_option('LB_DUPER', 'C6,P3343,C139,C143', "");

//BB Dev
update_option('LB_SUPER', 'C189' );
update_option('LB_DUPER', 'P63436,C224,C6,P3343');

// set ID for Google Analytics
add_option('GA_ID', 'UA-3408443-4', "");

// set ID for Yandex Statistics
add_option('YS_ID', '91293', "");

function bb_analytics_options_page() {
	if($_POST['GA_ID']) {
		// set the post formatting options
		update_option('GA_ID', $_POST['GA_ID']);
		echo '<div class="updated"><p>Google Analytics updated.</p></div>';
	}
	if($_POST['YS_ID']) {
		// set the post formatting options
		update_option('YS_ID', $_POST['YS_ID']);
		echo '<div class="updated"><p>Yandex Statistics updated.</p></div>';
	}
?>
<div class="wrap">
	<h2>Настройки Analytics</h2>
	<p>Различные настройки для Analytics.</p>

	<form method="post">
	<fieldset class="options">
		Google Analytics ID <input type="text" name="GA_ID" value="<?php echo get_option('GA_ID'); ?>" /><br />
		Yandex Statistics ID <input type="text" name="YS_ID" value="<?php echo get_option('YS_ID'); ?>" /><br />
		<input type="submit" value="update" />
	</fieldset>
	</form>
</div>
<?php
	update_option('GA_ID', $_POST['GA_ID']);
	update_option('YS_ID', $_POST['YS_ID']);
}

function bb_analytics_add_menu() {
		add_options_page('BB Analytics', 'BB Analytics', 8, __FILE__, 'bb_analytics_options_page');
}

function bb_wp_register_form() {
	echo '<hr style="clear: both; margin: 1.0em 0; border: 0; border-top: 1px solid #999; height: 1px;" />';
	echo '<p style="clear:both;text-align:justify;">Внимание! Письмо может быть принято Вашим почтовым ящиком за спам. В случае, если письмо до Вас не дойдет, проверьте спам. Если и там не окажется, свяжитесь с нами: <a href="mailto:admin.laitman.ru@gmail.com">admin.laitman.ru@gmail.com</a></p>';
}

function bb_admin_head()
{
	echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/bb-analytics/admin.css" type="text/css" />'."\n";
}

add_action( 'register_form', 'bb_wp_register_form', 11);

// add styles for gallery admin
if (strpos($_SERVER['REQUEST_URI'], 'wp-admin'))
{
	add_action('admin_menu', 'bb_analytics_add_menu');
	add_action('admin_head', 'bb_admin_head');
}

// Relative path fixes

function fix_relativepathproblem($the_content){
	$siteurl = get_option('siteurl');
	return str_replace('href="../', "href=\"$siteurl/", $the_content);
}
add_filter('the_content', 'fix_relativepathproblem'); 

function fix_relativeimageproblem($the_content)
{
    $siteurl = get_option('siteurl');
    return str_replace('src="../', "src=\"$siteurl/", $the_content);
}

add_filter('the_content', 'fix_relativeimageproblem'); 

// Special lesson moments fixes

function fix_special_lesson_moments($the_content)
{
	$url = getPageURL();
	if (!preg_match("/video-yarkie-momenty/", $url))
		return $the_content;
	return str_replace('Самые яркие моменты ежедневного урока:', "", $the_content);
}
add_filter('the_content', 'fix_special_lesson_moments'); 

/**
 * Add to extended_valid_elements for TinyMCE
 *
 * @param $init assoc. array of TinyMCE options
 * @return $init the changed assoc. array
 */
function my_change_mce_options( $init ) 
{
	$init['font_size_style_values'] = '8px,10px,12px,14px,18px,24px,36px';

	// Super important: return $init!
	return $init;
}

add_filter('tiny_mce_before_init', 'my_change_mce_options');

function getPageURL() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}

 	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


?>
