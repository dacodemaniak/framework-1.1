<?php
/**
 * @name DataUpdate Factory spécifique pour la création d'un objet de requête de base de données
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

use \wp\Database\dbConnector as dbConnector;

class DataUpdate extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si l'instanciation peut avoir lieu
	 * @var boolean
	 */
	private $addInstance = true;
	
	/**
	 * Instancie un nouvel objet de connexion à une base de données
	 */
	public function __construct(){
		
		$dbConnector = dbConnector::instance();
		
		if($this->addInstance = $this->setClassName("\\wp\\Database\\" . $dbConnector->getProvider() . "\\Query\\Update") != false){
			$this->addInstance();
		}
	}
	
	/**
	 * Retourne l'instance de la classe Get concrète
	 * @return \wp\Patterns\ClassFactory\Object
	 */
	public function instance(){
		return $this->instance;
	}
	
	protected function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $this->reflection->newInstanceArgs();
		}
		/**
		 * @todo Générer une exception impossible d'instancier l'objet
		 */
		return;
	}
}