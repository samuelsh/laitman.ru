version 1.5.5 release
Author: Harknell
AWSOM Project website: http://www.awsom.org


This release of Awsom News Announcement is designed to give the Wordpress user the ability
to easily update and display news announcements to their readers without needing to do extensive
file editing or template customization.

The plugin installs a database table to store the news announcement text and also creates a new Manage admin area that 
allows the admin to easily update their news announcement.

To Install:

Copy the plugin folder into your Wordpress plugin directory. Activate the plugin from the plugin interface.By default the plugin adds your new News Announcement area above the posts on your index page--If you would like to place the News Announcement in an area other than at the top of your posts add the following lines to your theme file where you want the announcement to appear:
<!-- Start AWSOM News Announcement Block -->
<div id="announcement">
<?php if (function_exists('display_my_news_announcement')) { display_my_news_announcement(0); }  ?>
</div>
<!-- End AWSOM News Announcement Block -->

or you can add the code %%awsomnews0%% to any post or page to have the news block appear there. By placing a number from 1 to 8 in place of the number 0 in both codes above you can make specialized areas that will display only the news posts you assign to those areas. If you turn on active php you
can use the news block to add different video or audio players or any plugin that needs php code to run.

You will then want to turn the option "Place the News Announcement On Index Page above Posts" to off in the Posts--> Awsom News area (unless you want
the block to appear in both place). 

You can choose to use the Visual editor or directly add HTML code. If you wish to switch between editor types you need to switch the setting
and update the news post.

The "Run PHP in News Announcement" setting allows the admin to use active PHP code in the block by entering it in the format: <?php .... ?> with the active code in the middle.
Now you can use many Wordpress plugins that require a template tag to display (such as video players, audio players, etc.) in your News Announcement block. Do not leave this setting active if you do not plan on using php code though.
Note: You must use the Code editor to input PHP code, the Visual editor will not allow PHP code to be correctly entered.

The "Would you like Support AWSOM News Announcement by adding a credit to AWSOM.org in your footer?" setting
is to help support AWSOM.org--you don't have to have it turned on, but it's a sign of support and helps me keep developing plugins.

The setting "Would you like to allow non-admins to edit your News Posts? Set User level for News Post Administration:" allows you designate what level of user to your site can access and update your news posts, you may set this to Authors, Editors, or Admins, the setting indicating the lowest level of user who may access the manage area.

The setting "You may add custom CSS to your News Posts by inputting it in the following field. Add in the CSS entries directly in standard format (example: font-size:20pt; background-color: #ffffff;)" is designed to allow you to add CSS settings directly through the admin interface to customize the look of your News Announcement blocks. Any regular CSS statements may be added and you may input a succession of them (make sure each ends with a ; ).

KNOWN ISSUES:

    * During News Post Deletion and Editing, after the process is completed and a confirmation screen is displayed, the admin page will have a forced refresh after about 5 seconds to clear some data. This is normal and not a glitch. Navigating away to a different screen before the refresh occurs is ok and will also clear the data.
    * If a News Post is in any way being seen in multiple spots on one page (either set to all locations or just happens to be in multiple spots) the view count for that News Post will not be accurate (basically it gets counted an extra time for each location).
    
For updates or support please go to http://www.awsom.org 
