<?php
/**
* @name ManyToMany.class.php Service de gestion de relations n,n entre Entités
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.0.1
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\Entity as Entity;
use \wp\Database\Entities\Columns\Column;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\SQL\Select;
use \wp\Database\Query\Get;

abstract class ManyToMany extends Entity {
	
	/**
	 * Entités parentes de la relation courante
	 * @var array
	 */
	protected $parentEntities = [];
	
	/**
	 * Ajoute les instances d'entités parentes dans la relation
	 * @param void
	 * @return void
	 */
	protected function setParentEntities(){
		$entities = $this->_parseName();

		for ($i = 0; $i < count($entities); $i++){
			if (($column = $this->columns->findByValue("parentEntity", ucfirst($entities[$i]))) !== false){
				$entityClass = $column->ns() . "\\" . ucfirst($entities[$i]) . "Entity";
				//$this->parentEntities[$entities[$i]] = new $entityClass();
				$this->parentEntities[] = new $entityClass();
			}
		}
	}
	
	/**
	 * Retourne les entités parentes de l'entité courante
	 * @return array
	 */
	public function getParentEntities(): array{
		return $this->parentEntities;
	}
	
	/**
	 * Override Entity::__set() Permet de définir la valeur d'un attribut d'une des entités
	 * {@inheritDoc}
	 * @see \wp\Database\Entities\Entity::__set()
	 */
	public function __set(string $attributeName, $value): bool {
		$attributeParts = explode("_", $attributeName);
		
		if(count($attributeParts) == 2){
			foreach ($this->parentEntities as $entity){
				if($entity->name() === $attributeParts[0]){
					return $entity->{$attributeParts[1]} = $value;
				}
			}
			//return $this->parentEntities[$attributeParts[0]]->{$attributeParts[1]} = $value;
		}
		
		return parent::__set($attributeName, $value);
	}
	
	/**
	 * Parse le nom de l'entité pour récupérer les noms des entités parentes
	 * @return array
	 */
	private function _parseName(): array {
		$sepPos = strpos($this->name, "to");
		
		$entities[] = substr($this->name, 0 , $sepPos);
		$entities[] = substr($this->name, $sepPos + 2, strlen($this->name));
		
		// On en profite pour définir l'alias de l'entité
		$alias = "";
		for($i = 0; $i < count($entities); $i++) {
			$alias .= substr($entities[$i], 0, 1);
		}
		
		$this->alias(strtoupper($alias));
		
		return $entities;
	}
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return \PDOStatement | false
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
		$this->query = "SELECT ";
		
		// Ajoute les colonnes des entités concernées
		$this->query .= $this->parentEntities[0]->getFullQualifiedColumns();
		$this->query .= "," . $this->parentEntities[1]->getFullQualifiedColumns();
		$this->query .= "," . $this->getFullQualifiedColumns();
		
		// Définit l'origine de la requête
		$this->query .= " FROM ";
		
		// Première entité parente
		$this->query .= $this->parentEntities[0]->getAliasedName();
		
		// Jointure avec la table d'association
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		$this->query .= " ON ";
		$this->query .= $this->parentEntities[0]->alias() . "." . $this->parentEntities[0]->getPrimaryCol();
		$this->query .= " = ";
		$this->query .= $this->alias() . "." . $this->columns->findByValue("parentEntity", ucfirst($this->parentEntities[0]->name()))->name();
		
		// Jointure avec la seconde table parente
		$this->query .= " INNER JOIN " . $this->parentEntities[1]->getAliasedName();
		$this->query .= " ON ";
		$this->query .= $this->alias() . "." . $this->columns->findByValue("parentEntity", ucfirst($this->parentEntities[1]->name()))->name();
		$this->query .= " = ";
		$this->query .= $this->parentEntities[1]->alias() . "." . $this->parentEntities[1]->getPrimaryCol();
		
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
		$this->query = "SELECT ";
		
		// Ajoute les colonnes des entités concernées
		$this->query .= $this->parentEntities[0]->getFullQualifiedColumns();
		$this->query .= "," . $this->parentEntities[1]->getFullQualifiedColumns();
		$this->query .= "," . $this->getFullQualifiedColumns();
		
		// Définit l'origine de la requête
		$this->query .= " FROM ";
		
		// Première entité parente
		$this->query .= $this->parentEntities[0]->getAliasedName();
		
		// Jointure avec la table d'association
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		$this->query .= " ON ";
		$this->query .= $this->parentEntities[0]->alias() . "." . $this->parentEntities[0]->getPrimaryCol();
		$this->query .= " = ";
		$this->query .= $this->alias() . "." . $this->columns->findByValue("parentEntity", ucfirst($this->parentEntities[0]->name()))->name();
		
		// Jointure avec la seconde table parente
		$this->query .= " INNER JOIN " . $this->parentEntities[1]->getAliasedName();
		$this->query .= " ON ";
		$this->query .= $this->alias() . "." . $this->columns->findByValue("parentEntity", ucfirst($this->parentEntities[1]->name()))->name();
		$this->query .= " = ";
		$this->query .= $this->parentEntities[1]->alias() . "." . $this->parentEntities[1]->getPrimaryCol();
		
		// Ajouter la clause WHERE le cas échéant
		$whereClause = "";
		$queryParams = [];
		foreach($this->columns as $column => $object){
			if(!is_null($object->value())){
				$whereClause .= $this->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
				$queryParams[$object->name()] = $object->value();
			}
		}
		
		foreach($this->parentEntities as $entity){
			foreach($entity->getScheme() as $column => $object){
				//echo "Valeur pour la colonne " . $column . " => " . $object->value() . "<br>";
				if(!is_null($object->value())){
					$whereClause .= $entity->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
					$queryParams[$object->name()] = $object->value();
				}
			}
		}
		
		if(strlen($whereClause)){
			$whereClause = substr($whereClause,0, strlen($whereClause) - 5);
			$this->query .= " WHERE " . $whereClause;
		}
		
		// Instancie une requête de type SELECT
		$query = Get::get();
		
		$query->SQL($this->query);
		$query->queryParams($queryParams);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
}