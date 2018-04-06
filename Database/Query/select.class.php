<?php
/**
 * @name select.class.php Service d'instanciation d'une requête de type SELECT en fonction du fournisseur
 * @author web-Projet.com (contact@web-projet.com) - Nov. 2016
 * @package wp\Database\Query
 * @version 1.0
 */
namespace wp\Database\Query;

use \wp\Database\Mapper\dataStoreMapper as Store;

class select extends \wp\Database\Query\lmd{
	
	/**
	 * Objet de définition de stockage de données
	 * @var \wp\Database\Mapper
	 */
	private $store;
	
	/**
	 * Instance d'un objet SELECT du fournisseur concerné
	 * @var Object
	 */
	private $query;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param Store $store
	 */
	public function __construct(Store $store, $useRelations=true){
		$this->store = $store;
		
		$query = new \wp\Patterns\ClassFactory\select($store,$useRelations);
		$query->addInstance();
		$this->query = $query->instance();
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function select(){
		return $this->query->select();
	}
	
	/**
	 * Retourne le nombre de lignes de la requête courante
	 */
	public function nbRows(){
		return $this->query->nbRows();
	}
	
	public function __toString(){
		return $this->query->__toString();
	}
}