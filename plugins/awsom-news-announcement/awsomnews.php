<?php
/*
 Plugin Name: Awsom News Announcement
 Plugin URI: http://www.awsom.org
 Author: Harknell
 Author URI: http://www.harknell.com
 Version: 1.5.5
 Description: Allows you to post news announcements in the area above your posts on the index page (or anywhere else you want).

 */
global $wpdb, $awsom_news_table_name, $awsom_news_db_version;

$awsom_news_db_version = 4;
$awsom_news_table_name = $wpdb->prefix . "awsomnews";
$awsom_news_min_admin_level = get_option("awsom_news_use_custom_role");
$awsom_news_displayed = 0;
if ($awsom_news_min_admin_level == "" || $awsom_news_min_admin_level == NULL){
$awsom_news_min_admin_level = "manage_options";
}

if ( !defined('WP_CONTENT_URL') )
define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
$plugin_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$awsomnews_path = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
$wp_ANP_url	= get_bloginfo('wpurl');
//$awsomnews_path = get_option('siteurl')."/wp-content/plugins/awsomnews/";

if ( FORCE_SSL_ADMIN !== true){
//no need for replacement
}
else {
$isitssl = "https";
$findoutiftrue = strpos($wp_ANP_url,$isitssl);
	if ($findoutiftrue === false) {
	$wp_ANP_url = str_replace("http", "https", $wp_ANP_url);
	$awsomnews_path = str_replace("http", "https", $awsomnews_path);
	}

}



function awsom_clear_get(){
global $wp_ANP_url;
if (isset($_POST['awsomnews_updatenews']) || isset($_POST['awsomnews_deletenews']) || isset($_POST['awsomnews_clearallviews'])){


echo "<meta http-equiv=\"refresh\" content=\"5;url=".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php\">";

}
 }
 function awsomnews_checktime($start,$end) {
 $rightnow = localtime();
 if (($rightnow[2] >= $start) && ($rightnow[2] <= $end)){
 return true;
 }
 if ($end <= $start){
 if (($rightnow[2] <= $start) && ($rightnow[2] <=$end)){
 return true;
 }
 }
 return false;
 }
 function awsomnews_checkdate($start,$end){
 $rightnow = date("Ymd");
 if (($start == "0") && ($end == "0")){
 return true;
 }
 if (($rightnow >= $start) && ($rightnow <= $end)){
 return true;
 }
 if (($rightnow >= $start) && ($end == "0")){
 return true;
 }
 return false;
 }

function determine_level($thislevel){
	if ($thislevel == "1"){
	$thislevel = "read";
	}
	elseif ($thislevel == "2"){
		$thislevel = "edit_posts";
	}
	elseif ($thislevel == "3"){
		$thislevel = "publish_posts";
	}
	elseif ($thislevel == "4"){
		$thislevel = "manage_links";
	}
	elseif ($thislevel == "5"){
		$thislevel = "manage_options";
	}
	else {
		$thislevel = "0";
		}
	return $thislevel;
}

function viewer_friendly_name ($thislevel){
if ($thislevel == "1"){
	$thislevel = "Registered Users";
	}
	elseif ($thislevel == "2"){
		$thislevel = "Contributers";
	}
	elseif ($thislevel == "3"){
		$thislevel = "Authors";
	}
	elseif ($thislevel == "4"){
		$thislevel = "Editors";
	}
	elseif ($thislevel == "5"){
		$thislevel = "Administrators Only";
	}
	elseif ($thislevel == "6"){
			$thislevel = "Non-Registered Only";
	}
	else {
		$thislevel = "All Visitors";
		}
	return $thislevel;
}

function display_my_news_announcement($locationcode) {
global $wpdb, $awsom_news_table_name, $awsom_news_min_admin_level,$wp_ANP_url;
if ($locationcode == "" || $locationcode == NULL){
$locationcode = "0";
}
$mynewsannouncement = $wpdb->get_results("SELECT anid, newstext, whocanview, startdate, enddate FROM $awsom_news_table_name WHERE isactive = '1' AND (displaywhere = $locationcode or displaywhere = '9') Order by displaypos");
$awsom_news_use_phpcode_status = get_option("awsom_news_use_phpcode");
$awsom_news_custom_css = get_option("awsom_news_use_custom_css");
	foreach ($mynewsannouncement as $thisnewspost){
	$thisnewspost->startdate = stripslashes($thisnewspost->startdate);
	$thisnewspost->enddate = stripslashes($thisnewspost->enddate);
	if (!awsomnews_checkdate($thisnewspost->startdate,$thisnewspost->enddate)) {
	continue;
	}
	$viewerlevel = stripslashes($thisnewspost->whocanview);

	if ($viewerlevel > 0 && $viewerlevel < 6){
				$viewerlevel = determine_level($viewerlevel);
				if ( function_exists('current_user_can') && !current_user_can($viewerlevel) ){
				continue;}
			}
			if ($viewerlevel == "6"){
				if ( function_exists('current_user_can') && current_user_can('read') && !current_user_can($awsom_news_min_admin_level)){
				continue;
				}
		}
	$thisnewspost->anid = stripslashes($thisnewspost->anid);
	$thisnewspost->newstext = stripslashes($thisnewspost->newstext);
	$thisnewspost->whocanview = stripslashes($thisnewspost->whocanview);
				$displaynewspost = $thisnewspost->newstext;
				$newspostanid = $thisnewspost->anid;
				$newspostwhoview = $thisnewspost->whocanview;
				$beforenewspost = "<div style=\"".$awsom_news_custom_css."\">";
				$afternewspost = "</div>";
				$displaynewspost = $beforenewspost.$displaynewspost.$afternewspost;

		if ($awsom_news_use_phpcode_status == 1){
			eval('?>' . $displaynewspost . '<?php ');
			}
		else{
			echo $displaynewspost;

			}
		$wpdb->query("UPDATE $awsom_news_table_name SET timesdisplayed=timesdisplayed+1 WHERE anid ='$thisnewspost->anid'");
		if ( function_exists('current_user_can') && current_user_can($awsom_news_min_admin_level) ) {

		echo"<p><a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=editnewspost&eid=".$newspostanid."\">Edit News Post</a></p>";
		}


	}
  }


