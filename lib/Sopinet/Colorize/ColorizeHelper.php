<?php
namespace Sopinet\Colorize;

use Sopinet\Colorize\GetMostCommonColors;
use KennWilson\ColorMap\ColorMap;

class ColorizeHelper
{
	/**
	 * get Colors used in an Image
	 * 
	 * @param string $image_src - Url to Image file
	 * @param string $library - getmost or colorsofimage library (colorsofimage removed) 
	 * @return multitype:string - Array of colors from image
	 */
	static public function getColorsFromImage($image_src) {
		$delta = 24;
		$reduce_brightness = true;
		$reduce_gradients = true;
		$num_results = 20;
		
		$ex=new GetMostCommonColors();
		$colors=$ex->Get_Color($image_src, $num_results, $reduce_brightness, $reduce_gradients, $delta);
		$colors_img = array();
		foreach($colors as $key => $value) {
			$colors_img[] = "#" . $key;
		}
		return $colors_img;
	}
	
	/**
	 * get Colors used in a css
	 *
	 * @param String $string_css - String with css code
	 * @return multitype:number - Array of colors from css
	 */	
	static public function getColorsFromCss($string_css) {
		$normal_regex = "~(#([0-9A-Fa-f]{3,6})\b)|
(aqua)|(black)|(blue)|(fuchsia)|
(gray)|(green)|(lime)|(maroon)|
(navy)|(olive)|(orange)|(purple)|
(red)|(silver)|(teal)|(white)|(yellow)~";
		preg_match_all($normal_regex, $string_css, $matches_normal);
		
		$rgb_color_regex = "~rgb\\(\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*\\)~";		
		preg_match_all($rgb_color_regex, $string_css, $matches_rgb);
		
		$rgba_color_regex = "~rgba\\(\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*((0.[1-9])|[01])\\s*\\)~";		
		preg_match_all($rgba_color_regex, $string_css, $matches_rgba);

		$colors_css = array();
		$map = new ColorMap();
		foreach($matches_normal[0] as $match) {
			if ($match[0] == "#") {
				$temp = $map->hex_to_six($match);
			} else {
				$temp = $map->name_to_hex($match);
			}
			if (!array_key_exists($temp, $colors_css)) {
				$colors_css[$temp] = array();
			}
			$colors_css[$temp][] = $match;
		}
		
		foreach($matches_rgba[0] as $match) {
			$temp0 = explode(",",$match);
			$temp1 = explode("(",$temp0[0]);
			$color_a['r'] = $temp1[1];
			$color_a['g'] = $temp0[1];
			$color_a['b'] = $temp0[2];
			$temp = $map->rgb_to_hex($color_a);
			if (!array_key_exists($temp, $colors_css)) {
				$colors_css[$temp] = array();
			}
			$colors_css[$temp][] = $match;
		}
		arsort($colors_css);
		return $colors_css;
	}
	
	/**
	 * Replace colors in css with colors from image
	 *
	 * @param string $string_css - String with css code
	 * @param array $colors_css - Array of colors in CSS code
	 * @param array $colors_img - Array of colors in Image file
	 * @return string - Css parsed with new colors
	 */	
	static public function paintCssWithColors($string_css, $colors_css, $colors_img) {
		$i = 0;
		foreach($colors_css as $keycolor => $array_forreplace) {
			if ($i < count($colors_img)) {
				foreach($array_forreplace as $color_forreplace) {
					$string_css = str_replace($color_forreplace, $colors_img[$i], $string_css);
				}
			}
			$i++;
		}
		return $string_css;
	}
}