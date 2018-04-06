<?php
/**
* @name ManyToMany.class.php Service de gestion de relations n,n entre Entités
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\Entity as Entity;

abstract class ManyToMany extends Entity {
	
	/**
	 * Entité principale pour la récupération des données
	 * @var \Entity
	 */
	protected $mainEntity;
	
	/**
	 * Autre entité parente
	 * @var \Entity
	 */
	protected $parentEntity;
	
	/**
	 * Définit et retourne l'entité principale de l'association
	 * @param string $mainEntity
	 * @return boolean|Entity
	 */
	protected function entity(string $entity = null){
		if(!is_null($entity)){
			if (($column = $this->columns->findByValue("parentEntity", $entity)) !== false){
				$entityClass = $column->ns() . $entity . "Entity";
				return new $entityClass();
			}
		}
		
		return false;
	}
	
	/**
	 * Retourne l'entité parente principale
	 */
	public function getMainEntity(){
		return $this->mainEntity;
	}
	
	
	public function getParentEntity(){
		return $this->parentEntity;
	}
	
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return \PDOStatement | false
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
		$entities = $this->columns->findByType("parentEntity");
		
		$this->query = "SELECT ";
		
		if(!is_null($this->mainEntity)){
			// Ajoute les colonnes de l'entité principale
			$this->query .= $this->mainEntity->getFullQualifiedColumns();
		} else {
			// Définit le nom des colonnes à partir des entités parentes
			foreach ($entities as $entity) {
				$this->query .= $entity->getFullQualifiedColumns() . ",";
			}
			$this->query = substr($this->query, 0, strlen($this->query) - 1);
		}
		
		
		// Définit l'origine de la requête
		$this->query .= " FROM ";
		
		if (!is_null($this->mainEntity)) {
			$this->query .= $this->mainEntity->getAliasedName();
		} else {
			$this->query .= $entities[0]->getAliasedName();
		}
		
		// Jointure avec l'association courante
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		
		$this->query .= " ON ";

		if (!is_null($this->mainEntity)) {
			$this->query .= $this->mainEntity->alias() . "." . $this->mainEntity->getScheme()->findByType("isPrimary")->name();
			
		} else {
			$this->query .= $entities[0]->alias() . "." . $entities[0]->getScheme()->findByType("isPrimary")->name();
		}
		
		$this->query .= " = " . $this->alias() . "." . $this->columns->findByValue("parentEntity", get_class($entities[0])->name());
		
		// Fin de la jointure
		if(!is_null($this->mainEntity)){
			$parent = null;
			// Cherche l'autre entité
			foreach($entities as $entity){
				if($entity->name() != $this->mainEntity->name()){
					$parent = $entity;
					break;
				}
			}
			$this->query .= " INNER JOIN " . $parent->getAliasedName();
			
			$this->query .= " ON " . $this->alias() . "." . $this->columns->findByValue("parentEntity", get_class($parent))->name();
			$this->query .= " = " . $parent->alias() . "." . $parent->findByType("isPrimary")->name();
			
		} else {
			$parent = $entities[1];
			$this->query .= " INNER JOIN " . $parent->getAliasedName();
			
			$this->query .= " ON " . $this->alias() . "." . $this->columns->findByValue("parentEntity", get_class($parent))->name();
			$this->query .= " = " . $parent->alias() . "." . $parent->findByType("isPrimary")->name();
		}
		
		$query = Get::get();
		
		$query->SQL($this->query);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectBy()
	 */
	public function selectBy(){
		$entities = $this->columns->findByType("parentEntity");
		
		$this->query = "SELECT ";
		
		if(!is_null($this->mainEntity)){
			// Ajoute les colonnes de l'entité principale
			$this->query .= $this->mainEntity->getFullQualifiedColumns();
		} else {
			// Définit le nom des colonnes à partir des entités parentes
			foreach ($entities as $entity) {
				$this->query .= $entity->getFullQualifiedColumns() . ",";
			}
			$this->query = substr($this->query, 0, strlen($this->query) - 1);
		}
		
		
		// Définit l'origine de la requête
		$this->query .= " FROM ";
		
		if (!is_null($this->mainEntity)) {
			$this->query .= $this->mainEntity->getAliasedName();
		} else {
			$this->query .= $entities[0]->getAliasedName();
		}
		
		// Jointure avec l'association courante
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		
		$this->query .= " ON ";
		
		if (!is_null($this->mainEntity)) {
			$this->query .= $this->mainEntity->alias() . "." . $this->mainEntity->getScheme()->findByType("isPrimary")->name();
			
		} else {
			$this->query .= $entities[0]->alias() . "." . $entities[0]->getScheme()->findByType("isPrimary")->name();
		}
		
		// Clé étrangère relative à l'entité parente courante
		$this->query .= " = " . $this->alias() . "." . $entities[0]->name();
		
		// Fin de la jointure
		if(!is_null($this->mainEntity)){
			$parent = null;
			// Cherche l'autre entité
			foreach($entities as $entity){
				if(strtolower($entity->parentEntity()) != strtolower($this->mainEntity->name())){
					$parentEntityClass = $entity->ns() . $entity->parentEntity() . "Entity";
					$parent = new $parentEntityClass();
					break;
				}
			}
			
			$this->query .= " INNER JOIN " . $parent->getAliasedName();
			
			$this->query .= " ON " . $this->alias() . "." . $this->columns->findByValue("parentEntity", UCFirst($parent->name()))->name();
			
			$this->query .= " = " . $parent->alias() . "." . $parent->getScheme()->findByType("isPrimary")->name();
			
		} else {
			$parent = $entities[1];
			$this->query .= " INNER JOIN " . $parent->getAliasedName();
			
			$this->query .= " ON " . $this->alias() . "." . $this->columns->findByValue("parentEntity", get_class($parent))->name();
			$this->query .= " = " . $parent->alias() . "." . $parent->findByType("isPrimary")->name();
		}
		
		// Ajouter la clause WHERE le cas échéant
		$whereClause = "";
		$queryParams = [];
		foreach($this->columns as $column => $object){
			if(!is_null($object->value())){
				$whereClause .= $this->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
				$queryParams[$object->name()] = $object->value();
			}
		}
		
		foreach($entities as $entity){
			$parentEntityClass = $entity->ns() . $entity->parentEntity() . "Entity";
			$parent = new $parentEntityClass();
			foreach($parent->getScheme() as $column => $object){
				echo "Valeur pour la colonne " . $column . " => " . $object->value() . "<br>";
				if(!is_null($object->value())){
					$whereClause .= $this->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
					$queryParams[$object->name()] = $object->value();
				}
			}
		}
		
		if(strlen($whereClause)){
			$whereClause = substr($whereClause,0, strlen($whereClause) - 5);
			$this->query .= " WHERE " . $whereClause;
		}
		
		echo $this->query . "<br>";
		
		// Instancie une requête de type SELECT
		$query = Get::get();
		
		$query->SQL($this->query);
		$query->queryParams($queryParams);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
}