<?php
/**
* @name Repository Service de définition d'un dépôt de données
* @author IDea Factory (dev-team@ideafactory.fr) - Avr. 2018
* @package wp\Database\Entities
* @version 0.0.1
**/
namespace wp\Database\Entities;

use wp\Database\Interfaces\IEntity;
use wp\Database\Entities\ActiveRecords;


class Repository {
	
	/**
	 * Instance courante du repository
	 * @var \Repository
	 */
	private static $instance;
	
	/**
	 * Entité de référence
	 * @var IEntity
	 */
	private $entity;
	
	/**
	 * Collection des lignes de l'entité
	 * @var \ActiveRecords
	 */
	private $activeRecords;

	/**
	 * Instance de la requête passée dans les entités respectives
	 * @var unknown
	 */
	private $statement;
	
	/**
	 * Instancie un nouveau Respository à partir d'une entité
	 * @param IEntity $entity
	 */
	private function __construct(IEntity $entity){
		$this->entity = $entity;
		
		$this->activeRecords = new ActiveRecords($entity);
	}
	
	/**
	 * Retourne l'instance courante du Repository pour une entité
	 * @param IEntity $entity
	 * @return \Repository
	 */
	public static function getRepository(IEntity $entity): Repository {
		/*
		if(is_null(self::$instance)){
			self::$instance = new Repository($entity);
		}
		
		// Vérifie si l'entité courante est la même que celle demandée
		if ($entity instanceof self::$instance->entity){}
		
		return self:: $instance;
		*/
		return new Repository($entity);
	}
	
	/**
	 * Définit la valeur d'une colonne de l'entité courante
	 * @param string $attributeName
	 * @param mixed $value
	 * @return boolean
	 */
	public function __set(string $attributeName, $value): bool {
		return ($this->entity->{$attributeName} = $value);
	}
	
	public function hydrate() {}
	
	public function addOrderBy($column, $direction="ASC") {
		$this->entity->addOrderBy($column, $direction);
	}
	
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return Boolean
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
		
		$this->statement = $this->entity->selectAll();
		
		// Alimenter les lignes actives dans ActiveRecords
		if ($this->statement !== false){
			$this->statement->setFetchMode(\PDO::FETCH_OBJ);
			while($data = $this->statement->fetch()){
				$record = $this->entity->getActiveRecordInstance();
				$record->hydrate($data);
				$this->activeRecords->set($record);
			}
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectBy()
	 */
	public function selectBy(){
		$this->statement = $this->entity->selectBy();
		
		if ($this->statement !== false){
			$this->statement->setFetchMode(\PDO::FETCH_OBJ);
			while($data = $this->statement->fetch()){
				$record = $this->entity->getActiveRecordInstance();
				$record->hydrate($data);
				$this->activeRecords->set($record);
			}
			return true;
		}
		
		return false;
	}
	
	public function selectLast() {
		$this->statement = $this->entity->selectLast();
		
		if ($this->statement !== false) {
			$this->statement->setFetchMode(\PDO::FETCH_OBJ);
			while ($data = $this->statement->fetch()) {
				$record = $this->entity->getActiveRecordInstance();
				$record->hydrate($data);
				$this->activeRecords->set($record);
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Retourne une collection ou une instance active de ligne
	 * @param mixed $index
	 * @return ActiveRecords | ActiveRecord | Boolean
	 */
	public function get($index = null) {
		return $this->activeRecords->get($index);
	}
}