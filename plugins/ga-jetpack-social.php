<?php
/**
 * Plugin Name: Jetpack Google Analytics Tracking
 * Description: Allows tracking of Facebook and Twitter shares in Google Analytics Social Tracking
 * Author: Benjamin J. Balter
 * Author URI:  http://ben.balter.com
 * Version: 0.1
 */
 
/**
 * Outputs javascript to document head
 */
function bb_ga_social_footer() { ?>
<script>
    jQuery(document).ready(function($) {
 
        //twitter
        jQuery('a.share-twitter').click(function(){
        _gaq.push( ['_trackSocial', 'twitter', 'share',
        jQuery(this).attr('href').substr(0,  jQuery(this).attr('href').indexOf('?'))]);
        });
 
        //facebook
        jQuery('a.share-facebook').click( function() {
        _gaq.push( ['_trackSocial', 'faceboook', 'share',
        jQuery(this).attr('href').substr(0, jQuery(this).attr('href').indexOf('?'))]);
        });
		
		//Google Plus
        jQuery('a.share-google-plus-1').click( function() {
        _gaq.push( ['_trackSocial', 'Google +1', 'share',
        jQuery(this).attr('href').substr(0, jQuery(this).attr('href').indexOf('?'))]);
        });
		
		//share-ВКонтакте
        jQuery('a.share-ВКонтакте').click( function() {
        _gaq.push( ['_trackSocial', 'ВКонтакте', 'share',
        jQuery(this).attr('href').substr(0, jQuery(this).attr('href').indexOf('?'))]);
        });
		
		//share-Одноклассники
        jQuery('a.share-Одноклассники').click( function() {
        _gaq.push( ['_trackSocial', 'Одноклассники', 'share',
        jQuery(this).attr('href').substr(0, jQuery(this).attr('href').indexOf('?'))]);
        });
		
		//share-Mail.ru
        jQuery('a.share-Mail.ru').click( function() {
        _gaq.push( ['_trackSocial', 'Mail.ru', 'share',
        jQuery(this).attr('href').substr(0, jQuery(this).attr('href').indexOf('?'))]);
        });
 
    });
</script>
<?php }
 
//add our hook with higher-than-default priority
add_action('wp_footer', 'bb_ga_social_footer', 60);
 
/**
* Require WP to load jQuery if not already loaded
* h/t @Ramoonus
*/
function bb_ga_social_enqueue() {
wp_enqueue_script("jquery");
}
 
//add hook to enqueue jQuery on load
add_action('init', 'bb_ga_social_enqueue');
?>