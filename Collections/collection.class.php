<?php
/**
 * @name collection.class.php Instance de collection d'objets
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Collections
 * @version 1.0
 */
namespace wp\Collections;

class collection implements \Iterator {
	/**
	 * Stockage des élements d'une collection
	 * @var array
	 */
	private $collection;
	
	/**
	 * Indice du tableau permettant le parcours
	 * @var int
	 */
	private $indice;
	
	/**
	 * Instancie une nouvelle collection
	 */
	public function __construct(){
		$this->collection = array();
		$this->indice = 0;
	}
	
	/**
	 * Ajoute un élément à la collection courante
	 * @param object $item
	 */
	public function add($item){
		$this->collection[] = $item;
		return $this;
	}
	
	/**
	 * Retourne la collection
	 */
	public function get(){
		return $this->collection;
	}
	
	/**
	 * Retourne un élément de la collection à partir de son identifiant
	 * @param string $itemId
	 * @return mixed|boolean
	 */
	public function getItem($itemId){
		foreach ($this->collection as $item){
			if($item->id() == $itemId){
				return $item->value();
			}
		}
		return false;
	}
	
	/**
	 * Retourne l'information relatives à la présence d'items dans la collection
	 * @return boolean
	 */
	public function hasItems(){
		return count($this->collection) ? true : false;
	}
	
	/**
	 * Détermine si l'expression fait partie de la collection
	 * @param mixed $expression
	 * @return boolean
	 */
	public function contains($expression){
		foreach($this->collection as $item){
			if(strtolower($item->value()) == strtolower($expression)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Retourne l'indice courant
	 * @return number
	 */
	public function getIndice(){
		return $this->indice;
	}
	
	/**
	 * Retourne la taille du tableau
	 * @return number
	 */
	public function length(){
		return count($this->collection);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */
	public function current(){
		return $this->collection[$this->indice]->value();
	}
	
	/**
	 * Incrémente l'indice
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next(){
		$this->indice++;
	}
	
	/**
	 * Retourne la clé de la collection à l'indice concerné
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */
	public function key(){
		return $this->collection[$this->indice]->id();
	}
	
	/**
	 * Détermine la validité du parcours
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */
	public function valid(){
		return $this->indice <= (count($this->collection) - 1);
	}
	
	/**
	 * Réinitialise l'indice au début du tableau à parcourir
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */
	public function rewind(){
		$this->indice = 0;
	}
}