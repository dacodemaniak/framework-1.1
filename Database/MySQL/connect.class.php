<?php
/**
 * @name connect.class.php Initialise une connexion à une base de données MySQL
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\MySQL
 * @version 1.0
**/

namespace wp\Database\MySQL;

class connect {
	/**
	 * Chaîne de connexion
	 * @var string
	 */
	private $dsn;
	
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
	 * Instance courante de connexion PDO
	 * @var \PDO
	 */
	private $connexion;
	
	/**
	 * Options de connexion PDO
	 * @var array
	 */
	private $options = array(
			\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
	);
	
	/**
	 * Retourne l'objet PDO de connexion
	 * @return PDO
	 */
	public function getConnexion(){
		return $this->connexion;
	}
	
	/**
	 * Définit les paramètres de connexion à la base de données
	 * @param JSON $params
	 */
	public function setParams($params){
		$this->dsn = $params->dsn;
		$this->user = $params->user;
		$this->password = $params->password;
		
		return $this;
	}
	
	/**
	 * Traite la connexion à la base de données MySQL
	 */
	public function process(){
		try{
			$this->connexion = new \PDO($this->dsn,$this->user,$this->password,$this->options);
		}
		catch(\PDOException $e){
			die("Impossible de se connecter à la base de données : " . $e->getMessage());
		}		
	}
	
	
}