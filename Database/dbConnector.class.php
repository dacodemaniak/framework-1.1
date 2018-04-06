<?php
/**
 * @name dbConnector.class.php Singleton de connexion à une base de données
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database
 * @version 1.0
 * @todo Déc. 2016 : Modifier la méthode ajaxInstance()
**/
namespace wp\Database;
use wp\Patterns\ClassFactory\database as Database;

class dbConnector {
	/**
	 * Instance courante de l'objet
	 * @var \wp\Database
	 */
	private static $instance;
	
	/**
	 * Objet de connexion à une base de données
	 * @var Object
	 */
	private $connector;
	
	/**
	 * Connexion active sur la base de données
	 * @var \PDO
	 */
	private $activeConnexion;
	
	/**
	 * Définit le fournisseur de données
	 * @var string
	 */
	private $provider;
	
	/**
	 * Constructeur privé pour gérer le singleton de connexion
	 */
	private function __construct(){
		$params = $this->readConfiguration();
		
		if(!is_null($params)){
			// Instancie une connexion vers le fournisseur concerné
			$factory = new Database($this->provider);
			$factory->addInstance();
			$this->connector = $factory->instance();
			
			if(!is_null($this->connector)){
				$this->connector->setParams($params)
					->process();
				$this->activeConnexion = $this->connector->getConnexion();
			}
		}
	}
	
	/**
	 * Récupère l'instance de connexion à une base de données
	 */
	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new dbConnector();
		}
		
		return self::$instance;
	}
	
	public function dbName(){
		return $this->connector->dbName();
	}
	
	/**
	 * Retourne l'instance courante de connexion à une base de données
	 */
	public function connexion(){
		return $this->activeConnexion;
	}
	
	/**
	 * Retourne le fournisseur de données
	 */
	public function getProvider(){
		return $this->provider;
	}
	
	/**
	 * Lit la configuration de connexion à la base de données
	 */
	private function readConfiguration(){
		$configFile = "_commun/configs/dbConfigure.json";
		
		if(!file_exists($configFile)){
			if(!is_null(\App\appLoader::wp())){
				$configFile = \App\appLoader::wp()->getPathes()->relativePath() . "_commun/configs/dbConfigure.json";
			} else {
				$configFile = \Backend\appLoader::wp()->getPathes()->relativePath() . "_commun/configs/dbConfigure.json";
			}
		}
		
		$config = file_get_contents($configFile);
			
		$dbConfig = json_decode($config);
			
		$this->provider = $dbConfig->provider;
			
		return $dbConfig->production;
	}
}