<?php
/**
 * @name dataStoreMapper.class.php Abstraction de définition de mapping sur les tables d'une
 * 	base de données.
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Mapper
 * @version 1.0
 * @version 0.1.1
 * 	- Modification de la méthode toStoreName() pour convertir en minuscules le nom de l'entité physique
 */
namespace wp\Database\Mapper;

use \wp\Database\Mapper\dataMapper as Data;
use \wp\Database\Mapper\clause as Clause;

abstract class dataStoreMapper {
	
	/**
	 * Nom de la table ou de la collection concernée
	 * @var string
	 */
	protected $storeName;
	
	/**
	 * Alias de la table ou collection concernée
	 * @var string
	 */
	protected $storeAlias;
	
	/**
	 * Collection des objets relatifs aux colonnes constituant la structure à mapper
	 * @var array
	 */
	protected $collection;
	
	/**
	 * Structure de stockage des relations éventuelles entre les tables
	 * @var array
	 */
	protected $relations;
	
	/**
	 * Structure de stockage des clauses de restrictions sur une requête
	 * @var array
	 */
	protected $clauses;
	
	/**
	 * Ordres de tris des requêtes
	 * @var array
	 */
	protected $orders;
	
	/**
	 * Détermine le type de Store à traiter : SQL | NOSQL
	 * @var string
	 */
	private $type = "SQL";
	
	/**
	 * Définit ou retourne le type de Store à traiter
	 * @param string $type
	 */
	public function type($type=null){
		if(is_null($type)){
			return $this->type;
		}
		$this->type = $type;
		return $this;
	}
	/**
	 * Définit la valeur de recherche pour la clé primaire dans la collection
	 * @param multitype $value
	 */
	public function primary($value){
		$data = $this->primaryData();
		$data->searchValue($value);
		$data->value($value);
		#begin_debug
		#echo "Stocke la valeur de recherche : $value dans la restriction du Mapper<br />\n";
		#end_debug
		
		$this->setPrimaryData($data);
		
		return $this;
	}
	
	/**
	 * Récupère la collection des colonnes 
	 */
	public function getCollection(){
		return $this->collection;
	}
	
	/**
	 * Modifie le type de jointure de l'association
	 * @param int $type
	 */
	public function joinType(int $type){
		if(count($this->relations)){
			$this->relations[0]->setJoinType($type);
		}
	}
	
	/**
	 * Ajoute une instance d'association entre les tables
	 * @param wp\Database\Mapper\association $association
	 */
	public function addRelation($association){
		$this->relations[] = $association;
	}
	
	/**
	 * Retourne la structure de stockage des relations
	 */
	public function relations(){
		return $this->relations;
	}
	
	/**
	 * Ajoute une clause pour définir une requête
	 * @param \wp\Database\Mapper\clause $clause
	 * @return \wp\Database\Mapper\dataStoreMapper
	 */
	public function addClause(\wp\Database\Mapper\clause $clause){
		$this->clauses[] = $clause;
		return $this;
	}
	
	/**
	 * Retourne les clause de requête
	 */
	public function clauses(){
		return $this->clauses;
	}
	
	/**
	 * Ajoute un élément de stockage à la collection
	 * @param Data $data
	 */
	public function hydrate(Data $data){
		$this->collection[] = $data;
		return;
	}
	
