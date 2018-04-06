<?php
/**
 * @name langue.class.php Service de définition des langues de l'application
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Locales
 * @version 1.0
**/
namespace wp\Locales;

class langue {
	/**
	 * Code ISO de la langue courante
	 * @var string
	 */
	private $iso;
	
	/**
	 * Détermine s'il s'agit de la langue par défaut définie pour l'application
	 * @var boolean
	 */
	private $isDefault;
	
	/**
	 * Détermine ou retourne le code ISO de la langue courante
	 * @param optional string $iso
	 * @return string|\wp\Locales\langue
	 */
	public function iso($iso=null){
		if(is_null($iso)){
			return $this->iso;
		}
		$this->iso = $iso;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut de langue par défaut
	 * @param optional bool $default
	 */
	public function isDefault($default=null){
		if(is_null($default)){
			return $this->isDefault;
		}
		$this->isDefault = $default;
		return $this;
	}
	
	
}