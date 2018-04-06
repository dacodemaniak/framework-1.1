<?php
/**
 * @name item.class.php Instance de représentation d'un élément de groupe ou liste
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Collections
 * @version 1.0
 */
namespace wp\Collections;

use \wp\Collections\collection as Collection;

class item {
	/**
	 * Instance de collection pour l'élément courant
	 * @var Collection
	 */
	private $collection;
	
	/**
	 * Identifiant de l'élément
	 * @var mixed
	 */
	private $id;
	
	/**
	 * Valeur associée à l'élément
	 * @var mixed
	 */
	private $value;
	
	/**
	 * Détermine si l'élément est actif ou non
	 * @var boolean
	 */
	private $isDisabled;
	
	
	/**
	 * Instancie un nouvel élément dans une collection donnée
	 * @param Collection $collection
	 */
	public function __construct(Collection $collection){
		$this->collection = $collection;
	}
	
	/**
	 * Alimente la collection avec l'objet lui-même
	 */
	public function hydrate(){
		$this->collection->add($this);
	}
	
	/**
	 * Définit ou retourne l'ID pour l'élément
	 * @param mixed $id
	 */
	public function id($id = null){
		if(is_null($id)){
			return $this->id;
		}
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Définit ou retourne la valeur pour l'élément
	 * @param mixed $value
	 */
	public function value($value = null){
		if(is_null($value)){
			return $this->value;
		}
		$this->value = $value;
		return $this;
	}
}