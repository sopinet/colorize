<?php
namespace Sopinet\Colorize;

/**
* EXTERNALS LIBRARY,
* FERNANDO HIDALGO IS NOT AUTHOR OF THIS LIBRARY
* I AM TRYING THAT AUTHOR USE COMPOSER FOR IT
*/

// +---------------------------------------------------------------------------+
// | Converts HTML colors between different formats                            |
// +---------------------------------------------------------------------------+
// | @author Kenn Wilson                                                       |
// | @copyright Copyright (c) 2008 Kenn Wilson                                 |
// | @version 1.1                                                              |
// +---------------------------------------------------------------------------+

class ColorMap {
	
	/**
	 * Variables used in this class.
	 */
	protected $colormap;
	 
	/**
	 * Initialize class
	 */
	public function __construct()
	{
		$this->load_colormap();
	}
		
	/**
	 * Convert three-digit hex codes to six digits.
	 * Preserves hash character if input contains one.
	 * 
	 * @param   string    Three character hex code.
	 * @return  string    Same hex codes, in its six character format.
	 */
	public function hex_to_six($color)
	{
		$prefix = "";
		if ($color[0] == "#") {
			$prefix = "#";
			$color = str_replace('#', '', $color);
		}
		if (strlen($color) == 6) {
			return $prefix . $color;
		}
		$color6char  = $color[0] . $color[0];
		$color6char .= $color[1] . $color[1];
		$color6char .= $color[2] . $color[2];
		return $prefix . $color6char;
	}
		
	/**
	 * Convert RGB colors to hex codes.
	 * 
	 * @param   array     $rgb    Three element array representing one RGB color.
	 * @return  string    $hex    Hex code for RGB color.
	 */
	public function rgb_to_hex($rgb)
	{
		$hex = "#";
		// Dump into indexed array to ensure the loop reads them in the correct order
		$rgb = array ($rgb['r'], $rgb['g'], $rgb['b']);
		foreach ($rgb as $value) {
			$value = intval($value);
			$value = dechex($value);
			$value = str_pad($value, 2, 0, STR_PAD_LEFT);
			$hex  .= $value;
		}
		return $hex;
	}
	
	/**
	 * Convert hex colors to RGB
	 * 
	 * @param   string    $hex       Single color in hexadecimal.
	 * @param   string    $format    Preferred format of output. Defaults to array.
	 * @return            $rgb       RGB representing input color.
	 *                               Array by default, string if requested in $format parameter.
	 */
	public function hex_to_rgb($hex, $format = "array")
	{
		$hex = str_replace('#', '', $hex);
		if (strlen($hex) == 3) {
			$hex = $this->hex_to_six($hex);
		}
		// Split into separate colors
		$rgb['r'] = substr($hex, 0, 2);
		$rgb['g'] = substr($hex, 2, 2);
		$rgb['b'] = substr($hex, 4, 2);
		// Convert each color to decimal
		$rgb['r'] = hexdec($rgb['r']);
		$rgb['g'] = hexdec($rgb['g']);
		$rgb['b'] = hexdec($rgb['b']);
		// Convert array to string, if requested
		if ($format == "string") {
			$rgb = $this->rgb_array_to_string($rgb);
		}
		return $rgb;
	}
	
	/**
	 * Converts RGB array to string
	 * 
	 * @param   array     Three element array containing RGB values.
	 * @return  string    String containing RGB colors, space-delimited.
	 */
	public function rgb_array_to_string($rgb)
	{
		$rgb_string = $rgb['r'] . " " . $rgb['g'] . " " . $rgb['b'];
		return $rgb_string;
	}
	
	/** 
	 * Converts RGB color string to array
	 * 
	 * @param   string    String containing RGB color code, space-delimited
	 * @return  array     Three element array containing original RGB color
	 */
	public function rgb_string_to_array($rgb)
	{
		$array = split(' ', $rgb);
		$rgb_array['r'] = $array[0];
		$rgb_array['g'] = $array[1];
		$rgb_array['b'] = $array[2];
		return $rgb_array;
	}
	
	/**
	 * Convert a named color to its hexadecimal equivalent
	 * 
	 * @param   string    Named color to convert 
	 * @return  string    Specified color in hexadecimal, if name exists
	 *                    in array. Returns false if name does not exist.
	 */
	public function name_to_hex($name)
	{
		$name = strToLower($name);
		$colormap = array_change_key_case($this->colormap);
		if (array_key_exists($name, $colormap)) {
			return $colormap[$name];
		} else {
			return false;
		}
	}
	
