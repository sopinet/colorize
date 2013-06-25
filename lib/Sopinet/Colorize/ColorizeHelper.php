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
	 * @param boolean $checkinverse - Boolean for check inverse colors problems
	 * @return string - Css parsed with new colors
	 */	
	static public function paintCssWithColors($string_css, $colors_css, $colors_img, $checkinverse = true) {
		$i = 0;
		foreach($colors_css as $keycolor => $array_forreplace) {
			if ($i < count($colors_img)) {
				foreach($array_forreplace as $color_forreplace) {
					$string_css = str_replace($color_forreplace, $colors_img[$i], $string_css);
				}
			}
			$i++;
		}
		if ($checkinverse) {
			return ColorizeHelper::fixInverse($string_css);
		} else {
			return $string_css;
		}
	}
	
	static private function isTooLightYIQ($R, $G, $B){
		$yiq = (($R*299)+($G*587)+($B*114))/1000;
		return $yiq >= 128;
	}	
	
	static private function getInverseColor($rgb1,$rgb2) {
		$map = new ColorMap();
		$rgb1 = $map->hex_to_six($rgb1);
		$aRGB = $map->hex_to_rgb($rgb1);
		if (count($aRGB) > 2) {
			if (ColorizeHelper::isTooLightYIQ($aRGB['r'], $aRGB['g'], $aRGB['b'])) return "#000";
			else return "#FFF";
		} else {
			return "#F00";
		}
	}
	
	static private function fixInverse($string_css) {
		$elements = explode("}", $string_css);
		$ret_string_css = "";
		foreach($elements as $element) {
			$temp = explode("{", $element);
			if (count($temp) > 1) {
				$attributes = explode(";",$temp[1]);
				$color = "";
				$bgcolor = "";
				foreach($attributes as $attribute) {
					$forreplace = explode(":",$attribute);
					//exit();
					if (trim($forreplace[0]) == "color") {
						$color_at = $attribute;
						$color = $forreplace[1];
					} elseif (trim($forreplace[0]) == "background-color") {
						$bgcolor_at = $attribute;
						$bgcolor = $forreplace[1];
					}
				}
				if ($color != "" && $bgcolor != "") {
					$element = str_replace($color_at, "color: ".ColorizeHelper::getInverseColor($bgcolor, $color), $element);
					$element = str_replace($bgcolor_at, "background-color: ".$bgcolor, $element);
				}
			}
			$ret_string_css .= $element . "} ";
		}
		return $ret_string_css;
	}
}