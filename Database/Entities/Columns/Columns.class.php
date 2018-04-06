<?php
/**
* @name Columns.class.php Collection des colonnes d'une entité
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities\Columns
* @version 0.1.0
**/

namespace wp\Database\Entities\Columns;

use \wp\Database\Entities\Columns\Column as Column;

class Columns implements \Iterator {
	/**
	 * Collection des colonnes
	 * @var array
	 */
	protected $columns;
	
	
	/**
	 * Retourne le nombre de colonnes de la collection
	 * @return number
	 */
	public function length(){
		return count($this->columns);	
	}
	
	/**
	 * Retourne la définition d'une colonne de la collection
	 * @param string $column Nom de la colonne à rechercher
	 * @return boolean|mixed
	 */
	public function find(string $column){
		return array_key_exists($column, $this->columns) ? $this->columns[$column] : false;	
	}
	
	/**
	 * Retourne la définition d'une colonne par le nom ou l'alias de la colonne
	 * @param string $id Nom de la colonne ou alias
	 * @return mixed|unknown|boolean
	 */
	public function findBy(string $id){
		if(array_key_exists($id, $this->columns)){
			return $this->columns[$id];
		}
		
		foreach ($this->columns as $column){
			if($column->alias() === $id){
				return $column;
			}
		}
		
		return false;
	}
	
	/**
	 * Récupère les colonnes par leur type
	 * @param string $type
	 * @return \Database\Entities\Columns\Column|\Database\Entities\Columns\Column[]|boolean
	 */
	public function findByType(string $type){
		$columns = array();
		
		foreach($this->columns as $column => $object){
			if(property_exists($object, $type)){
				if (substr($type, 0, 2) == "is"){
					$type = substr($type, 2, strlen($type));
				}
				if(!is_null($object->$type())){
					$columns[] = $object;
				}
			}
		}
		
		if(count($columns)){
			if(count($columns) == 1){
				return $columns[0];
			} else {
				return $columns;
			}
		}
		
		return false;
	}
	
	/**
	 * Retourne une colonne par valeur et type
	 * @param string $type Propriété à chercher
	 * @param mixed $value Valeur de comparaison
	 * @return \Column|boolean
	 */
	public function findByValue(string $type, $value){
		foreach($this->columns as $column => $object){
			if(property_exists($object, $type)){
				if ($object->$type() == $value) {
					return $object;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Retourne les colonnes de l'entité courante
	 * @return array
	 */
	public function get(){
		return $this->columns;	
	}
	
	/**
	 * Ajoute une colonne à la collection
	 * @param \Column $column
	 */
	public function hydrate(\wp\Database\Entities\Columns\Column $column){
		$this->columns[$column->name()] = $column;	
	}
	
	/**
	 * Retourne le nom des colonnes de l'entité courante
	 * @return array
	 */
	public function names(){
		return array_keys($this->columns);	
	}
	
	/**
	 * Retourne les noms de colonnes avec leurs alias
	 * @return string[]
	 */
	public function aliasedNames(){
		$aliasedNames = array();
		foreach ($this->columns as $name => $object){
			$aliasedNames[] = $name . " AS " . $object->alias();
		}
		
		return $aliasedNames;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */
	public function current(){
		return current($this->columns);
	}
	
	/**
	 * Incrémente l'indice
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next(){
		next($this->columns);
	}
	
	/**
	 * Retourne la clé de la collection à l'indice concerné
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */
	public function key(){
		return key($this->columns);
	}
	
	/**
	 * Détermine la validité du parcours
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */
	public function valid(){
		return key($this->columns) !== null;
	}
	
	/**
	 * Réinitialise l'indice au début du tableau à parcourir
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */
	public function rewind(){
		reset($this->columns);
	}
}