<?php
/**
 * @name locales.class.php Service de conversion des données de langues
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Helpers\String
 * @version 1.0
**/

namespace wp\Helpers\String;

class locales {
	
	public static final function toLocale($data){
		$localeParts = array();
		
		if(strlen($data >= 2)){
			if(preg_match('#[^a-zA-Z-]#', $data)){
				$data = strtoupper($data); // Tout convertir en majuscule
				// Y-a-t-il un tiret
				if(strpos($data,"-") !== false){
					$localeParts = explode("-",$data);
					if(sizeof($localeParts >= 2)){
						return strtolower($localeParts[0]) . "-" . strtoupper($localeParts[1]);
					} else {
						return strtolower($localeParts[0]) . "-" . strtoupper($localeParts[0]);
					}
				} else {
					return strtolower($data) . "-" . strtoupper($data);
				}
			}
		}
		return;
	}
}