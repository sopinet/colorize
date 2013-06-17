<?php
	ini_set("display_errors",1);
	require_once '../vendor/autoload.php';
	require_once '../vendor/humanmade/Colors-Of-Image/colorsofimage.class.php';
	require_once '../vendor/kennwilson/colormap-php/colormap.php';
	
	Ladybug\Loader::loadHelpers();
	
	$image_src = "http://www.sopinet.com/layout/bootstrap/template/sopinetoliva_mini.png";

	$image = new ColorsOfImage( $image_src, 5, 5);
	$colors_img = $image->getProminentColors();
	
	$oCssParser = new Sabberworm\CSS\Parser(file_get_contents('template.css'));
	$oCss = $oCssParser->parse();
	
	/*echo '<pre>';
	print_r($image);
	echo '</pre>';*/
	//echo "H";
	/*foreach($oCss->getAllRuleSets() as $t) {
		ladybug_dump($t->__toString());
	}
	*/
	$colors_css = array();
	foreach($oCss->getAllRuleSets() as $t) {
		//ladybug_dump($t);
		foreach($t->getRules() as $i) {
			//echo gettype($i).'<br/>';
			//echo get_class($i).'<br/>';
			//if ($i instanceof Sabberworm\CSS\Value\Color) {
			if (method_exists($i->getValue(), 'getColor')) {
				$color = $i->getValue()->getColor();
				$color_a['r'] = $color['r']->__toString();
				$color_a['g'] = $color['g']->__toString();
				$color_a['b'] = $color['b']->__toString();
				$map = new ColorMap();
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
	
	arsort($colors_css);
	ladybug_dump($colors_css);
	ladybug_dump($colors_img);
	
	foreach($oCss->getAllRuleSets() as $t) {
		//ladybug_dump($t);
		foreach($t->getRules() as $i) {
			//echo gettype($i).'<br/>';
			//echo get_class($i).'<br/>';
			//if ($i instanceof Sabberworm\CSS\Value\Color) {
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
							$setc['r'] = new Sabberworm\CSS\Value\Size($setca['r'], null, true);
							$setc['g'] = new Sabberworm\CSS\Value\Size($setca['g'], null, true);
							$setc['b'] = new Sabberworm\CSS\Value\Size($setca['b'], null, true);;
							$i->getValue()->setColor($setc);
						}
						//echo "ESTA AKI!!!";
						//exit();
					}
					$order++;
				}
			}
			//}
		}
	}
	
	print_r($oCss->__toString());
	
	/**
	* DEBUG
	 */
	/*
	$colors_css = array();
	foreach($oCss->getAllRuleSets() as $t) {
		//ladybug_dump($t);
		foreach($t->getRules() as $i) {
			//echo gettype($i).'<br/>';
			//echo get_class($i).'<br/>';
			//if ($i instanceof Sabberworm\CSS\Value\Color) {
			if (method_exists($i->getValue(), 'getColor')) {
			$color = $i->getValue()->getColor();
			$color_a['r'] = $color['r']->__toString();
			$color_a['g'] = $color['g']->__toString();
			$color_a['b'] = $color['b']->__toString();
			$map = new ColorMap();
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
			
echo "HOLA";
			ladybug_dump($colors_css);
	/*
	ladybug_dump($image);
	
	exit();
	*/
	/*
	
	foreach($oCss->getAllValues() as $mValue) {
		//if($mValue instanceof CSSColor) {
			ladybug_dump($mValue);
		//}
	}
	*/	
	/*
	foreach($oCss->getAllValues() as $mValue) {
		if($mValue instanceof Color) {
			echo "h";
		}
	}*/	
	//ladybug_dump($oCssDocument->getAllRuleSets());
	/*echo '<pre>';
	print_r($oCssDocument);
	echo '</pre>';*/



?>