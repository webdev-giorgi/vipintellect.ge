<?php 
namespace functions; 

class strings
{
	public function cut($text,$number)
	{
		$charset = 'UTF-8';
		$length = $number;
		$string = strip_tags($text);
		if(mb_strlen($string, $charset) > $length) {
			$string = mb_substr($string, 0, $length, $charset) . '...';
		}
		else
		{
			$string=$text;
		}
		return $string; 
	}

	public static function cutstatic($text,$number)
	{
		$charset = 'UTF-8';
		$length = $number;
		$string = strip_tags($text);
		if(mb_strlen($string, $charset) > $length) {
			$string = mb_substr($string, 0, $length, $charset) . '...';
		}
		else
		{
			$string=$text;
		}
		return $string; 
	}

	public static function random($length)
	{
		$bytes = openssl_random_pseudo_bytes($length * 2);
		return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
	}

	public static function escapeJavaScriptText($string){
		$string = strip_tags($string); 
		return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
	}

	public static function utf82lat($string="")
	{
		$utf8 = array("ა", "ბ", "გ", "დ", "ე", "ვ", "ზ", "თ", "ი", "კ", "ლ", "მ", "ნ", "ო", "პ", "ჟ", "რ", "ს", "ტ", "უ", "ფ", "ქ", "ღ", "ყ", "შ", "ჩ", "ც", "ძ", "წ", "ჭ", "ხ", "ჯ", "ჰ", );
		$lat  = array("a", "b", "g", "d", "e", "v", "z", "t", "i", "k", "l", "m", "n", "o", "p", "j", "r", "s", "t", "u", "f", "q", "gh", "k", "sh", "ch", "ts", "dz", "ts", "ch", "kh", "dj", "h", );
		$out = str_replace($utf8, $lat, $string);
		return $out;
	}
}