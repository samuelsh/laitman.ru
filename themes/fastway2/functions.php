<?php
load_theme_textdomain('fastway2');
if ( function_exists('register_sidebar') )
	register_sidebars(2);
remove_filter('the_title', 'wptexturize');
remove_filter('the_content', 'wptexturize');
remove_filter('comment_text', 'wptexturize');

# Disable autosave
if ( ! function_exists('mytheme_comment') ) {
    function disable_autosave() {
	wp_deregister_script('autosave');
    }
    add_action( 'wp_print_scripts', 'disable_autosave' );
}
/*
# Exclude selected categories BB-Dev
function exclude_category($query) {
if ($query->is_home || $query->is_archive) {
$query->set('cat', '-29');
}
return $query;
}
add_filter('pre_get_posts', 'exclude_category');
*/

if ( ! function_exists('mytheme_comment') ) {
function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
	 <?php printf(__('Comment from %s:', 'fastway2'), get_comment_author_link()) ?>
	 <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php echo __('Link', 'fastway2') ?></a>
         <?php edit_comment_link(__('Edit', 'fastway2'),'  ','') ?>
      </div>

      <div class="meta"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></div>

      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <div class="clear"/>
     </div>
<?php
  }
}

if ( ! function_exists('list_super_duper') ) {
    function list_super_duper() {
	$super = get_option("LB_SUPER");
	$duper = get_option("LB_DUPER");

	$exclude_s = produce_list($super, 'main_theme');
	$exclude_d = produce_list($duper, 'submain_theme');
	$exclude_a = array_merge($exclude_s, $exclude_d);
	sort($exclude_a);
	echo "<div class='regular_themes'><ul>\n";
	wp_list_categories('exclude=' . join(',', $exclude_a).',29' .'&hierarchical=0' . '&title_li=<span style="display: none"> </span>');
	echo "</ul></div>\n";
    }
}

if ( ! function_exists('produce_list') ) {
    function produce_list($items, $klass) {
	$exclude_a = Array();
	$items_a = explode(',', $items);

	echo "<ul class='$klass'>";
	foreach ($items_a as $item) {
	   $item = trim($item);
	   $type = substr($item, 0, 1);
	   $id = substr($item, 1);
	   if ($type == "C") {
              $exclude_a[] = $id;
	      $the_cat = get_the_category_by_ID($id);
	      $the_link = get_category_link($id);
	      //BB Dev <--
	      $op = get_option('print_options');
	      if((current_user_can('publish_posts')) && $op['cats']) { 
		  echo  '<li><a href="'.$the_link.'">'.$the_cat.'</a><a href="'.$the_link.'?cat='.$id.'&amp;print=1" rel="nofollow" title="Export category '.$the_cat.' as printable">(<img src="'.get_option('siteurl').'/wp-content/plugins/wp-print/images/print.gif" style="border:none" width="10px" height="10px"/>)</a></li>';
	      }
	      else {
		  echo '<li><a href="'.$the_link.'">'.$the_cat.'</a></li>';
	      }
	      // BB Dev -->
	    } elseif ($type == "P") {
	      $the_page = get_page($id);
	      $the_link = get_page_uri($id);
	      echo "<li><a href='";
	      bloginfo('url');
	      echo "/$the_link'>" . $the_page->post_title . "</a></li>";
	   }
	}
	echo "</ul>";

	return $exclude_a;
    }
}

/*
 *
 *  Adds a filter to append the default stylesheet to the tinymce editor.
 *
 */
if ( ! function_exists('tdav_css') ) {
	function tdav_css($wp) {
		$wp .= ',' . get_bloginfo('stylesheet_url');
	return $wp;
	}
}
//add_filter( 'mce_css', 'tdav_css' );

/*
 *
 *  Adds a shortcode to create a Show more link for long posts
 *
 */
if (!function_exists('more_link_tag'))   {
    add_shortcode("more_link", "more_link_tag");

    function more_link_tag(){
        return('&nbsp;<a class="a-link-toggle" style="display: none;"></a>');                            
    }
}


// Adds nofollow to blogroll links 
if (!function_exists('EB_nofollow_blogroll'))   {
    function EB_nofollow_blogroll( $html ) {
        // remove existing rel attributes
        $html = preg_replace( '/\s?rel=".*"/', '', $html );
        // add rel="nofollow" to all links
        $html = str_replace( '<a ', '<a rel="nofollow" ', $html );
        return $html;
    }
    add_filter( 'wp_list_bookmarks', 'EB_nofollow_blogroll' );
}


// DEV -- ovrrides default WP search by relevance to search by date
if (!function_exists('my_search_query'))   {
    function my_search_query( $query ) {
	// not an admin page and is the main query
	if ( !is_admin() && $query->is_main_query() ) {
		if ( is_search() ) {
			$query->set( 'orderby', 'date' );
		}
	}
    }   
} 

add_action( 'pre_get_posts', 'my_search_query' );

if (!function_exists('excludeCat'))   {
    function excludeCat($query) {
        if ( $query->is_home ) {
           $query->set('cat', '-224');
        }
        return $query;
    }
    add_filter('pre_get_posts', 'excludeCat');
}

/*
function add_meta_to_posts(){

 if(is_admin()) {
  global $post;

  $radio_posts = new WP_Query(array('nopaging' => true,'cat' => 173));
   while ( $radio_posts->have_posts() ) : $radio_posts->the_post();
    $post_title = get_the_title();
    preg_match_all('/[0-9]|\./', $post_title, $post_title_splitted);
    $post_date = implode($post_title_splitted[0]);
    $post_date = date("y/m/d", strtotime($post_date));
    update_post_meta($post->ID, 'postdate', $post_date);
   endwhile;
 }

}
add_action( 'admin_init', 'add_meta_to_posts', 1 );
*/
?>
