<?php
	use Sopinet\Colorize\ColorizeService;
	use Sopinet\Colorize\ColorizeHelper;

	require_once '../vendor/autoload.php';

	// TODO: AutoLoad these class
	require_once '../vendor/humanmade/Colors-Of-Image/colorsofimage.class.php';
	require_once '../vendor/kennwilson/colormap-php/colormap.php';
	
	Ladybug\Loader::loadHelpers();

	$img_src = $_GET['img_src'];
	if ($img_src == "") $img_src = "http://www.sopinet.com/layout/bootstrap/template/sopinetoliva_mini.png";
	
	$css_src = $_GET['css_src'];
	if ($css_src == "") $css_src = "template.css";
	
	$new_css = ColorizeService::autoColorize($css_src, $img_src);
	
	echo $new_css;
?>