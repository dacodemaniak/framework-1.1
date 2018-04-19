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
use \wp\Database\SQL\Select;
use \wp\Database\Query\Get;

class Repository  implements Select {
	
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
	 * Instance de PDOStatement
	 * @var \PDOStatement
	 */
	protected $statement;
	
	/**
	 * Chaîne de requête SQL
	 * @var string
	 */
	protected $query;
	
	/**
	 * Tableau des paramètres de requête pour les requêtes préparées
	 * @var array
	 */
	protected $queryParams;
	
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
		if(is_null(self::$instance)){
			self::$instance = new Repository($entity);
		}
		return self:: $instance;
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
	
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return Boolean
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
		$this->query = "SELECT ";
		
		// Ajoute les colonnes de la table
		$this->query .= $this->entity->getFullQualifiedColumns();
		
		// Définit l'origine de la requête
		$this->query .= " FROM " . $this->entity->getAliasedName();
		
		$query = Get::get();
		
		$query->SQL($this->query);
		
		$this->statement = $query->process();
		
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
		$this->query = "SELECT ";
		
		// Ajoute les colonnes de la table
		$this->query .= $this->entity->getFullQualifiedColumns();
		
		// Définit l'origine de la requête
		$this->query .= " FROM " . $this->entity->getAliasedName();
		
		// Ajouter la clause WHERE le cas échéant
		$whereClause = "";
		$queryParams = [];
		foreach($this->entity->getScheme() as $column => $object){
			if(!is_null($object->value())){
				$whereClause .= $this->entity->alias() . "." . $object->name() . "=:" . $object->name() . " AND ";
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
	 * Retourne une collection ou une instance active de ligne
	 * @param mixed $index
	 * @return ActiveRecords | ActiveRecord | Boolean
	 */
	public function get($index = null) {
		return $this->activeRecords->get($index);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::addOrderBy()
	 */
	public function addOrderBy(string $column, string $direction="ASC"){}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::addGroupBy()
	 */
	public function addGroupBy(string $column){}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::addConstraint()
	 */
	public function addConstraint(string $column, string $operator, string $logical=null){}
}