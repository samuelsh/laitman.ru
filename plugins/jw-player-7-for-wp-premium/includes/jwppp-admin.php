<?php

/**
 * JW Player for Wordpress - Premium
 * Administration functions
 */

//GET THE SCRIPT REQUIRED FROM THE MENU
function jwppp_register_js_menu() {
	wp_register_script('jwppp-admin-nav', plugins_url('js/jwppp-admin-nav.js', 'jw-player-7-for-wp-premium/js'), array('jquery'), '1.0', true );
	wp_enqueue_style('jwppp-style', plugins_url('includes/jwppp-style.css', 'jw-player-7-for-wp-premium/includes'));
}
add_action( 'admin_init', 'jwppp_register_js_menu' );


function jwppp_js_menu() {
	wp_enqueue_script('jwppp-admin-nav');
}
add_action( 'admin_menu', 'jwppp_js_menu' );


//MENU ITEMS
function jwppp_add_menu() {
	$jwppp_page = add_menu_page( 'JW Player for Wordpress - Premium', 'JW Player', 'manage_options', 'jw-player-for-wp', 'jwppp_options', 'dashicons-format-video');
	
	//SCRIPT
	add_action( 'admin_print_scripts-' . $jwppp_page, 'jwppp_js_menu');
	
	return $jwppp_page;
}
add_action( 'admin_menu', 'jwppp_add_menu' );


//ADD COLOR PICKER
function jwppp_add_color_picker() {
    if( is_admin() ) { 
        wp_enqueue_style( 'wp-color-picker' );          
        wp_enqueue_script( 'wp-color-picker', array('jquery'), '', true ); 
    }
}
add_action( 'admin_enqueue_scripts', 'jwppp_add_color_picker' );


//VALIDATE COLOR
function jwppp_check_color( $value ) { 
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) || $value == '' ) {     
        return true;
    }
    return false;
}


//CHECK FOR CAPTION STYLE
function jwppp_caption_style() {
	$options = array(
		'jwppp-subtitles-color',
		'jwppp-subtitles-font-size',
		'jwppp-subtitles-font-family',
		'jwppp-subtitles-opacity',
		'jwppp-subtitles-back-color',
		'jwppp-subtitles-back-opacity'
	);
	foreach ($options as $option) {
		if(get_option($option)) {
			return true;
			continue;
		}
	}
	return false;
}


//OPTION PAGE
function jwppp_options() {
	
	//CAN YOU?
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'It looks like you do not have sufficient permissions to view this page.', 'jwppp' ) );
	}

//START PAGE TEMPLATE
echo '<div class="wrap">'; 
	echo '<div class="wrap-left" style="float:left; width:70%;">';

	echo '<div id="jwppp-description">';
	    //HEADER
		echo "<h1 class=\"jwppp main\">" . __( 'JW Player for Wordpress - Premium', 'jwppp' ) . "<span style=\"font-size:60%;\"> 1.5.0</span></h1>";
	echo '</div>';

