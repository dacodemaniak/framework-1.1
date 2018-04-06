<?php
/**
 * @name controller.class.php Classe spécifique pour l'instanciation d'un objet de type Contrôleur
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Patterns\ClassFactory
 * @version 1.0
**/

namespace wp\Patterns\ClassFactory;

use \wp\Http\Request\requestData as Request;
use \wp\Http\Routes\route as Route;

class controller extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si on peut ajouter une instance de la classe concernée
	 * @var boolean
	 */
	private $addInstance		= true;
	
	/**
	 * Instancie la création d'un nouvel objet de type Controller
	 * @param Request $request
	 */
	public function __construct(Route $route){
		if(($this->addInstance = $this->setClassName($route->getNameSpace() . $route->getClassName())) != false){
			$this->route = $route;
			$this->setRequest($route->getQuery());
		}
	}
	
	/**
	 * Instancie la classe concernée et retourne le contrôleur
	 * {@inheritDoc}
	 * @see \wp\Patterns\ClassFactory\factory::addInstance()
	 */
	protected function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			if(!is_null($this->params)){
				if(is_array($this->params)){
					$this->params[] = $this->request;
				} else {
					$params = array($params,$this->request);
					$this->params = $params;
				}
			} else {
				$this->params = array($this->request);
			}
			
			$this->instance = $this->reflection->newInstanceArgs($this->params);
		}
		return;
	}
}