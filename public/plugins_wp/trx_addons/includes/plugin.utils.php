<?php
/**
 * PHP utilities
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



/* Arrays manipulations
----------------------------------------------------------------------------------------------------- */

// Return first key (by default) or value from associative array
if (!function_exists('trx_addons_array_get_first')) {
	function trx_addons_array_get_first($arr, $key=true) {
		$rez = false;
		foreach ($arr as $k=>$v) {
			$rez = $key ? $k : $v;
			break;
		}
		return $rez;
	}
}

// Return first key from associative array
if (!function_exists('trx_addons_array_get_first_key')) {
	function trx_addons_array_get_first_key($arr) {
		return trx_addons_array_get_first($arr, true);
	}
}

// Return first value from associative array
if (!function_exists('trx_addons_array_get_first_value')) {
	function trx_addons_array_get_first_value($arr) {
		return trx_addons_array_get_first($arr, false);
	}
}

// Return keys by value from associative string: categories=1|author=0|date=0|counters=1...
if ( ! function_exists( 'trx_addons_array_get_keys_by_value' ) ) {
	function trx_addons_array_get_keys_by_value( $arr, $value = 1 ) {
		if ( ! is_array( $arr ) ) {
			parse_str( str_replace( '|', '&', $arr ), $arr );
		}
		return $value != null ? array_keys( $arr, $value ) : array_keys( $arr );
	}
}

if ( ! function_exists( 'trx_addons_array_delete_by_value' ) ) {
	/**
	 * Delete items by value from an array (any type). All entries equal to value will be removed.
	 *
	 * @param array $arr    An array to return keys with a specified value.
	 * @param mixed $value  A value to delete.
	 *
	 * @return array        A processed array without items equals to a value.
	 */
	function trx_addons_array_delete_by_value( $arr, $value ) {
		foreach( (array)$value as $v ) {
			do {
				$key = array_search( $v, $arr );
				if ( false !== $key ) {
					unset( $arr[ $key ] );
				}
			} while ( false !== $key );
		}
		return $arr;
	}
}

// Convert list to associative array (use values as keys)
if (!function_exists('trx_addons_array_from_list')) {
	function trx_addons_array_from_list($arr) {
		$new = array();
		foreach ($arr as $v) $new[$v] = $v;
		return $new;
	}
}

// Return values from all levels of array
if (!function_exists('trx_addons_array_get_values')) {
	function trx_addons_array_get_values($arr) {
		$new = array();
		foreach ($arr as $v) {
			if ( is_array( $v )) {
				$new = array_merge($new, trx_addons_array_get_values($v));
			} else {
				$new[] = $v;
			}
		}
		return $new;
	}
}

// Return part of array from key=$from to key=$to
if ( ! function_exists( 'trx_addons_array_slice' ) ) {
	function trx_addons_array_slice( $arr, $from, $to='' ) {
		if ( is_array( $arr ) && count( $arr ) > 0 && ( ! empty( $from ) || ! empty( $to ) ) ) {
			$arr_new  = array();
			$copy     = empty( $from );
			$from_inc = false;
			$to_inc   = false;
			if ( substr( $from, 0, 1) == '+' ) {
				$from_inc = true;
				$from     = substr( $from, 1 );
			}
			if ( substr( $to, 0, 1) == '+' ) {
				$to_inc = true;
				$to     = substr( $to, 1 );
			}
			foreach ( $arr as $k => $v ) {
				if ( ! empty( $from ) && $k == $from ) {
					$copy = true;
					if ( ! $from_inc ) {
						continue;
					}
				}
				if ( ! empty( $to ) && $k == $to ) {
					if ( $copy && $to_inc ) {
						$arr_new[ $k ] = $v;
					}
					break;
				}
				if ( $copy ) {
					$arr_new[ $k ] = $v;
				}
			}
			$arr = $arr_new;
		}
		return $arr;
	}
}