	/**
	 * Définit ou retourne le nom de la structure de stockage
	 * @param string $store
	 */
	public function name($store=null){
		if(is_null($store)){
			if(is_null($this->storeName))
				$this->storeName = $this->toStoreName();
			
			return $this->storeName;
		}
		
		$this->storeName = $store;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'alias de l'élément de stockage
	 * @param unknown $alias
	 * @return string|\wp\Database\Mapper\dataStoreMapper
	 */
	public function alias($alias=null){
		if(is_null($alias)){
			return $this->storeAlias;
		}
		$this->storeAlias = $alias;
		return $this;
	}
	
	/**
	 * Retourne le nom qualifié ou non de la table concernée
	 * @return string
	 */
	public function getName(){
		if(!is_null($this->storeAlias)){
			return $this->storeName . " as " . $this->storeAlias;
		}
		return $this->storeName;
	}
	
	/**
	 * Retourne le nom de la table ou son alias uniquement
	 * @return string
	 */
	public function getShortName(){
		return is_null($this->storeAlias) ? $this->storeName : $this->storeAlias;
	}
	
	/**
	 * Retourne le nom de la table elle-même
	 */
	public function getTableName(){
		return $this->storeName;
	}
	
	/**
	 * Retourne le nom de la clé primaire du store courant
	 * @return unknown
	 */
	public function getPrimaryKeyName(){
		$data = $this->primaryData();
		return $data->name();
	}
	
	/**
	 * Retourne la restriction sur la clé primaire, si elle a été définie
	 */
	public function primaryRestriction(){
		$restriction = $this->primaryData();
		
		#begin_debug
		#echo "Valeur de restriction pour " . $restriction->name() . " => " . $restriction->value() . "<br />\n";
		#end_debug
		
		if(!is_null($restriction->value())){
			if($this->type == "SQL")
				return $restriction->getQualifiedName() . " = " . $restriction->placeholder();
			else {
				return array($restriction->name() => $restriction->value());
			}
		}
	}
	
	public function restriction($clause){}
	
	/**
	 * Ajoute ou retourne les éventuelles clauses de tri
	 * @param string $colName Optionnel colonne sur laquelle trier les résultats
	 * @param string $direction Optionnel direction du tri Defaut ASC ou DESC
	 */
	public function orderClause($colName = null, $direction="ASC"){
		if($this->type == "SQL"){
			if(!is_null($colName)){
				if(is_array($this->orders)){
					foreach($this->orders as $order){
						if(in_array($colName,$colName)){
							return $this; // La colonne existe déjà
						}
					}
					$this->orders[] = array($colName,$direction);
				} else {
					$this->orders[] = array($colName,$direction);
				}
				return $this;
			}
			
			// Sans paramètre, on retourne la liste des clauses de tri avec la colonne order par défaut si définie
			foreach($this->collection as $data){
				if($data->name() == "order"){
					$this->orders[] = array($data->getQualifiedName(), "ASC");
				}
			}
			if(sizeof($this->orders)){
				return $this->orders;
			}
		} else {
			// Traitement spécifique pour une interrogation MongoDB
		}
	}
	
	public function __toString(){
		$output					= "";
		$output .= "<ul>\n";
		$output .= "\t<li>" . $this->name() . "(" . $this->alias() . ")</li>\n";
		$output .= "\t<li>Schéma :\n";
		$output .= "\t\t<ul>\n";
		foreach($this->collection as $element){
			$output .= "\t\t\t<li>" . $element->name() . "</li>\n";
		}
		$output .= "\t\t</ul>\n";
		$output .= "</ul>\n";
		
		return $output;
	}
	
	/**
	 * Efface les clauses de requêtes lors d'un clone et les valeurs de recherche
	 */
	public function __clone(){
		$this->clauses = [];
		
		$collection = array();
		
		foreach($this->collection as $data){
			$data->resetValue();
			$collection[] = $data;
		}
		
		$this->collection = $collection;
	}
	
	/**
	 * Définit le nom du Store à partir du nom de la classe
	 * @return string
	 */
	private function toStoreName(){
		$fullClassName = get_class($this);
		$classParts = explode("\\",$fullClassName);
		
		return strtolower(substr(array_pop($classParts),0,-5));
	}
	
	/**
	 * Récupère l'objet identifiant la clé primaire dans la collection
	 */
	private function primaryData(){
		foreach ($this->collection as $data){
			if($data->primary()){
				return $data;
			}
		}
	}
	
	/**
	 * Réinjecte la donnée de clé primaire modifiée
	 * @param \wp\Database\Mapper $primaryData
	 */
	private function setPrimaryData($primaryData){
		$collection = array();
		foreach($this->collection as $data){
			if(!$data->primary()){
				$collection[] = $data;
			}
		}
		// Ajoute la nouvelle donnée collectée
		$collection[] = $primaryData;
		$this->collection = $collection;
		
	}
}