function display_my_awsom_news_inpost($content) {
global $wpdb, $awsom_news_table_name, $awsom_news_min_admin_level,$wp_ANP_url;

  if (preg_match("/%%awsomnews([0-8]*)%%/", $content, $locationcode)){
if ($locationcode[1] == "" || $locationcode[1] == NULL){
$locationcode[1] = "0";
}
$mynewsannouncement = $wpdb->get_results("SELECT anid, newstext, whocanview, startdate, enddate FROM $awsom_news_table_name WHERE isactive = '1' AND (displaywhere = '9' or displaywhere = $locationcode[1]) Order by displaypos");
$awsom_news_use_phpcode_status = get_option("awsom_news_use_phpcode");
$awsom_news_custom_css = get_option("awsom_news_use_custom_css");
	foreach ($mynewsannouncement as $thisnewspost){
	$thisnewspost->startdate = stripslashes($thisnewspost->startdate);
	$thisnewspost->enddate = stripslashes($thisnewspost->enddate);
	if (!awsomnews_checkdate($thisnewspost->startdate,$thisnewspost->enddate)  ) {
	continue;
	}
	$viewerlevel = stripslashes($thisnewspost->whocanview);
		if ($viewerlevel > 0 && $viewerlevel < 6){
			$viewerlevel = determine_level($viewerlevel);
			if ( function_exists('current_user_can') && !current_user_can($viewerlevel) ){
			continue;}
		}
		if ($viewerlevel == "6"){
			if ( function_exists('current_user_can') && current_user_can('read') && !current_user_can($awsom_news_min_admin_level)){
			continue;
			}
		}
	$thisnewspost->anid = stripslashes($thisnewspost->anid);
	$thisnewspost->newstext = stripslashes($thisnewspost->newstext);

				$displaynewspost = $thisnewspost->newstext;
				$newspostanid = $thisnewspost->anid;

				$beforenewspost = "<div class=\"announcement\" style=\"".$awsom_news_custom_css."\">";
				$afternewspost = "</div>";
				$displaynewspost = $beforenewspost.$displaynewspost.$afternewspost;
				$wpdb->query("UPDATE $awsom_news_table_name SET timesdisplayed=timesdisplayed+1 WHERE anid ='$thisnewspost->anid'");
			if ( function_exists('current_user_can') && current_user_can($awsom_news_min_admin_level) ) {

							$ANOutput="<p><a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=editnewspost&eid=".$newspostanid."\">Edit News Post</a></p>";
						$displaynewspost .= $ANOutput;
			}
				if ($awsom_news_use_phpcode_status == '1'){
					 ob_start();
					 $displaynewspost = str_replace('<'.'?php','<'.'?',$displaynewspost);
					 eval('?'.'>'.trim($displaynewspost).'<'.'?');
					 $displaynewspost = ob_get_contents();
					 ob_end_clean();
					 $displaynewsposts .= $displaynewspost;

					}
				else{
					$displaynewsposts .= $displaynewspost;
					}


	}

$content = str_replace($locationcode[0], $displaynewsposts, $content);


}

return $content;
}


function display_my_news_announcement_preset() {
global $wpdb, $awsom_news_table_name, $awsom_news_displayed, $awsom_news_min_admin_level,$wp_ANP_url;
$awsom_news_div_preset_status = get_option("awsom_news_use_presetdiv");
$awsom_news_use_phpcode_status = get_option("awsom_news_use_phpcode");
$awsom_news_custom_css = get_option("awsom_news_use_custom_css");
if ($awsom_news_div_preset_status == 1 && $awsom_news_displayed ==0){
if (is_home() && !is_feed()){
$awsom_news_displayed = 1;
$mynewsannouncement = $wpdb->get_results("SELECT anid, newstext, whocanview, startdate, enddate FROM $awsom_news_table_name WHERE isactive = '1' AND ( displaywhere = '9' or displaywhere = '0') Order by displaypos");

	foreach ($mynewsannouncement as $thisnewspost){
	$thisnewspost->startdate = stripslashes($thisnewspost->startdate);
	$thisnewspost->enddate = stripslashes($thisnewspost->enddate);
	if ( !awsomnews_checkdate($thisnewspost->startdate,$thisnewspost->enddate)  ) {
	continue;
	}
	$viewerlevel = stripslashes($thisnewspost->whocanview);
		if ($viewerlevel > 0 && $viewerlevel < 6){
					$viewerlevel = determine_level($viewerlevel);
					if ( function_exists('current_user_can') && !current_user_can($viewerlevel) ){
					continue;}
				}
				if ($viewerlevel == "6"){
					if ( function_exists('current_user_can') && current_user_can('read') && !current_user_can($awsom_news_min_admin_level)){
					continue;
					}
		}
	$thisnewspost->anid = stripslashes($thisnewspost->anid);
	$thisnewspost->newstext = stripslashes($thisnewspost->newstext);

				$displaynewspost = $thisnewspost->newstext;
				$newspostanid = $thisnewspost->anid;



		echo "<!-- Start AWSOM News Announcement Block -->
		<div class=\"announcement\" style=\"$awsom_news_custom_css\">";
		if ($awsom_news_use_phpcode_status == 1){
					eval('?>' . $displaynewspost . '<?php ');
					}
				else{
					echo $displaynewspost;
			}
		echo"</div>";
		$wpdb->query("UPDATE $awsom_news_table_name SET timesdisplayed=timesdisplayed+1 WHERE anid ='$thisnewspost->anid'");
		if ( function_exists('current_user_can') && current_user_can($awsom_news_min_admin_level) ) {

		echo"<p><a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=editnewspost&eid=".$newspostanid."\">Edit News Post</a></p>";
		}
		echo"<!-- End AWSOM News Announcement Block -->";


	  }
	}
  }
}



