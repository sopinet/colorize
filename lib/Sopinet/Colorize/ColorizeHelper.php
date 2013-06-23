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
	static public function getColorsFromImage($image_src, $library = "getmost") {
		/*
		 * Removed colorsofimage library, waiting that author will add composer/PSR-0
		 * 
		if ($library == "colorsofimage") {
			$image = new \ColorsOfImage( $image_src, 5, 10);
			$colors_img = $image->getProminentColors();
		} else if ($library == "getmost") {
		*/
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
		//}
		return $colors_img;
	}
	
	static public function getColorsFromParserCss($oCssParser) {
		$oCss = $oCssParser->parse();
		
		/*
		 * https://github.com/ju1ius/PHP-CSS-Parser
		*/
		/*
		 $map = new \ColorMap();
		$colors_css = array();
		foreach($oCss->getAllValues() as $mValue) {
		if($mValue instanceof CSSColor) {
		$color = $mValue->getColor();
		$color_a['r'] = $color['r']->__toString();
		$color_a['g'] = $color['g']->__toString();
		$color_a['b'] = $color['b']->__toString();
		$hex  = $map->rgb_to_hex($color_a);
		if (!array_key_exists($hex, $colors_css)) {
		$colors_css[$hex] = 0;
		}
		$colors_css[$hex]++;
		}
		}
		
		return $colors_css;
		*/
		
		$map = new ColorMap();
		$colors_css = array();
		foreach($oCss->getAllRuleSets() as $ruleSet) {
			foreach($ruleSet->getRules() as $rule) {
				if (method_exists($rule->getValue(), 'getColor')) {
					$color = $rule->getValue()->getColor();
					$color_a['r'] = $color['r']->__toString();
					$color_a['g'] = $color['g']->__toString();
					$color_a['b'] = $color['b']->__toString();
					$hex  = $map->rgb_to_hex($color_a);
					//$hex_without = str_replace("#","",$hex);
					if (!array_key_exists($hex, $colors_css)) {
						$colors_css[$hex] = 0;
					}
					$colors_css[$hex]++;
				}
				//}
			}
		}
		
		return $colors_css;
	}
	
	static public function getColorsFromStringCss($string_css) {
		$oCssParser = new \Sabberworm\CSS\Parser($string_css);
		return ColorizeHelper::getColorsFromParserCss($oCssParser);		
	}
	
	/**
	 * get Colors used in a css
	 * 
	 * @param String $file_css - Url to Css file
	 * @return multitype:number - Array of colors from css
	 */
	static public function getColorsFromCss($file_css) {
		$oCssParser = new \Sabberworm\CSS\Parser(file_get_contents($file_css));
		return ColorizeHelper::getColorsFromParserCss($oCssParser);
	}
	
	/**
	 * Replace colors in css with colors from image
	 * 
	 * @param string $file_css - Url to Css file
	 * @param array $colors_css - Array of colors in CSS file
	 * @param array $colors_img - Array of colors in Image file
	 * @return string - Css parsed with new colors
	 */
	static public function paintCssWithColors($string_css, $colors_css, $colors_img) {
		$oCssParser = new \Sabberworm\CSS\Parser($string_css);
		$oCss = $oCssParser->parse();
				
		foreach($oCss->getAllRuleSets() as $t) {
			foreach($t->getRules() as $i) {
				if (method_exists($i->getValue(), 'getColor')) {
					$color = $i->getValue()->getColor();
					$color_a['r'] = $color['r']->__toString();
					$color_a['g'] = $color['g']->__toString();
					$color_a['b'] = $color['b']->__toString();
					$map = new ColorMap();
					$hex  = $map->rgb_to_hex($color_a);
		
					$order = 0;
					foreach($colors_css as $key => $value) {
						if ($key == $hex) {
							if (array_key_exists($order, $colors_img)) {
								//ladybug_dump($colors_img[$order]);
								$map = new ColorMap();
								$setca = $map->hex_to_rgb($colors_img[$order]);
								//exit();
								$setc['r'] = new \Sabberworm\CSS\Value\Size($setca['r'], null, true);
								$setc['g'] = new \Sabberworm\CSS\Value\Size($setca['g'], null, true);
								$setc['b'] = new \Sabberworm\CSS\Value\Size($setca['b'], null, true);;
								$i->getValue()->setColor($setc);
							}
						}
						$order++;
					}
				}
				//}
			}
		}
		
		return $oCss->__toString();
	}
}