// Merge arrays and lists (preserve number indexes)
// $a = array("one", "k2"=>"two", "three");
// $b = array("four", "k1"=>"five", "k2"=>"six", "seven");
// $c = array_merge($a, $b);			["one", "k2"=>"six", "three", "four", "k1"=>"five", "seven");
// $d = trx_addons_array_merge($a, $b);	["four", "k2"=>"six", "seven", "k1"=>"five");
if (!function_exists('trx_addons_array_merge')) {
	function trx_addons_array_merge($a1, $a2) {
		for ($i = 1; $i < func_num_args(); $i++){
			$arg = func_get_arg($i);
			if (is_array($arg) && count($arg)>0) {
				foreach($arg as $k=>$v) {
					$a1[$k] = $v;
				}
			}
		}
		return $a1;
	}
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately after the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
// Modifies $haystack in place.
// @param array &$haystack the associative array to search. This will be modified by the function
// @param string $needle the key to search for
// @param mixed $stuff one or more arrays or scalars to be inserted into $haystack
// @return int the index at which $needle was found
if (!function_exists('trx_addons_array_insert_after')) {
	function trx_addons_array_insert_after(&$haystack, $needle, $stuff){
		if (! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = trx_addons_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				$i++;
				if ($key == $needle) break;
			}
		}

		$haystack = is_int( $needle )
						? array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true))
						: trx_addons_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
    }
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately before the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
// Modifies $haystack in place.
// @param array &$haystack the associative array to search. This will be modified by the function
// @param string $needle the key to search for
// @param mixed $stuff one or more arrays or scalars to be inserted into $haystack
// @return int the index at which $needle was found
if (!function_exists('trx_addons_array_insert_before')) {
	function trx_addons_array_insert_before(&$haystack, $needle, $stuff){
		if ( ! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = trx_addons_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				if ($key == $needle) break;
				$i++;
			}
		}

		$haystack = is_int( $needle )
						? array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true))
						: trx_addons_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
	}
}


/* Colors manipulations
----------------------------------------------------------------------------------------------------- */

if (!function_exists('trx_addons_hex2rgb')) {
	function trx_addons_hex2rgb($hex) {
		$dec = hexdec(substr($hex, 0, 1)== '#' ? substr($hex, 1) : $hex);
		return array('r'=> $dec >> 16, 'g'=> ($dec & 0x00FF00) >> 8, 'b'=> $dec & 0x0000FF);
	}
}

if (!function_exists('trx_addons_hex2rgba')) {
	function trx_addons_hex2rgba($hex, $alpha) {
		$rgb = trx_addons_hex2rgb($hex);
		return 'rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].','.$alpha.')';
	}
}

if (!function_exists('trx_addons_hex2hsb')) {
	function trx_addons_hex2hsb ($hex, $h=0, $s=0, $b=0) {
		$hsb = trx_addons_rgb2hsb(trx_addons_hex2rgb($hex));
		$hsb['h'] = min(359, max(0, $hsb['h'] + $h));
		$hsb['s'] = min(100, max(0, $hsb['s'] + $s));
		$hsb['b'] = min(100, max(0, $hsb['b'] + $b));
		return $hsb;
	}
}

