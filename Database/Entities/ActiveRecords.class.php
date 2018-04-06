<?php
/**
* @name ActiveRecords.class.php Collection des lignes actives d'une entité physique
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\ActiveRecord as ActiveRecord;

class ActiveRecords implements \Iterator {
	
	/**
	 * Index pour le parcours du tableau
	 * @var int
	 */
	private $index = 0;
	
	/**
	 * Structure de stockage des lignes actives
	 * @var array
	 */
	private $records;
	
	/**
	 * Instance de l'entité de référence
	 * @var Object
	 */
	private $entity;
	
	/**
	 * Constructeur de la classe courante
	 * @param unknown $entity
	 */
	public function __construct($entity){
		$this->records = array();
		
		$this->entity = $entity;
	}
	
	/**
	 * Alimente la collection des enregistrements actifs avec la totalité des lignes
	 */
	public function findAll(){
		if(($statement = $this->entity->selectAll()) !== false){
			$statement->setFetchMode(\PDO::FETCH_OBJ);
			while($data = $statement->fetch()){
				$record = $this->entity->getActiveRecordInstance();
				$record->hydrate($data);
				$this->records[] = $record;
			}
		}
	}

	/**
	 * Alimente la collection des enregistrements actifs avec la totalité des lignes
	 */
	public function findBy(){
		if(($statement = $this->entity->selectBy()) !== false){
			$statement->setFetchMode(\PDO::FETCH_OBJ);
			while($data = $statement->fetch()){
				$record = $this->entity->getActiveRecordInstance();
				$record->hydrate($data);
				$this->records[] = $record;
			}
		}
	}
	
	/**
	 * Retourne le nombre de lignes de la collection courante
	 * @return number
	 */
	public function length(){
		return count($this->records);	
	}
	
	/**
	 * Retourne le tableau des enregistrements actifs
	 * @return array | ActiveRecord | boolean
	 */
	public function get($index = null){
		if(is_null($index)){
			if($this->length() > 1){
				return $this->records;
			} elseif($this->length() == 1){
				return $this->records[0];
			}
		}
		if(is_int($index) && ($index >= 0 && $index < $this->length())){
			return $this->records[$index];
		}
		
		return false;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */
	public function current(){
		return $this->records[$this->index];
	}
	
	/**
	 * Incrémente l'indice
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next(){
		$this->index++;
	}
	
	/**
	 * Retourne la clé de la collection à l'indice concerné
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */
	public function key(){
		return $this->index;
	}
	
	/**
	 * Détermine la validité du parcours
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */
	public function valid(){
		return $this->index < count($this->records) ? true : false;
	}
	
	/**
	 * Réinitialise l'indice au début du tableau à parcourir
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */
	public function rewind(){
		$this->index = 0;
	}
	
}