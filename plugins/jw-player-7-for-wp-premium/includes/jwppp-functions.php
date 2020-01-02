<?php
/**
* JW PLAYER FOR WORDPRESS - PREMIUM
*/

require('jwppp-ajax-add-video-callback.php');
require('jwppp-ajax-remove-video-callback.php');

//ADD META BOX
function jwppp_add_meta_box() {

	$jwppp_get_types = get_post_types();
	$exclude = array('attachment', 'nav_menu_item');
	$screens = array();

	foreach($jwppp_get_types as $type) {
		if(sanitize_text_field(get_option('jwppp-type-' . $type) == 1)) {
			array_push($screens, $type);
		}
	}

	foreach($screens as $screen) {
		add_meta_box('jwppp-box', __( 'JW Player for Wordpress - Premium', 'jwppp' ), 'jwppp_meta_box_callback', $screen);
	}
}
add_action('add_meta_boxes', 'jwppp_add_meta_box');


//GET ALL VIDEOS OF A SINGLE POST
function jwppp_get_post_videos($post_id) {
	global $wpdb;
	$query = "
		SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE \"_jwppp-video-url-%\" ORDER BY meta_key
	";
	$videos = $wpdb->get_results($query, ARRAY_A);
	return $videos;
}

//GET VIDEOS IDS STRING
function jwppp_videos_string($post_id) {
	$ids = array();
	$videos = jwppp_get_post_videos($post_id);
	foreach ($videos as $video) {
		$video_id = explode('_jwppp-video-url-', $video['meta_key']);
		$ids[] = $video_id[1];
	}
	$string = implode(',', $ids);
	return $string;
}


//SINGLE VIDEO BOX WITH ALL HIS OPTION
function jwppp_single_video_box($post_id, $number) {
	//DELETE VIDEO IF URL==1, IT MEANS EMPTY
	if(get_post_meta( $post_id, '_jwppp-video-url-' . $number, true ) == 1) {
		delete_post_meta($post_id, '_jwppp-video-url-' . $number);
		return;
	}

	//HOW TO ADD THE PLAYLIST
	$plist_hide = true;
	if($number == 1 && get_option('jwppp-position') == 'custom' && count(jwppp_get_post_videos($post_id)) > 1) {
		$plist_hide = false;
	}

	if($number == 1) {
		$output  = '<div class="playlist-how-to" style="position:relative;background:#2FBFB0;color:#fff;padding:0.5rem 1rem;';
		$output .= ($plist_hide) ? 'display:none;">' : 'display:block">';
		$output .= 'Add a playlist of your videos using this code: <code style="display:inline-block;color:#fff;background:none;">[jwp-video n="' . jwppp_videos_string($post_id) . '"]</code>';
		if(get_option('jwppp-position') != 'custom') {
			$output .= '<a class="attention-mark" title="' . __('You need to set the VIDEO PLAYER POSITION option to CUSTOM in order to use this shortcode.', 'jwppp') . '"><img class="attention-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/attention-mark.png" /></a></th>';			
		}
		$output .= '</div>';
	}

	require('jwppp-single-video-box.php');

}


//OUTPUT THE JWPPP META BOX WITH ALL VIDEOS
function jwppp_meta_box_callback($post) {
	
	//JUST A LITTLE OF STYLE
	echo '<style>';
	echo 'a.question-mark {position:relative; left:1rem;}';
	echo 'a.attention-mark {position:absolute; right:1rem;}';
	echo 'img.question-mark, img.attention-mark {position:relative; top:0.2rem;}';
	echo '</style>';

	$jwppp_videos = jwppp_get_post_videos($post->ID);
	if($jwppp_videos != null) {
		foreach($jwppp_videos as $jwppp_video) {
			$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
			jwppp_single_video_box($post->ID, $jwppp_number[1]);
		}
	} else {
		jwppp_single_video_box($post->ID, 1);
	}
}


//AJAX - ADD VIDEO
function jwppp_ajax_add_video() { 
	if(get_the_ID()) { 
	?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$('.jwppp-add').on('click', function() {
				var number = parseInt($('.order:visible').last().html())+1;
				var data = {
					'action': 'jwppp_ajax_add',
					'number': number,
					'post_id': <?php echo get_the_ID(); ?>
				};

				$.post(ajaxurl, data, function(response) {
					$('#jwppp-box .inside').append(response);

					$('.jwppp-remove').bind('click', function() {
						var data = {
							'action': 'jwppp_ajax_remove',
							'number': $(this).attr('data-numb'),
							'post_id': <?php echo get_the_ID(); ?>
						};

						$.post(ajaxurl, data, function(response) {
							var element = '.jwppp-' + response;
							$(element).hide();

							//CHANGE PLAYLIST-HOW-TO
							var tot = $('.jwppp-input-wrap:visible').length;
							if(tot==1) {
								$('.playlist-how-to').hide('slow');			
							} else {
								var string = [];
								$('.order:visible').each(function(i, el) {
									string.push($(el).html());	
								})
								$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
							}

						});

					});

					//CHANGE PLAYLIST-HOW-TO
					$('.playlist-how-to').show('slow');
					var tot = $('.jwppp-input-wrap:visible').length;
					var string = [];
					$('.order:visible').each(function(i, el) {
						string.push($(el).html());	
					})
					$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');

				});
			});
		});
		</script> 
	<?php
	}
}
add_action( 'admin_footer', 'jwppp_ajax_add_video' );
add_action( 'wp_ajax_jwppp_ajax_add', 'jwppp_ajax_add_video_callback' );


