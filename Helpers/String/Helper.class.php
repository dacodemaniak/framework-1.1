<?php
/**
 * @name Helper.class.php Services de gestion des chaînes de caractères
 * @author web-Projet.com (contact@web-projet.com) - Mar 2017
 * @package wp\Helpers\String
 * @version 1.0
 */
namespace wp\Helpers\String;

class Helper {
	private static $replacements = array(
      "à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","å"=>"a", 
      "ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o",
      "è"=>"e","é"=>"e","ê"=>"e","ë"=>"e", 
      "ì"=>"i","í"=>"i","î"=>"i","ï"=>"i", 
      "ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u", 
      "ÿ"=>"y", 
      "ñ"=>"n", 
      "ç"=>"c", 
      "ø"=>"o",
      "À" => "A","Á"=>"A","Â" => "A","Ã" => "A","Ä" => "A","Å" => "A",
      "Ò"  => "O","Ó"  => "O","Ô"  => "O","Õ"  => "O","Ö"  => "O","Ø" => "O",
      "È" => "E", "É" => "E","Ê" => "E","Ë" => "E",
      "Ç" => "C",
      "Ì" => "I","Í" => "I","Î" => "I","Ï" => "I",
      "Ù" => "U","Ú" => "U","Û" => "U","Ü" => "U",
      "Ñ" => "N",            
      " " => "_",
      "&" => "",
      "/" => "_",
      "?" => "_",
      ":" => "_",
      "'" => "_");
	
	private static $chars = array(
		"a", "A", "à", "ä", "â", "Ä", "Â",
		"b", "B",
		"c", "C",
		"d", "D",
		"e", "E", "é", "è", "ë", "ê", "Ë", "Ê",
		"f", "F",
		"g", "G",
		"h", "H",
		"i", "I", "ï", "î", "Ï", "Î",
		"j", "J",
		"k", "K",
		"l", "L",
		"m", "M",
		"n", "N",
		"o", "O", "ô", "ö", "Ö", "Ô",
		"p", "P",
		"q", "Q",
		"r", "R",
		"s", "S",
		"t", "T",
		"u", "U", "û", "Û", "ü", "Ü",
		"v", "V",
		"w", "W",
		"x", "X",
		"y", "Y",
		"z", "Z",
		"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
		"/", "|", "?", "*", ".", ":", "+", "-", "_", ")", "("
	);
	
	private static $specialChars = array(
		"_" => ""
	);
	
	/**
	 * Retourne une chaîne "nettoyée" des caractères non autorisés
	 * @param string $string
	 * @return string
	 */
	public static function replaceChars($string){
		return strtr($string,self::$replacements);
	}
	
	/**
	 * Retourne une chaîne sans les caractères spéciaux inutiles
	 * @param unknown $string
	 * @return string
	 */
	public static function escapeSpecialChars($string) {
		return strtr($string,self::$specialChars);
	}
	
	public static function escapeTabsAndCRLF(string $string){
		$formating = array(
			"\t" => "",
			"\n" => "",
			"\r" => "",
			" " => "",
			"\\" => ""
		);
		
		return strtr($string,$formating);
	}
	
	public static function camelToArray(string $camelString){
		return preg_split('/([[:upper:]][[:lower:]]+)/', $camelString, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
	}
	
	/**
	 * Convertit une chaîne avec un underline en chaîne camelCase
	 * @param string $underlineString
	 * @return string
	 */
	public static function toCamelCase(string $underlineString): string {
		$underlineString = strtolower($underlineString);
		
		if (strpos($underlineString, "_") !== false) {
			$parts = explode("_", $underlineString);
			for ($i = 1; $i < count($parts); $i++) {
				$parts[$i] = ucfirst($parts[$i]);
			}
			return implode("", $parts);
		}
		return $underlineString;
	}
	
	/**
	 * Retourne une chaîne convertie en minuscule
	 * @param string $string
	 * @return string
	 */
	public static function toLower(string $string){
		return strtolower($string);
	}
	
	/**
	 * Retourne un hash md5 avec un sel et un mot de passe
	 * @param string $src
	 * @param string $salt
	 * @return string
	 */
	public static function hash(string $src, string $salt){
		return md5($salt.$src.$salt);
	}
	
	public static function makeSalt(int $length=8){
		$salt = "";
		$previous = "";
		$current = "";
		
		for($i = 0; $i < $length; $i++){
			$choice = rand(0, count(self::$chars) - 1);
			$current = self::$chars[$choice];
			$salt .= $current;
		}
		
		return $salt;
	}
}