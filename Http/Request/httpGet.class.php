<?php
/**
 * @name httpGet.class.php Service de gestion des données de type GET
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Http\Request
 * @version 1.0
**/
namespace wp\Http\Request;

class httpGet implements \wp\Http\Request\httpRequest {
	/**
	 * Nom de la données (clé du tableau $_GET)
	 * @var string
	 */
	private $name;
	
	/**
	 * Valeur associée à la clé du tableau $_GET
	 * @var string
	 */
	private $value;
	
	/**
	 * Définit ou retourne le nom de la données du tableau $_GET
	 * @param string $name
	 * @return \wp\Http\Request\httpGet|string
	 */
	public function name($name=null){
		if(!is_null($name)){
			$this->name = $name;
			return $this;
		}
		return $this->name;
	}
	
	/**
	 * Définit ou retourne la valeur de la données de requête $_GET
	 * @param string $value
	 * @return \wp\Http\Request\httpGet|string
	 */
	public function value($value=null){
		if(!is_null($value)){
			$this->value = $value;
			return $this;
		}
		return $this->value;
	}
}