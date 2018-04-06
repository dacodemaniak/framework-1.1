<?php
/**
 * @name association.class.php Service de définition d'une table d'association entre deux tables
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Database\Mapper
 * @version 1.0
 */
namespace wp\Database\Mapper;

class association {
	/**
	 * Instance du Mapper sur la table d'association
	 * @var Mapper
	 */
	private $associationStore;
	
	/**
	 * Instance du Mapper parent principal
	 * @var Mapper
	 */
	private $mainParentStore;
	
	/**
	 * Instance du Mapper parent secondaire
	 * @var unknown
	 */
	private $secondaryParentStore;

	/**
	 * Type de jointure
	 * @var int
	 * 	- 0 : INNER (default)
	 * 	- 1 : LEFT
	 * 	- 2 RIGHT

	 */
	private $joinType = 0;
	
	/**
	 * Définit ou retourne le Mapper sur la table d'association
	 * @param Mapper $store
	 */
	public function associationStore($store=null){
		if(!is_null($store)){
			$this->associationStore = $store;
			return $this;
		}
		return $this->associationStore;
	}
	
	/**
	 * Définit ou retourne le Mapper sur la table parente primaire
	 * @param Mapper $store
	 */
	public function mainStore($store=null){
		if(!is_null($store)){
			$this->mainParentStore = $store;
			return $this;
		}
		return $this->mainParentStore;
	}
	
	/**
	 * Définit le type de jointure de l'association
	 * @param int $type
	 * @return \wp\Database\Mapper\association
	 */
	public function setJoinType(int $type){
		$this->joinType = $type;
		return $this;
	}
	
	
	/**
	 * Définit ou retourne le Mapper sur la table principale secondaire
	 * @param Mapper $store
	 */
	public function secondaryStore($store=null){
		if(!is_null($store)){
			$this->secondaryParentStore = $store;
			return $this;
		}
		return $this->secondaryParentStore;
	}
	
	/**
	 * Définit les relations et retourne la chaîne SQL associée
	 */
	public function setAssociation(){
		if($this->joinType === 0){
			$join = "INNER";
		} else if($this->joinType === 1){
			$join = "LEFT";
		} else {
			$join = "RIGHT";
		}
		
		$jointure = " FROM " . $this->mainParentStore->getShortName();
		$jointure .= " " . $join ." JOIN " . $this->associationStore->getShortName();
		$jointure .= " ON " . $this->mainParentStore->getShortName() . ".id = " . $this->associationStore->getShortName() . "." . $this->mainParentStore->getTableName() . "_id";
		$jointure .= " " . $join ." JOIN " . $this->secondaryParentStore->getShortName();
		$jointure .= " ON " . $this->associationStore->getShortName() . "." . $this->secondaryParentStore->getTableName() . "_id = " . $this->secondaryParentStore->getShortName() . ".id";
		
		return $jointure;
	}
}