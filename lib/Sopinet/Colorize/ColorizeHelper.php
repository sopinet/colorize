<?php
namespace Sopinet\Colorize;

class ColorizeHelper
{
	static public function getColorsFromImage($image_src) {
		//$image_src = "http://www.sopinet.com/layout/bootstrap/template/sopinetoliva_mini.png";
		$image = new \ColorsOfImage( $image_src, 5, 5);
		$colors_img = $image->getProminentColors();
		return $colors_img;		
	}
	
	static public function getColorsFromCss($file_css) {
		$oCssParser = new \Sabberworm\CSS\Parser(file_get_contents($file_css));
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
		
		$map = new \ColorMap();
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
	
	static public function paintCssWithColors($file_css, $colors_css, $colors_img) {
		$oCssParser = new \Sabberworm\CSS\Parser(file_get_contents($file_css));
		$oCss = $oCssParser->parse();
				
		foreach($oCss->getAllRuleSets() as $t) {
			foreach($t->getRules() as $i) {
				if (method_exists($i->getValue(), 'getColor')) {
					$color = $i->getValue()->getColor();
					$color_a['r'] = $color['r']->__toString();
					$color_a['g'] = $color['g']->__toString();
					$color_a['b'] = $color['b']->__toString();
					$map = new \ColorMap();
					$hex  = $map->rgb_to_hex($color_a);
		
					$order = 0;
					foreach($colors_css as $key => $value) {
						if ($key == $hex) {
							if (array_key_exists($order, $colors_img)) {
								//ladybug_dump($colors_img[$order]);
								$map = new \ColorMap();
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