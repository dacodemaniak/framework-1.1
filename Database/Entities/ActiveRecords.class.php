<?php
/**
* @name ActiveRecords.class.php Collection des lignes actives d'une entité physique
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\ActiveRecord as ActiveRecord;
use \wp\Database\Interfaces\IActiveRecord;

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
		$this->records = [];
		
		$this->entity = $entity;
	}
	
	/**
	 * Ajoute un item à la collection des enregistrements actifs
	 * @param IActiveRecord $activeRecord
	 */
	public function set(IActiveRecord $activeRecord){
		$this->records[] = $activeRecord;
	}
	
	/**
	 * Retourne le nombre de lignes de la collection courante
	 * @return number
	 */
	public function length(){
		return count($this->records);	
	}
	
	/**
	 * Alias pour la méthode length()
	 * @see ActiveRecords::length()
	 * @return number
	 */
	public function size() {
		return count($this->records);
	}
	
	/**
	 * Retourne le tableau des enregistrements actifs
	 * @return ActiveRecords | ActiveRecord | boolean
	 */
	public function get($index = null){
		if (is_null($index)) {
			if ($this->length() > 1) {
				return $this;
			} elseif ($this->length() == 1){
				return $this->records[0];
			}
		}
		if(is_int($index) && ($index >= 0 && $index < $this->length())){
			return $this->records[$index];
		}
		
		return false;
	}
	
	/**
	 * Humanise le contenu des enregistrements récupérés.
	 * @return string
	 */
	public function __toString(): string {
		$output = "Nombre d'enregistrements : " . $this->size() . "<br>\n";
		
		foreach($this as $record) {
			$output .= "id : " . $record->id . "<br>\n";
		}
		
		return $output;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */
	public function current(){
		#$record = $this->records[$this->index];
		#echo "Ligne courante : " . $record->id . " pour index = " . $this->index . "<br>\n";
		return $this->records[$this->index];
	}
	
	/**
	 * Incrémente l'indice
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next() {
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