//AJAX - REMOVE VIDEO
function jwppp_ajax_remove_video() { 
	if(get_the_ID()) { 
	?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$('.jwppp-remove').bind('click', function() {
				var data = {
					'action': 'jwppp_ajax_remove',
					'number': $(this).attr('data-numb'),
					'post_id': <?php echo get_the_ID(); ?>
				};

				$.post(ajaxurl, data, function(response) {
					var element = '.jwppp-' + response;
					$(element).hide();
				
				//CHANGE PLAYLIST-HOW-TO
				var tot = $('.jwppp-input-wrap:visible').length;
				if(tot==1) {
					$('.playlist-how-to').hide('slow');			
				} else {
					var string = [];
					$('.order:visible').each(function(i, el) {
						string.push($(el).html());	
					})
					$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
				}
				});
			});
		});
		</script> 
	<?php
	}
}
add_action( 'admin_footer', 'jwppp_ajax_remove_video' );
add_action( 'wp_ajax_jwppp_ajax_remove', 'jwppp_ajax_remove_video_callback' );


//SAVE ALL INFORMATIONS OF THE SINGLE VIDEO
function jwppp_save_single_video_data( $post_id ) {

	if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
		return;
	}

	if (isset( $_POST['post_type'] ) && 'page' == $_POST['post_type']) {
		if (!current_user_can( 'edit_page', $post_id )) {
			return;
		}
	} else {
		if (!current_user_can( 'edit_post', $post_id )) {
			return;
		}
	}

	$jwppp_videos = jwppp_get_post_videos($post_id);
	if($jwppp_videos==null) {
		$jwppp_videos = array(array('meta_key' => '_jwppp-video-url-1', 'meta_value' => 1));
	}

	foreach($jwppp_videos as $jwppp_video) {

		$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
		$number = $jwppp_number[1];

		if (!isset( $_POST['jwppp-meta-box-nonce-' . $number] )) {
			return;
		}

		if (!wp_verify_nonce( $_POST['jwppp-meta-box-nonce-' . $number], 'jwppp_save_single_video_data' )) {
			return;
		}

		if (!isset( $_POST['_jwppp-video-url-' . $number] )) {
			return;
		} else {
			$video = sanitize_text_field($_POST['_jwppp-video-url-' . $number]);
			if($video == null) {
				delete_post_meta($post_id, '_jwppp-video-url-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-url-' . $number, $video );
			}
		}

		if (!isset( $_POST['_jwppp-' . $number . '-main-source-label'] )) {
			return;
		} else {
			$label = sanitize_text_field($_POST['_jwppp-' . $number . '-main-source-label']);
			if($label == null) {
				delete_post_meta($post_id, '_jwppp-' . $number . '-main-source-label');
			} else {
				update_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label', $label );
			}
		}

		$sources = sanitize_text_field($_POST['_jwppp-sources-number-' . $number]);
		update_post_meta($post_id, '_jwppp-sources-number-' . $number, $sources);

			for($i=1; $i<$sources+1; $i++) {	
				$source_url = sanitize_text_field($_POST['_jwppp-' . $number . '-source-' . $i . '-url']);
				if($source_url == null) {
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-url');
				} else {
					update_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-url', $source_url);
				}

				$source_label = sanitize_text_field($_POST['_jwppp-' . $number . '-source-' . $i . '-label']);
				if($source_label == null) {
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-label');
				} else {
					update_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-label', $source_label);
				}
			}

		if (!isset( $_POST['_jwppp-video-image-' . $number] )) {
			return;
		} else {
			$image = sanitize_text_field($_POST['_jwppp-video-image-' . $number]);
			if($image == null) {
				delete_post_meta($post_id, '_jwppp-video-image-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-image-' . $number, $image );
			}
		}

		if (!isset( $_POST['_jwppp-video-title-' . $number] )) {
			return;
		} else {
			$title = sanitize_text_field($_POST['_jwppp-video-title-' . $number]);
			if($title == null) {
				delete_post_meta($post_id, '_jwppp-video-title-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-title-' . $number, $title );
			}
		}

		if (!isset( $_POST['_jwppp-video-description-' . $number] )) {
			return;
		} else {
			$description = sanitize_text_field($_POST['_jwppp-video-description-' . $number]);
			if($description == null) {
				delete_post_meta($post_id, '_jwppp-video-description-' . $number);
			} else {;
				update_post_meta( $post_id, '_jwppp-video-description-' . $number, $description );
			}
		}

		if($_POST['activate-media-type-hidden-' . $number] == 1) {
			$jwppp_activate_media_type = isset($_POST['_jwppp-activate-media-type-' . $number]) ? $_POST['_jwppp-activate-media-type-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-activate-media-type-' . $number, $jwppp_activate_media_type );
		}

		if($jwppp_activate_media_type == 1) {
			$media_type = sanitize_text_field($_POST['_jwppp-media-type-' . $number]);
			update_post_meta( $post_id, '_jwppp-media-type-' . $number, $media_type );
		} else {
			delete_post_meta($post_id, '_jwppp-media-type-' . $number);
		}

		if($_POST['autoplay-hidden-' . $number]) {
			$jwppp_autoplay = isset($_POST['_jwppp-autoplay-' . $number]) ? $_POST['_jwppp-autoplay-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-autoplay-' . $number, $jwppp_autoplay );
		}

		if($_POST['mute-hidden-' . $number]) {
			$jwppp_mute = isset($_POST['_jwppp-mute-' . $number]) ? $_POST['_jwppp-mute-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-mute-' . $number, $jwppp_mute );
		}

		if($_POST['repeat-hidden-' . $number]) {
			$jwppp_repeat = isset($_POST['_jwppp-repeat-' . $number]) ? $_POST['_jwppp-repeat-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-repeat-' . $number, $jwppp_repeat );
		}

		if($_POST['single-embed-hidden-' . $number] == 1) {
			$jwppp_single_embed = isset($_POST['_jwppp-single-embed-' . $number]) ? $_POST['_jwppp-single-embed-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-single-embed-' . $number, $jwppp_single_embed );
		}

		if($_POST['download-video-hidden-' . $number] == 1) {
			$jwppp_download_video = isset($_POST['_jwppp-download-video-' . $number]) ? $_POST['_jwppp-download-video-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-download-video-' . $number, $jwppp_download_video );
		}
		
		if($_POST['add-chapters-hidden-' . $number] == 1) {
			$jwppp_add_chapters = isset($_POST['_jwppp-add-chapters-' . $number]) ? $_POST['_jwppp-add-chapters-' . $number] : 0;
			$jwppp_chapters_subtitles = $_POST['_jwppp-chapters-subtitles-' . $number];

			if($jwppp_chapters_subtitles == 'subtitles') {
				$jwppp_subtitles_method = $_POST['_jwppp-subtitles-method-' . $number];
			}

			update_post_meta( $post_id, '_jwppp-add-chapters-' . $number, $jwppp_add_chapters );
			update_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number, $jwppp_chapters_subtitles);
			update_post_meta( $post_id, '_jwppp-subtitles-method-' . $number, $jwppp_subtitles_method);
		}

		if($_POST['subtitles-load-default-hidden-' . $number]) {
			$jwppp_subtitles_load_default = isset($_POST['_jwppp-subtitles-load-default-' . $number]) ? $_POST['_jwppp-subtitles-load-default-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-subtitles-load-default-' . $number, $jwppp_subtitles_load_default );
		}

		if($_POST['subtitles-write-default-hidden-' . $number]) {
			$jwppp_subtitles_write_default = isset($_POST['_jwppp-subtitles-write-default-' . $number]) ? $_POST['_jwppp-subtitles-write-default-' . $number] : 0;
			update_post_meta( $post_id, '_jwppp-subtitles-write-default-' . $number, $jwppp_subtitles_write_default );
		}


		if($jwppp_add_chapters == 1) {

			$chapters = sanitize_text_field($_POST['_jwppp-chapters-number-' . $number]);
			update_post_meta($post_id, '_jwppp-chapters-number-' . $number, $chapters);

			for($i=1; $i<$chapters+1; $i++) {
				
				if($jwppp_chapters_subtitles == 'subtitles' && $jwppp_subtitles_method == 'load') {
					
					//DELETE OLD DIFFERENT ELEMENTS
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
					delete_post_meta($post_id, '_jwppp-subtitles-write-default-' . $number);


					$sub_url = sanitize_text_field($_POST['_jwppp-' . $number . '-subtitle-' . $i . '-url']);
					if($sub_url == null) {
						delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
					} else {
						update_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', $sub_url);
					}

					$sub_label = sanitize_text_field($_POST['_jwppp-' . $number . '-subtitle-' . $i . '-label']);
					if($sub_label == null) {
						delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
					} else {
						update_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', $sub_label);
					}


				} else {

					//DELETE OLD DIFFERENT ELEMENTS
					delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
					delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
					delete_post_meta($post_id, '_jwppp-subtitles-load-default-' . $number);

					$title = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-title']);
					if($title == null) {
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
					} else {
						update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', $title);
					}

					$start = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-start']);
					if($start == null) {
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
					} else {
						update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', $start);
					}

					$end = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-end']);
					if($end == null) {
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
					} else {
						update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', $end);
					}
					
				}
				
			}

		} else {
			$chapters = sanitize_text_field($_POST['_jwppp-chapters-number-' . $number]);
			for($i=1; $i<$chapters+1; $i++) {
				delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
				delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
				delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
				delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
				delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
				delete_post_meta($post_id, '_jwppp-chapters-subtitles-' . $number );
				delete_post_meta($post_id, '_jwppp-subtitles-method-' . $number);
			}
			delete_post_meta($post_id, '_jwppp-chapters-number-' . $number);
		}			
		
	}
}
add_action( 'save_post', 'jwppp_save_single_video_data');