function easiernews_manage_news() {
global $wpdb, $awsom_news_table_name, $awsom_news_min_admin_level, $awsomnews_path, $wp_ANP_url;


if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      	die(__('Cheatin’ uh?'));

if (isset($_POST['awsomnews_update_options']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
		check_admin_referer('ana_add_news_options');
		$updatedusetinymce = $_POST['awsomnewsusetinymce'];
		$updatedusetinymce = $wpdb->escape($updatedusetinymce);
		$updatedshowcredit = $_POST['awsomnewscredit'];
		$updatedshowcredit = $wpdb->escape($updatedshowcredit);
		$updatedusephpcode = $_POST['awsomnewsusephpcode'];
		$updatedusephpcode = $wpdb->escape($updatedusephpcode);
		$updatedpresetdivenable = $_POST['presetdivenable'];
		$updatedpresetdivenable = $wpdb->escape($updatedpresetdivenable);
		$updatedusecustomcss = $_POST['awsomnewscustomcssentry'];
		$updatedusecustomcss = trim($updatedusecustomcss);
		$updatedusecustomcss = $wpdb->escape($updatedusecustomcss);
		$updatedusecustomrole = $_POST['awsomnewscustomrole'];
		$updatedusecustomrole = $wpdb->escape($updatedusecustomrole);
		update_option("awsom_news_use_presetdiv", $updatedpresetdivenable);
		update_option("awsom_news_use_tinymce", $updatedusetinymce);
		update_option("awsom_news_use_phpcode", $updatedusephpcode);
		update_option("awsom_news_display_credit", $updatedshowcredit);
		update_option("awsom_news_use_custom_css", $updatedusecustomcss);
		update_option("awsom_news_use_custom_role", $updatedusecustomrole);
		echo "<div class='updated'><p><strong>News Announcement Options updated successfully!</strong></p></div>";
	}

if ($_GET['anpaction'] == "clearallviews" && !isset($_POST['awsomnews_clearallviews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
    	$getnewspostid= esc_attr($_GET['eid']);

    	echo "<div class='updated'>";
		echo"<form method=\"post\">";
		if (function_exists('wp_nonce_field')) { wp_nonce_field('ana_clear_views_news_post'); }
		echo"Are You Sure You Want To Clear All Views From Post Number ".$getnewspostid."?";
		echo"<input type=\"hidden\" name=\"clearanid\" value='$getnewspostid'>";
		echo"<div class=\"submit\" style=\"text-align: left;\"><input type=\"submit\" name=\"awsomnews_clearallviews\" value=\"Clear News Post Views\" /></form></div>";
		echo "</div>";
		}
if ($_GET['anpaction'] == "deletenewspost" && !isset($_POST['awsomnews_deletenews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
    	$getnewspostid = esc_attr($_GET['eid']);

    	echo "<div class='updated'>";
		echo"<form method=\"post\">";
		if (function_exists('wp_nonce_field')) { wp_nonce_field('ana_delete_news_post'); }
		echo"Are You Sure You Want To Delete Post Number ".$getnewspostid."?";
		echo"<input type=\"hidden\" name=\"deleteanid\" value='$getnewspostid'>";
		echo"<div class=\"submit\" style=\"text-align: left;\"><input type=\"submit\" name=\"awsomnews_deletenews\" value=\"Delete News Post\" /></form></div>";
		echo "</div>";
		}
if (isset($_POST['awsomnews_clearallviews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
      check_admin_referer('ana_clear_views_news_post');
    	$clearnewspostanid = $_POST['clearanid'];
		$clearnewspostanid = $wpdb->escape($clearnewspostanid);
		if ($clearnewspostanid !==""){
		$wpdb->query("UPDATE $awsom_news_table_name SET timesdisplayed= '0' WHERE anid ='$clearnewspostanid'");
		AWSOM_clear_get();
    	echo "<div class='updated'>";
		echo "All views cleared for News Post #".$clearnewspostanid;
		echo "</div>";
		}
		}
if (isset($_POST['awsomnews_deletenews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
		check_admin_referer('ana_delete_news_post');
		$deletenewspostanid = $_POST['deleteanid'];
		$deletenewspostanid = $wpdb->escape($deletenewspostanid);
		if ($deletenewspostanid !==""){
				$wpdb->query("
		DELETE FROM $awsom_news_table_name WHERE anid='$deletenewspostanid'");
		AWSOM_clear_get();
		echo "<div class='updated'>News Post Successfully Deleted!</div>";
		}
		}


if ($_GET['anpaction'] == "editnewspost" && !isset($_POST['awsomnews_updatenews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
    	$getnewspostid= esc_attr($_GET['eid']);
		$newsposttoedit = $wpdb->get_row("SELECT displayname, isactive, displaypos, newstext, whocanview, displaywhere, startdate, enddate FROM $awsom_news_table_name WHERE anid = '$getnewspostid'");
		$newsposttoedit->displayname = stripslashes($newsposttoedit->displayname);
		$newsposttoedit->isactive = stripslashes($newsposttoedit->isactive);
		$newsposttoedit->displaypos = stripslashes($newsposttoedit->displaypos);
		$newsposttoedit->startdate = stripslashes($newsposttoedit->startdate);
		$newsposttoedit->enddate = stripslashes($newsposttoedit->enddate);
		$newsposttoedit->newstext = stripslashes($newsposttoedit->newstext);
		$newsposttoedit->whocanview = stripslashes($newsposttoedit->whocanview);
		$newsposttoedit->displaywhere = stripslashes($newsposttoedit->displaywhere);
		$displayrightnow = date("Ymd");
	echo "<div class='updated'>";
	echo"<form method=\"post\">";
	if (function_exists('wp_nonce_field')) { wp_nonce_field('ana_update_news_post'); }
	echo"<b>Click \"Update News Post\" to Update your News Post</b>
	<blockquote>
		<table border=0 cellpadding=0 cellspacing=0>
			<tr><td>Display Name: <input type=\"text\" size=\"20\" name=\"updateddisplayname\" value='$newsposttoedit->displayname'><input type=\"hidden\" name=\"updatedanid\" value='$getnewspostid'><br /><br /></td></tr>
			<tr><td>Order Position: <input type=\"text\" size=\"5\" name=\"updatednewspos\" value='$newsposttoedit->displaypos'><br /><br /></td></tr>
			<tr><td width=\"360\">Is This News Announcement Active?:
													<select name=\"updatednewsstatus\">
									  <option value =\"1\"";
	if ($newsposttoedit->isactive == "1"){echo"selected";}
	echo">Yes</option><option value =\"0\"";
	if ($newsposttoedit->isactive == "0"){echo"selected";}
	echo">No</option></select><br /><br /></td>
					</tr><tr><td width=\"360\">Who can view this News Post? (minimum user level):
														<select name=\"updatednewsviewlevel\">
										  <option value =\"0\"";
										  if ($newsposttoedit->whocanview == "0"){echo"selected";}
										  echo">All Visitors</option>
										  <option value =\"1\"";
										  if ($newsposttoedit->whocanview == "1"){echo"selected";}
										  echo">Registered Users</option>
										  <option value =\"2\"";
										  if ($newsposttoedit->whocanview == "2"){echo"selected";}
										  echo">Contributers</option>
										  <option value =\"3\"";
										  if ($newsposttoedit->whocanview == "3"){echo"selected";}
										  echo">Authors</option>
										  <option value =\"4\"";
										  if ($newsposttoedit->whocanview == "4"){echo"selected";}
										  echo">Editors</option>
										  <option value =\"5\"";
										  if ($newsposttoedit->whocanview == "5"){echo"selected";}
										  echo">Administrators Only</option>
										  <option value =\"6\"";
										  if ($newsposttoedit->whocanview == "6"){echo"selected";}
										  echo">Non-Registered Only</option>
										  </select><br /><br /></td>
				</tr>
				<tr><td width=\"360\">What Locations display this News Post? :
														<select name=\"updatednewslocation\">
										  <option value =\"0\"";
										  if ($newsposttoedit->displaywhere == "0"){echo"selected";}
										  echo">Preset/Default Location</option>
										  <option value =\"1\"";
										  if ($newsposttoedit->displaywhere == "1"){echo"selected";}
										  echo">Optional Location 1</option>
										  <option value =\"2\"";
										  if ($newsposttoedit->displaywhere == "2"){echo"selected";}
										  echo">Optional Location 2</option>
										  <option value =\"3\"";
										  if ($newsposttoedit->displaywhere == "3"){echo"selected";}
										  echo">Optional Location 3</option>
										  <option value =\"4\"";
										  if ($newsposttoedit->displaywhere == "4"){echo"selected";}
										  echo">Optional Location 4</option>
										  <option value =\"5\"";
										  if ($newsposttoedit->displaywhere == "5"){echo"selected";}
										  echo">Optional Location 5</option>
										  <option value =\"6\"";
										  if ($newsposttoedit->displaywhere == "6"){echo"selected";}
										  echo">Optional Location 6</option>
										  <option value =\"7\"";
										  if ($newsposttoedit->displaywhere == "7"){echo"selected";}
										  echo">Optional Location 7</option>
										  <option value =\"8\"";
										  if ($newsposttoedit->displaywhere == "8"){echo"selected";}
										  echo">Optional Location 8</option>
										  <option value =\"9\"";
										  if ($newsposttoedit->displaywhere == "9"){echo"selected";}
										  echo">All Locations</option>
										  </select><br /><br /></td>
				</tr>
				<tr><td><b>Note: please input Date using following pattern: 20090110 (YYYYMMDD) = January 10, 2009</b><br />Today's Date is: $displayrightnow<br />Display Start Date (0 = immediately):<input type=\"text\" size=\"7\" name=\"updatednewsstartdate\" value='$newsposttoedit->startdate'> Display End Date (0 = No End date):<input type=\"text\" size=\"7\" name=\"updatednewsenddate\" value='$newsposttoedit->enddate'><br /><br /></td></tr>
			<tr><td>Enter Your News Announcement:<br />
				<TEXTAREA name=\"awsomnewspost\" id=\"awsomnewspost\" rows=\"10\" cols=\"80\">";
	echo $newsposttoedit->newstext;
	 echo"</TEXTAREA><br /></td>
			</tr>
		</table>
		</blockquote>

	<div class=\"submit\" style=\"text-align: left;\"><input type=\"submit\" name=\"awsomnews_updatenews\" value=\"Update News Post\" /></div>

	</form>
</div>";

		}

if (isset($_POST['awsomnews_updatenews']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
		check_admin_referer('ana_update_news_post');
		/* update news post in db */
		$updatednewspostanid = $_POST['updatedanid'];
		$updatednewspostanid = $wpdb->escape($updatednewspostanid);
		$updatednewspost = $_POST['awsomnewspost'];
		$updatednewspost = trim($updatednewspost);
		$updatednewspost = $wpdb->escape($updatednewspost);
		$updatednewspostdisname = $_POST['updateddisplayname'];
		$updatednewspostdisname = trim($updatednewspostdisname);
		$updatednewspostdisname = $wpdb->escape($updatednewspostdisname);
		if ($updatednewspostdisname == ""){
		$updatednewspostdisname = "news_".$updatednewspostanid;
		}
		$updatednewspostpos = $_POST['updatednewspos'];
		$updatednewspostpos = trim($updatednewspostpos);
		$updatednewspostpos = $wpdb->escape($updatednewspostpos);
		if ($updatednewspostpos == ""){
		$updatednewspostpos = "0";
		}
		$updatednewspoststartdate = $_POST['updatednewsstartdate'];
		$updatednewspoststartdate = trim($updatednewspoststartdate);
		$updatednewspoststartdate = $wpdb->escape($updatednewspoststartdate);
		$updatednewspostenddate = $_POST['updatednewsenddate'];
		$updatednewspostenddate = trim($updatednewspostenddate);
		$updatednewspostenddate = $wpdb->escape($updatednewspostenddate);
		$updatednewspoststatus = $_POST['updatednewsstatus'];
		$updatednewspoststatus = $wpdb->escape($updatednewspoststatus);
		$updatednewspostviewerlvl = $_POST['updatednewsviewlevel'];
		$updatednewspostviewerlvl = $wpdb->escape($updatednewspostviewerlvl);
		$updatednewspostlocation = $_POST['updatednewslocation'];
		$updatednewspostlocation = $wpdb->escape($updatednewspostlocation);
		$wpdb->query("
		UPDATE $awsom_news_table_name SET newstext = '$updatednewspost', isactive = '$updatednewspoststatus', displaypos = '$updatednewspostpos', displayname = '$updatednewspostdisname', whocanview = '$updatednewspostviewerlvl', displaywhere = '$updatednewspostlocation', startdate = '$updatednewspoststartdate', enddate = '$updatednewspostenddate' WHERE anid = '$updatednewspostanid'");
		echo "<div class='updated'><p><strong>News Post updated successfully!</strong></p></div>";
		}
if (isset($_POST['awsomnews_addnew']))
		{
		if ( function_exists('current_user_can') && !current_user_can($awsom_news_min_admin_level) )
      die(__('Cheatin’ uh?'));
		check_admin_referer('ana_add_news_post');
		/* Add new news post in db */
		$newdnewspost = $_POST['awsomnewspost'];
		$newdnewspost = trim($newdnewspost);
		$newdnewspost = $wpdb->escape($newdnewspost);
		$newnewspostviewlvl = $_POST['newnewsviewlevel'];
		$newnewspostviewlvl = $wpdb->escape($newnewspostviewlvl);
		$newnewspostlocation = $_POST['newnewslocation'];
		$newnewspostlocation = $wpdb->escape($newnewspostlocation);
		$newnewspostdisname = $_POST['newdisplayname'];
		$newnewspostdisname = trim($newnewspostdisname);
		$newnewspostdisname = $wpdb->escape($newnewspostdisname);
		if ($newnewspostdisname == ""){
		$newnewspostdisname = "news post";
		}
		$newnewspostpos = $_POST['newnewspos'];
		$newnewspostpos = trim($newnewspostpos);
		$newnewspostpos = $wpdb->escape($newnewspostpos);
		if ($newnewspostpos == ""){
		$$newnewspostpos = "0";
		}
		$newnewspoststartdate = $_POST['newnewsstartdate'];
		$newnewspoststartdate = trim($newnewspoststartdate);
		$newnewspoststartdate = $wpdb->escape($newnewspoststartdate);
		$newnewspostenddate = $_POST['newnewsenddate'];
		$newnewspostenddate = trim($newnewspostenddate);
		$newnewspostenddate = $wpdb->escape($newnewspostenddate);
		$newnewspoststatus = $_POST['newnewsstatus'];
		$newnewspoststatus = $wpdb->escape($newnewspoststatus);
		$wpdb->query("
		INSERT INTO $awsom_news_table_name (anid, displayname, newstext, displaypos, isactive, whocanview, displaywhere, startdate, enddate )
		VALUES (null, '$newnewspostdisname', '$newdnewspost', '$newnewspostpos', '$newnewspoststatus', '$newnewspostviewlvl', '$newnewspostlocation', '$newnewspoststartdate', '$newnewspostenddate')");
		echo "<div class='updated'><p><strong>News Post added successfully!</strong></p></div>";
		}



?>

<?php $awsom_news_use_tinymce_status = get_option("awsom_news_use_tinymce");
	if ($awsom_news_use_tinymce_status == 1  ){  ?>
<script type="text/javascript" src="<?php echo $awsomnews_path; ?>tiny_mce/tiny_mce.js"></script>



	<script type="text/javascript">
	tinyMCE.init({
		// General options
		theme : "advanced",
		plugins : "safari,pagebreak,table,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,visualchars,nonbreaking,xhtmlxtras",
		mode : "exact",
        elements : "awsomnewspost, newawsomnewspost",
        relative_urls : false,
        remove_script_host : false,
        width : "565",
	height : "250",
	skin : "wp_theme",
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true



	});
</script>
<!-- /TinyMCE -->




<?php }  ?>


<div class="wrap" style="font-size:12pt;">


  <br />

    <h2>AWSOM News ver. 1.5.5 Administration Page</h2>
<b>Setup Instructions: </b>
By default the plugin will display the News Announcement above your posts on the index page, select "no" to disable this and allow a custom area to display the News Announcement.<br /><br />
By adding a new line to or changing your template's CSS file entry for ".announcement" you can make the announcement look differently.<br /><br /><a href="<?php echo $awsomnews_path; ?>help_docs/known_issues.txt" style="text-decoration:none" target="_blank">Read the Known Issues file</a> or To get support or check for updates please go to <a href="http://www.awsom.org">Awsom.org</a>
<br />****MAJOR CHANGE**** Please note that the template code below is now different and must be updated in all existing theme locations if you want to support numbered locations.<br />
<br />To add the news post to your wordpress template in a custom area of a template file add
the following code where you want the News Announcement to appear:
<pre>
&#60;!-- Start AWSOM News Announcement Block --&#62;
&#60;div id="announcement"&#62;
&#60;?php if (function_exists('display_my_news_announcement')) { display_my_news_announcement(0); } ?&#62;
&#60;/div&#62;
&#60;!-- End AWSOM News Announcement Block --&#62;
</pre><br /><br />
Or you can add the news post block to any post or page by putting the code: <b>&#37;&#37;awsomnews0&#37;&#37;</b> wherever you want the the news block to appear. You can use the news block then to add active php code to any page or post by turning on the allow php option below.<br /><br />
[Note: For the above codes you may place an optional number from 1-8  (and replace the 0) within the () of "display_my_news_announcement()" or &#37;&#37;awsomnews0&#37;&#37; to have specific news posts *only* appear in this location.<br /><br />

<hr />
<b>The following are your current News Posts</b><br /><br />
<?php   $oldnewsannouncements = $wpdb->get_results("SELECT anid, displayname, isactive, displaypos, whocanview, displaywhere, timesdisplayed, startdate, enddate FROM $awsom_news_table_name Order by anid");

$showoldnews = "<table border=\"1\" cellpadding=\"4\"><tr><td align=\"center\">ID</td><td align=\"center\">: News Post Name :</td><td align=\"center\">: Display Position :</td><td align=\"center\">: Active? :</td><td align=\"center\">: Start Date :</td><td align=\"center\">: End Date :</td><td align=\"center\">: Who Can View It? :</td><td align=\"center\">: Location :</td><td align=\"center\">: No. of Views :</td><td align=\"center\">: Admin Functions :</td></tr>";
	foreach ($oldnewsannouncements as $thisnewspost){
	 $thisnewspost->anid = stripslashes($thisnewspost->anid);
	 $thisnewspost->timesdisplayed = stripslashes($thisnewspost->timesdisplayed);
	 $thisnewspost->startdate = stripslashes($thisnewspost->startdate);
	 $thisnewspost->enddate = stripslashes($thisnewspost->enddate);
	 $thisnewspost->displayname = stripslashes($thisnewspost->displayname);
	 $thisnewspost->isactive = stripslashes($thisnewspost->isactive);
	 if ($thisnewspost->isactive == "1"){
	 $thisnewspost->isactive = "Yes";}
	 else {
	 $thisnewspost->isactive  = "No"; }
	 $thisnewspost->displaypos = stripslashes($thisnewspost->displaypos);
	 $thisnewspost->displaywhere = stripslashes($thisnewspost->displaywhere);
	 if ( $thisnewspost->displaywhere == "0"){
	 $thisnewspost->displaywhere = "Preset/Default"; }
	 if ( $thisnewspost->displaywhere == "9"){
	 $thisnewspost->displaywhere = "All"; }
	 $thisnewspost->whocanview = stripslashes($thisnewspost->whocanview);
	 $thisnewspost->whocanview = viewer_friendly_name($thisnewspost->whocanview);
	 //$link = 'your-url.php';
	 //$link = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($link, 'plugin-name-action_' . $your_object) : $link;
	 $showoldnews .= "<td align=\"center\"><b>".$thisnewspost->anid."</b></td><td align=\"center\">".$thisnewspost->displayname."</td><td align=\"center\">".$thisnewspost->displaypos."</td><td align=\"center\">".$thisnewspost->isactive."</td><td align=\"center\">".$thisnewspost->startdate."</td><td align=\"center\">".$thisnewspost->enddate."</td><td align=\"center\">".$thisnewspost->whocanview."</td><td align=\"center\">".$thisnewspost->displaywhere."</td><td align=\"center\">".$thisnewspost->timesdisplayed."</td><td nowrap align=\"center\"><a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=editnewspost&eid=".$thisnewspost->anid."\">Edit</a>&nbsp;::&nbsp;<a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=deletenewspost&eid=".$thisnewspost->anid."\">Delete</a>&nbsp;::&nbsp;<a href=\"".$wp_ANP_url."/wp-admin/edit.php?page=awsomnews.php&anpaction=clearallviews&eid=".$thisnewspost->anid."\">Zero Views</a></td><tr>";
	}
	$showoldnews .= "</table><hr />";
	echo $showoldnews; ?>





<form method="post"><?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ana_add_news_post'); } ?>
<legend><b>New News Post Instructions:</b> Add your New News post in the text field then click "Update News". You may use HTML in your news post or use the Visual Editor. To make is easier for you to remember different news posts you may assign a display name to a post, otherwise it will be assigned a default name (Note: This name is only used in the admin area and is never displayed to visitors). Also, if you have multiple news posts active you may assign the order these posts are displayed by inputting a number into the Display order field, otherwise your active posts will display in ID order. The Location option allows you to place your news posts in specific locations (requires theme/template/post codes) or in the default location on your index page.</legend>
<blockquote>
	<table border=0 cellpadding=0 cellspacing=0>
		<tr><td>Display Name: <input type="text" size="20" name="newdisplayname"><br /><br /></td></tr>
		<tr><td>Order Position: <input type="text" size="5" name="newnewspos"><br /><br /></td></tr>
		<tr><td width="360">Is This News Announcement Active?:
												<select name="newnewsstatus">
								  <option value ="1" selected>Yes</option>
								  <option value ="0">No</option></select><br /><br /></td>
				</tr>
		<tr><td width="360">Who can view this News Post? (minimum user level):
														<select name="newnewsviewlevel">
										  <option value ="0" selected>All Visitors</option>
										  <option value ="1">Registered Users</option>
										  <option value ="2">Contributers</option>
										  <option value ="3">Authors</option>
										  <option value ="4">Editors</option>
										  <option value ="5">Administrators Only</option>
										  <option value ="6">Non-Registered Only</option>
										  </select><br /><br /></td>
				</tr>
				<tr><td width="360">What Locations Display this News Post?:
														<select name="newnewslocation">
										  <option value ="0" selected>Preset/Default Location</option>
										  <option value ="1">Optional Location 1</option>
										  <option value ="2">Optional Location 2</option>
										  <option value ="3">Optional Location 3</option>
										  <option value ="4">Optional Location 4</option>
										  <option value ="5">Optional Location 5</option>
										  <option value ="6">Optional Location 6</option>
										  <option value ="7">Optional Location 7</option>
										  <option value ="8">Optional Location 8</option>
										  <option value ="9">All Locations</option>
										  </select><br /><br /></td>
				</tr>
			<tr><td><b>Note: please input Date using following pattern: 20090110 (YYYYMMDD) = January 10, 2009</b><br /><?php $displayrightnow = date("Ymd"); echo "Today's Date is: $displayrightnow"; ?><br />Display Start Date (0 = immediately): <input type="text" size="7" value="0" name="newnewsstartdate"> Display End Date (0 = No End date): <input type="text" size="7" value ="0" name="newnewsenddate"><br /><br /></td></tr>
		<tr><td>Enter Your News Announcement:<br />
			<TEXTAREA name="awsomnewspost" id="newawsomnewspost" rows="10" cols="80">
    </TEXTAREA><br /></td>
		</tr>
	</table>
	</blockquote>

<div class="submit" style="text-align: left;"><input type="submit" name="awsomnews_addnew" value="Add New News Post" /></div>

</form>
<hr /><form method="post"><?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ana_add_news_options'); } ?>
<b>AWSOM News General Options:</b><br />
		Would you like to use the Visual Editor as the News Announcement editor? (Note: may require page refresh after update to switch between editor modes)<br /> <select name="awsomnewsusetinymce">
		 <?php $awsom_news_use_tinymce_status = get_option("awsom_news_use_tinymce"); ?>
		  <option value ="1" <?php if ($awsom_news_use_tinymce_status == '1') echo "selected"; ?>>Yes</option>
		  <option value ="0" <?php if ($awsom_news_use_tinymce_status == '0') echo "selected"; ?>>No</option></select>
 <br />
 <br />
 		You may add custom CSS to your News Posts by inputting it in the following field. Add in the CSS entries directly in standard format (example: font-size:20pt; background-color: #ffffff;)<br />
 		 <?php $awsom_news_use_custom_css = get_option("awsom_news_use_custom_css"); ?>
 		  <input type="text" size="50" name="awsomnewscustomcssentry" value="<?php echo $awsom_news_use_custom_css; ?>"><a href="<?php echo $awsomnews_path; ?>help_docs/custom_css_setting.txt" style="text-decoration:none" target="_blank"><img src="<?php echo $awsomnews_path; ?>images/help.png" border="0" align="top" title="Click here to get Help on the Custom CSS setting" alt="Get Help on the Custom CSS Setting"></a>
  <br />
 <br />
  		Would you like to allow PHP code to run in your News Announcements?(Note: This can cause errors if the PHP code is not properly formatted.)<br /> <select name="awsomnewsusephpcode">
  		 <?php $awsom_news_use_phpcode_status = get_option("awsom_news_use_phpcode"); ?>
  		  <option value ="1" <?php if ($awsom_news_use_phpcode_status == '1') echo "selected"; ?>>Yes</option>
  		  <option value ="0" <?php if ($awsom_news_use_phpcode_status == '0') echo "selected"; ?>>No</option></select><a href="<?php echo $awsomnews_path; ?>help_docs/run_php_setting.txt" style="text-decoration:none" target="_blank"><img src="<?php echo $awsomnews_path; ?>images/help.png" border="0" align="top" title="Click here to get Help on the Run Php setting" alt="Get Help on the Run PHP Setting"></a>
  <br /><br />
  Enable News Announcement On Index Page above Posts (default location, select No if you want to place News announcements only in a custom location by editing your template or using the post code)? <br /><select name="presetdivenable">
   <?php $awsom_news_div_preset_status = get_option("awsom_news_use_presetdiv"); ?>
    <option value ="1" <?php if ($awsom_news_div_preset_status == '1') echo "selected"; ?>>Yes</option>
  <option value ="0" <?php if ($awsom_news_div_preset_status == '0') echo "selected"; ?>>No</option></select><a href="<?php echo $awsomnews_path; ?>help_docs/default_location_setting.txt" style="text-decoration:none" target="_blank"><img src="<?php echo $awsomnews_path; ?>images/help.png" border="0" align="top" title="Click here to get Help on the Default Location setting" alt="Get Help on the Default Location Setting"></a><br /><br />
  Would you like to allow non-admins to edit your News Posts? Set User level for News Post Administration:<br /> <select name="awsomnewscustomrole">
    		 <?php $awsom_news_use_custom_role = get_option("awsom_news_use_custom_role"); ?>
    		  <option value ="manage_options" <?php if ($awsom_news_use_custom_role == 'manage_options') echo "selected"; ?>>Administrator</option>
    		  <option value ="manage_links" <?php if ($awsom_news_use_custom_role == 'manage_links') echo "selected"; ?>>Editor</option>
    		  <option value ="publish_posts" <?php if ($awsom_news_use_custom_role == 'publish_posts') echo "selected"; ?>>Author</option></select>
  <br /><br />
  Would you like to Support AWSOM News Announcement by adding a credit to AWSOM.org in your footer? (It's appreciated, but not required)? <br /><select name="awsomnewscredit">
     <?php $awsom_news_credit_status = get_option("awsom_news_display_credit"); ?>
      <option value ="1" <?php if ($awsom_news_credit_status == '1') echo "selected"; ?>>Yes</option>
  <option value ="0" <?php if ($awsom_news_credit_status == '0') echo "selected"; ?>>No</option></select><br /><br />
   <div class="submit" style="text-align: left;"><input type="submit" name="awsomnews_update_options" value="Update Options" /></div>

</form>
</div>

<?php
}

function easiernews_TableInstall() {
   global $wpdb, $awsom_news_table_name, $awsom_news_db_version ;


   if($wpdb->get_var("show tables like '$awsom_news_table_name'") != $awsom_news_table_name) {

      $sql = "CREATE TABLE ".$awsom_news_table_name." (
	  	      anid mediumint(9) NOT NULL AUTO_INCREMENT,
			  newstext longtext NOT NULL,
			  isactive tinyint DEFAULT '0' NOT NULL,
			  lastdisplayed  bigint(11) DEFAULT '0' NOT NULL,
			  timesdisplayed  bigint(11) DEFAULT '0' NOT NULL,
			  whocanview  tinyint  DEFAULT '0' NOT NULL,
			  displaypos  int  NOT NULL,
			  startdate  bigint(11) DEFAULT '0' NOT NULL,
			  enddate  bigint(11) DEFAULT '0' NOT NULL,
			  displaywhere  smallint  DEFAULT '0' NOT NULL,
			  displayname  text NOT NULL,
	 	  	  UNIQUE KEY id (anid)
              );";

      require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      dbDelta($sql);
      $firstnews = "<h2>My First News Announcement. Your News Announcement plugin is now working.</h2>";

      $wpdb->query("INSERT INTO $awsom_news_table_name ( isactive, newstext, displayname) VALUE ( 1,'$firstnews','Default News Post' )");
		update_option("awsom_news_use_presetdiv", 1);
		update_option("awsom_news_db_version", $awsom_news_db_version);
		update_option("awsom_news_use_tinymce", 1);
		update_option("awsom_news_use_phpcode", 0);
		update_option("awsom_news_display_credit", 1);
      }
$installed_ver = get_option( "awsom_news_db_version" );

   if( $installed_ver < $awsom_news_db_version ) {

  	 $updatetonewestdatabase = $wpdb->get_row("SELECT isactive, newstext FROM $awsom_news_table_name");

  	 require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
  	 $sql = "DROP TABLE ".$awsom_news_table_name;
  	 $wpdb->query($sql);

  	 $sql = "CREATE TABLE ".$awsom_news_table_name." (
	 	  	      anid mediumint(9) NOT NULL AUTO_INCREMENT,
				  newstext longtext NOT NULL,
	 			  isactive tinyint DEFAULT '0' NOT NULL,
	 			  lastdisplayed  bigint(11) DEFAULT '0' NOT NULL,
	 			  timesdisplayed  bigint(11) DEFAULT '0' NOT NULL,
	 			  whocanview  tinyint  DEFAULT '0' NOT NULL,
	 			  displaypos  int  NOT NULL,
	 			  startdate  bigint(11) DEFAULT '0' NOT NULL,
	 			  enddate  bigint(11) DEFAULT '0' NOT NULL,
	 		  displaywhere  smallint  DEFAULT '0' NOT NULL,
	 			  displayname  text NOT NULL,
	 	 	  	  UNIQUE KEY id (anid)
              );";


      dbDelta($sql);

		$wpdb->query("INSERT INTO $awsom_news_table_name ( anid, isactive, newstext, displayname) VALUE ( NULL, '$updatetonewestdatabase->isactive','$updatetonewestdatabase->newstext','Default News Post' )");
		update_option("awsom_news_db_version", $awsom_news_db_version);
		update_option("awsom_news_display_credit", 1);
		}

}



function easiernews_create_admin()
{
global $awsom_news_min_admin_level;
    if (function_exists('add_submenu_page'))
			add_submenu_page('post.php','AWSOM News Management Page', 'AWSOM News', $awsom_news_min_admin_level, basename(__FILE__), 'easiernews_manage_news');
}

function AWSOM_Footer_Credit()
{
?>
<div id="awsomfootercredit"><!--- AWSOM News Announcement Footer Credit Block -->
 <a href="http://www.awsom.org" title="AWSOM Plugin Powered">AWSOM Powered</a>
</div><!--- end AWSOM News Announcement Footer Credit Block -->
<?php
}

function awsom_news_multi_loop_blocker ()
{
global $awsom_news_displayed;

$awsom_news_displayed = 0;
}

function awsom_news_reset_loop()
{
global $awsom_news_displayed;
$awsom_news_displayed = 0;
}






$awsomnews_footercredit = get_option('awsom_news_display_credit');
if ($awsomnews_footercredit == "1") {
if (function_exists('AWSOM_Archive_Footer_Credit')){
	 remove_action('wp_footer', 'AWSOM_Archive_Footer_Credit',11);
	 }
	if (function_exists('PixGallery_Footer_Credit')){
	 	 remove_action('wp_footer', 'PixGallery_Footer_Credit');
	 }
	add_action('wp_footer', 'AWSOM_Footer_Credit',11);
	}

$onlyshownewspresetdiv = get_option('awsom_news_use_presetdiv');

if ($onlyshownewspresetdiv == 1) {
add_action('init', 'awsom_news_multi_loop_blocker');
add_action('shutdown', 'awsom_news_reset_loop');
}
add_action('loop_start', 'display_my_news_announcement_preset', 8);
add_action('admin_menu', 'awsom_clear_get');
add_action('admin_menu',	'easiernews_create_admin');
add_action('activate_awsom-news-announcement/awsomnews.php','easiernews_TableInstall');
add_action('the_content','display_my_awsom_news_inpost', 7);

?>