	/**
	 * Convert a hex color to its named equivalent, if possible.
	 * 
	 * @param   string    Hexidecimal color code
	 * @return  string    Named color corresponding to specified hex code.
	 *                    Returns original hex code if named color does not exist.
	 */
	public function hex_to_name($hex)
	{
		$hex = strToLower($hex);
		if ($hex[0] != '#') {
			$hex = "#" . $hex;
		}
		$colormap = array_change_key_case($this->colormap);
		$colormap = array_flip($colormap);
		if (array_key_exists($hex, $colormap)) {
			return $colormap[$hex];
		} else {
			return $hex;
		}
	}
	
	/**
	 * Convert a named color to its RGB equivalent
	 * 
	 * @param   string    $name      Named color to convert 
	 * @param   string    $format    Preferred format of output. Defaults to array.
	 * @return            $rgb       RGB representing input color. 
	 *                               Array by default, string if requested in $format parameter.
	 */
	public function name_to_rgb($name, $format = "array")
	{
		$hex = $this->name_to_hex($name);
		$rgb = $this->hex_to_rgb($hex, $format);
		return $rgb;
	}
	
	/**
	 * Convert an RGB color to its named equivalent, if possible.
	 * 
	 * @param             RGB color code, either array or space-delimited string
	 * @return  string    Named color corresponding to specified RGB code.
	 *                    Returns original RGB code if named color does not exist.
	 */
	public function rgb_to_name($rgb)
	{
		if (is_string($rgb)) {
			$rgb = $this->rgb_string_to_array($rgb);
		}
		$hex  = $this->rgb_to_hex($rgb);
		$name = $this->hex_to_name($hex);
		return $name;
	}
	
	/**
	 * Retrieve entire colormap array
	 */
	public function get_colormap()
	{
		return $this->colormap;
	}
	
	
	// Private
	
