<?php
/**
* @name ManyToOne.class.php Service de gestion de relations entre tables de type Many To One
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/
namespace wp\Database\Entities;

use \wp\Database\Entities\Columns\Column;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\SQL\Select as Select;
use \wp\Database\Query\Get as Get;
use \wp\Database\Interfaces\IEntity;

abstract class ManyToOne extends Entity implements Select {
	
	/**
	 * Instance de l'entité parente
	 * @var \wp\Database\Interfaces\IEntity
	 */
	protected $parentEntity;

	/**
	 * Override Entity::__set() Permet de définir la valeur d'un attribut d'une des entités
	 * {@inheritDoc}
	 * @see \wp\Database\Entities\Entity::__set()
	 */
	public function __set(string $attributeName, $value): bool {
		$attributeParts = explode("_", $attributeName);
		
		if(count($attributeParts) == 2){
			// Traite l'entité parente
			if ($this->parentEntity->name() === $attributeParts[0]) {
				return $this->parentEntity->{$attributeParts[1]} = $value;
			}
		}
		
		return parent::__set($attributeName, $value);
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
		
		// Ajoute les colonnes de l'entité courante
		$this->query .= $this->getFullQualifiedColumns();
		
		// Ajoute les colonnes de l'entité parente
		$this->query .= "," . $this->getParentEntity()->getFullQualifiedColumns();
		
		// Définit la jointure entre les deux tables
		$this->query .= " FROM ";
		
		$this->query .= $this->parentEntity->getAliasedName();
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		$this->query .= " ON " . $this->parentEntity->alias() . "." . $this->parentEntity->getPrimaryCol();
		$this->query .= " = " . $this->alias() . "." . $this->columns->findByType("parentEntity")->name();
		
		
		$query = Get::get();
		
		$query->SQL($this->query);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}

	public function selectBy() {
		$this->query = "SELECT ";
		
		// Ajoute les colonnes de l'entité courante
		$this->query .= $this->getFullQualifiedColumns();
		
		// Ajoute les colonnes de l'entité parente
		$this->query .= "," . $this->getParentEntity()->getFullQualifiedColumns();
		
		// Définit la jointure entre les deux tables
		$this->query .= " FROM ";
		
		$this->query .= $this->parentEntity->getAliasedName();
		$this->query .= " INNER JOIN " . $this->getAliasedName();
		$this->query .= " ON " . $this->parentEntity->alias() . "." . $this->parentEntity->getPrimaryCol();
		$this->query .= " = " . $this->alias() . "." . $this->columns->findByType("parentEntity")->name();
		
		
		// Ajouter la clause WHERE le cas échéant
		$whereClause = "";
		$queryParams = [];
		
		// Contraintes sur l'entité principale (fille)
		foreach($this->columns as $column => $object){
			if(!is_null($object->value())){
				$whereClause .= $this->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
				$queryParams[$object->name()] = $object->value();
			}
		}
		
		// Contraintes sur l'entité parente
		foreach($this->parentEntity->getScheme() as $column => $object){
			//echo "Valeur pour la colonne " . $column . " => " . $object->value() . "<br>";
			if(!is_null($object->value())){
				$whereClause .= $this->parentEntity->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
				$queryParams[$object->name()] = $object->value();
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
	/**
	 * Ajoute les instances d'entités parentes dans la relation
	 * @param void
	 * @return void
	 */
	protected function setParentEntity(){
		if (($column = $this->columns->findByType("parentEntity")) !== false){
			$entityClass = $column->ns() . "\\" . $column->parentEntity() . "Entity";
			$this->parentEntity = new $entityClass();
		}
	}
	
	/**
	 * Retourne l'instance de l'entité parente
	 */
	public function getParentEntity(){
		return $this->parentEntity;
	}
}