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
		$colors_css = ColorizeHelper::getColorsFromCss($css);		
		$colors_img = ColorizeHelper::getColorsFromImage($image);
		return ColorizeHelper::paintCssWithColors(file_get_contents($css), $colors_css, $colors_img);
	}
	
	static public function autoColorizeFromString($string_css, $image) {
		$colors_css = ColorizeHelper::getColorsFromStringCss($string_css);
		$colors_img = ColorizeHelper::getColorsFromImage($image);
		return ColorizeHelper::paintCssWithColors($string_css);
	}
}