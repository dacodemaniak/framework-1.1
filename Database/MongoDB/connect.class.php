<?php
/**
 * @name connect.class.php Initialise une connexion à une base de données MongoDB
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\MongoDB
 * @version 1.0
**/

namespace wp\Database\MongoDB;

class connect {
	/**
	 * Chaîne de connexion
	 * @var string
	 */
	private $dsn;
	
	/**
	 * Driver à utiliser pour la connexion MongoDB
	 * @var string
	 */
	private $driver;
	
	/**
	 * Adresse du serveur MongoDB
	 * @var string
	 */
	private $serverAddress;
	
	
	/**
	 * Port d'écoute du serveur MongoDB
	 * @var string
	 */
	private $serverPort;
	
	/**
	 * Utilisateur de la base de données
	 * @var string
	 */
	private $user;
	
	/**
	 * Mot de passe de l'utilisateur de base de données
	 * @var string
	 */
	private $password;
	
	/**
	 * Base de données sur laquelle se connecter
	 * @var string
	 */
	private $db;
	
	/**
	 * Instance courante de connexion MongoDB
	 * @var \MongoClient
	 */
	private $connexion;
	
	/**
	 * Options de connexion PDO
	 * @var array
	 */
	private $options = array();
	
	/**
	 * Retourne l'objet MongoDB de connexion
	 * @return \MongoClient
	 */
	public function getConnexion(){
		return $this->connexion;
	}
	
	/**
	 * Définit les paramètres de connexion à la base de données
	 * @param JSON $params
	 */
	public function setParams($params){
		$this->driver = $params->dsn;
		$this->user = $params->user;
		$this->serverAddress = $params->address;
		$this->serverPort = $params->port;
		$this->password = $params->password;
		$this->db = $params->db;
		
		// Détermine la chaîne de connexion au serveur
		$this->dsn = $this->driver;
		
		if($this->user != ""){
			$this->dsn .= $this->user;
		}
		
		if($this->password != ""){
			$this->dsn .= ":" . $this->password;
		}
		
		if($this->user != ""){
			$this->dsn .= "@";
		}
		
		$this->dsn .= $this->serverAddress;
		
		if($this->serverPort != ""){
			$this->dsn .= ":" . $this->serverPort;
		}
		
		$this->dsn .= "/" . $this->db;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne la connexion à une base de données MongoDB
	 * @param string $db
	 * @return \wp\Database\MongoDB\connect
	 */
	public function db($db=null){
		if(is_null($db)){
			return $this->connexion->selectDB($this->db);
		}
		$this->db = $db;
		return $this;
	}
	
	/**
	 * Retourne le nom de la base de données courante
	 * @return string
	 */
	public function dbName(){
		return $this->db;
	}
	/**
	 * Traite la connexion à la base de données MySQL
	 */
	public function process(){
		try{
			$this->connexion = new \MongoDB\Driver\Manager($this->dsn);
		}
		catch(\MongoDB\Driver\Exception\InvalidArgumentException $e){
			die("Impossible de se connecter à la base de données : " . $e->getMessage());
		} catch(\MongoDB\Driver\Exception\RuntimeException $e){
			die("Erreur générale MongoDB : " . $e->getMessage());
		}
	}
}