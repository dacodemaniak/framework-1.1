<?php
/**
 * @name factory.class.php Design Pattern "Class Factory"
 * @author web-Projet.com (contact@web-projet.com) - Juil. 2012
 * @package wp\Patterns\ClassFactory
 * @version 0.1.0
 * @version 0.1.1 Ajout de décorateurs
 */
namespace wp\Patterns\ClassFactory;

use \wp\Http\Request\requestData as Request;
use \wp\Exceptions\Errors\error as Error;
use \wp\Exceptions\Classes\classLoaderException as classException;

abstract class factory {
	/**
	 * Données de la requête initiale
	 * @var \wp\Http\Request\requestData
	 */
	protected $request;
	
	/**
	 * Nom de la classe à intancier
	 * @var string
	 */
	protected $className;
	
	/**
	 * Paramètres à passer au constructeur de la classe concernée
	 * @var string | array
	 */
	protected $params;
	
	/**
	 * Instance de la classe à retourner
	 * @var \Object
	 */
	protected $instance;
	
	/**
	 * Instance de la classe \ReflectionClass
	 * @var \ReflectionClass
	 */
	protected $reflection;
	
	/**
	 * Route définie pour le contrôleur courant
	 * @var \wp\Http\Routes\route
	 */
	protected $route;
	
	
	/**
	 * Définit le nom de la classe à instancier
	 * @param string $className
	 * @return multitype | wp\Patterns\factory
	 */
	protected function setClassName($className){
		if($this->classExists($className)){
			$this->className = $className;
			return $this;
		}
		return false;
	}
	
	/**
	 * Définit les données de requête à passer à la classe à instancier
	 * @param Request $request
	 */
	protected function setRequest(Request $request=null){
		if(!is_null($request))
			$this->request = $request;
		return $this;
	}
	
	/**
	 * Définit les paramètres à passer au constructeur de la classe à instancier
	 * @param multitype|array $params
	 */
	protected function setParams($params){
		$this->params = $params;
		return $this;
	}
	
	/**
	 * Prototype d'implémentation de l'instanciation des classes
	**/
	abstract protected function addInstance();
	
	/**
	 * Retourne l'instance d'une classe avec injection de dépendances
	 */
	public function getInstance(){
		$this->addInstance();
		
		if($this->instance){
			// Gère l'ajout des décorateurs
			$decorator = new \wp\Patterns\Decorator\Decorator($this->instance, $this->reflection, $this->route);
		}
		
		return $this->instance;
	}
	
	/**
	 * Détermine si la classe existe
	 * @param string $className
	 * @throws classException
	 * @return boolean
	 */
	private function classExists($className){
		
		$classParts = explode("\\",substr($className,1,strlen($className)));
		
		$rootPath = array_shift($classParts);
		
		if(!is_null(\App\appLoader::wp())){
			if($rootPath != "wp")
				$fullClassPath = \App\appLoader::wp()->getPathes()->getRootPath($rootPath) . "$rootPath/" . implode("/", $classParts);
			else 
				$fullClassPath = \App\appLoader::wp()->getPathes()->getRootPath($rootPath) . "/" . implode("/", $classParts);
		} else {
			if($rootPath != "wp"){
				$fullClassPath = \Backend\appLoader::wp()->getPathes()->getRootPath($rootPath) . "/" . implode("/", $classParts);
			} else {
				$fullClassPath = \Backend\appLoader::wp()->getPathes()->getRootPath($rootPath) . "/" . implode("/", $classParts);
			}
		}
		
		//1. On vérifie la version .class.php
		if(file_exists($fullClassPath . ".class.php"))
			return true;
		
		//2. On vérifie juste avec .php
		if(file_exists($fullClassPath . ".php"))
			return true;
		
		//3. Dans le cas du Backend, peut être faut-il rediriger la demande vers App
		if(is_null(\App\appLoader::wp())){
			if($rootPath != "wp")
				$fullClassPath = \Backend\appLoader::wp()->getPathes()->getRootPath("App") . "App/" . implode("/", $classParts);
			else
				$fullClassPath = \Backend\appLoader::wp()->getPathes()->getRootPath("App") . "/" . implode("/", $classParts);			
		}
		
		//1. On vérifie la version .class.php
		if(file_exists($fullClassPath . ".class.php"))
			return true;
		
		//2. On vérifie juste avec .php
		if(file_exists($fullClassPath . ".php"))
			return true;
			
		$error = new Error();
		$error->message("Le fichier de classe : " . $fullClassPath . " pour la classe : " . $className . " n'existe pas pour l'instanciation")
			->code(-10002)
			->doLog(true)
			->doRender(true);
				
		throw new classException($error);
	}
	
}