<?php
/**
* @name ClientCoords.class.php Détermine les coordonnées géographique du client
* @author web-Projet.com (contact@web-projet.com) - Mai 2017
* @package wp\Spatial
* @version 1.0
**/
namespace wp\Spatial;

use \wp\Utilities\Client\ClientInfo as ClientInfo;

class ClientCoords {
	
	/**
	 * Données du client
	 * @var ClientInfo
	 */
	private $clientInfo;
	
	/**
	 * Constructeur ClientCoords
	 * @param void
	 * @return void
	 */
	public function __construct(){
		$this->clientInfo = new ClientInfo();
	}
	
	/**
	 * Retourne la latitude à partir des informations clientes
	 * @return string
	 */
	public function getLat(){
		return $this->clientInfo->latitude;
	}
	
	/**
	 * Retourne la longitude à partir des informations clientes
	 * @return string
	 */
	public function getLong(){
		return $this->clientInfo->longitude;
	}
}