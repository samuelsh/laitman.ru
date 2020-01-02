<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
	<title><?php if (is_single() || is_page() || is_archive()) { wp_title('',true); } else { bloginfo('description'); } ?> &#8212; <?php bloginfo('name'); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta http-equiv="Content-Language" content="utf8" /> 
	<meta name="google-site-verification" content="OLvAXOGliUKI0yZIrKRubVdEmEgh6ZyTtOzXFSJ0HGE" /> <!-- Google WebMaster -->
	<meta name="google-site-verification" content="HcEeB5vF_4N8rwLCtQs8EqTV4pjIG8_Faj5RouOOoVY" />
	<!-- BB SEO: Yahoo Explorer / Google diagnostics -->
	<meta name="y_key" content="76c691e1f698c2c0" />
	<meta name="verify-v1" content="CdEXu2d8M3Hhc9fIscKpJzBpVHMU9R2yz9ixR1hG4fc=" />

	<meta property="fb:pages" content="1551528425093736" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel='stylesheet' id='NextGEN-css'  href='https://laitman.ru/wp-content/plugins/nextgen-gallery/css/ngg_shadow2.css?ver=1.0.0' type='text/css' media='screen' />
	
	<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie6.css" media="screen" />
	<script defer type="text/javascript" src="<?php bloginfo('template_url'); ?>/fixpng.js"></script>
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie7.css" media="screen" />
	<![endif]-->
	<?php wp_head(); ?>
	<!--link rel="stylesheet" href="/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" /-->
	<script type="text/javascript">
		if (jQuery('html').width() <= 800) {
			document.write('<' + 'link rel="stylesheet" type="text/css" href="<' + '?php bloginfo(\'template_url\'); ?>/low_res.css" media="screen" />');
		}
	//BB-Dev: appendig my private CSS in order to load as fast as possble	
	jQuery('head').append('<link rel="stylesheet" type="text/css" href="/wp-content/themes/fastway2/mystyles.css" media="screen" >');
	</script>

	<script type="text/javascript">
	function resetVal(id, defval)
	{
		val = document.getElementById(id).value;
		if (val == defval)
			document.getElementById(id).value = "";
	}
	</script>	
	<?php if( current_user_can( 'edit_posts' ) ) { ?>
		<style type="text/css">
			#postdivrich table, #postdivrich #quicktags {border-top: none;}
			#quicktags{border-bottom: none; padding-bottom: 2px; margin-bottom: -1px;}
			#edButtons{border-bottom: 1px solid #ccc;}
		</style>
	<?php } ?>

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_enqueue_script('jquery');
	wp_enqueue_script('swfobject');
	wp_enqueue_script('thickbox',null,array('jquery'));
	wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
	?>

</head>


<body <?php body_class(); ?>>
<!-- Google Tag Manager -->

<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K9TZPR" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

})(window,document,'script','dataLayer','GTM-K9TZPR');</script>

<!-- End Google Tag Manager -->
<div id="header">
	<div id="header-inner">
		<img id="header-photo" src="/wp-content/themes/fastway2/images/LB_rav.png" alt="Михаэль Лайтман" /> 
		<?php if (!is_home()) { ?><div id="header-ref"><?php } ?>
			<div id="header-h4"> <!-- BB Dev: replacing H4 with div -->
			<?php if (!is_home()) { ?><a href="<?php bloginfo('url'); ?>"><?php } ?>
			<?php bloginfo('description'); ?>
			<?php if (!is_home()) { ?></a><?php } ?>
			</div>
			<?php if (is_home()):?>
				<h1>
			      <?php else: ?>
			      	<div id="header-h1"> <!-- BB Dev: replcaing H1 with div on NOT home page -->
			      <?php endif;
			?>	
			<?php if (!is_home()) { ?><a href="<?php bloginfo('url'); ?>"><?php } ?>
			<?php bloginfo('name'); ?>
			<?php if (!is_home()) { ?></a><?php } ?>
			<?php if (is_home()):?>
				</h1>
			      <?php else: ?>
				</div>
			      <?php endif;
			?>	
		<?php if (!is_home()) { ?></div><?php } ?>
		<div id="top-menu">
			<ul id="nav">
				<li class="home-page_item"><a <?php if (is_home()) echo('class="current" '); ?>href="<?php bloginfo('url'); ?>">Главная</a></li>

		<?php
				$head_page_names = array( 'blog-map', 'fotoalbom', 'my-blogs', 'useful-links', 'kabbalah-in-internet',
					'ask-kabbalist', 'author');
				foreach ($head_page_names as $page_name)
				{
					$page = get_page_by_path($page_name);
					if ($page == NULL || $page->post_status != 'publish')
						continue;
					$page_id = $page->ID;
					$page_title = $page->post_title;
					$page_ref = get_bloginfo('url')."/$page_name";
					echo "<li>|</li>";
					echo "<li class=\"page_item page-item-$page_id\">";
					echo "<a title=\"$page_title\" href=\"$page_ref\">$page_title</a></li>";
				}
				echo "<li>|</li>";
				$rss_page = get_page_by_path('rss_page');
				if ($rss_page == NULL)
				{	
		?>	
				<li class="sub"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>" class="feed">RSS</a></li>
		<?php
				} else {
					$rss_href = get_bloginfo('url')."/rss_page";
		?>
			<li class="sub"><a href="<?php echo $rss_href; ?>" title="<?php _e('Syndicate this site using RSS'); ?>" class="feed">RSS</a></li>
		<?php
				}
		?>
			</ul>
		</div>

		<div class="to_write">
		<?php if( current_user_can( 'edit_posts' ) ) { ?>
			<a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php" title="Написать"><img style="border:0;" src="/wp-content/themes/fastway2/images/pen_icon.gif" alt="Написать"><br/>Написать</a>
		<?php }
			else {	// BB Dev: Graphical button for logon/logoff
				echo '<a href="'.wp_login_url().'" title="Войти"><img style="border:0;" src="/wp-content/themes/fastway2/images/LB_button-off.png" alt="Войти"><br/>Войти</a>';
			} ?>
		<br/>
		<!--form name="switch_form" method="POST">
		<a href='#' onclick="switch_form.submit(); return false">Switch to <?php global $novice_mode; echo $novice_mode ? 'expert' : 'novice' ?> mode</a>
			<input type="hidden" name='switch' value="switch" />
		</form-->
		</div>
		<div class="clear"></div>
	</div>
</div>

<div id="wrapper">
<div id="blog-body">
<?php include (TEMPLATEPATH . '/leftbar.php'); ?>

<!--img style="display: none" src="https://www.laitman.ru/wp-content/themes/fastway2/images/logo.jpg"-->