if (!function_exists('trx_addons_rgb2hsb')) {
	function trx_addons_rgb2hsb ($rgb) {
		$hsb = array();
		$hsb['b'] = max(max($rgb['r'], $rgb['g']), $rgb['b']);
		$hsb['s'] = ($hsb['b'] <= 0) ? 0 : round(100*($hsb['b'] - min(min($rgb['r'], $rgb['g']), $rgb['b'])) / $hsb['b']);
		$hsb['b'] = round(($hsb['b'] /255)*100);
		if (($rgb['r']==$rgb['g']) && ($rgb['g']==$rgb['b'])) $hsb['h'] = 0;
		else if($rgb['r']>=$rgb['g'] && $rgb['g']>=$rgb['b']) $hsb['h'] = 60*($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']);
		else if($rgb['g']>=$rgb['r'] && $rgb['r']>=$rgb['b']) $hsb['h'] = 60  + 60*($rgb['g']-$rgb['r'])/($rgb['g']-$rgb['b']);
		else if($rgb['g']>=$rgb['b'] && $rgb['b']>=$rgb['r']) $hsb['h'] = 120 + 60*($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']);
		else if($rgb['b']>=$rgb['g'] && $rgb['g']>=$rgb['r']) $hsb['h'] = 180 + 60*($rgb['b']-$rgb['g'])/($rgb['b']-$rgb['r']);
		else if($rgb['b']>=$rgb['r'] && $rgb['r']>=$rgb['g']) $hsb['h'] = 240 + 60*($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']);
		else if($rgb['r']>=$rgb['b'] && $rgb['b']>=$rgb['g']) $hsb['h'] = 300 + 60*($rgb['r']-$rgb['b'])/($rgb['r']-$rgb['g']);
		else $hsb['h'] = 0;
		$hsb['h'] = round($hsb['h']);
		return $hsb;
	}
}

if (!function_exists('trx_addons_hsb2rgb')) {
	function trx_addons_hsb2rgb($hsb) {
		$rgb = array();
		$h = round($hsb['h']);
		$s = round($hsb['s']*255/100);
		$v = round($hsb['b']*255/100);
		if ($s == 0) {
			$rgb['r'] = $rgb['g'] = $rgb['b'] = $v;
		} else {
			$t1 = $v;
			$t2 = (255-$s)*$v/255;
			$t3 = ($t1-$t2)*($h%60)/60;
			if ($h==360) $h = 0;
			if ($h<60) { 		$rgb['r']=$t1; $rgb['b']=$t2; $rgb['g']=$t2+$t3; }
			else if ($h<120) {	$rgb['g']=$t1; $rgb['b']=$t2; $rgb['r']=$t1-$t3; }
			else if ($h<180) {	$rgb['g']=$t1; $rgb['r']=$t2; $rgb['b']=$t2+$t3; }
			else if ($h<240) {	$rgb['b']=$t1; $rgb['r']=$t2; $rgb['g']=$t1-$t3; }
			else if ($h<300) {	$rgb['b']=$t1; $rgb['g']=$t2; $rgb['r']=$t2+$t3; }
			else if ($h<360) {	$rgb['r']=$t1; $rgb['g']=$t2; $rgb['b']=$t1-$t3; }
			else {				$rgb['r']=0;   $rgb['g']=0;   $rgb['b']=0; }
		}
		return array('r'=>round($rgb['r']), 'g'=>round($rgb['g']), 'b'=>round($rgb['b']));
	}
}

if (!function_exists('trx_addons_rgb2hex')) {
	function trx_addons_rgb2hex($rgb) {
		$hex = array(
			dechex($rgb['r']),
			dechex($rgb['g']),
			dechex($rgb['b'])
		);
		return '#'.(strlen($hex[0])==1 ? '0' : '').($hex[0]).(strlen($hex[1])==1 ? '0' : '').($hex[1]).(strlen($hex[2])==1 ? '0' : '').($hex[2]);
	}
}

if (!function_exists('trx_addons_hsb2hex')) {
	function trx_addons_hsb2hex($hsb) {
		return trx_addons_rgb2hex(trx_addons_hsb2rgb($hsb));
	}
}






/* Date manipulations
----------------------------------------------------------------------------------------------------- */

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('trx_addons_date_to_sql')) {
	function trx_addons_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\.,','----');
		if (trim($str)=='00-00-0000' || trim($str)=='00-00-00') return '';
		$pos = strpos($str,'-');
		if ($pos > 3) return $str;
		$d=trim(substr($str,0,$pos));
		$str=substr($str,$pos+1);
		$pos = strpos($str,'-');
		$m=trim(substr($str,0,$pos));
		$y=trim(substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(strlen($m)<2?'0':'').($m).'-'.(strlen($d)<2?'0':'').($d);
	}
}






/* Numbers manipulations
----------------------------------------------------------------------------------------------------- */

// Display price
if (!function_exists('trx_addons_format_price')) {
	function trx_addons_format_price( $price ) {
		$thousands_separator = apply_filters( 'trx_addons_filter_thousands_separator',
											trx_addons_exists_woocommerce()
												? get_option( 'woocommerce_price_thousand_sep', ' ' )
												: ' '
											);
		$decimals_separator  = apply_filters( 'trx_addons_filter_decimals_separator',
											trx_addons_exists_woocommerce()
												? get_option( 'woocommerce_price_decimal_sep', '.' )
												: '.'
											);
		$num_decimals        = apply_filters( 'trx_addons_filter_num_decimals',
											trx_addons_exists_woocommerce()
												? get_option( 'woocommerce_price_num_decimals', 2 )
												: 2
											);
		return apply_filters( 'trx_addons_filter_format_price',
				is_numeric( $price ) 
					? ( $price != round( $price, 0 )
						? number_format( round( $price, $num_decimals ), $num_decimals, $decimals_separator, $thousands_separator )
						: number_format( $price, 0, $decimals_separator, $thousands_separator )
						)
					: $price,
				$price
				);
	}
}


// Convert number to K
if (!function_exists('trx_addons_num2kilo')) {
	function trx_addons_num2kilo($num) {
		$num = intval( str_replace( ' ', '', $num ) );
		return $num > 1000 ? round( $num / 1000, 0 ) . 'K' : $num;
	}
}

// Convert size in bytes to string with suffix K(ilo)|M(ega)|G(iga)|T(era)|P(enta)
// For example: 10543 -> 10K
if (!function_exists('trx_addons_num2size')) {
	function trx_addons_num2size($num, $precision = 0) { 
		$num   = intval( str_replace( ' ', '', $num ) );
		$units = array( 'B', 'K', 'M', 'G', 'T', 'P' ); 
		$num   = max( $num, 0 ); 
		$pow   = floor( ( $num ? log( $num ) : 0 ) / log( 1024 ) ); 
		$pow   = min( $pow, count( $units ) - 1 ); 
		$num  /= ( 1 << ( 10 * $pow ) ); 
		return round( $num, $precision ) . $units[ $pow ];
	}
}

// Convert size string with suffix K(ilo)|M(ega)|G(iga)|T(era)|P(enta) to the integer
// For example: 10K -> 10240
if (!function_exists('trx_addons_size2num')) {
	function trx_addons_size2num($size) {
		$size = str_replace( ' ', '', $size );
		$suff = strtoupper( substr( $size, -1 ) );
		$pos  = strpos( 'KMGTP', $suff );
		if ( $pos !== false ) {
			$size = intval( substr( $size, 0, -1 ) ) * pow( 1024, $pos + 1 );
		}
		return (int) $size;
	}
}

// Clear number - leave only sign (+/-), digits and point (.) as delimiter
if (!function_exists('trx_addons_parse_num')) {
	function trx_addons_parse_num($str) {
/*
		$rez = '';
		$dot = false;
		for ( $i = 0; $i < strlen( $str ); $i++ ) {
			$ch = substr($str, $i, 1);
			if ( 0 == $i && in_array( $ch, array( '-', '+' ) ) ) {
				$rez .= $ch;
			} else if ( '.' == $ch ) {
				if ( ! $dot ) {
					$rez .= $ch;
				} else {
					break;
				}
			} else if ( '0' <= $ch && '9' >= $ch ) {
				$rez .= $ch;				
			} else {
				break;
			}
		}
		return $rez;
*/
		return (float) filter_var( html_entity_decode( strip_tags( $str ) ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}
}






/* String manipulations
----------------------------------------------------------------------------------------------------- */

// Check value for "on" | "off" | "inherit" values
if ( ! function_exists('trx_addons_is_on') ) {
	function trx_addons_is_on( $prm ) {
		return ( is_bool( $prm ) && $prm === true )
				|| ( is_numeric( $prm ) && $prm > 0 )
				|| in_array( strtolower( $prm ), array( '1', 'true', 'on', 'yes', 'show' )
				);
	}
}
if ( ! function_exists('trx_addons_is_off') ) {
	function trx_addons_is_off( $prm ) {
		return empty( $prm )
				|| ( is_numeric( $prm ) && $prm === 0 )
				|| in_array( strtolower( $prm ), array( '0', 'false', 'off', 'no', 'none', 'hide' ) );
	}
}
if ( ! function_exists('trx_addons_is_inherit') ) {
	function trx_addons_is_inherit( $prm ) {
		return in_array( strtolower( $prm ), array( 'inherit' ) );
	}
}


// str_replace with arrays and serialize support
if ( ! function_exists('trx_addons_str_replace') ) {
	function trx_addons_str_replace( $from, $to, $str ) {
		if ( is_array( $str ) ) {
			foreach ( $str as $k => $v ) {
				$str[ $k ] = trx_addons_str_replace( $from, $to, $v );
			}
		} else if ( is_object( $str ) ) {
			if ( '__PHP_Incomplete_Class' !== get_class( $str ) ) {
				foreach ( $str as $k => $v ) {
					$str->{$k} = trx_addons_str_replace( $from, $to, $v );
				}
			}
		} else if ( is_string( $str ) ) {
			if ( is_serialized( $str ) ) {
				$str = serialize( trx_addons_str_replace( $from, $to, trx_addons_unserialize( $str ) ) );
			} else {
				$str = str_replace( $from, $to, $str );
			}
		}
		return $str;
	}
}

// Uses only the first encountered substitution from the list
if ( ! function_exists('trx_addons_str_replace_once') ) {
	function trx_addons_str_replace_once( $from, $to, $str ) {
		$rez = '';
		if ( ! is_array( $from ) ) {
			$from = array( $from );
		}
		if ( ! is_array( $to ) ) {
			$to = array( $to );
		}
		for ( $i = 0; $i < strlen( $str ); $i++ ) {
			$found = false;
			for ( $j = 0; $j < count( $from ); $j++ ) {
				if ( substr( $str, $i, strlen( $from[ $j ] ) ) == $from[ $j ] ) {
					$rez .= isset( $to[ $j ] ) ? $to[ $j ] : '';
					$found = true;
					$i += strlen( $from[ $j ] ) - 1;
					break;
				}
			}
			if ( ! $found ) {
				$rez .= $str[ $i ];
			}
		}
		return $rez;
	}
}


// Remove non-text tags from html
if ( ! function_exists( 'trx_addons_strip_tags' ) ) {
	function trx_addons_strip_tags( $str ) {
		// remove comments and any content found in the the comment area (strip_tags only removes the actual tags).
		$str = preg_replace( '#<!--.*?-->#s', '', $str );
		// remove all script and style tags
		$str = preg_replace( '#<(script|style)\b[^>]*>(.*?)</(script|style)>#is', "", $str );
		// remove br tags (missed by strip_tags)
		$str = preg_replace( '#<br[^>]*?>#', ' ', $str );
		// put a space between list items, paragraphs and headings (strip_tags just removes the tags).
		$str = preg_replace( '#</(li|p|span|h1|h2|h3|h4|h5|h6)>#', ' </$1>', $str );
		// remove all remaining html
		$str = strip_tags( $str );
		return trim( $str );
	}
}

// Return truncated string (by chars number)
if ( ! function_exists('trx_addons_strshort') ) {
	function trx_addons_strshort($str, $maxlength, $add='&hellip;') {
		if ($maxlength <= 0) {
			return '';
		}
		$str = trx_addons_strip_tags( $str );
		if ($maxlength >= strlen($str)) {
			return $str;
		}
		$str = substr($str, 0, $maxlength - strlen($add));
		$ch  = substr($str, $maxlength - strlen($add), 1);
		if ($ch != ' ') {
			for ($i = strlen($str) - 1; $i > 0; $i--) {
				if (substr($str, $i, 1) == ' ') break;
			}
			$str = trim(substr($str, 0, $i));
		}
		if (!empty($str) && strpos(',.:;-', substr($str, -1))!==false) $str = substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Return truncated string (by words number)
if ( ! function_exists('trx_addons_strwords') ) {
	function trx_addons_strwords($str, $maxlength, $add='&hellip;') {
		if ($maxlength <= 0) {
			return '';
		}
		$words = explode( ' ', trx_addons_strip_tags( $str ) );
		if ( count( $words ) > $maxlength ) {
			$words = array_slice( $words, 0, $maxlength );
			$words[ count( $words ) - 1 ] .= $add;
		}
		return join(' ', $words	);
	}
}

// Make excerpt from html
if ( ! function_exists( 'trx_addons_excerpt' ) ) {
	function trx_addons_excerpt( $str, $maxlength, $add = '&hellip;' ) {
		if ( $maxlength <= 0 ) {
			return '';
		}
		return trx_addons_strwords( trx_addons_strip_tags( $str ), $maxlength, $add );
	}
}

// Unserialize string
if ( ! function_exists( 'trx_addons_unserialize' ) ) {
	function trx_addons_unserialize( $str ) {
		if ( ! empty( $str ) && is_serialized( $str ) ) {
			// If serialized data content unrecoverable object (base class for this object is not exists) - skip this string
			if ( true || ! preg_match( '/O:[0-9]+:"([^"]*)":[0-9]+:{/', $str, $matches ) || empty( $matches[1] ) || class_exists( $matches[1] ) ) {
				// Attempt 1: try unserialize original string
				try {
					$data = unserialize( $str );
				} catch ( Exception $e ) {
					if ( trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) ) ) {
						dcl( $e->getMessage() );
					}
					$data = false;
				}
				// Attempt 2: try unserialize original string without CR symbol '\r'
				if ( false === $data ) {
					try {
						$str2 = str_replace( "\r", "", $str );
						$data = unserialize( $str2 );
					} catch ( Exception $e ) {
						if ( trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) ) ) {
							dcl( $e->getMessage() );
						}
						$data = false;
					}
				}
				// Attempt 3: try unserialize original string with modified character counters
				if ( false === $data ) {
					try {
						$str3 = preg_replace_callback(
								'!s:(\d+):"(.*?)";!',
								function( $match ) {
									return ( strlen( $match[2] ) == $match[1] )
										? $match[0]
										: 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
								},
								$str
							);
						$data = unserialize( $str3 );
					} catch ( Exception $e ) {
						if ( trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) ) ) {
							dcl( $e->getMessage() );
						}
						$data = false;
					}
				}
				// Attempt 4: try unserialize original string without CR symbol '\r' with modified character counters
				if ( false === $data ) {
					try {
						$str3 = preg_replace_callback(
								'!s:(\d+):"(.*?)";!',
								function( $match ) {
									return ( strlen( $match[2] ) == $match[1] )
										? $match[0]
										: 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
								},
								$str2
							);
						$data = unserialize( $str3 );
					} catch ( Exception $e ) {
						if ( trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) ) ) {
							dcl( $e->getMessage() );
						}
						$data = false;
					}
				}
				return $data;
			} else {
				return $str;
			}
		} else {
			return $str;
		}
	}
}