//SCRIPT AND LICENCE KEY FOR JW PLAYER
function jwppp_add_header_code() {
	$library = sanitize_text_field(get_option('jwppp-library'));
	$licence = sanitize_text_field(get_option('jwppp-licence'));
	if($library != null) {
		echo "<script src=\"$library\"></script>\n";
	}
	if($licence != null) {
		echo "<script>jwplayer.key=\"$licence\";</script>\n";
	}

	//ADD STYLE FOR BETTER PREVIEW IMAGE
	echo "<style>.jw-preview { background-size: 100% auto !important;}</style>";
}
add_filter('wp_head', 'jwppp_add_header_code');


//GET VIDEO POST-TYPES
function jwppp_get_video_post_types() {
	$types = get_post_types(array('public' => 'true'));
	$video_types = array();
	foreach($types as $type) {
		if(get_option('jwppp-type-' . $type) == 1) {
			array_push($video_types, $type);
		}
	}
	return $video_types;
}
add_action('init', 'jwppp_get_video_post_types', 0);


//GET ALL VIDEO POSTS
function jwppp_get_video_posts() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_jwppp-video-url' AND meta_value <> ''";
	$posts = $wpdb->get_results($query);
	$video_posts = array();
	foreach($posts as $post) {
		array_push($video_posts, $post->post_id);
	}
	return $video_posts;
}


