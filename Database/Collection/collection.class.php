<?php
/**
 * @name collection.class.php Abstraction de collecte de données à partir d'un Mapper
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Collection
 * @version 1.0
 */
namespace wp\Database\Collection;

use \wp\Database\Mapper\dataStoreMapper as Store;

abstract class collection implements \Iterator{
	/**
	 * Objet de définition des informations de stockage des données
	 * @var Store
	 */
	protected $store;
	
	/**
	 * Structure de stockage des données collectées
	 * @var array
	 */
	protected $collection;
	
	/**
	 * Nom de la clé primaire du store courant
	 * @var string
	 */
	protected $primaryKeyName;
	
	
	/**
	 * Retourne la taille du tableau des documents
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
		return current($this->collection);
	}
	
	/**
	 * Incrémente l'indice
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next(){
		next($this->collection);
	}
	
	/**
	 * Retourne la clé de la collection à l'indice concerné
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */
	public function key(){
		return key($this->collection);
	}
	
	/**
	 * Détermine la validité du parcours
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */
	public function valid(){
		return key($this->collection) !== null;
	}
	
	/**
	 * Réinitialise l'indice au début du tableau à parcourir
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */
	public function rewind(){
		reset($this->collection);
	}
	
}