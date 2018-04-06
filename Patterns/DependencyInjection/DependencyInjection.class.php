<?php
/**
* @name DependencyInjection.class.php Service pour l'injection de dépendances
* @author IDea Factory (dev-team@ideafactory.fr) - Fév. 2018
* @package wp\Patterns\DependencyInjection
* @version 0.1.0
**/
namespace wp\Patterns\DependencyInjection;

use \wp\Patterns\ClassFactory\InjectionFactory as Injection;

class DependencyInjection {
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
	 * Définit les classes à injecter
	 * @var array
	 */
	private $injections;
	
	/**
	 * Gère l'injection de dépendances dans une classe
	 * @param Object $instance
	 * @param \ReflectionClass $reflection Instance de la classe \ReflectionClass
	 */
	public function __construct($instance, \ReflectionClass $reflection){
		$this->instance = $instance;
		$this->reflection = $reflection;
		
		$this->injections = $this->parseComments();
		
		$this->inject();
	}
	
	/**
	 * Traite l'injection de dépendances proprement dite
	 */
	private function inject(){
		if(count($this->injections)){
			for($i = 0; $i < count($this->injections); $i++){
				$classParts = explode("\\", $this->injections[$i]);
				// Le dernier élément est le nom de la classe elle-même
				$className = array_pop($classParts);
				$injectedAttributeName = strtolower($className);
				$classNS = join("\\", $classParts);
				$fullClassName = $classNS . "\\" . $className;
				
				#begin_debug
				#echo "Classe à injecter : " . $fullClassName . "<br>";
				#end_debug
				
				$factory = new Injection($fullClassName);
				 
				$object = $factory->getInstance();
				var_dump($object);
				
				// Ajoute un attribut contenant la dépendance
				$object->{$injectedAttributeName} = $object;
			}
		}
	}
	
	/**
	 * Parse les commentaires de la classe pour définir les injections
	 * @return <string>[]
	 */
	private function parseComments(){
		$classesToInject = array();
		
		$comments = $this->reflection->getDocComment();
		
		if($comments){
			$commentLines = explode("\n", $comments);
			foreach ($commentLines as $commentLine){
				if(count($parts = explode("@Inject", $commentLine)) > 1){
					$parts = explode(" ", $parts[1]);
					if (count($parts) > 1) {
						$key = str_replace("\n", "", $parts[1]);
						$classesToInject[] = str_replace("\r", "", $key);
					}
				}
			}
		}
		
		return $classesToInject;
	}
}