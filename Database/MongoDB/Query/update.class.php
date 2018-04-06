<?php
/**
 * @name update.class.php Service d'éxécution d'une requête MongoDB de type UPDATE
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

class update extends \wp\Database\Query\lmd{

	
	/**
	 * Identifiant du document courant
	 * @var array
	 */
	private $idArray;
	
	/**
	 * Tableau de stockage des données à mettre à jour
	 * @var array
	 */
	private $updateArray;
	
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
	private $update;
	
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
	 * Identifiant au format BSON du document actif
	 * @var unknown
	 */
	private $_id;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param ActiveRecord $activeRecord
	 */
	public function __construct(ActiveRecord $activeRecord){
		$this->activeRecord = $activeRecord;
		
		$this->store = $this->activeRecord->getStore();
		
		#begin_debug : 5011546498393
		#$id = "";
		#$id = (string) $this->activeRecord->_id;
		#echo "Définit l'ObjectID à partir de : " . $id . "<br />\n";
		#end_debug
		
		//$this->_id = new \MongoDB\BSON\ObjectID((string) $this->activeRecord->_id);
		//$this->_id = new \MongoDB\BSON\ObjectID("5011546498393           ");
		$this->_id = (string) $this->activeRecord->_id;
		$this->idArray = array('_id' => $this->_id);
		
		$this->create();
		
		$this->update = $this->process();
		
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function update(){
		return $this->update;
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
	 * Crée la requête UPDATE à partir des données du Store courant
	 */
	public function create(){
		// Définit le tableau des données à traiter
		$this->updateArray = array(
			'$set' => array(
				'purchases' => $this->activeRecord->purchases,
				'rates' => $this->activeRecord->rates
			)
		);
	}
	
	/**
	 * Exécute la requête concernée
	 * @throws QueryException
	 * @return boolean|array
	 */
	public function process(){
		$dbConnector = dbConnector::instance(); // Instance de connexion à la base de données
		
		/*
		 * ['_id'=>new MongoDB\BSON\ObjectID($id)],['$set' =>['product_name' =>$product_name, 'price'=>$price, 'category'=>$category]], ['multi' => false, 'upsert' => false]
		 */
		$query = new \MongoDB\Driver\BulkWrite;
		
		$query->update($this->idArray, $this->updateArray, ['multi' => false, 'upsert' => false]);
		
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