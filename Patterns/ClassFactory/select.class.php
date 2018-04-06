<?php
/**
 * @name select.class.php Factory spécifique pour la création d'un objet de requête de base de données
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

use \wp\Database\dbConnector as dbConnector;

class select extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si l'instanciation peut avoir lieu
	 * @var boolean
	 */
	private $addInstance = true;
	
	/**
	 * Définit le magasin de données à traiter
	 * @var Object
	 */
	private $store;
	
	/**
	 * Définit si la requête doit utiliser ou non les relations entre les tables
	 * @var boolean
	 */
	private $useRelations;
	
	
	/**
	 * Instancie un nouvel objet de connexion à une base de données
	 * @param object $store Définition du magasin de données à traiter
	 */
	public function __construct($store, $useRelations){
		$this->store = $store;
		$this->useRelations = $useRelations;
		
		$dbConnector = dbConnector::instance();
		
		if($this->addInstance = $this->setClassName("\\wp\\Database\\" . $dbConnector->getProvider() . "\\Query\\select") != false){
			$this->addInstance();
		}
	}
	
	public function instance(){
		return $this->instance;
	}
	
	public function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $this->reflection->newInstanceArgs(array($this->store, $this->useRelations));
		}
		/**
		 * @todo Générer une exception impossible d'instancier l'objet
		 */
		return;
	}
}