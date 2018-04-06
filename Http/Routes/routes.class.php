<?php
/**
 * @name routes.class.php Service de gestion des routes de l'application
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Http\Routes
 * @version 1.0
**/
namespace wp\Http\Routes;

use \wp\Http\Routes\route as Route;
use \wp\Utilities\Pathes\pathes as Pathes;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\Routes\routeException as RouteException;

class routes {
	/**
	 * Collection des routes de l'application
	 * @var array
	 */
	private $routes;
	
	/**
	 * Dossier de base, sans vhost (exécution dans un dossier particulier)
	 * @var string
	 */
	private $base;
	
	/**
	 * Instancie une nouvelle collection des routes de l'application
	 */
	public function __construct(){
		$this->base = "";
		
		if($_SERVER["SERVER_NAME"] == "127.0.0.1" || $_SERVER["SERVER_NAME"] == "localhost"){
			$this->base = $_SERVER["BASE"];
		}
		$this->routes = array();
		$this->hydrate();
	}
	
	/**
	 * Retourne un objet Route par le nom de la route
	 * @param string $name
	 * @return \wp\Http\Routes\route | null
	 */
	public function routeByName($name){
		foreach($this->routes as $route){
			if($route->name() == $name){
				return $route;
			}
		}
		
		return false;
	}
	
	/**
	 * Alimente la liste des routes de l'application
	 */
	private function hydrate(){
		if(!is_null(\App\appLoader::wp()))
			$pathes = \App\appLoader::wp()->getPathes()->getRootPath("App") . "_commun/configs/appPathes.json";
		else 
			$pathes = \Backend\appLoader::wp()->getPathes()->getRootPath("App") . "_commun/configs/appPathes.json";

		if(file_exists($pathes)){
			$jsonContent = file_get_contents($pathes);
				
			$routes = json_decode($jsonContent);
				
			if(!is_null($routes)){
				foreach($routes as $name => $object){
					$route = new Route();
					$route->name($name)
						->setClassName($object->class)
						->setNamespace($object->namespace)
						->setMethod($object->method)
						->renderMode($object->rendermode);
						
					if(property_exists($object, "url"))
						$route->url($object->url);
						
					if(property_exists($object,"params")){
						$route->params($object->params);
					}
					
					if(property_exists($object, "decorators")){
						foreach($object->decorators as $decorator){
							$route->decorators($decorator);
						}
					}
					/**
					* @todo Intégrer les paramètres attendus
					**/
					$this->routes[] = $route;
							
				}
			} else {
				// Problème de lecture des routes
				$error = new error();
					
				$message = "Le fichier de routes : " . $pathes . " est incorrect.<br />\nVérifiez la syntaxe JSON.";
					
				$error->message($message)
					->code(-98001)
					->doLog(true)
					->doRender(true);
				throw new RouteException($error);
					
				return false;
					
			}
		} else {
			// Problème de disponibilités du fichier de configuration
			$error = new error();
			
			$message = "Le fichier de configuration des routes : " . $pathes . " n'existe pas ou n'est pas placé dans le bon dossier.<br />\nVérifiez votre arborescence.";
			
			$error->message($message)
			->code(-98000)
			->doLog(true)
			->doRender(true);
			throw new RouteException($error);
			
			return false;
		}
	}
	
}