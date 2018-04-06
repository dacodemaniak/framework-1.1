<?php
/**
 * @name controller.class.php Classe spécifique pour l'instanciation d'un objet de type Contrôleur
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Patterns\ClassFactory
 * @version 1.0
**/

namespace wp\Patterns\ClassFactory;

use \wp\Http\Routes\route as Route;

class activeRecord extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si on peut ajouter une instance de la classe concernée
	 * @var boolean
	 */
	private $addInstance		= true;
	
	/**
	 * Mapper associé
	 * @var string
	 */
	private $mapperClass;
	/**
	 * Instancie la création d'un nouvel objet de type activeRecord
	 */
	public function __construct(Route $route){
		$this->route = $route;
		$this->mapperClass = str_replace("Common\\","",$route->getNameSpace()) . str_replace("ActiveRecord","",$route->getClassName()) . "Store";
		
		$this->addInstance = $this->setClassName($route->getNameSpace() . $route->getClassName());
	}
	
	/**
	 * Instancie la classe concernée et retourne le contrôleur
	 * {@inheritDoc}
	 * @see \wp\Patterns\ClassFactory\factory::addInstance()
	 */
	protected function addInstance(){
		if($this->addInstance){
			// Instancie le Mapper associé
			$reflection = new \ReflectionClass($this->mapperClass);
			$mapper = $reflection->newInstance();
			
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $this->reflection->newInstanceArgs(array($mapper));
		}
		return;
	}
}