// Replace URLs in the string (array, object)
if ( ! function_exists('trx_addons_url_replace') ) {
	function trx_addons_url_replace($from, $to, $str) {
		if ( substr($from, -1) == '/' ) {
			$from = substr($from, 0, strlen($from)-1);
		}
		if ( substr($to, -1) == '/' ) {
			$to = substr($to, 0, strlen($to)-1);
		}
		$from_clear = trx_addons_remove_protocol($from, true);
		$to_clear = trx_addons_remove_protocol($to, true);
		return trx_addons_str_replace(
					array(
/* 1 */					urlencode("http://{$from_clear}"),						// http%3A%2F%2Fdemo.domain%2Furl
/* 2 */					urlencode("https://{$from_clear}"),						// https%3A%2F%2Fdemo.domain%2Furl
/* 3 */					urlencode($from),										// protocol%3A%2F%2Fdemo.domain%2Furl
/* 4 */					urlencode("//{$from_clear}"),							// %2F%2Fdemo.domain%2Furl
/* 5 */					"http://{$from_clear}",									// http://demo.domain/url
/* 6 */					str_replace('/', '\\/', "http://{$from_clear}"),		// http:\/\/demo.domain\/url
/* 7 */					"https://{$from_clear}",								// https://demo.domain/url
/* 8 */					str_replace('/', '\\/', "https://{$from_clear}"),		// https:\/\/demo.domain\/url
/* 9 */					$from,													// protocol://demo.domain/url
/* 10 */				str_replace('/', '\\/', $from),							// protocol:\/\/demo.domain\/url
/* 11 */				"//{$from_clear}",										// //demo.domain/url
/* 12 */				str_replace('/', '\\/', "//{$from_clear}"),				// \/\/demo.domain\/url
/* 13 */				$from_clear,											// demo.domain/url
/* 14 */				str_replace('/', '\\/', $from_clear)					// demo.domain\/url
						),
					array(
/* 1 */					urlencode(trx_addons_get_protocol() . "://{$to_clear}"),
/* 2 */					urlencode(trx_addons_get_protocol() . "://{$to_clear}"),
/* 3 */					urlencode($to),
/* 4 */					urlencode("//{$to_clear}"),
/* 5 */					trx_addons_get_protocol() . "://{$to_clear}",
/* 6 */					str_replace('/', '\\/', trx_addons_get_protocol() . "://{$to_clear}"),
/* 7 */					trx_addons_get_protocol() . "://{$to_clear}",
/* 8 */					str_replace('/', '\\/', trx_addons_get_protocol() . "://{$to_clear}"),
/* 9 */					$to,
/* 10 */				str_replace('/', '\\/', $to),
/* 11 */				"//{$to_clear}",
/* 12 */				str_replace('/', '\\/', "//{$to_clear}"),
/* 13 */				$to_clear,
/* 14 */				str_replace('/', '\\/', $to_clear)
						),
					$str
				);
	}
}

