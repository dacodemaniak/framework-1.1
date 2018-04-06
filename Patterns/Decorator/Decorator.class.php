<?php
/**
* @name Decorator.class.php Pattern Decor
* @author IDea Factory (dev-team@ideafactory.fr) - Fév. 2018
* @package wp\Patterns\Decor
* @version 0.1.0
**/
namespace wp\Patterns\Decorator;

use \wp\Patterns\ClassFactory\DecoratorFactory as DecorFactory;
use \wp\Http\Routes\route as Route;
use \wp\Annotations\ReflectionAnnotatedClass;

class Decorator {
	/**
	 * Instance d'une classe
	 * @var Object
	 */
	private $instance;
	
	/**
	 * Instance de la classe ReflectionClass
	 * @var \ReflectionClass
	 */
	private $reflection;
	
	/**
	 * Instance de la route du contrôleur
	 * @var Route
	 */
	private $route;
	
	/**
	 * Définit les classes de décor
	 * @var array
	 */
	private $decors;
	
	/**
	 * Gère la création des Decorators de la classe
	 * @param Object $instance
	 * @param \ReflectionClass $reflection Instance de la classe \ReflectionClass
	 */
	public function __construct($instance, \ReflectionClass $reflection, Route $route=null){
		$this->instance = $instance;
		$this->reflection = $reflection;
		
		if(!is_null($route)){
			$this->route = $route;
		
			$this->decors = $this->parseComments();
			if(count($this->decors))
				$this->decor();
		}
	}
	
	/**
	 * Crée les décorateurs proprement dit
	 */
	private function decor(){
		if(count($this->decors)){
			for($i = 0; $i < count($this->decors); $i++){
				$classParts = explode("\\", $this->decors[$i]);
				// Le dernier élément est le nom de la classe elle-même
				$className = array_pop($classParts);
				$decoratorName = strtolower($className);
				$classNS = join("\\", $classParts);
				$fullClassName = $classNS . "\\" . $className;
				
				#begin_debug
				#echo "Classe Decorator : " . $fullClassName . "<br>";
				#end_debug
				
				$factory = new DecorFactory($fullClassName);
				 
				$object = $factory->getInstance();
				
				// Définit l'attribut de la classe contenant le Decorator
				$this->instance->setDecorator($decoratorName,$object);
			}
		}
	}
	
	/**
	 * Parse les commentaires de la classe pour définir les injections
	 * @return <string>[]
	 */
	private function parseComments(){
		$decorators = array();
		
		// On commence par Addendum
		$reflection = new ReflectionAnnotatedClass($this->instance);
		if($reflection->hasAnnotation("Decorator")){
			echo "<br>";
			echo " Addendum => avec Decorator<br>";
			// Traite à partir des annotations
			$decorators = $reflection->getAllAnnotations("Decorator");

		} else {
			// Sinon, inspection directe via Reflection
			// @todo Vérifier le parsing du commentaire
			$comments = $this->reflection->getDocComment();
			if($comments){
				#begin_debug
				#echo "Charge les décorateurs à partir des annotations<br>";
				#var_dump($comments);
				#end_debug
				$commentLines = explode("\n", $comments);
				foreach ($commentLines as $commentLine){
					if(count($parts = explode("@Decorator", $commentLine)) > 1){
						//var_dump($parts);
						$parts = explode(" ", $parts[1]);
						if (count($parts) > 1) {
							$key = str_replace("\n", "", $parts[1]);
							$decorators[] = str_replace("\r", "", $key);
						}
					}
				}
			} else {
				// Commentaires non disponibles, on vérifie la route
				$decorators = $this->route->decorators();
			}
		}
		
		/*
		$comments = $this->reflection->getDocComment();
		if($comments){
			#begin_debug
			#echo "Charge les décorateurs à partir des annotations<br>";
			#end_debug
			$commentLines = explode("\n", $comments);
			foreach ($commentLines as $commentLine){
				echo "Commentaire : " . $commentLine . "<br>";
				if(count($parts = explode("@Decorator", $commentLine)) > 1){
					$parts = explode(" ", $parts[1]);
					if (count($parts) > 1) {
						$key = str_replace("\n", "", $parts[1]);
						$decorators[] = str_replace("\r", "", $key);
					}
				}
			}
		} else {
			$reflection = new ReflectionAnnotatedClass($this->instance);
			if($reflection->hasAnnotation("Decorator")){
				// Traite à partir des annotations
				$decorators = $reflection->getAllAnnotations("Decorator");
				echo "<br>";
				echo " => avec Decorator<br>"; 
			} else {
				// Commentaires non disponibles, on vérifie la route
				$decorators = $this->route->decorators();
			}
		}
		*/
		return $decorators;
	}
}