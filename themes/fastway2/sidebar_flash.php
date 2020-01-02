<?php

require_once( '../../../wp-config.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title>Flash Player</title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta http-equiv="Content-Language" content="utf8" /> 

	<style type="text/css">
		#mediaspace{
			margin: 0 auto;
			padding:0 auto;
			width: 320px;
			height:267px;
		}
	</style>
</head>
<body>
<?php

$flv_src = $_GET['flv'];

if ($flv_src != "")
	show_player($flv_src);

?>
</body>
</html>

<?php

function show_player($flv_src)
{
?>
<script type='text/javascript' src='/wp-includes/js/mediaplayer-viral/swfobject.js'></script>

  <div id='mediaspace'>This div will be replaced</div>

  <script type='text/javascript'>
  var s1 = new SWFObject('/wp-includes/js/mediaplayer-viral/player-viral.swf','ply','320','267','9','#ffffff');
  s1.addParam('allowfullscreen','true');
  s1.addParam('allowscriptaccess','always');
  s1.addParam('wmode','opaque');
  s1.addParam('flashvars','file=<?php echo $flv_src; ?>&resizing=true&autostart=true');
  s1.write('mediaspace');
</script>
<?php
}

?>