// Replace macros in the string
if ( ! function_exists('trx_addons_prepare_macros') ) {
	function trx_addons_prepare_macros($str) {
		$str = str_replace(
			array("{{",  "}}",   "((",  "))",   "||"),
			array("<i>", "</i>", "<b>", "</b>", "<br>"),
			$str);
		$str = preg_replace('/(\^(\d+))/', '<sup>$2</sup>', $str);
		return $str;
	}
}

// Remove macros from the string
if ( ! function_exists('trx_addons_remove_macros') ) {
	function trx_addons_remove_macros($str) {
		return str_replace(
			array("{{", "}}", "((", "))", "||", "^"),
			array("",   "",   "",   "",   " ",  ""),
			$str);
	}
}

// Prepare string to use as telephone link
if ( ! function_exists('trx_addons_get_phone_link') ) {
	function trx_addons_get_phone_link($str) {
		return 'tel:'.str_replace(array(' ', '-', '(', ')', '.', ','), '', $str);
	}
}

// Return initials from string
if ( ! function_exists('trx_addons_get_initials') ) {
	function trx_addons_get_initials($str) {
		$initials = '';
		$str_array = explode(' ', $str);
		if (count($str_array) > 0) {
			$initials = empty($str_array[0][0]) ? '' : $str_array[0][0];
			$initials .= empty($str_array[1][0]) ? '' : $str_array[1][0];
		}
		return $initials;
	}
}