//CREATE "VIDEO CATEGORIES" TAXONOMY
function jwppp_create_taxonomy() {
	$labels = array(
		'name'              => __( 'Video categories', 'jwppp' ),
		'singular_name'     => __( 'Video category', 'jwppp' ),
		'search_items'      => __( 'Search video categories', 'jwppp' ),
		'all_items'         => __( 'All video categories', 'jwppp' ),
		'parent_item'       => __( 'Parent video category', 'jwppp' ),
		'parent_item_colon' => __( 'Parent video category:', 'jwppp' ),
		'edit_item'         => __( 'Edit video category', 'jwppp' ),
		'update_item'       => __( 'Update video category', 'jwppp' ),
		'add_new_item'      => __( 'Add New video category', 'jwppp' ),
		'new_item_name'     => __( 'New video category Name', 'jwppp' ),
		'menu_name'         => __( 'Video categories', 'jwppp' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'video-categories' ),
	);

	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	if($jwppp_taxonomy_select == 'video-categories') {
		register_taxonomy( 'video-categories', jwppp_get_video_post_types(), $args );
	}
}
add_action( 'init', 'jwppp_create_taxonomy', 1 );


//ADD "VIDEO CATEGORIES" TO ALL CHOOSED POST TYPES
function jwppp_add_taxonomy() {
	$types = jwppp_get_video_post_types();
	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	// if($jwppp_taxonomy_select != 'video-categories') {
		foreach($types as $type) {
			register_taxonomy_for_object_type($jwppp_taxonomy_select, $type);
			add_post_type_support( $type, $jwppp_taxonomy_select );
		}
	// }
}
add_action('admin_init', 'jwppp_add_taxonomy');


//GET THE FEEED FOR RELATED VIDEOS
function jwppp_get_feed_url() {
	$id = get_the_ID();
	$taxonomy = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	$terms = wp_get_post_terms($id, $taxonomy);
	
	// $feed = home_url() . '/';
	// foreach($terms as $term) {
		// $term_link = get_term_link($term->term_id);
		// $feed .= $taxonomy . '/' . $term->slug . '/';
	// }
	// $feed .= '?feed=related-videos';

	if($terms != null) {
		$feed = get_term_link($terms[0]->term_id, $taxonomy); 
		if(get_option('permalink_structure')) {
			$feed .= '/related-videos';
		} else {
			$feed .= '&feed=related-videos';
		}

		return $feed;
	}	
}


