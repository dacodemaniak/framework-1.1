<?php
/**
 * @name database.class.php Factory spécifique pour la connexion à une base de données
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

class database extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si l'instanciation peut avoir lieu
	 * @var boolean
	 */
	private $addInstance = true;
	
	/**
	 * Définit le fournisseur de données
	 * @var string
	 */
	private $provider;
	
	
	/**
	 * Instancie un nouvel objet de connexion à une base de données
	 * @param string $provider
	 */
	public function __construct($provider){
		$this->provider = $provider;
		
		if($this->addInstance = $this->setClassName("\\wp\\Database\\" . $provider . "\\connect") != false){
			$this->addInstance();
		}
	}
	
	public function instance(){
		return $this->instance;
	}
	
	public function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $this->reflection->newInstance();
		}
		/**
		 * @todo Générer une exception impossible d'instancier l'objet
		 */
		return;
	}
}