// Output string with the html layout (if not empty)
// (put it between 'before' and 'after' tags)
// Attention! This string may contain layout formed in any plugin (widgets or shortcodes output) and not require escaping to prevent damage!
if ( ! function_exists('trx_addons_show_layout') ) {
	function trx_addons_show_layout($str, $before='', $after='') {
		if (trim($str) != '') {
			printf("%s%s%s", $before, $str, $after);
		}
	}
}

// Output value as email or phone or plain text
if ( ! function_exists('trx_addons_show_value') ) {
	function trx_addons_show_value($val, $type) {
		if (in_array($type, array('email', 'phone'))) {
			$val = str_replace(',', '|', $val);
		}
		$val = explode('|', $val);
		foreach($val as $item) {
			$item = trim($item);
			if (empty($item)) continue;
			if ($type == 'email') {
				?><a href="<?php printf('mailto:%s', antispambot($item)); ?>"><?php echo antispambot($item); ?></a><?php
			} elseif ($type == 'phone') {
				?><a href="<?php trx_addons_show_layout(trx_addons_get_phone_link($item)); ?>"><?php echo esc_html($item); ?></a><?php
			} else {
				echo (count($val) > 1 ? '<span>' : '') . esc_html($item) . (count($val) > 1 ? '</span>' : '');
			}
		}
	}
}


