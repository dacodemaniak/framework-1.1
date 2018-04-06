<?php
/**
 * @name select.class.php Service d'éxécution d'une requête MongoDB
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Query
 * @version 1.0
 */
namespace wp\Database\MongoDB\Query;

use \wp\Database\dbConnector as dbConnector;
use \wp\Database\Mapper\dataStoreMapper as Store;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\Query as QueryException;

class select extends \wp\Database\Query\lmd{

	/**
	 * Nombre de lignes retournée par la requête courante
	 * @var int
	 */
	private $nbRows;
	
	/**
	 * Paramètres de la méthode find() de MongoDB
	 * @var array
	 */
	private $queryArray;
	
	/**
	 * Options de récupération des documents
	 * @var array
	 */
	private $options = [];
	
	/**
	 * Objet de définition de stockage de données
	 * @var \wp\Database\Mapper
	 */
	private $store;
	
	/**
	 * Instance d'objet de requête MongoDB
	 * @var Object
	 */
	private $select;
	
	/**
	 * Curseur retourné par la requête
	 * @var \MongoDB\Cursor
	 */
	private $cursor;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param Store $store
	 */
	public function __construct(Store $store){
		$this->store = $store;
		
		$this->queryArray = array();
		
		$this->create();
		
		$this->select = $this->process();
		
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function select(){
		return $this->select;
	}
	
	/**
	 * Retourne le nombre de lignes de la requête courante
	 */
	public function nbRows(){
		return $this->nbRows;
	}
	
	public function __toString(){
		if($this->store->type() != "NOSQL"){
			$output = "<pre><code>Requête générée : " . $this->statement . "<br />\n";
			if(sizeof($this->bindingDatas)){
				foreach($this->bindingDatas as $column => $value){
					$output .= $column . " : " . $value . "<br />\n";
				}
			}
			$output .= "</code></pre>";
		} else {
			$output = "<pre><code>Interrogation de la collection : " . $this->store->collectionName() . "<br/>\n";
			$output .= "Avec le filtre : <br />\n";
			foreach($this->queryArray as $key => $value){
				$output .= "{\"" . $key . "\":\"" . $value . "\"},<br />\n";
			}
		}
		return $output;
	}
	
	/**
	 * Crée la requête SELECT à partir des données du Store courant
	 */
	public function create(){
		
		// Requête sur la clé primaire
		if(!is_null($restriction = $this->store->primaryRestriction())){
			//$this->statement .= $this->store->primaryRestriction();
			$this->queryArray = $restriction;
		}
		
		// Détermine si des filtres ont été définis
		if(method_exists($this->store, "getFilter")){
			if(!is_null($this->store->getFilter())){
				$this->queryArray = $this->store->getFilter()->getFilters();
			}
		}
		
		// Détermine les options de récupération des documents
		if(method_exists($this->store, "getOption")){
			if(!is_null($this->store->getOption()))
				$this->options = $this->store->getOption()->getOptions();
		}
	}
	
	/**
	 * Exécute la requête concernée
	 * @throws QueryException
	 * @return boolean|array
	 */
	public function process(){
		
		$dbConnector = dbConnector::instance(); // Instance de connexion à la base de données
		

		$query = new \MongoDB\Driver\Query($this->queryArray, $this->options);

		/*$filter = array('$or' => array(
				array("generic_name" => new \MongoDB\BSON\RegEx("^pain", "i")),
				array("product_name" => new \MongoDB\BSON\RegEx("^pain", "i"))
			)	
		);
		print_r($filter);
		
		$query = new \MongoDB\Driver\Query($filter);
		*/
		
		//$query = new \MongoDB\Driver\Query(array("generic_name" => new \MongoDB\BSON\RegEx("^pain", "i")));
		//$query = new \MongoDB\Driver\Query(array("generic_name" => "Anti-perspirant+Deodorant"));
		
		
		//die();
		
		//$collection = new \MongoCollection($dbConnector->connexion()->db(), $this->store->collectionName());
		try{
			$this->cursor = $dbConnector->connexion()->executeQuery($dbConnector->dbName() . "." . $this->store->collectionName(), $query);
		} catch (\MongoDB\Driver\AuthenticationException $e){
			echo "Erreur d'authentification : " . $e->getMessage() . "<br />\n";
		} catch(\MongoDB\Driver\ConnextionException $e){
			echo "Erreur de connexion : " . $e->getMessage() . "<br />\n";
		}
		catch(\MongoDB\Driver\Exception\RuntimeException $e){
			echo "Erreur d'exécution : " . $e->getMessage() . "<br />\n";
		}
		
		// Initialise le tableau des données de retour
		if(count($this->cursor) > 0){
			return $this->cursor;
		}
		
		return false;
	}
}