<?php
/**
 * @name languages.class.php Service de gestion des langues de l'application
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Locales
 * @version 1.0
**/
namespace wp\Locales;

use \wp\Locales\langue as Language;
use \wp\Http\Request\request as Request;

class languages {
	/**
	 * Collection des langues utilisées sur le site
	 * @var array
	 */
	private $collection;
	
	/**
	 * Définit la langue par défaut définie dans la requête HTTP
	 * @var string
	 */
	private $requestLanguage;
	
	/**
	 * Instancie un nouvel objet de gestion des langues
	 */
	public function __construct(Request $request){
		$this->collection = array();
		
		// Parcours les données de requête à la recherche d'un indicateur de langue
		$this->requestLanguage = $this->find($request->getRequestData());
	}
	
	/**
	 * Ajoute une langue à la pile de langue
	 * @param Language $langue
	**/
	public function add(Language $langue){
		if(sizeof($this->collection)){
			foreach($this->collection as $language){
				if($language->iso() == $langue->iso()){
					return false;
				}
			}
		}
		$this->collection[] = $langue;
	}
	
	/**
	 * Retourne la langue courante à traiter
	**/
	public function currentLanguage(){
		if(!is_null($this->requestLanguage)){
			return $this->requestLanguage;
		}
		foreach ($this->collection as $language){
			if($language->isDefault()){
				return $language->iso();
			}
		}
		return "fr-FR"; // Langue par défaut en français, soyons fou
	}
	/**
	 * Tente de trouver un indicateur de langue dans la requête HTTP
	 * @param \wp\Http\Request\requestData $requestData
	 */
	private function find($requestData){
		if($requestData->count("total") > 0){
			if($requestData->langue != null){
				return \wp\Helpers\Sring\locales::toLocale($requestData->langue);
			}
			if($requestData->lng != null){
				return \wp\Helpers\Sring\locales::toLocale($requestData->lng);
			}
			if($requestData->locale != null){
				return \wp\Helpers\Sring\locales::toLocale($requestData->locale);
			}
		}
		return;
	}
}