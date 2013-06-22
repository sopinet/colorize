<?php
	use Sopinet\Colorize\ColorizeService;
	use Sopinet\Colorize\ColorizeHelper;

	require_once '../vendor/autoload.php';	
	// Ladybug\Loader::loadHelpers();

	$img_src = $_GET['img_src'];
	if ($img_src == "") $img_src = "http://www.sopinet.com/layout/bootstrap/template/sopinetoliva_mini.png";
	
	$new_css = ColorizeService::autoColorize("template.css", $img_src);
	//$new_css = ColorizeService::autoColorizeFromString("body { color: #F00 }", $img_src);

	include("layout.php");
?>