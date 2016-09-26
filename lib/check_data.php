<?php
class CheckData{
	/**
	 * Käytetään kun tietokannasta on luettu arvo ja se halutaan näyttää HTML:n seassa
	 * Ei käytetä tietokantaan vietäessä
	 * 
	 * Idea otettu joskus aikanaan stackoverflow foorumilta.
	 * Tarkka lähde ei ole tiedossa
	 */
	public static function character_escape($value){
		$new_value = '';
		$allowed = array('á','Á','à','À','â','Â','å','Å','ã','Ã','ä','Ä','æ','Æ','ç',
				'Ç','é','É','è','È','ê','Ê','ë','Ë','í','Í','ì','Ì','î','Î',
				'ï','Ï','ñ','Ñ','ó','Ó','ò','Ò','ô','Ô','ø','Ø','õ','Õ','ö',
				'Ö','ß','ú','Ú','ù','Ù','û','Û','ü','Ü','ÿ');
		$chars = preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY);
		foreach($chars as $char){
			if(in_array($char, $allowed)){
				$new_value .= $char;
			}else{
				$new_value .= htmlentities($char, ENT_QUOTES, "UTF-8");
			}
		}
		$new_value = str_replace('\\', '&#92;', $new_value);
		return $new_value;
	}
	public static function text_to_float($string){
        $string = $string."";
        $string = str_replace(" ", "", $string);
        $string = str_replace(",", ".", $string);
        return (float)$string;
    }
	public static function float_to_currency($value){
        $decimals = 2;
		$parts = explode('.', ((float)$value).'');
		if(isset($parts[1])){
			$val = strlen($parts[1].'');
			if($val > 2){
				$decimals = $val;
			}
		}
		return number_format(round(CheckData::text_to_float($value), $decimals), $decimals, ',', ' ');
    }
}