	/**
	 * Loads array of named colors
	 */
	private function load_colormap()
	{
		$this->colormap = array (
			'AliceBlue'            => '#f0f8ff',
			'AntiqueWhite'         => '#faebd7',
			'Aqua'                 => '#00ffff',
			'Aquamarine'           => '#7fffd4',
			'Azure'                => '#f0ffff',
			'Beige'                => '#f5f5dc',
			'Bisque'               => '#ffe4c4',
			'Black'                => '#000000',
			'BlanchedAlmond'       => '#ffebcd',
			'Blue'                 => '#0000ff',
			'BlueViolet'           => '#8a2be2',
			'Brown'                => '#a52a2a',
			'Burlywood'            => '#deb887',
			'CadetBlue'            => '#5f9ea0',
			'Chartreuse'           => '#7fff00',
			'Chocolate'            => '#d2691e',
			'Coral'                => '#ff7f50',
			'CornflowerBlue'       => '#6495ed',
			'Cornsilk'             => '#fff8dc',
			'Crimson'              => '#dc143c',
			'Cyan'                 => '#00ffff',
			'DarkBlue'             => '#00008b',
			'DarkCyan'             => '#008b8b',
			'DarkGoldenrod'        => '#b8860b',
			'DarkGray'             => '#a9a9a9',
			'DarkGrey'             => '#a9a9a9',
			'DarkGreen'            => '#006400',
			'DarkKhaki'            => '#bdb76b',
			'DarkMagenta'          => '#8b008b',
			'DarkOliveGreen'       => '#556b2f',
			'DarkOrange'           => '#ff8c00',
			'DarkOrchid'           => '#9932cc',
			'DarkRed'              => '#8b0000',
			'DarkSalmon'           => '#e9967a',
			'DarkSeaGreen'         => '#8fbc8f',
			'DarkSlateBlue'        => '#483d8b',
			'DarkSlateGray'        => '#2f4f4f',
			'DarkSlateGrey'        => '#2f4f4f',
			'DarkTurquoise'        => '#00ced1',
			'DarkViolet'           => '#9400d3',
			'DeepPink'             => '#ff1493',
			'DeepSkyBlue'          => '#00bfff',
			'DimGray'              => '#696969',
			'DimGrey'              => '#696969',
			'DodgerBlue'           => '#1e90ff',
			'FireBrick'            => '#b22222',
			'FloralWhite'          => '#fffaf0',
			'ForestGreen'          => '#228b22',
			'Fuchsia'              => '#ff00ff',
			'Gainsboro'            => '#dcdcdc',
			'GhostWhite'           => '#f8f8ff',
			'Gold'                 => '#ffd700',
			'Goldenrod'            => '#daa520',
			'Gray'                 => '#808080',
			'Grey'                 => '#808080',
			'Green'                => '#008000',
			'GreenYellow'          => '#adff2f',
			'Honeydew'             => '#f0fff0',
			'HotPink'              => '#ff69b4',
			'IndianRed'            => '#cd5c5c',
			'Indigo'               => '#4b0082',
			'Ivory'                => '#fffff0',
			'Khaki'                => '#f0e68c',
			'Lavender'             => '#e6e6fa',
			'LavenderBlush'        => '#fff0f5',
			'LawnGreen'            => '#7cfc00',
			'LemonChiffon'         => '#fffacd',
			'LightBlue'            => '#add8e6',
			'LightCoral'           => '#f08080',
			'LightCyan'            => '#e0ffff',
			'LightGoldenrodYellow' => '#fafad2',
			'LightGray'            => '#d3d3d3',
			'LightGrey'            => '#d3d3d3',
			'LightGreen'           => '#90ee90',
			'LightPink'            => '#ffb6c1',
			'LightSalmon'          => '#ffa07a',
			'LightSeaGreen'        => '#20b2aa',
			'LightSkyBlue'         => '#87cefa',
			'LightSlateGray'       => '#778899',
			'LightSlateGrey'       => '#778899',
			'LightSteelBlue'       => '#b0c4de',
			'LightYellow'          => '#ffffe0',
			'Lime'                 => '#00ff00',
			'Limegreen'            => '#32cd32',
			'Linen'                => '#faf0e6',
			'Magenta'              => '#ff00ff',
			'Maroon'               => '#800000',
			'MediumAquamarine'     => '#66cdaa',
			'MediumBlue'           => '#0000cd',
			'MediumOrchid'         => '#ba55d3',
			'MediumPurple'         => '#9370d8',
			'MediumSeaGreen'       => '#3cb371',
			'MediumSlateBlue'      => '#7b68ee',
			'MediumSpringGreen'    => '#00fa9a',
			'MediumTurquoise'      => '#48d1cc',
			'MediumVioletRed'      => '#c71585',
			'MidnightBlue'         => '#191970',
			'MintCream'            => '#f5fffa',
			'MistyRose'            => '#ffe4e1',
			'Moccasin'             => '#ffe4b5',
			'NavajoWhite'          => '#ffdead',
			'Navy'                 => '#000080',
			'OldLace'              => '#fdf5e6',
			'Olive'                => '#808000',
			'OliveDrab'            => '#6b8e23',
			'Orange'               => '#ffa500',
			'OrangeRed'            => '#ff4500',
			'Orchid'               => '#da70d6',
			'PaleGoldenrod'        => '#eee8aa',
			'PaleGreen'            => '#98fb98',
			'PaleTurquoise'        => '#afeeee',
			'PaleVioletRed'        => '#d87093',
			'PapayaWhip'           => '#ffefd5',
			'PeachPuff'            => '#ffdab9',
			'Peru'                 => '#cd853f',
			'Pink'                 => '#ffc0cb',
			'Plum'                 => '#dda0dd',
			'PowderBlue'           => '#b0e0e6',
			'Purple'               => '#800080',
			'Red'                  => '#ff0000',
			'RosyBrown'            => '#bc8f8f',
			'RoyalBlue'            => '#4169e1',
			'SaddleBrown'          => '#8b4513',
			'Salmon'               => '#fa8072',
			'SandyBrown'           => '#f4a460',
			'SeaGreen'             => '#2e8b57',
			'Seashell'             => '#fff5ee',
			'Sienna'               => '#a0522d',
			'Silver'               => '#c0c0c0',
			'SkyBlue'              => '#87ceeb',
			'SlateBlue'            => '#6a5acd',
			'SlateGray'            => '#708090',
			'SlateGrey'            => '#708090',
			'Snow'                 => '#fffafa',
			'SpringGreen'          => '#00ff7f',
			'SteelBlue'            => '#4682b4',
			'Tan'                  => '#d2b48c',
			'Teal'                 => '#008080',
			'Thistle'              => '#d8bfd8',
			'Tomato'               => '#ff6347',
			'Turquoise'            => '#40e0d0',
			'Violet'               => '#ee82ee',
			'Wheat'                => '#f5deb3',
			'White'                => '#ffffff',
			'WhiteSmoke'           => '#f5f5f5',
			'Yellow'               => '#ffff00',
			'YellowGreen'          => '#9acd32'
		);
	}
		
}

?>