/* Templates manipulations
----------------------------------------------------------------------------------------------------- */

// Return template part as string
if ( ! function_exists( 'trx_addons_get_template_part_as_string' ) ) {
	function trx_addons_get_template_part_as_string($file, $args_name, $args=array(), $cb='') {
		$output = '';
		ob_start();
		trx_addons_get_template_part($file, $args_name, $args, $cb);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}	
}

// Include part of template with specified parameters
if ( ! function_exists('trx_addons_get_template_part') ) {	
	function trx_addons_get_template_part($file, $args_name='', $args=array(), $cb='') {
		static $fdirs = array();
		if (!is_array($file)) {
			$file = array($file);
		}
		foreach ($file as $f) {
            if (empty($fdirs[$f])) {
                $fdirs[$f] = !empty($cb) ? $cb($f) : trx_addons_get_file_dir($f);
            }
            if (!empty($fdirs[$f])) {
				if (!empty($args_name) && !empty($args)) {
					set_query_var($args_name, apply_filters( 'trx_addons_filter_template_part_args', $args, $args_name, $file ) );
				}
				include $fdirs[$f];
				break;
			}
		}
	}
}


// Add dynamic CSS and return class for it
if ( ! function_exists('trx_addons_add_inline_css_class') ) {
	function trx_addons_add_inline_css_class($css, $suffix='', $prefix='') {
		$class_name = trx_addons_generate_id( 'trx_addons_inline_' );
		trx_addons_add_inline_css(
			sprintf(
				'%s.%s%s{%s}',
				$prefix,
				$class_name,
				! empty($suffix) 
					? (substr($suffix, 0, 1) != ':' ? ' ' : '') . str_replace(',', ",.{$class_name} ", $suffix)
					: '',
				$css
			)
		);
		return $class_name;
	}
}

