<?php
/**
 * @name asset.class.php Factory spécifique pour la gestion des ressources de l'application
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

use \wp\Http\Request\requestData as Request;
use \wp\Http\Routes\route as Route;

class asset extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si on peut ajouter une instance de la classe concernée
	 * @var boolean
	 */
	private $addInstance		= true;
	
	/**
	 * Instancie la création d'un nouvel objet de type Asset
	 * @param Request $request
	 */
	public function __construct(Route $route){
		$this->route = $route;
		$this->addInstance = $this->setClassName($route->getNameSpace() . $route->getClassName());
	}
	
	/**
	 * Instancie la classe concernée et retourne l'objet concerné
	 * {@inheritDoc}
	 * @see \wp\Patterns\ClassFactory\factory::addInstance()
	 */
	public function addInstance(){
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