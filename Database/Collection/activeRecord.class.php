<?php
/**
 * @name activeRecord.class.php Abstraction de ligne active d'une structure de données
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Collection
 * @version 1.0
 */
namespace wp\Database\Collection;

use wp\Collections\collection;

abstract class activeRecord {
	/**
	 * Objet de définition des informations de stockage des données
	 * @var Store
	 */
	protected $store;
	
	/**
	 * Clé primaire / Identifiant pour remonter l'enregistrement souhaité
	 * @var multitype
	 */
	protected $id;
	
	/**
	 * Objet de requête de manipulation de données
	 * @var \wp\Database\Query
	 */
	protected $query;
	
	/**
	 * Instance d'objet PDOStatement
	 * @var \PDOStatement
	 */
	protected $select;
	
	/**
	 * Instance d'objet de création ou d'insertion
	 * @var \wp\Database\Query\insert
	 */
	protected $insert;
	
	/**
	 * Vrai si effectivement un enregistrement a été remonté
	 * @var boolean
	 */
	protected $activeRecord = false;
	
	/**
	 * Collection des éléments enfants d'un table auto-jointée
	 * @var collection
	 */
	protected $children = null;
	
	/**
	 * Collection des ancêtres d'une table auto-jointée
	 * @var Array
	 */
	protected $ancestors = null;
	
	/**
	 * Définit l'identifiant sur lequel récupérer l'enregistrement courant
	 * @param unknown $id
	 * @return \wp\Database\Collection\multitype|\wp\Database\Collection\activeRecord
	 */
	public function id($id=null){
		if(is_null($id)){
			return $this->id;
		}
		$this->id = $id;
		
		// Définit la valeur de recherche dans la collection des données
		#begin_debug
		#echo "Stocke : " . $this->id . " en clé de recherche pour " . $this->store->name() . "<br />\n";
		#end_debug
		$this->store->primary($this->id);
		
		return $this;
	}
	
	/**
	 * Détermine si un élément dispose d'enfants ou pas
	 * @return boolean
	 */
	public function hasChildren(){
		return sizeof($this->children) ? true : false;
	}
	
	/**
	 * Retourne vrai si la ligne d'une table auto-jointée a des ancêtres
	 * @return boolean
	 */
	public function hasAncestors(){
		return count($this->ancestors) ? true : false;
	}
	
	protected function create(){}
	
	/**
	 * Retourne le nom du store associé
	 */
	public function name(){
		return $this->store->name();
	}
	
	public function hydrate($content){
		foreach($this->store->getCollection() as $data){
			$name = $data->selectName();
			$this->{$name} = $content->$name;
		}		
	}
	
	/**
	 * Permet de retourner une donnée du store parent
	 * @param string $objectDataName
	 */
	public function get($objectDataName){
		return $this->store->get($objectDataName);
	}
	
	/**
	 * Retourne l'objet de stockage des données
	 * @return \wp\Database\Mapper\dataStoreMapper
	 */
	public function getStore(){
		return $this->store;
	}
	
	/**
	 * Extrait une donnée d'une structure
	 * @param object $data
	 */
	protected function extract($data){
		#begin_debug
		#echo "Retourne la donnée avec une localisation définie à : " . \App\appLoader::wp()->defaultLanguage() . "<br />\n";
		#end_debug
		if(is_array($data)){
			if(sizeof($data) == 1){
				return $data[0]->content;
			} else {
				foreach($data as $object){
					if($object->language == \App\appLoader::wp()->defaultLanguage){
						return $object->content;
					}
				}
			}
		}
		if(is_bool($data)){
			return (bool) $data;
		}
		
		if(is_object($data)){
			return $data;
		}
		/**
		 * Il s'agit d'une simple chaîne de caractère
		 */
		return (string) $data;
	}
	
	/**
	 * Permet de "simuler" un getter pour récupérer une données d'une colonne "content"
	 * @param string $methodName Nom de la méthode appelée
	 * @param array $args Tableau des arguments à traiter
	 * @return boolean|string|object|string
	 */
	public function __call($methodName, $args){
		$methodPrefix = substr($methodName,0,3);
		$methodSuffix = strtolower(substr($methodName,3,strlen($methodName)));
		$alias = "";
		
		$attributeName = $args[0];
		
		// Récupère la colonne de contenu courante (content)
		$collection = $this->store->getCollection();
		foreach($collection as $col){
			if($col->name() == "content"){
				$alias = $col->alias();
			}
		}
		
		// Récupère le contenu courant
		$globalContent = json_decode($this->{$alias});
		
		// Teste l'existence de l'attribut dans le contenu...
		if(property_exists($globalContent, $methodSuffix)){
			$data = $globalContent->{$methodSuffix};
			return $this->extract($data->{$attributeName});
		}
		
		return "/!\\" . $attributeName . "/!\\";
	}
	
	/**
	 * Prépare et exécute la requête de récupération d'un enregistrement
	 */
	abstract public function query();
	
	abstract public function toJSON();
}