// Add dynamic CSS to insert it to the footer
if ( ! function_exists('trx_addons_add_inline_css') ) {
	function trx_addons_add_inline_css($css) {
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['inline_css'] = ( ! empty($TRX_ADDONS_STORAGE['inline_css']) ? $TRX_ADDONS_STORAGE['inline_css'] : '' ) . $css;
	}
}

// Return dynamic CSS to insert it to the footer
if ( ! function_exists('trx_addons_get_inline_css') ) {
	function trx_addons_get_inline_css($clear=false) {
		global $TRX_ADDONS_STORAGE;
		$rez = '';
        if (!empty($TRX_ADDONS_STORAGE['inline_css'])) {
        	$rez = $TRX_ADDONS_STORAGE['inline_css'];
        	if ($clear) $TRX_ADDONS_STORAGE['inline_css'] = '';
        }
        return $rez;
	}
}

// Add dynamic HTML to insert it to the footer
if ( ! function_exists('trx_addons_add_inline_html') ) {
	function trx_addons_add_inline_html($html) {
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['inline_html'] = (!empty($TRX_ADDONS_STORAGE['inline_html']) ? $TRX_ADDONS_STORAGE['inline_html'] : '')
											. $html;
	}
}

// Return dynamic HTML to insert it to the footer
if ( ! function_exists('trx_addons_get_inline_html') ) {
	function trx_addons_get_inline_html() {
		global $TRX_ADDONS_STORAGE;
		return !empty($TRX_ADDONS_STORAGE['inline_html']) ? $TRX_ADDONS_STORAGE['inline_html'] : '';
	}
}

// Set a new dynamic HTML to insert it to the footer
if ( ! function_exists('trx_addons_set_inline_html') ) {
	function trx_addons_set_inline_html( $html ) {
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['inline_html'] = $html;
	}
}
