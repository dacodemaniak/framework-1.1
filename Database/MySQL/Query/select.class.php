<?php
/**
 * @name select.class.php Service d'éxécution d'une requête préparée
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Query
 * @version 0.1.0
 * @version 0.1.1 Ajout d'un log pour les requêtes SQL
 */

namespace wp\Database\MySQL\Query;

use \wp\Database\dbConnector as dbConnector;
use \wp\Database\Mapper\dataStoreMapper as Store;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\Database\queryException as QueryException;
use \wp\Utilities\Logger\Logger as Logger;


class select extends \wp\Database\Query\lmd{
	/**
	 * Requête SQL à générer
	 * @var string
	 */
	private $statement;
	
	/**
	 * Requête de comptabilisation des lignes
	 * @var string
	 */
	private $countStatement;
	
	/**
	 * Nombre de lignes retournée par la requête courante
	 * @var int
	 */
	private $nbRows;
	
	/**
	 * Stockage des données liées pour la requête préparée
	 * @var array
	 */
	private $bindingDatas;
	
	/**
	 * Objet de définition de stockage de données
	 * @var \wp\Database\Mapper
	 */
	private $store;
	
	/**
	 * Instance d'objet PDOStatement
	 * @var \PDOStatement
	 */
	private $select;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param Store $store
	 */
	public function __construct(Store $store, $useRelations=true){
		$this->store = $store;
		
		$this->useRelations($useRelations);
		
		$this->create();
		
		$this->select = $this->process();
		
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function select(){
		$this->select->setFetchMode(\PDO::FETCH_OBJ);
		return $this->select;
	}
	
	/**
	 * Retourne le nombre de lignes de la requête courante
	 * @return int
	 */
	public function nbRows(){
		return $this->nbRows;
	}
	
	public function __toString(){
		$output = "<pre><code>Requête générée : " . $this->statement . "<br />\n";
		if(sizeof($this->bindingDatas)){
			foreach($this->bindingDatas as $column => $value){
				$output .= $column . " : " . $value . "<br />\n";
			}
		}
		$output .= "</code></pre>";
		
		return $output;
	}
	
	/**
	 * Crée la requête SELECT à partir des données du Store courant
	 */
	public function create(){
		$this->statement = "SELECT ";
		
		$this->countStatement = "SELECT COUNT(*) AS nbrows ";
		
		// Boucle sur la collection des colonnes du Store courant
		foreach($this->store->getCollection() as $data){
			$this->statement .= $data->getName() . ",";
			if(!is_null($data->searchValue())){
				$this->bindingDatas[$data->placeholder()] = $data->searchValue();
			}
		}
		
		// Y-a-t-il une association
		if(!is_null($this->store->relations()) && $this->useRelations()){
			foreach($this->store->relations() as $relation){
				foreach($relation->associationStore()->getCollection() as $data){
					$this->statement .= $data->getName() . ",";
				}
				foreach($relation->secondaryStore()->getCollection() as $data){
					$this->statement .= $data->getName() . ",";
				}
			}
		}
		
		$this->statement = substr($this->statement, 0, strlen($this->statement) - 1);

		// Définit le Store à partir duquel remonter les données
		if(is_null($this->store->relations())){
			$this->statement .= " FROM " . $this->store->getName();
			$this->countStatement .= " FROM " . $this->store->getName();
		} else {
			if($this->useRelations()){
				foreach($this->store->relations() as $relation){
					$this->statement .= $relation->setAssociation();
					$this->countStatement .= $relation->setAssociation();
				}
			} else {
				// Ne traite que la table elle-même
				$this->statement .= " FROM " . $this->store->getName();
				$this->countStatement .= " FROM " . $this->store->getName();
			}
		}
		
		// Ajoute la clause de restriction sur la clé primaire
		$whereClause = "";
		
		#begin_debug
		#echo "Restriction sur clé primaire : " . $this->store->primaryRestriction() . "<br />\n";
		#end_debug
		
		if(!is_null($restriction = $this->store->primaryRestriction())){
			//$this->statement .= $this->store->primaryRestriction();
			$whereClause .= $restriction;
		}
		
		if(!is_null($this->store->clauses()) && sizeof($this->store->clauses())){
			foreach($this->store->clauses() as $clause){
				$whereClause .= $clause->process() . $clause->queue();
				$queue = $clause->queue();
			}
			$whereClause = substr($whereClause,0,strlen($whereClause) - strlen($queue));
		}
		
		if(strlen($whereClause)){
			$this->statement .= " WHERE " . $whereClause;
			$this->countStatement .= " WHERE " . $whereClause;
		}
		
		// Existe-t-il des clauses "order"
		if(!is_null($orderClause = $this->store->orderClause())){
			$this->statement .= " ORDER BY ";
			foreach($orderClause as $orderData){
				$this->statement .= $orderData[0] . " " . $orderData[1] . ",";
			}
			$this->statement = substr($this->statement,0,strlen($this->statement)-1);
		}
		
		$this->statement .= ";";
		$this->countStatement .= ";";
		
		#begin_debug
		#echo "<pre><code>" . $this->statement . "</code></pre>\n";
		#end_debug
	}
	
	/**
	 * Exécute la requête concernée
	 * @throws QueryException
	 * @return boolean|PDO
	 */
	public function process(){
		$dbConnector = dbConnector::instance(); // Instance de connexion à la base de données
		
		// Prépare la requete
		if(!$select = $dbConnector->connexion()->prepare($this->statement)){
			$rawBindingDatas = ""; // Sortie des données liées
			
			$error = new error();
			list($pdoCode, $internalCode, $msg) = $dbConnector->connexion()->errorInfo();
			
			if(count($this->bindingDatas)){
				foreach ($this->bindingDatas as $column => $value){
					$rawBindingDatas .= "<strong>" . $column . "</strong> : <em>" . $value . "</em><br>";
				}
			}
			
			$message = "Erreur dans la préparation de la requête : " . \wp\Helpers\SQL\SqlFormatter::format($this->statement). "<br>\n";
			$message .= "Données :<br>\n" . $rawBindingDatas . "<br>\n";
			$message .= "Code : " . $pdoCode . " [" . $internalCode . "]<br>\n";
			$message .= "Message : " . $msg . "\n";
			
			$error->message($message)
				->code(-99001)
				->doLog(true)
				->doRender(true);
			throw new QueryException($error);
			return false;
		} else {
			if(!$select->execute($this->bindingDatas)){
				$error = new error();
				list($pdoCode, $internalCode, $msg) = $dbConnector->connexion()->errorInfo();
				
				if(count($this->bindingDatas)){
					foreach ($this->bindingDatas as $column => $value){
						$rawBindingDatas .= "<strong>" . $column . "</strong> : <em>" . $value . "</em><br>";
					}
				}
				
				$message = "Erreur dans la préparation de la requête : " . \wp\Helpers\SQL\SqlFormatter::format($this->statement). "<br>\n";
				$message .= "Données :<br>\n" . $rawBindingDatas . "<br>\n";
				$message .= "Code : " . $pdoCode . " [" . $internalCode . "]<br>\n";
				$message .= "Message : " . $msg . "\n";
				if (count($this->bindingDatas)) {
					$message .= "Données mappées : \n";
					foreach ($this->bindingDatas as $placeholder => $value){
						$message .= $placeholder . " => " . $value . "\n";
					}
				}
				
				$error->message($message)
					->code(-99002)
					->doLog(true)
					->doRender(true);
				throw new QueryException($error);
				return false;
			} else {
				$logger = new Logger("mysqlselect");
				$logger->add($this->statement, __FILE__, __LINE__);
			}
		}
		
		/**
		 * Exécute et récupère le nombre de lignes
		 */
		$count = $dbConnector->connexion()->prepare($this->countStatement);
		$count->execute($this->bindingDatas);
		$count->setFetchMode(\PDO::FETCH_OBJ);
		$result = $count->fetch();
		$this->nbRows = $result->nbrows;
		
		return $select;
	}
}