//CHECK FOR A YOUTUBE VIDEO
function jwppp_search_yt($jwppp_video_url='', $number='') {
	if($number) {
		$jwppp_video_url = get_post_meta(get_the_ID(), '_jwppp-video-url-' . $number, true);
	}
	$youtube1 	   = 'https://www.youtube.com/watch?v=';
	$youtube2 	   = 'https://youtu.be/';
	$youtube_embed = 'https://www.youtube.com/embed/';
	$is_yt = false;
	//ALL YOUTUBE LINKS
	if(strpos($jwppp_video_url, $youtube1) !== false) {
		$jwppp_embed_url = str_replace($youtube1, $youtube_embed, $jwppp_video_url);
		$yt_parts = explode($youtube1, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif(strpos($jwppp_video_url, $youtube2) !== false) {
		$jwppp_embed_url = str_replace($youtube2, $youtube_embed, $jwppp_video_url);	
		$yt_parts = explode($youtube2, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif(strpos($jwppp_video_url, $youtube_embed) !== false) {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = explode($youtube_embed, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} else {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = '';
		$yt_video_id = '';
		$is_yt = false;
	}

	$yt_video_image = 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg';

	return array('yes' => $is_yt, 'embed-url' => $jwppp_embed_url, 'video-image' => $yt_video_image);

}


//JW PLAYER CODE
function jwppp_video_code($p, $n, $ar, $width, $height, $pl_autostart, $pl_mute, $pl_repeat) {

	//GET THE OPTIONS
	$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
	$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
	$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
	$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
	$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));
	$jwppp_skin = sanitize_text_field(get_option('jwppp-skin'));
	$jwppp_logo = sanitize_text_field(get_option('jwppp-logo'));
	$jwppp_logo_vertical = sanitize_text_field(get_option('jwppp-logo-vertical'));
	$jwppp_logo_horizontal = sanitize_text_field(get_option('jwppp-logo-horizontal'));
	$jwppp_logo_link = sanitize_text_field(get_option('jwppp-logo-link'));
	$active_share = sanitize_text_field(get_option('jwppp-active-share'));	
	$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
	$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
	$jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
	$jwppp_next_up = sanitize_text_field(get_option('jwppp-next-up'));
	$jwppp_playlist_tooltip = sanitize_text_field(get_option('jwppp-playlist-tooltip'));
	$jwppp_show_ads = sanitize_text_field(get_option('jwppp-active-ads'));
	$jwppp_ads_client = sanitize_text_field(get_option('jwppp-ads-client'));
	$jwppp_ads_tag = sanitize_text_field(get_option('jwppp-ads-tag'));
	$jwppp_ads_skip = sanitize_text_field(get_option('jwppp-ads-skip'));

	//NEW SUBTITLES OPTIONS
	$jwppp_sub_color = sanitize_text_field(get_option('jwppp-subtitles-color'));
	$jwppp_sub_font_size = sanitize_text_field(get_option('jwppp-subtitles-font-size'));
	$jwppp_sub_font_family = sanitize_text_field(get_option('jwppp-subtitles-font-family'));
	$jwppp_sub_opacity = sanitize_text_field(get_option('jwppp-subtitles-opacity'));
	$jwppp_sub_back_color = sanitize_text_field(get_option('jwppp-subtitles-back-color'));
	$jwppp_sub_back_opacity = sanitize_text_field(get_option('jwppp-subtitles-back-opacity'));


	//GETTING THE POST/ PAGE ID
	if($p) {
		$p_id = $p;
	} else {
		$p_id = get_the_ID();
	}
	//GETTING THE NUMBER/ S OF VIDEO/ S
	$videos = explode(',', $n);
	$jwppp_new_playlist = ( count($videos)>1 ) ? true : false;

	foreach($videos as $number) {
		$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);
		$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
		$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
		$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
		$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);		
		$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);		
		$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
		$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
		$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
		$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
		if($jwppp_single_embed == null) {
			$jwppp_single_embed = $jwppp_embed_video;
		}
		$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
		$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
		$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
		$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
		$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
		$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
		$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

	}
	
	//CHECK FOR PLAYLIST
	$file_info = pathinfo($jwppp_video_url);
	$jwppp_playlist = false;
	if( array_key_exists('extension', $file_info) ) {
		if( in_array( $file_info['extension'], array('xml', 'feed', 'php', 'rss') ) ) {
			$jwppp_playlist = true;
		}
	}

	$this_video = $p_id . $number;

	$output = "<div id='jwppp-video-box-" . $this_video . "' class='jwppp-video-box' data-video='" . $n . "' style=\"margin: 1rem 0;\">\n";
	$output .= "<div id='jwppp-video-" . $this_video . "'>";
	if(sanitize_text_field(get_option('jwppp-text')) != null) {
		$output .= sanitize_text_field(get_option('jwppp-text'));
	} else {
		$output .= __('Loading the player...', 'jwppp');
	}
	$output .= "</div>\n"; 
	$output .= "</div>\n"; 
	$output .= "<script type='text/javascript'>\n";
		$output .= "var playerInstance_$this_video = jwplayer(\"jwppp-video-$this_video\");\n";
		$output .= "playerInstance_$this_video.setup({\n";
			if($jwppp_playlist) {
			    $output .= "playlist: '" . get_post_meta($p_id, '_jwppp-video-url-' . $number, true) . "',\n"; 
			} else {
				if($jwppp_new_playlist) {
					$n=0;
					$output .= "playlist: [\n";
				}
				foreach($videos as $number) {

					$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);
					// $jwppp_video_mobile_url = get_post_meta($p_id, '_jwppp-video-mobile-url-' . $number, true);
					$jwppp_sources_number = get_post_meta($p_id, '_jwppp-sources-number-' . $number);
					$jwppp_source_1 = get_post_meta($p_id, '_jwppp-' . $number . '-source-1-url', true);
					$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
					$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
					$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
					$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);
					$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);
					$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
					$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
					$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
					$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
					if($jwppp_single_embed == null) {
						$jwppp_single_embed = $jwppp_embed_video;
					}
					$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
					$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
					$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
					$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
					$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
					$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
					$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

					//CHECK FOR A YT VIDEO
					$youtube = jwppp_search_yt($jwppp_video_url);
					$jwppp_embed_url = $youtube['embed-url'];
					$yt_video_image  = $youtube['video-image'];

					if($jwppp_new_playlist) {
						$output .= "{\n"; 
					}

				    //MOBILE SOURCE
					if($jwppp_source_1) {
						$output .= "sources: [\n";
						$output .= "{\n";
					}
				    $output .= "file: '" . $jwppp_video_url . "',\n"; 
				    if($jwppp_sources_number[0] > 1) {
				    	$main_source_label = get_post_meta($p_id, '_jwppp-' . $number . '-main-source-label', true);
			      		$output .= ($main_source_label) ? "label: '" . $main_source_label . "'\n" : '';
					}
					
					if($jwppp_source_1) {
						$output .= "},\n";
					}

				    if($jwppp_source_1) {
						for($i=1; $i<$jwppp_sources_number[0]+1; $i++) {	
							$source_url = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-url', true);
							$source_label = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-label', true);
							if($source_url) {
					      		$output .= "{\n";
					      		$output .= "file: '" . $source_url . "',\n";
					      		$output .= ($source_label) ? "label: '" . $source_label . "'\n" : '';
					      		$output .= "},\n";
							} 
						}
			      	}

			      	if($jwppp_source_1) {
			      		$output .= "],\n";
					}

			      	//VIDEO TITLE
				    if($video_title) {
					    $output .= "title: '" . esc_html($video_title) . "',\n";
					}

					//VIDEO DESCRIPTION
					if($video_description) {
					    $output .= "description: '" . esc_html($video_description) . "',\n";
					}

					//POSTER IMAGE
					if($video_image) {
				    	$output .= "image: '" . $video_image . "',\n";
				    } else if(has_post_thumbnail($p_id) && get_option('jwppp-post-thumbnail') == 1) {
				    	$output .=  "image: '" . get_the_post_thumbnail_url() . "',\n";
				    } else if($youtube['yes']) {
				    	$output .= "image: '" . $yt_video_image . "',\n";
					} else if(get_option('jwppp-poster-image')) {
					    $output .= "image: '" . get_option('jwppp-poster-image') . "',\n";
					}

					if($jwppp_new_playlist) {
						$output .= "mediaid: '" . $this_video . $n++ . "',\n";
					}

					//MEIA-TYPE
					if($jwppp_media_type) {
				    	$output .= "type: '" . $jwppp_media_type . "',\n";
				    }

				    //AUTOPLAY
					if(!$jwppp_new_playlist && $jwppp_autoplay == 1) {
				    	$output .= "autostart: 'true',\n";
				    }

				    //MUTE
				    if(!$jwppp_new_playlist && $jwppp_mute == 1) {
				    	$output .= "mute: 'true',\n";
				    	// if($youtube['yes']) {
				    	// 	$output .= "var down_volume = 'true',\n";
				    	// }
				    }

				    //REPEAT
				    if(!$jwppp_new_playlist && $jwppp_repeat == 1) {
				    	$output .= "repeat: 'true',\n";
				    }

				    //GOOGLE ANALYTICS
				    $output .= "ga: {},\n";
				    


					//SHARING FOR SINGLE VIDEO
					if(!$jwppp_new_playlist && $active_share == 1) {
						$output .= "sharing: {\n";
							$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
							if($jwppp_share_heading != null) {
								$output .= "heading: '" . $jwppp_share_heading . "',\n";
							} else {
								$output .= "heading: '" . __('Share Video', 'jwppp') . "',\n"; 
							}
							$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
							if(($jwppp_embed_video || $jwppp_single_embed == 1) && !$jwppp_playlist) {
								$output .= "code: '<iframe src=\"" . $jwppp_embed_url . "\"  width=\"640\"  height=\"360\"  frameborder=\"0\"  scrolling=\"auto\"></iframe>'\n";
							}
						$output .= "},\n";
					}

					//ADD CHAPTERS
					if($jwppp_add_chapters == 1) {
						$output .= "tracks:[\n";

						if($jwppp_chapters_subtitles == 'subtitles' && $jwppp_subtitles_method == 'load') {
							for($i=1; $i<$jwppp_chapters_number+1; $i++) {
								$output .= "{\n";
							    $output .= "file:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', true) . "',\n";
							    $output .= "kind:'captions',\n";	
							    $output .= "label:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', true) . "',\n";	
							    if($i==1 && $jwppp_subtitles_load_default == 1) {
							    	$output .= "'default': 'true'\n";
							    }
								$output .= "},\n";
							}

						} else {
							$output .= "{\n";
						    $output .= "file:'" . plugins_url('jw-player-7-for-wp-premium')  . "/includes/jwppp-chapters.php?id=" . $p_id . "&number=$number',\n";
						    if($jwppp_chapters_subtitles == 'chapters') {
							    $output .= "kind:'chapters'\n";						    	
						    } else if($jwppp_chapters_subtitles == 'subtitles') {
							    $output .= "kind:'captions',\n";		
							    if($jwppp_subtitles_write_default == 1) {
							    	$output .= "'default': 'true'\n";
							    }				    	
						    } else {
						    	$output .= "kind:'thumbnails'\n";
						    }
							$output .= "}\n";
						}

		      			$output .= "],\n";

		      		}

		      		if($jwppp_new_playlist) {
			      		$output .= "},\n";
			      	}
			    }

			    if($jwppp_new_playlist) {
					$output .= "],\n";
				}
			}

			//SHARING FOR PLAYLIST
			if($jwppp_new_playlist && $active_share == 1) {
				$output .= "sharing: {\n";
					$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
					if($jwppp_share_heading != null) {
						$output .= "heading: '" . $jwppp_share_heading . "',\n";
					} else {
						$output .= "heading: '" . __('Share Video', 'jwppp') . "',\n"; 
					}
					$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
				$output .= "},\n";
			}
		   
			//PLAYER DIMENSIONS
			if($width && $height) {

				    $output .= "width: '" . $width . "',\n";
				    $output .= "height: '" . $height . "',\n";

			} else {
			    
			    if($jwppp_method_dimensions == 'fixed') {
				    $output .= "width: '";
				    $output .= ($jwppp_player_width != null) ? $jwppp_player_width : '640';
				    $output .= "',\n";
				    $output .= "height: '";
				    $output .= ($jwppp_player_height != null) ? $jwppp_player_height : '360';
				    $output .= "',\n";
				    
				} else {
					$output .= "width: '";
					$output .= ($jwppp_responsive_width != null) ? $jwppp_responsive_width . '%' : '100%';
					$output .= "',\n";
					$output .= "aspectratio: '";
					if($ar) {
						$output .= $ar;
					} elseif($jwppp_aspectratio) {
						$output .= $jwppp_aspectratio;
					} else {
						$output .= '16:9';
					}
					$output .= "',\n";
				}

			}

			//SKIN
		    if($jwppp_skin != 'none') {
		    	$output .= "skin: {\n";
		    	$output .= "name: '" . $jwppp_skin . "',\n";
		    	//START - CUSTOM CODE
		    	// $output .= "active: '#B30000',\n";
		    	// $output .= "inactive: '#B30000'\n";
		    	//END - CUSTOM CODE
		    	$output .= "},\n";
		    }

			//LOGO
		    if($jwppp_logo != null) {
		    	$output .= "logo: {\n";
		    	$output .= "file: '" . $jwppp_logo . "',\n";
		    	$output .= "position: '" . $jwppp_logo_vertical . '-' . $jwppp_logo_horizontal . "',\n";
		    	if($jwppp_logo_link != null) {
		    		$output .= "link: '" . $jwppp_logo_link . "'\n";
		    	}
		    	$output .= "},\n";
		    }

		    // SHORTCODE OPTIONS FOR PLAYLISTS
			if($jwppp_new_playlist) {
				//AUTOPLAY
				if($pl_autostart == 1) {
			    	$output .= "autostart: 'true',\n";
			    }

			    //MUTE
			    if($pl_mute == 1) {
			    	$output .= "mute: 'true',\n";
			    }

			    //REPEAT
			    if($pl_repeat == 1) {
			    	$output .= "repeat: 'true',\n";
			    }
			}    
			    
			//ADS
			if($jwppp_show_ads == 1) {
				$output .= "advertising: {\n";
				$output .= "client: '" . $jwppp_ads_client . "',\n";
				$output .= "tag: '" . $jwppp_ads_tag . "',\n";
				if($jwppp_ads_skip != 0) {
					$output .= "skipoffset: " . $jwppp_ads_skip . "\n";
				}
				$output .= "},\n";
			}

			//RELATED VIDEOS
		    if($jwppp_show_related == 1 && jwppp_get_feed_url() != null) {
				$output .= "related: {\n";
				$output .= "file: '" . jwppp_get_feed_url() . "',\n";
				if($jwppp_related_heading != null) {
					$output .= "heading: '" . $jwppp_related_heading . "',\n";
				} else {
					$output .= "heading: '" . __('Related Videos', 'jwppp') . "',\n";
				}
				$output .= "onclick: 'link'\n";				
				$output .= "},\n";
			}

			//SUBTITLES STYLE
      		// if($jwppp_chapters_subtitles == 'subtitles' && jwppp_caption_style()) {
      		if( jwppp_caption_style() ) {
      			$output .= "captions: {\n";
      			$output .= $jwppp_sub_color ? "color: '" . $jwppp_sub_color . "',\n" : "";
      			$output .= $jwppp_sub_font_size ? "fontSize: '" . $jwppp_sub_font_size . "',\n" : "";
      			$output .= $jwppp_sub_font_family ? "fontFamily: '" . $jwppp_sub_font_family . "',\n" : "";
      			$output .= $jwppp_sub_opacity ? "fontOpacity: '" . $jwppp_sub_opacity . "',\n" : "";
      			$output .= $jwppp_sub_back_color ? "backgroundColor: '" . $jwppp_sub_back_color . "',\n" : "";
      			$output .= $jwppp_sub_back_opacity ? "backgroundOpacity: '" . $jwppp_sub_back_opacity . "',\n" : "";
      			$output .= "},\n";
      		}

			//LOCALIZATION
		    $output .= "localization: {\n";
		    	if($jwppp_next_up) {
				    $output .= "nextUp: '" . $jwppp_next_up . "',\n";		    		
		    	}
		    	if($jwppp_playlist_tooltip) {
				    $output .= "playlist: '" . $jwppp_playlist_tooltip . "',\n";		    		
		    	}
			    if($jwppp_related_heading) {
				    $output .= "related: '" . $jwppp_related_heading . "',\n";			    	
			    }
		    $output .= "},\n";

	$output .= "});\n";

	//CHECK FOR A YOUTUBE VIDEO
	$is_yt = jwppp_search_yt('', $number);

	//DOWNLOAD BUTTON
	if($jwppp_download_video && !$jwppp_new_playlist && !$is_yt['yes']) {
		$output .= "playerInstance_$this_video.addButton(\n";
			$output .= "'" . plugins_url('jw-player-7-for-wp-premium')  . "/images/download-icon.png',\n";
			$output .= "'Download Video',\n";
			$output .= "function() {\n";
				$output .= "var file = playerInstance_$this_video.getPlaylistItem()['file'];\n";
				$output .= "var file_download = '" . plugins_url('jw-player-7-for-wp-premium')  . "/includes/jwppp-video-download.php?file=' + file;\n";
				$output .= "window.location.href = file_download;\n";
			$output .= "},\n";
			$output .= "'download'\n";
		$output .=")\n";
	}

	if($is_yt['yes'] || $pl_mute == 1) {
		//VOLUME OFF
		$output .= "playerInstance_$this_video.on('play', function(){\n";
			$output .= "var sound_off = playerInstance_$this_video.getMute();\n";
			$output .= "if(sound_off) {\n";
				$output .= "playerInstance_$this_video.setVolume(0);\n";
			$output .= "}\n";
		$output .= "})\n";
	}

	$output .= "</script>\n";

	if(get_post_meta($p_id, '_jwppp-video-url-' . $number, true)) { return $output; }
}


