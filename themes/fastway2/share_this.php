<?php

require_once( '../../../wp-config.php');

$url = $_GET['url'];
$title = $_GET['title'];
$sharethis_page = get_page_by_path('sharethis');
$sharethis_content = $sharethis_page->post_content;
$sharethis_content = preg_replace('/%%title%%/', $title, $sharethis_content);
$sharethis_content = preg_replace('/%%url%%/', $url, $sharethis_content);
?>

<head>
	<style type="text/css">
	<!--
	.sharethis-comment {
		display:none;
	}
	.sharethis-table td {
		vertical-align:top;
	}
	.sharethis-entry {
		margin:0;
		padding:5px 8px 5px 0;	
	}
	.sharethis-entry a {
		text-decoration:none;
		color:gray;
	}
	.sharethis-entry img {
		border:none;
	}
	-->
	</style>
</head>
<body>
	<div><?php echo $sharethis_content; ?></div>
</body>

