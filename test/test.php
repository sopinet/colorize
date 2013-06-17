<?php
	use Sopinet\Colorize\ColorizeService;

	use Sopinet\Colorize\ColorizeHelper;

	require_once '../vendor/autoload.php';
	require_once '../vendor/humanmade/Colors-Of-Image/colorsofimage.class.php';
	require_once '../vendor/kennwilson/colormap-php/colormap.php';
	
	Ladybug\Loader::loadHelpers();

	$img_src = $_GET['img_src'];
	if ($img_src == "") $img_src = "http://www.sopinet.com/layout/bootstrap/template/sopinetoliva_mini.png";
	$new_css = ColorizeService::autoColorize("template.css", $img_src);

	include("layout.php");
?>