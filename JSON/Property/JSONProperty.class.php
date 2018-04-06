<?php
/**
 * @name JSONProperty.class.php : Définition d'une propriété JSON pour la gestion des colonnes JSON d'une table
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\JSON\Property
 * @version 1.0
**/
namespace wp\JSON\Property;

use wp\JSON\Map\JSONMap as Mapper;

class JSONProperty {
	
	/**
	 * Nom de la propriété JSON
	 * @var string
	 */
	protected $name;
	
	/**
	 * Type de la propriété JSON
	 * @var string
	 */
	protected $type;
	
	/**
	 * Définition du mapping JSON des objets d'une propriété de type array_of_object
	 * \wp\JSON\Map\JSONMap
	 */
	protected $objectMapper;
	
	/**
	 * Définit l'IHM à utiliser pour représenter la propriété dans un formulaire
	 * @var string
	 */
	protected $humanInterface;
	
	/**
	 * Valeur du label qui sera associé à la propriété dans un formulaire
	 * @var string
	 */
	protected $label;
	
	/**
	 * Définit le nom d'une propriété JSON
	 * @param string $name
	 * @return string|\wp\JSON\Property\JSONProperty
	 */
	public function name($name=null){
		if(is_null($name)){
			return $this->name;
		}
		
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Définit ou retourne le type de la propriété JSON
	 * Les types possibles sont :
	 * object : Objet JSON
	 * array_of_data : Tableau de données
	 * array_of_object : Tableau d'objets
	 * localized_content
	 * string : chaîne de caractère,
	 * boolean : valeur booléenne
	 * @param string $type
	 * @return string|\wp\JSON\Property\JSONProperty
	 */
	public function type($type=null){
		if(is_null($type)){
			return $this->type;
		}
		
		$this->type = $type;
		return $this;
	}
	
	/**
	 * Définit le Mapper JSON d'une propriété de type array_of_object
	 * @param Mapper $mapper
	 * @return \wp\JSON\Map\JSONMap|\wp\JSON\Property\JSONProperty
	 */
	public function objectMapper(Mapper $mapper=null){
		if(is_null($mapper)){
			return $this->objectMapper;
		}
		
		$this->objectMapper = $mapper;
		return $this;
	}
	
	/**
	 * Définit ou retourne la forme "humaine" de la propriété courante
	 * @param string $ihm
	 * @return string|\wp\JSON\Property\JSONProperty
	 */
	public function humanInterface($ihm=null){
		if(is_null($ihm)){
			return $this->humanInterface;
		}
		
		$this->humanInterface = $ihm;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne le label associé à la propriété dans un formulaire
	 * @param unknown $label
	 * @return string|\wp\JSON\Property\JSONProperty
	 */
	public function label($label=null){
		if(is_null($label)){
			return $this->label;
		}
		
		$this->label = $label;
		return $this;
	}
}