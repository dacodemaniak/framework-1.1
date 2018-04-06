<?php
/**
 * @name InjectionFactory.class.php Factory spécifique pour l'injection de dépendances
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;


class InjectionFactory extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si on peut ajouter une instance de la classe concernée
	 * @var boolean
	 */
	private $addInstance		= true;
	
	/**
	 * Instancie la création d'une nouvelle Injection
	 * @param string $className Nom complet de la classe à instancier
	 */
	public function __construct(string $className){
		$this->addInstance = $this->setClassName($className);
	}
	
	/**
	 * Instancie la classe concernée et retourne l'objet concerné
	 * {@inheritDoc}
	 * @see \wp\Patterns\ClassFactory\factory::addInstance()
	 */
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