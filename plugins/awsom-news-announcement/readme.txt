=== AWSOM News Announcement ===
Contributors: Harknell
Donate link: http://www.awsom.org
Tags: post, posts, admin, news, announcement
Requires at least: 2.0.4
Tested up to: 3.3
Stable tag: 1.5.5

AWSOM News Announcement allows you to post news announcements in the area above your posts (or anywhere else you want) and edit it using the Visual Editor.
== Description ==

AWSOM News Announcement allows you to post news announcements in the area above your posts (or anywhere else you want) and edit it using the Visual Editor or code editor, all from the Wordpress admin area. You can select who sees your posts (only non-registered people, or whatever level of registered user for your site) so you can keep certain things for only people who are members, or you can show ads only to non-registered visitors. You can now also run php code from the news area, so audio or video players can now be run without any other plugin. You can now add the news area to a post or page and also run php code there. You can set start and end dates for all news posts. The Plugin is now fully XHTML compliant.
Part of the www.AWSOM.org series of Wordpress Plugins developed by Harknell, webmaster of the Webcomic at www.onezumi.com


== Installation ==



To Install:

Copy the plugin folder into your Wordpress plugin directory. Activate the plugin from the plugin interface. If you would like to place the News Announcement in an area other than at the top of your posts add the following lines to your theme file where you want the announcement to appear:

&#60;!-- Start AWSOM News Announcement Block --&#62;
&#60;div id="announcement"&#62;
&#60;?php if (function_exists('display_my_news_announcement')) { display_my_news_announcement(0); } ?&#62;
&#60;/div&#62;
&#60;!-- End AWSOM News Announcement Block --&#62;

or you can add the code %%awsomnews0%% to any post or page to have the news block appear there. If you turn on active php you
can use the news block to add different video or audio players or any plugin that needs php code to run.

You will then want to turn the option "Place the News Announcement On Index Page above Posts" to off in the Write/Posts-> Awsom News area. 
You can choose to use the Visual editor or directly add HTML code. If you wish to switch between editor types you need to switch the setting
and update the news post, then refresh the page again to have the editor reload properly.



For updates or support please go to http://www.awsom.org 


== Upgrading From Previous Version ==


Upgrade instructions for users of AWSOM News 1.02 

To upgrade the AWSOM News Announcement plugin from 1.0.2 to the new version do the following steps in order:

1) Go to your Admin Plugins area and deactivate the previous AWSOM News Announcement plugin.
2) Delete the awsomnews plugin folder from the wp-content/plugin directory.
3) unzip the new  AWSOM News Announcement zip file and move the contents to your wp-content/plugins folder
4) Go to your Admin Plugins area and activate the new AWSOM News Announcement plugin.
5) You have 2 options at this point: If you previously used the Theme file tag to display your News Announcement and don't want to change
things then turn the option "Place the News Announcement On Index Page above Posts" to off. 
6) OR If you want to remove the now unnecessary Theme edits that version 1.0.2 required go into your theme index.php file
(or wherever you may have added the code) and delete:

<div id="announcement">
<?php if (function_exists('display_my_news_announcement')) { display_my_news_announcement(); } ?>
</div>

from your theme file. Make sure that you then turn the option "Place the News Announcement On Index Page above Posts" to Yes.

To upgrade from 1.1.0 - 1.4.7 to new version

1) deactivate the current Awsom News plugin from the admin Plugin menu.
2) Delete the current Awsom News folder from the wp-content/plugins folder.
3) Move the new folder to the wp-content/plugins folder.
4) Activate the plugin from the Admin Plugins menu.
5) Make sure the settings in the Write/Posts--> Awsom News menu are set correctly for your set up.

== Screenshots ==

1. The News Input Area, showing usage of the Visual Editor.
2. The General Options Area, showing the run php settings, management level selector, and other options
3. The Listing of your current news posts, showing who will see them and what order they are displayed.