?>
	    
	<h2 id="jwppp-admin-menu" class="nav-tab-wrapper">
		<a href="#" data-link="jwppp-settings" class="nav-tab nav-tab-active" onclick="return false;"><?php echo __('Settings', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-related" class="nav-tab" onclick="return false;"><?php echo __('Related videos', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-subtitles" class="nav-tab" onclick="return false;"><?php echo __('Subtitles', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-social" class="nav-tab" onclick="return false;"><?php echo __('Sharing', 'jwppp'); ?></a>    
		<a href="#" data-link="jwppp-ads" class="nav-tab" onclick="return false;"><?php echo __('Ads', 'jwppp'); ?></a>                                        
	</h2>


	<!-- START - SETTINGS -->
 	<div name="jwppp-settings" id="jwppp-settings" class="jwppp-admin" style="display: block;">

 		<?php

 			echo '<form id="jwppp-options" method="post" action="">';
 			echo '<table class="form-table">';

 			//PLUGIN PREMIUM KEY
			$key = sanitize_text_field(get_option('jwppp-premium-key'));
			if(isset($_POST['jwppp-premium-key'])) {
				$key = sanitize_text_field($_POST['jwppp-premium-key']);
				update_option('jwppp-premium-key', $key);
			}
			echo '<tr>';
			echo '<th scope="row">' . __('Premium Key', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" name="jwppp-premium-key" id="jwppp-premium-key" placeholder="' . __('Add your Premium Key', 'jwppp' ) . '" value="' . $key . '" />';
			echo '<p class="description">' . __('Please, paste here the <strong>Premium Key</strong> that you received buying this plugin.<br>You\'ll be able to keep upgraded with the new versions of JW Player for Wordpress - Premium.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			//JW PLAYER LIBRARY URL
			$library = sanitize_text_field(get_option('jwppp-library'));
 			if(isset($_POST['jwppp-library'])) {
 				$library = sanitize_text_field($_POST['jwppp-library']);
 				update_option('jwppp-library', $library);
 			}

 			//JUST A LITTLE OF STYLE
 			echo '<style>';
 			echo '.question-mark {position:relative; float:right; top:2px; right:3rem;}';
 			echo '</style>';

 			echo '<tr>';
 			echo '<th scope="row">' . __('Player library URL', 'jwppp');
 			echo '<a href="https://www.ilghera.com/documentation/setup-the-player/" title="More informations" target="_blank"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
 			echo '<td>';
 			echo '<input type="text" class="regular-text" id="jwppp-library" name="jwppp-library" placeholder="https://content.jwplatform.com/libraries/jREFGDT.js" value="' . $library . '" />';
 			echo '<p class="description">You can use a cloud or a self hosted library.</p>';
 			echo '</td>';
 			echo '</tr>';

 			//JW PLAYER LICENCE KEY
 			$licence = sanitize_text_field(get_option('jwppp-licence'));
 			if(isset($_POST['jwppp-licence'])) {
 				$licence = sanitize_text_field($_POST['jwppp-licence']);
 				update_option('jwppp-licence', $licence);
 			}
 			echo '<tr>';
 			echo '<th scope="row">' . __('JWP Licence Key', 'jwppp');
 			echo '<a href="https://www.ilghera.com/support/topic/jw-player-self-hosted-setup/" title="More informations" target="_blank"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
 			echo '<td>';
 			echo '<input type="text" class="regular-text" id="jwppp-licence" name="jwppp-licence" placeholder="Only for self-hosted players" value="' . $licence . '" />';
 			echo '<p class="description">' . __('Self hosted player? Please, add your JW Player license key.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//POST TYPES WITH WHICH USE THE PLUGIN
 			$jwppp_get_types = get_post_types(array('public' => true));
 			$exclude = array('attachment', 'nav_menu_item');

 			echo '<tr>';
 			echo '<th scope="row">' . __('Post types', 'jwppp') . '</th>';
 			echo '<td>';

 			foreach($jwppp_get_types as $type) {
 				if(!in_array($type, $exclude)) {

 					$var_type = get_option('jwppp-type-' . $type);
 					if(isset($_POST['done'])) {
 						$var_type = isset($_POST[$type]) ? $_POST[$type] : 0;
 						update_option('jwppp-type-' . $type, $var_type);
 					}
	 				echo '<input type="checkbox" name="' . $type . '" id="' . $type . '" value="1"';
	 				echo ($var_type == 1) ? 'checked="checked"' : '';
	 				echo ' /><span class="jwppp-type">' . ucfirst($type) . '</span><br>';
 				}
 			}
 			echo '<p class="description">' . __('Select the type of content to display videos.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';


 			//BEFORE OR AFTER THE CONTENT
 			$position = get_option('jwppp-position');
 			if(isset($_POST['position'])) {
 				$position = $_POST['position'];
 				update_option('jwppp-position', $_POST['position']);
 			}
 			echo '<th scope="row">' . __('Video Player position', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<select id="position" name="position" />';
 			echo '<option id="before-content"  name="before-content" value="before-content"';
 			echo ($position == 'before-content') ? ' selected="selected"' : '';
 			echo ' />' . __('Before the content', 'jwppp');
 			echo '<option id="after-content"  name="after-content" value="after-content"';
 			echo ($position == 'after-content') ? ' selected="selected"' : '';
 			echo ' />' . __('After the content', 'jwppp');
 			echo '<option id="custom"  name="custom" value="custom"';
 			echo ($position == 'custom') ? ' selected="selected"' : '';
 			echo ' />' . __('Custom', 'jwppp');
 			echo '</select>';
 			echo '<p class="description">' . __('Select the location where you want the video player is displayed.', 'jwppp') . '<br>';
 			echo __('For custom position use the shortcode <b>[jwp-video]</b>.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//TEXT
			$jwppp_text = sanitize_text_field(get_option('jwppp-text'));
			if(isset($_POST['jwppp-text'])) {
				$jwppp_text = sanitize_text_field($_POST['jwppp-text']);
				update_option('jwppp-text', $jwppp_text);
			}

 			echo '<tr>';
 			echo '<th scope="row">' . __('Video text', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<textarea cols="40" rows="2" id="jwppp-text" name="jwppp-text" placeholder="' . __('Loading the player...', 'jwppp') . '">' . $jwppp_text . '</textarea>';
 			echo '<p class="description">' . __('Add custom text that appears while the player is loading.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//POSTER IMAGE
 			$poster_image = sanitize_text_field(get_option('jwppp-poster-image'));
 			if(isset($_POST['poster-image'])) {
 				$poster_image = sanitize_text_field($_POST['poster-image']);
 				update_option('jwppp-poster-image', $poster_image);
 			}

 			echo '<tr>';
 			echo '<th scope="row">' . __('Default poster image', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="poster-image" name="poster-image" value="' . $poster_image . '" />';
			echo '<p class="description">' . __('Add the url of a default poster image.', 'jwppp') . '</p>';
			echo '<td>';
 			echo '</tr>';

 			//POST THUMBNAIL AS POSTER IMAGE
 			$thumbnail = sanitize_text_field(get_option('jwppp-post-thumbnail'));
 			if(isset($_POST['done'])) {
 				$thumbnail = isset($_POST['post-thumbnail']) ? $_POST['post-thumbnail'] : 0;
 				update_option('jwppp-post-thumbnail', $thumbnail);
 			}

 			echo '<tr>';
 			echo '<th scope="row">' . __('Post thumbnail', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="checkbox" id="post-thumbnail" name="post-thumbnail" ';
			echo ($thumbnail == 1) ? ' checked="checked"' : '';
			echo 'value="1" />' . __('Use the post thumbnail', 'jwppp');
			echo '<p class="description">' . __('When present, use the post thumbnail as poster image.', 'jwppp') . '</p>';
			echo '<td>';
 			echo '</tr>';

 			//FIXED DIMENSIONS OR RESPONSIVE?
 			$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
 			if(isset($_POST['jwppp-method-dimensions'])) {
 				$jwppp_method_dimensions = sanitize_text_field($_POST['jwppp-method-dimensions']);
 				update_option('jwppp-method-dimensions', $jwppp_method_dimensions);
 			}

 			//PLAYER FIXED WIDTH
 			$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
 			if(isset($_POST['jwppp-player-width'])) {
 				$jwppp_player_width = sanitize_text_field($_POST['jwppp-player-width']);
 				update_option('jwppp-player-width', $jwppp_player_width);
 			}

 			//PLAYER FIXED HEIGHT
 			$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
 			if(isset($_POST['jwppp-player-height'])) {
 				$jwppp_player_height = sanitize_text_field($_POST['jwppp-player-height']);
 				update_option('jwppp-player-height', $jwppp_player_height);
 			}

 			//PLAYER %
 			$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
 			if(isset($_POST['jwppp-responsive-width'])) {
 				$jwppp_responsive_width = sanitize_text_field($_POST['jwppp-responsive-width']);
 				update_option('jwppp-responsive-width', $jwppp_responsive_width);
 			}

 			//PLAYER ASPECT RATIO
 			$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));
 			if(isset($_POST['jwppp-aspectratio'])) {
 				$jwppp_aspectratio = sanitize_text_field($_POST['jwppp-aspectratio']);
 				update_option('jwppp-aspectratio', $jwppp_aspectratio);
 			}

 			//FIXED DIMENSIONS OR RESPONSIVE? 
 			echo '<tr>';
 			echo '<th scope="row">Player dimensions</th>';
 			echo '<td>';
 			echo '<select id="jwppp-method-dimensions" name="jwppp-method-dimensions" />';
 			echo '<option name="fixed" id="fixed" value="fixed" ';
 			echo ($jwppp_method_dimensions == 'fixed') ? 'selected="selected"' : '';
 			echo '>' . __('Fixed', 'jwppp') . '</option>';
 			echo '<option name="responsive" id="responsive" value="responsive"';
 			echo ($jwppp_method_dimensions == 'responsive') ? 'selected="selected"' : '';
 			echo '>' . __('Responsive', 'jwppp') . '</option>';
 			echo '</select>';
 			echo '<p class="description">' . __('Select how define the measures of the player.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//PLAYER FIXED WIDTH & HEIGHT
 			echo '<tr class="more-fixed">';
 			echo '<th scope="row">' . __('Fixed measures', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-width" name="jwppp-player-width" value="';
			echo ($jwppp_player_width != null) ? $jwppp_player_width : '640';
			echo '" />';
			echo ' x ';
			echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-height" name="jwppp-player-height" value="';
			echo ($jwppp_player_height != null) ? $jwppp_player_height : '360';
			echo '" />';
 			echo '<p class="description"></p>';
 			echo '</td>';
 			echo '</tr>';

 			//PLAYER %
			echo '<tr class="more-responsive">';
			echo '<th scope="row">' . __('Player width', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="number" min="10" step="5" class="small-text" id="jwppp-responsive-width" name="jwppp-responsive-width" value="';
			echo ($jwppp_responsive_width != null) ? $jwppp_responsive_width : '100';
			echo '" /> %';
			echo '<p class="description">' . __('Add the player\'s width (eg. 80%)', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			//PLAYER ASPECT RATIO
			echo '<tr class="more-responsive">';
			echo '<th scope="row">' . __('Aspect ratio', 'jwppp') . '</th>';
			echo '<td>';
			echo '<select id="jwppp-aspectratio" name="jwppp-aspectratio" class="small-text" />';
			echo '<option name="16:10" value="16:10"';
			echo ($jwppp_aspectratio == '16:10') ? ' selected="selected"' : '';
			echo '>16:10</option>';
			echo '<option name="16:9" value="16:9"';
			echo ($jwppp_aspectratio == '16:9') ? ' selected="selected"' : '';
			echo '>16:9</option>';
			echo '<option name="4:3" value="4:3"';
			echo ($jwppp_aspectratio == '4:3') ? ' selected="selected"' : '';
			echo '>4:3</option>';
			echo '</select>';
			echo '<p class="description">' . __('Select the aspect ratio of the player', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			//SKIN
 			$jwppp_skin = sanitize_text_field(get_option('jwppp-skin'));
 			if(isset($_POST['jwppp-skin'])) {
 				$jwppp_skin = sanitize_text_field($_POST['jwppp-skin']);
 				update_option('jwppp-skin', $jwppp_skin);
 			}

			echo '<tr>';
			echo '<th scope="row">' . __('Skin', 'jwppp') . '</th>';
			echo '<td>';
			echo '<select id="jwppp-skin" name="jwppp-skin" />';
			echo '<option name="none" value="none" ';
			echo ($jwppp_skin == 'none') ? 'selected="selected"' : '';
			echo '>--</option>';
			echo '<option name="seven" value="seven" ';
			echo ($jwppp_skin == 'seven') ? 'selected="selected"' : '';
			echo '>Seven</option>';
			echo '<option name="six" value="six" ';
			echo ($jwppp_skin == 'six') ? 'selected="selected"' : '';
			echo '>Six</option>';
			echo '<option name="five" value="five" ';
			echo ($jwppp_skin == 'five') ? 'selected="selected"' : '';
			echo '>Five</option>';
			echo '<option name="glow" value="glow" ';
			echo ($jwppp_skin == 'glow') ? 'selected="selected"' : '';
			echo '>Glow</option>';
			echo '<option name="beelden" value="beelden" ';
			echo ($jwppp_skin == 'beelden') ? 'selected="selected"' : '';
			echo '>Beelden</option>';
			echo '<option name="vapor" value="vapor" ';
			echo ($jwppp_skin == 'vapor') ? 'selected="selected"' : '';
			echo '>Vapor</option>';
			echo '<option name="bekle" value="bekle" ';
			echo ($jwppp_skin == 'bekle') ? 'selected="selected"' : '';
			echo '>Bekle</option>';
			echo '<option name="roundster" value="roundster" ';
			echo ($jwppp_skin == 'roundster') ? 'selected="selected"' : '';
			echo '>Roundster</option>';
			echo '<option name="stormtrooper" value="stormtrooper" ';
			echo ($jwppp_skin == 'stormtrooper') ? 'selected="selected"' : '';
			echo '>Stormtrooper</option>';			
			echo '</select>';
			echo '<p class="description">Choose a skin to customize your player.</p>';
			echo '</td>';
			echo '</tr>';

			//LOGO
			$jwppp_logo = sanitize_text_field(get_option('jwppp-logo'));
			if(isset($_POST['jwppp-logo'])) {
				$jwppp_logo = sanitize_text_field($_POST['jwppp-logo']);
				update_option('jwppp-logo', $jwppp_logo);
			}

			//LOGO POSITION
			$jwppp_logo_vertical = sanitize_text_field(get_option('jwppp-logo-vertical'));
 			if(isset($_POST['jwppp-logo-vertical'])) {
 				$jwppp_logo_vertical = sanitize_text_field($_POST['jwppp-logo-vertical']);
 				update_option('jwppp-logo-vertical', $jwppp_logo_vertical);
 			}
			$jwppp_logo_horizontal = sanitize_text_field(get_option('jwppp-logo-horizontal'));
 			if(isset($_POST['jwppp-logo-horizontal'])) {
 				$jwppp_logo_horizontal = sanitize_text_field($_POST['jwppp-logo-horizontal']);
 				update_option('jwppp-logo-horizontal', $jwppp_logo_horizontal);
 			}

			//LOGO LINK
			$jwppp_logo_link = sanitize_text_field(get_option('jwppp-logo-link'));
			if(isset($_POST['jwppp-logo-link'])) {
				$jwppp_logo_link = sanitize_text_field($_POST['jwppp-logo-link']);
				update_option('jwppp-logo-link', $jwppp_logo_link);
			}

			//NEXT UP
			$jwppp_next_up = sanitize_text_field(get_option('jwppp-next-up'));
			if(isset($_POST['jwppp-next-up'])) {
				$jwppp_next_up = sanitize_text_field($_POST['jwppp-next-up']);
				update_option('jwppp-next-up', $jwppp_next_up);
			}


			//PLAYLIST TOOLTIP
			$jwppp_playlist_tooltip = sanitize_text_field(get_option('jwppp-playlist-tooltip'));
			if(isset($_POST['jwppp-playlist-tooltip'])) {
				$jwppp_playlist_tooltip = sanitize_text_field($_POST['jwppp-playlist-tooltip']);
				update_option('jwppp-playlist-tooltip', $jwppp_playlist_tooltip);
			}


			echo '<tr>';
			echo '<th scope="row">' . __('Logo', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-logo" name="jwppp-logo" ';
			echo 'placeholder="' . __('Image url', 'jwppp') . '" value="' . $jwppp_logo . '" />';
			echo '<p class="description">' . __('Add your logo to the player.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Logo Position', 'jwppp') . '</th>';
			echo '<td>';
			echo '<select id="jwppp-logo-vertical" name="jwppp-logo-vertical" />';
			echo '<option id="top" name="top" value="top"';
			echo ($jwppp_logo_vertical == 'top') ? ' selected="selected"' : '';
			echo '>Top</option>';
			echo '<option id="bottom" name="bottom" value="bottom"';
			echo ($jwppp_logo_vertical == 'bottom') ? ' selected="selected"' : '';
			echo '>Bottom</option>';
			echo '</select>';
			echo '<select style="margin-left: 0.5rem;" id="jwppp-logo-horizontal" name="jwppp-logo-horizontal" />';
			echo '<option id="right" name="right" value="right"';
			echo ($jwppp_logo_horizontal == 'right') ? ' selected="selected"' : '';
			echo '>Right</option>';
			echo '<option id="left" name="left" value="left"';
			echo ($jwppp_logo_horizontal == 'left') ? ' selected="selected"' : '';
			echo '>Left</option>';
			echo '</select>';
			echo '<p class="description">' . __('Choose the logo position.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Logo Link', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-logo-link" name="jwppp-logo-link" ';
			echo 'placeholder="' . __('Link url', 'jwppp') . '" value="' . $jwppp_logo_link . '" />';
			echo '<p class="description">' . __('Add a link to the logo.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Next Up', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-next-up" name="jwppp-next-up" ';
			echo 'placeholder="' . __('Next Up', 'jwppp') . '" value="' . $jwppp_next_up . '" />';
			echo '<p class="description">' . __('Add a different text for the "Next Up" prompt', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Playlist tooltip', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-playlist-tooltip" name="jwppp-playlist-tooltip" ';
			echo 'placeholder="' . __('Playlist', 'jwppp') . '" value="' . $jwppp_playlist_tooltip . '" />';
			echo '<p class="description">' . __('Add a different text for the tooltip in Playlist mode', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

 			echo '</table>';

 			echo '<input type="hidden" name="done" value="1" />';
 			echo '<input type="submit" class="button button-primary" value="' . __('Save changes ', 'jwppp') . '" />';
 			echo '</form>';
 		?>
 	</div>
	<!-- END - SETTINGS -->


	<!-- START - RELATED VIDEOS -->
	<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">

		<?php //GET INFO FROM DATABASE

		//SHOW RELATED?
		$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
		if( isset($_POST['set']) )  {
			$jwppp_show_related = isset($_POST['jwppp-show-related']) ? $_POST['jwppp-show-related'] : 0;
			update_option('jwppp-show-related', $jwppp_show_related);
		}

		//HEADING
		$jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
		if(isset($_POST['jwppp-related-heading'])) {
			$jwppp_related_heading = sanitize_text_field($_POST['jwppp-related-heading']);
			update_option('jwppp-related-heading', $jwppp_related_heading);
		}

		//THUMBNAIL
		$set = sanitize_text_field(get_option('jwppp-image'));
		$field_set = sanitize_text_field(get_option('jwppp-field'));

		if( isset($_POST['thumbnail']) ) {
			$set = sanitize_text_field($_POST['thumbnail']);
			update_option('jwppp-image', $set);
			if($set == 'custom-field') {
				$field_set = $_POST['field'];
				update_option('jwppp-field', $field_set );
			}
		}

		//TAXONOMY SELECT
		$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
		if(isset($_POST['jwppp-taxonomy-select'])) {
			$jwppp_taxonomy_select = sanitize_text_field($_POST['jwppp-taxonomy-select']);
			update_option('jwppp-taxonomy-select', $jwppp_taxonomy_select);

			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

		

		//FORM POST-IMAGE
		echo '<form id="post-image" name="post-image" method="post" action="">';
		echo '<table class="form-table">';

		//SHOW RELATED?
		echo '<tr>';
		echo '<th scope="row">' . __('Active Related Videos option', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="checkbox" id="jwppp-show-related" name="jwppp-show-related" value="1"';
		echo ($jwppp_show_related == 1) ? ' checked="checked"' : '';
		echo '/>';
		echo '<p class="description">' . __('Show Related Videos overlay as default option.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		//HEADING
		echo '<tr class="related-options">';
		echo '<th scope="row">' . __('Next Up tooltip', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" ';
		echo 'placeholder="' . __('Related Videos', 'jwppp') . '" value="' . $jwppp_related_heading . '" />';
		echo '<p class="description">' . __('Title of the Next Up tooltip in Related mode, default is <strong>Related</strong>.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		//THUMBNAIL
		echo '<tr class="related-options">';
		echo '<th scope="row">' . __('Related image', 'jwppp') . '</th>';
		echo '<td>';
		echo '<select id="thumbnail" name="thumbnail"/>';

		echo '<option id="featured-image" value="featured-image"';
		echo ($set == 'featured-image') ? 'selected="selected">' : '>';
		echo __('Featured image', 'jwppp') . '</option>';

		echo '<option id="custom-field" value="custom-field"';
		echo ($set == 'custom-field') ? 'selected="selected">' : '>';
		echo __('Custom field', 'jwppp') . '</option>';

		echo '</select>';
		echo '<p class="description">' . __('Select how get images for related contents.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr class="related-options cf-row">';
		echo '<th scope="row">' . __('Custom field name', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="text" class="regular-text" ';
		echo 'id="field" name="field" placeholder="' . __('Custom field name', 'jwppp') . '" value="' . $field_set . '" />';
		echo '<p class="description">' . __('Add the name of the custom field you want to use.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		//TAXONOMY SELECT
		echo '<tr class="related-options">';
		echo '<th scope="row">Related taxonomy</th>';
		echo '<td>';
		echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" />';
		echo '<option name="null" value=""';
		echo ($jwppp_taxonomy_select == null) ? ' selected="selected"' : '';
		echo '>--</option>';

		$args = array('public' => true, 'hierarchical' => true);
		$taxes = get_taxonomies($args, 'objects');
		foreach($taxes as $taxonomy) {
			if($taxonomy->name != 'video-categories') {
				echo '<option id="' . $taxonomy->name . '" name="' . $taxonomy->name . '" value="' . $taxonomy->name . '"';
				echo ($jwppp_taxonomy_select == $taxonomy->name) ? ' selected="selected"' : '';
				echo '>' . $taxonomy->labels->name . '</option>';
			}
		}

		$video_cat = __('Video categories', 'jwppp');
		echo '<option id="video-categories" name="video-categories" value="video-categories"';
		echo ($jwppp_taxonomy_select == 'video-categories') ? ' selected="selected"' : '';
		echo '>' . $video_cat . '</option>';

		echo '</select>';
		echo '<p class="description">' . __('Use a taxonomy to get more specific related videos. It will be add to all post types you choosed.', 'jwppp') . '<br>';
		echo __('You can even use <strong>Video categories</strong> provided by this plugin.', 'jwppp') . '</p>';
		echo '</td>';

		echo '</table>';
		echo '<input type="hidden" name="set" value="1" />';
		echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '">';

		echo '</form>'; ?>

	</div>
	<!-- END - RELATED VIDEOS -->


	<!-- START SUBTITLES -->
	<div name="jwppp-subtitles" id="jwppp-subtitles" class="jwppp-admin" style="display: none;">
		<?php
			//COLOR
			$sub_color = sanitize_text_field(get_option('jwppp-subtitles-color'));
			if(isset($_POST['jwppp-subtitles-color'])) {
				if(jwppp_check_color($_POST['jwppp-subtitles-color'])) {
					$sub_color = sanitize_text_field($_POST['jwppp-subtitles-color']);
					update_option('jwppp-subtitles-color', $sub_color);										
				} else {
				    add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', 'Please, insert a valid color.', 'error' );
				}
			} 
			//FONT-SIZE
			$sub_font_size = sanitize_text_field(get_option('jwppp-subtitles-font-size'));
			if(isset($_POST['jwppp-subtitles-font-size'])) {
				$sub_font_size = sanitize_text_field($_POST['jwppp-subtitles-font-size']);
				update_option('jwppp-subtitles-font-size', $sub_font_size);
			}
			//FONT-FAMILY
			$sub_font_family = sanitize_text_field(get_option('jwppp-subtitles-font-family'));
			if(isset($_POST['jwppp-subtitles-font-family'])) {
				$sub_font_family = sanitize_text_field($_POST['jwppp-subtitles-font-family']);
				update_option('jwppp-subtitles-font-family', $sub_font_family);
			}
			//OPACITY
			$sub_opacity = sanitize_text_field(get_option('jwppp-subtitles-opacity'));
			if(isset($_POST['jwppp-subtitles-opacity'])) {
				$sub_opacity = sanitize_text_field($_POST['jwppp-subtitles-opacity']);
				update_option('jwppp-subtitles-opacity', $sub_opacity);
			}
			//BACK-COLOR
			$sub_back_color = sanitize_text_field(get_option('jwppp-subtitles-back-color'));
			if(isset($_POST['jwppp-subtitles-back-color'])) {
				if(jwppp_check_color($_POST['jwppp-subtitles-back-color'])) {
					$sub_back_color = sanitize_text_field($_POST['jwppp-subtitles-back-color']);
					update_option('jwppp-subtitles-back-color', $sub_back_color);										
				} else {
				    add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', 'Please, insert a valid background color.', 'error' );
				}
			} 
			//BACK-OPACITY
			$sub_back_opacity = sanitize_text_field(get_option('jwppp-subtitles-back-opacity'));
			if(isset($_POST['jwppp-subtitles-back-opacity'])) {
				$sub_back_opacity = sanitize_text_field($_POST['jwppp-subtitles-back-opacity']);
				update_option('jwppp-subtitles-back-opacity', $sub_back_opacity);
			}

		    settings_errors();


			echo '<form id="jwppp-subtitles" name="jwppp-subtitles" method="post" action="">';
			echo '<table class="form-table">';
			echo '<tr>';
			echo '<th scope="row">' . __('Text color', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-color" value="' . $sub_color . '">';
			echo '<p class="description">' . __('Choose the text-color for your subtitles.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Font size', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="number" class="jwppp-subtitles-font-size" min="8" max="30" step="1" name="jwppp-subtitles-font-size" value="' . $sub_font_size . '">';
			echo '<p class="description">' . __('Choose the font-size for your subtitles.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Font family', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="jwppp-subtitles-font-family" name="jwppp-subtitles-font-family" value="' . $sub_font_family . '">';
			echo '<p class="description">' . __('Choose the font-family for your subtitles.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Font opacity', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="number" class="jwppp-subtitles-opacity" min="0" max="100" step="10" name="jwppp-subtitles-opacity" value="' . $sub_opacity . '">';
			echo '<p class="description">' . __('Add opacity to your subtitles.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Background color', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-back-color" value="' . $sub_back_color . '">';
			echo '<p class="description">' . __('Choose the background-color for your subtitles.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Background opacity', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="number" class="jwppp-subtitles-back-opacity" min="0" max="100" step="10" name="jwppp-subtitles-back-opacity" value="' . $sub_back_opacity . '">';
			echo '<p class="description">' . __('Add opacity to your subtitles background.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '</table>';
			echo '<input type="hidden" name="set" value="1" />';
			echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '">';
			echo '</form>';
		?>
	</div>
	<!-- END - SUBTITLES -->


	<!-- START -  SHARING -->
	<div name="jwppp-social" id="jwppp-social" class="jwppp-admin" style="display: none;">

		<?php 
			//ACTIVE SHARE?
			$active_share = sanitize_text_field(get_option('jwppp-active-share'));
			if(isset($_POST['share-sent'])) {
				$active_share = isset($_POST['jwppp-active-share']) ? $_POST['jwppp-active-share'] : 0;
				update_option('jwppp-active-share', $active_share);
			}
			//HEADING
			$share_heading = get_option('jwppp-share-heading');
			if(isset($_POST['share-heading'])) {
				$share_heading = sanitize_text_field($_POST['share-heading']);
				update_option('jwppp-share-heading', $share_heading);
			} 
			//EMBED?
			$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
			if(isset($_POST['share-sent'])) {
				$jwppp_embed_video = isset($_POST['jwppp-embed-video']) ? $_POST['jwppp-embed-video'] : 0;
				update_option('jwppp-embed-video', $jwppp_embed_video);
			}
	
			echo '<form id="jwppp-sharing" name="jwppp-sharing" method="post" action="">';
			echo '<table class="form-table">';

			//ACTIVE SHARE?
			echo '<tr>';
			echo '<th scope="row">' . __('Active Share option', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="checkbox" id="jwppp-active-share" name="jwppp-active-share" value="1"';
			echo ($active_share == 1) ? ' checked="checked"' : '' ;
			echo ' />';
			echo '<p class="description">' . __('Active <strong>share video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			//HEADING
			echo '<tr class="share-options">';
			echo '<th scope="row">' . __('Heading', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="share-heading" name="share-heading" placeholder="' . __('Share Video', 'jwppp') . '" value="' . $share_heading . '" />';
			echo '<p class="description">' . __('Add a custom heading, default is <strong>Share Video</strong>', 'jwppp') . '</p>';
			echo '</td>';	
			echo '</tr>';

			//EMBED?
			echo '<tr class="share-options">';
			echo '<th scope="row">' . __('Active embed option', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="checkbox" id="jwppp-embed-video" name="jwppp-embed-video" value="1"';
			echo ($jwppp_embed_video == 1) ? ' checked="checked"' : '';
			echo ' />';
			echo '<p class="description">' . __('Active <strong>embed video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '</table>';

			echo '<input type="hidden" name="share-sent" value="1" />';
			echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save options', 'jwppp') . '" />';
			echo '</form>';


		?>
 	</div>
	<!-- END - SOCIAL SHARING -->

	<!-- START ADS -->
	<div name="jwppp-ads" id="jwppp-ads" class="jwppp-admin" style="display: none;">
		<?php

			//ACTIVE ADS?
			$active_ads = sanitize_text_field(get_option('jwppp-active-ads'));
			if( isset($_POST['ads-sent']) ) {
				$active_ads = isset($_POST['jwppp-active-ads']) ? $_POST['jwppp-active-ads'] : 0;
				update_option('jwppp-active-ads', $active_ads);
			}

			//ADS CLIENT
			$ads_client = sanitize_text_field(get_option('jwppp-ads-client'));
			if(isset($_POST['jwppp-ads-client'])) {
				$ads_client = sanitize_text_field($_POST['jwppp-ads-client']);
				update_option('jwppp-ads-client', $ads_client);
			}

			//ADS TAG
			$ads_tag = sanitize_text_field(get_option('jwppp-ads-tag'));
			if(isset($_POST['jwppp-ads-tag'])) {
				$ads_tag = sanitize_text_field($_POST['jwppp-ads-tag']);
				update_option('jwppp-ads-tag', $ads_tag);
			}

			//SKIPOFFSET
			$ads_skip = sanitize_text_field(get_option('jwppp-ads-skip'));
			if(isset($_POST['jwppp-ads-skip'])) {
				$ads_skip = sanitize_text_field($_POST['jwppp-ads-skip']);
				update_option('jwppp-ads-skip', $ads_skip);
			}

			echo '<form id="jwppp-ads" name="jwppp-ads" method="post" action="">';
			echo '<table class="form-table">';

			//ACTIVE ADS?
			echo '<tr>';
			echo '<th scope="row">' . __('Active Video Ads', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="checkbox" id="jwppp-active-ads" name="jwppp-active-ads" value="1"';
			echo ($active_ads == 1) ? ' checked="checked"' : '';
			echo ' />';
			echo '<p class="description">' . __('Add a <strong>Basic Preroll Video Ads</strong>', 'jwppp') . '</p>';
			echo '<p class="description">' . __('A valid license for the Advertising edition of JW Player is required. The Free, Premium, and Enterprise editions do not support this function.', 'jwppp') . '</p>';
			echo '<td>';
			echo '</tr>';

			//ADS CLIENT
			echo '<tr class="ads-options">';
			echo '<th scope="row">' . __('Ads Client') . '</th>';
			echo '<td>';
			echo '<select id="jwppp-ads-client" name="jwppp-ads-client" />';
			echo '<option name="googima" value="googima"';
			echo ($ads_client == 'googima') ? ' selected="selected"' : '';
			echo '>Googima</option>';
			echo '<option name="vast" value="vast"';
			echo ($ads_client == 'vast') ? ' selected="selected"' : '';
			echo '>Vast</option>';
			echo '</select>';
			echo '<p class="description">' . __('Select your ADS Client. More info <a href="http://support.jwplayer.com/customer/portal/articles/1431638-ad-formats-reference" target="_blank">here</a>') . '</p>';
			echo '</td>';
			echo '</tr>';

			//ADS TAG
			echo '<tr class="ads-options">';
			echo '<th scope="row">' . __('Ads Tag', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-ads-tag" name="jwppp-ads-tag" placeholder="' . __('Add the url of your XML file.', 'jwppp') . '" value="' . $ads_tag . '" />';
			echo '<p class="description">' . __('Please, set this to the URL of the ad tag that contains the pre-roll ad.', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			//SKIPOFFSET
			echo '<tr class="ads-options">';
			echo '<th scope="row">' . __('Ad Skipping', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="number" min="0" step="1" class="small-text" id="jwppp-ads-skip" name="jwppp-ads-skip" value="' . $ads_skip . '" />';
			echo '<p class="description">' . __('Please, set an amount of time (seconds) that you want your viewers to watch an ad before being allowed to skip it', 'jwppp') . '</p>';
			echo '</td>';
			echo '</tr>';

			echo '</table>';

			echo '<input type="hidden" name="ads-sent" value="1" />';
			echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save options', 'jwppp') . '" />';
			echo '</form>';
		?>
	</div>
	<!-- END ADS -->

	</div><!-- WRAP LEFT -->
	<div class="wrap-right" style="float:left; width:30%; text-align:center; padding-top:3rem;">
		<iframe width="300" height="800" scrolling="no" src="http://www.ilghera.com/images/jwppp-premium-iframe.html"></iframe>
	</div>
	<div class="clear"></div>

</div>

<?php

}

//JWPPP FOOTER TEXT
function jwppp_footer_text($text) {
	$screen = get_current_screen();
	if($screen->id == 'toplevel_page_jw-player-for-wp') {
		$text = __('If you like <strong>JW Player for Wordpress - Premium</strong>, please give it a <a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium" target="_blank">★★★★★</a> rating. Thanks in advance! ', 'jwppp');
		echo $text;
	} else {
		echo $text;
	}
}
add_filter('admin_footer_text', 'jwppp_footer_text');


//UPDATE MESSAGE
function jwppp_update_message( $plugin_data, $response) {
	$key = get_option('jwppp-premium-key');

	if(!$key) {

		$message = 'A <b>Premium Key</b> is required for keeping this plugin up to date. Please, add yours in the <a href="' . admin_url() . 'admin.php/?page=jw-player-for-wp">options page</a> or click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	
	} else {
	
		$decoded_key = explode('|', base64_decode($key));
	    $bought_date = date( 'd-m-Y', strtotime($decoded_key[1]));
	    $limit = strtotime($bought_date . ' + 365 day');
	    $now = strtotime('today');

	    if($limit < $now) { 
	        $message = 'It seems like your <strong>Premium Key</strong> is expired. Please, click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	    } elseif($decoded_key[2] != 241) {
	    	$message = 'It seems like your <strong>Premium Key</strong> is not valid. Please, click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	    }

	}
	echo ($message) ? '<br><span class="jwppp-alert">' . $message . '</span>' : '';

}
add_action('in_plugin_update_message-jw-player-7-for-wp-premium/jw-player-7-for-wp-premium.php', 'jwppp_update_message', 10, 2);