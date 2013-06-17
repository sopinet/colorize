<?php
namespace Sopinet\Colorize;

class ColorizeService
{
	static public function autoColorize($css, $image) {
		$colors_css = ColorizeHelper::getColorsFromCss($css);		
		$colors_img = ColorizeHelper::getColorsFromImage($image);
		return ColorizeHelper::paintCssWithColors($css, $colors_css, $colors_img);
	}
}