<?php
/**
* @name Option.class.php : Options pour les requêtes MongoDB
* @author web-Projet.com (contact@web-projet.com) Juil. 2017
* @package wp\Database\MongoDB\Option
* @version 1.0
**/
namespace wp\Database\MongoDB\Option;

class Option {
	
	/**
	 * Stocke les filtres définis
	 * @var array
	 */
	private $options = [];
	
	/**
	 * Ajoute l'option "limit" avec le nombre de lignes maximum à retourner
	 * @param int $nbRows
	 */
	public function limitRows($nbRows){
		$this->options["limit"] = $nbRows;
	}
	
	public function getOptions(){
		return $this->options;
	}
}