<?php
namespace Sopinet\Colorize;

class ColorizeService
{
	/**
	 * Return new css parsed from colors in image
	 * 
	 * @param String $css - Url to css file
	 * @param String $image - Url to image file
	 * @return string - Css parsed with colors from image
	 */
	static public function autoColorize($css, $image) {
		$string_css = file_get_contents($css);
		return ColorizeService::autoColorizeFromString($string_css, $image);
	}
	
	/**
	 * Return new css parsed from colors in image with css string
	 * 
	 * @param String $string_css - String with css code
	 * @param String $image - Url to image file
	 * @return String <string, string, mixed> - Css parsed with colors from image
	 */
	static public function autoColorizeFromString($string_css, $image) {
		$colors_css = ColorizeHelper::getColorsFromCss($string_css);
		$colors_img = ColorizeHelper::getColorsFromImage($image);
		return ColorizeHelper::paintCssWithColors($string_css, $colors_css, $colors_img);
	}
	
	static public function getMainBackgroundColor($image) {
		//TODO: do cache
		$colors_img = ColorizeHelper::getColorsFromImage($image);
		return $colors_img[0];
	}
}