//CREATE JWPPP-VIDEO SHORTCODE
function jwppp_video_s_code($var) {
	ob_start();
	$video = shortcode_atts( array(
		'p'      => get_the_ID(),
		'n'      => '1',	
		'ar'     => '',
		'width'  => '',
		'height' => '',
		'pl_autostart' => '',
		'pl_mute'     => '',
		'pl_repeat'   => ''
		), $var );
	echo jwppp_video_code(
		$video['p'], 
		$video['n'], 
		$video['ar'], 
		$video['width'], 
		$video['height'],
		$video['pl_autostart'],
		$video['pl_mute'],
		$video['pl_repeat']
	);
	$output = ob_get_clean();
	return $output;
}
add_shortcode('jw7-video', 'jwppp_video_s_code');
add_shortcode('jwp-video', 'jwppp_video_s_code');

//EXECUTE SHORTCODES IN WIDGETS
if(!has_filter('widget_text', 'do_shortcode')) {
	add_filter('widget_text', 'do_shortcode');
} 

//ADD PLAYER TO THE CONTENT
function jwppp_add_player($content) {
	global $post;
	$type = get_post_type($post->ID);
	if(is_singular() && (sanitize_text_field(get_option('jwppp-type-' . $type)) == 1)) {
		$jwppp_videos = jwppp_get_post_videos($post->ID);
		if($jwppp_videos) {
			foreach($jwppp_videos as $jwppp_video) {
				$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
				$number 	  = $jwppp_number[1];
				$post_id 	  = get_the_ID();
				$video   	 .= jwppp_video_code(
									$post_id, 
									$number, 
									$ar='', 
									$width='', 
									$height='', 
									$pl_autostart='', 
									$pl_mute='', 
									$pl_repeat=''
								);
			}

			$position = get_option('jwppp-position');
			if($position == 'after-content') {
				$content = $content . $video;
			} elseif($position == 'before-content') {
				$content = $video . $content;
			}
		}
	} 
	return $content;
}
add_filter('the_content', 'jwppp_add_player');
