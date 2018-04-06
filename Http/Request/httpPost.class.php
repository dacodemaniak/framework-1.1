<?php
/**
 * @name httpPost.class.php Service de gestion des données de type POST
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Http\Request
 * @version 1.0
**/
namespace wp\Http\Request;

class httpPost implements \wp\Http\Request\httpRequest {
	/**
	 * Nom de la données (clé du tableau $_POST)
	 * @var string
	 */
	private $name;
	
	/**
	 * Valeur associée à la clé du tableau $_POST
	 * @var string
	 */
	private $value;
	
	/**
	 * Définit ou retourne le nom de la données du tableau $_POST
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
	 * Définit ou retourne la valeur de la données de requête $_POST
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