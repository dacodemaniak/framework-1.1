<?php
/**
 * @name update.class.php Factory spécifique pour la mise à jour d'un objet de création de données de base de données
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

use \wp\Database\dbConnector as dbConnector;

class update extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si l'instanciation peut avoir lieu
	 * @var boolean
	 */
	private $addInstance = true;
	
	/**
	 * Définit le magasin de données à traiter
	 * @var Object
	 */
	private $activeRecord;
	
	/**
	 * Instancie un nouvel objet de connexion à une base de données
	 * @param object $activeRecord Enregistrement actif
	 */
	public function __construct($activeRecord){
		$this->activeRecord = $activeRecord;
		
		$dbConnector = dbConnector::instance();
		
		if($this->addInstance = $this->setClassName("\\wp\\Database\\" . $dbConnector->getProvider() . "\\Query\\update") != false){
			$this->addInstance();
		}
	}
	
	public function instance(){
		return $this->instance;
	}
	
	public function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $reflection->newInstanceArgs(array($this->activeRecord));
		}
		/**
		 * @todo Générer une exception impossible d'instancier l'objet
		 */
		return;
	}
}