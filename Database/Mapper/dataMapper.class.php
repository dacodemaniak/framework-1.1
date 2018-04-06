<?php
/**
 * @name dataMapper.class.php Définition d'un élément de stockage de données
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Mapper
 * @version 1.0
 */
namespace wp\Database\Mapper;

use wp\Database\Mapper\dataStoreMapper as Store;
use wp\JSON\Map\JSONMap as JSONMap;

class dataMapper {
	/**
	 * Objet de collection à alimenter
	 * @var Store
	 */
	private $store;
	
	/**
	 * Nom de l'élément de stockage
	 * @var string
	 */
	private $name;
	
	/**
	 * Alias de l'élément de stockage
	 * @var string
	 */
	private $alias;
	
	/**
	 * Type de l'élément de stockage
	 * @var string
	 */
	private $type;
	
	/**
	 * Taille maximale de l'élément de stockage
	 * @var int
	 */
	private $maxLength;
	
	/**
	 * Valeur par défaut de l'élément de stockage
	 * @var multitype
	 */
	private $default;
	
	/**
	 * Valeur courante
	 * @var multitype
	 */
	private $value = null;
	
	/**
	 * Valeur sur laquelle effectuer une recherche
	 * @var multitype
	 */
	private $searchValue;
	
	/**
	 * Détermine si l'élément est une clé primaire
	 * @var boolean
	 */
	private $primary;
	
	/**
	 * Tableau de stockage des clés étrangères et des collections concernées
	 * @var array
	 */
	private $foreignKeys;
	
	/**
	 * Définit le mapping JSON d'une colonne de ce type
	 * @var object
	 */
	private $JSONMap;
	
	/**
	 * Instancie un objet de mapping de données
	 * @param Store $store
	 */
	public function __construct(Store $store){
		$this->store = $store;
	}
	
	/**
	 * Définit ou retourne le nom de l'élément
	 * @param string $name
	 */
	public function name($name=null){
		if(is_null($name)){
			return $this->name;
		}
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Retourne le nom de la données à récupérer
	 * @return string
	 */
	public function getName(){
		$name					= "";
		
		if(!is_null($this->store->alias())){
			$name = $this->store->alias() . "." . $this->name;
		} else {
			$name = $this->store->name() . "." . $this->name;
		}
		
		if(!is_null($this->alias)){
			return $name . " as " . $this->alias;
		}
		
		return $name;
	}
	
	/**
	 * Retourne le nom de la colonne dans le select (alias, nom, nom qualifié)
	 */
	public function selectName(){
		if(!is_null($this->alias)){
			return $this->alias;
		}
		
		if(!is_null($this->store->alias())){
			return $this->store->alias() . "." . $this->name;
		} else {
			return $this->store->name() . "." . $this->name;
		}		
	}
	
	/**
	 * Retourne le nom qualifié d'un élément de stockage
	 * @return string
	 */
	public function getQualifiedName(){
		$name					= "";
		
		if(!is_null($this->store->alias())){
			return $this->store->alias() . "." . $this->name;
		} else {
			return $this->store->name() . "." . $this->name;
		}		
	}
	
	/**
	 * Retourne la chaîne de remplacement pour les requêtes préparées
	 * @return string
	 */
	public function placeholder(){
		if(!is_null($this->store->alias())){
			return ":" . $this->store->alias() . "_" . $this->name;
		}
		return ":" . $this->store->name() . "_" . $this->name;		
	}
	
	/**
	 * Définit ou retourne l'alias de l'élément de stockage
	 * @param unknown $alias
	 * @return string|\wp\Database\Mapper\dataMapper
	 */
	public function alias($alias=null){
		if(is_null($alias)){
			return $this->alias;
		}
		$this->alias = $alias;
		return $this;
	}
	
	/**
	 * Définit ou retourne le type de l'élément de stockage
	 * @param string $type
	 */
	public function type($type=null){
		if(is_null($type)){
			return $this->type;
		}
		$this->type = $type;
		return $this;
	}

	public function maxLength($length=null){
		if(is_null($length)){
			return $this->maxLength;
		}
		$this->maxLength = $length;
		return $this;
	}
	
	/**
	 * Définit la valeur par défaut de l'élément
	 * @param multitype $default
	 */
	public function defaut($default=null){
		if(is_null($default)){
			return $this->default;
		}
		$this->default = $default;
		return $this;
	}
	
	/**
	 * Définit la valeur de l'élément de stockage
	 * @param multitype $value
	 */
	public function value($value=null){
		if(is_null($value)){
			return $this->value;
		}
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut de clé primaire de l'élément
	 * @todo Contrôler dans la collection si une clé primaire n'existe pas déjà
	 * @param boolean $primary
	 */
	public function primary($primary=null){
		if(is_null($primary)){
			return $this->primary;
		}
		$this->primary = (bool) $primary;
		return $this;
	}
	
	/**
	 * Associe une clé étrangère à un store parent
	 * @param Store $parentStore
	 */
	public function foreignKey(Store $parentStore){
		$this->foreignKeys[] = array("foreign" => $this, "parentStore" => $parentStore);
		return $this;
	}
	
	/**
	 * Définit la valeur sur laquelle effectuer une recherche
	 * @param multitype $value
	 */
	public function searchValue($value=null){
		if(is_null($value)){
			return $this->searchValue;
		}
		$this->searchValue = $value;
		return $this;
	}
	
	/**
	 * Réinitialise à nul les valeurs et les valeurs de recherche
	 */
	public function resetValue(){
		$this->searchValue = null;
		$this->value = null;
	}
	
	/**
	 * Ajoute une définition de la colonne JSON d'une table
	 * @param JSONMap $map
	 * @return object|\wp\Database\Mapper\dataMapper
	 */
	public function JSONMap(JSONMap $map=null){
		if(is_null($map)){
			return $this->JSONMap;
		}
		$this->JSONMap = $map;
		return $this;
	}
	
	/**
	 * Ajoute l'élément de stockage à la collection
	 */
	public function hydrate(){
		$this->store->hydrate($this);
	}
}