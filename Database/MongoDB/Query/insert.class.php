<?php
/**
 * @name insert.class.php Service d'éxécution d'une requête MongoDB de type INSERT
 * @author web-Projet.com (contact@web-projet.com) - Juin 2017
 * @package wp\Database\Query
 * @version 1.0
 */
namespace wp\Database\MongoDB\Query;

use \wp\Database\dbConnector as dbConnector;
use \wp\Database\Collection\activeRecord as ActiveRecord;
use \wp\Database\Mapper\dataStoreMapper as Store;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\Query as QueryException;

class insert extends \wp\Database\Query\lmd{

	
	/**
	 * Paramètres de la méthode find() de MongoDB
	 * @var array
	 */
	private $queryArray;
	
	/**
	 * Document actif
	 * @var ActiveRecord
	 */
	private $activeRecord;
	
	/**
	 * Objet de définition de stockage de données
	 * @var \wp\Database\Mapper
	 */
	private $store;
	
	/**
	 * Instance d'objet de requête MongoDB
	 * @var Object
	 */
	private $insert;
	
	/**
	 * Curseur retourné par la requête
	 * @var \MongoDB\Cursor
	 */
	private $cursor;
	
	/**
	 * Résultat de l'exécution d'une insertion ou mise à jour
	 * @var unknown
	 */
	private $result;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param ActiveRecord $activeRecord
	 */
	public function __construct(ActiveRecord $activeRecord){
		$this->activeRecord = $activeRecord;
		$this->store = $this->activeRecord->getStore();
		
		$this->queryArray = array();
		
		$this->create();
		
		$this->insert = $this->process();
		
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function insert(){
		return $this->insert;
	}
	
	
	public function __toString(){
		$output = "<pre><code>Interrogation de la collection : " . $this->store->collectionName() . "<br/>\n";
		$output .= "Avec le filtre : <br />\n";
		foreach($this->queryArray as $key => $value){
			$output .= "{\"" . $key . "\":\"" . $value . "\"},<br />\n";
		}
		return $output;
	}
	
	/**
	 * Crée la requête INSERT à partir des données du Store courant
	 */
	public function create(){
		// Définit le tableau des données à traiter
		$this->queryArray = $this->activeRecord->getData();
	}
	
	/**
	 * Exécute la requête concernée
	 * @throws QueryException
	 * @return boolean|array
	 */
	public function process(){
		$dbConnector = dbConnector::instance(); // Instance de connexion à la base de données
		
		
		$query = new \MongoDB\Driver\BulkWrite;
		
		$query->insert($this->queryArray);
		
		$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		//$collection = new \MongoCollection($dbConnector->connexion()->db(), $this->store->collectionName());
		try{
			$this->result = $dbConnector->connexion()->executeBulkWrite($dbConnector->dbName() . "." . $this->store->collectionName(), $query, $writeConcern);
		} catch (\MongoDB\Driver\AuthenticationException $e){
			echo "Erreur d'authentification : " . $e->getMessage() . "<br />\n";
		} catch(\MongoDB\Driver\ConnextionException $e){
			echo "Erreur de connexion : " . $e->getMessage() . "<br />\n";
		}
		catch(\MongoDB\Driver\Exception\RuntimeException $e){
			echo "Erreur d'exécution : " . $e->getMessage() . "<br />\n";
		}
	}
}