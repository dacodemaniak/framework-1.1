<?php
/**
* @name ClientInfo.class.php Service de récupération des données clientes
* @author web-Projet.com (contact@web-projet.com) - Mai 2017
* @package wp\Utilities\Client
* @version 1.0
**/
namespace wp\Utilities\Client;

class ClientInfo {
	/**
	 * Adresse IP du client
	 * @var string
	 */
	private $ipAddress;
	
	/**
	 * Fournisseur des données géographiques
	 * @var string
	 */
	private $provider = "https://freegeoip.net/json/";
	
	/**
	 * Objet JSON contenant les données de géolocalisation
	 * @var JSON
	 */
	private $geoLocation;
	
	/**
	 * Constructeur ClientInfo
	 * @param void
	 * @return void
	**/
	public function __construct(){
		$this->ipAddress = $this->getIpAddress();
		
		#begin_debug
		#echo "IP Address : " . $this->ipAddress . "<br />\n";
		#end_debug
		
		if($this->ipAddress != "UNKNOWN"){
			$this->process();
		}
	}
	
	/**
	 * Retourne une donnée de géolocalisation à partir des données du provider
	 * @param string $attributeName
	 * @return null|string
	 */
	public function __get($attributeName){
		if(!is_null($this->geoLocation)){
			if(property_exists($this->geoLocation, $attributeName)){
				return $this->geoLocation->{$attributeName};
			}
		}
	}
	
	/**
	 * Traite l'appel à l'API du provider
	 */
	private function process(){
		$this->geoLocation =  json_decode(file_get_contents($this->provider . $this->ipAddress));
	}
	
	/**
	 * Détermine l'adresse IP du client
	 * private string getIpAddress(void)
	 * @return string
	 */
	private function getIpAddress(){
		$ipAddress = "UNKNOWN";
		
		if(array_key_exists("HTTP_CLIENT_IP", $_SERVER)){
			$ipAddress = $_SERVER["HTTP_CLIENT_IP"];
		}
	
		if(array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)){
			$ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
	
		if(array_key_exists("HTTP_X_FORWARDED", $_SERVER)){
			$ipAddress = $_SERVER["HTTP_X_FORWARDED"];
		}
	
		if(array_key_exists("HTTP_FORWARDED_FOR", $_SERVER)){
			$ipAddress = $_SERVER["HTTP_FORWARDED_FOR"];
		}
	
		if(array_key_exists("HTTP_FORWARDED", $_SERVER)){
			$ipAddress = $_SERVER["HTTP_FORWARDED"];
		}
	
		if(array_key_exists("REMOTE_ADDR", $_SERVER)){
			$ipAddress = $_SERVER["REMOTE_ADDR"];
		}
		
		if($ipAddress == $_SERVER["SERVER_NAME"]){
			// Appelle un provider pour déterminer l'adresse IP publique
/* 			$externalContent = file_get_contents('http://checkip.dyndns.com/');
			preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $matches);
			$ipAddress = $matches[1]; */
			$ipAddress = "78.203.249.184"; // IP sortante en mode local
		}
		
		return $ipAddress;
	}
	
	/**
	 * Retourne l'état de l'hôte, vrai s'il s'agit d'une adresse IP
	 * @return boolean
	 */
	private function isIPHost(){
		if($_SERVER["SERVER_NAME"] == "127.0.0.1" || $_SERVER["SERVER_NAME"]== "localhost" || $_SERVER["SERVER_NAME"] == $_SERVER["HTTP_CLIENT_IP"]){
			return true;
		}
		return (bool) ip2long($this->host);
	}
}