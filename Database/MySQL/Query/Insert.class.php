<?php
/**
* @name Insert Service d'exécution d'une requête INSERT sur une base MySQL
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\MySQL\Query
* @version 0.1.0
**/

namespace wp\Database\MySQL\Query;

use \wp\Database\dbConnector as Connexion;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\Database\queryException as QueryException;
use \wp\Utilities\Logger\Logger as Logger;

class Insert {
	
	/**
	 * Chaîne contenant la requête UPDATE à exécuter
	 * @var string
	 */
	private $sqlStatement;
	
	/**
	 * Paramètres de la requête
	 * @var array
	 */
	private $queryParams;
	
	/**
	 * Requête préparée
	 * @var \PDOStatement
	 */
	private $preparedStatement;
	
	/**
	 * Définit la chaîne de requête SQL à traiter
	 * @param string $sqlStatement
	 * @return \wp\Database\MySQL\Query\Get
	 */
	public function SQL(string $sqlStatement){
		$this->sqlStatement = $sqlStatement;
		return $this;
	}
	
	/**
	 * Définit les paramètres de la requête à exécuter
	 * @param array $queryParams
	 * @return \wp\Database\MySQL\Query\Get
	 */
	public function queryParams($queryParams){
		$this->queryParams = $queryParams;
		return $this;
	}
	
	/**
	 * Exécute la requête préparée et retourne l'objet PDO associé
	 * @throws QueryException
	 * @return boolean|PDOStatement
	 */
	public function process(){
		$rawBindingDatas = ""; // Sortie des données liées
		
		// Récupère l'instance de connexion à la base de données
		$connexion = Connexion::instance();
		
		if(is_null($this->preparedStatement)){
			// Prépare la requete
			if(!$this->preparedStatement = $connexion->connexion()->prepare($this->sqlStatement)){
				
				
				$error = new error();
				list($pdoCode, $internalCode, $msg) = $connexion->connexion()->errorInfo();
				
				if(count($this->queryParams)){
					foreach ($this->queryParams as $column => $value){
						$rawBindingDatas .= "<strong>" . $column . "</strong> : <em>" . $value . "</em><br>";
					}
				}
				
				$message = "Erreur dans la préparation de la requête : " . \wp\Helpers\SQL\SqlFormatter::format($this->sqlStatement). "<br>\n";
				$message .= "Données :<br>\n" . $rawBindingDatas . "<br>\n";
				$message .= "Code : " . $pdoCode . " [" . $internalCode . "]<br>\n";
				$message .= "Message : " . $msg . "\n";
				
				$error->message($message)
				->code(-99001)
				->doLog(true)
				->doRender(true);
				throw new QueryException($error);
				return false;
			}
		}
		
		if(!$this->preparedStatement->execute($this->queryParams)){
			$error = new error();
			list($pdoCode, $internalCode, $msg) = $connexion->connexion()->errorInfo();
			
			if(count($this->queryParams)){
				foreach ($this->queryParams as $column => $value){
					$rawBindingDatas .= "<strong>" . $column . "</strong> : <em>" . $value . "</em><br>";
				}
			}
			
			$message = "Erreur dans la préparation de la requête : " . \wp\Helpers\SQL\SqlFormatter::format($this->sqlStatement). "<br>\n";
			$message .= "Données :<br>\n" . $rawBindingDatas . "<br>\n";
			$message .= "Code : " . $pdoCode . " [" . $internalCode . "]<br>\n";
			$message .= "Message : " . $msg . "\n";
			
			$error->message($message)
			->code(-99002)
			->doLog(true)
			->doRender(true);
			throw new QueryException($error);
			return false;
		}
		
		return $this->preparedStatement;
	}
}