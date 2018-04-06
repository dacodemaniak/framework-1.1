<?php
/**
 * @name response.class.php Génère les données de réponse HTTP
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Http\Response
 * @version 1.0
 */
namespace wp\Http\Response;

abstract class response {
	/**
	 * Collection des contrôleurs à passer à la vue
	 * @var array
	 */
	protected $controllers;
	
	/**
	 * Moteur de gestion de tempate utilisé
	 * @var object
	 */
	protected $engine;
	
	/**
	 * Ajoute automatiquement les contrôleurs communs de l'application
	 */
	protected function addCommonControllers(){
		$renderMode = is_null(\App\appLoader::wp()) ? \Backend\appLoader::wp()->request()->getRoute()->renderMode() : \App\appLoader::wp()->request()->getRoute()->renderMode();
		$controllers = is_null(\App\appLoader::wp()) ? \Backend\appLoader::$controllers : \App\appLoader::$controllers;
		if($renderMode == "page"){
			if(sizeof($controllers)){
				foreach($controllers as $controller){
					#begin_debug
					#echo "Ajoute le contrôleur : " . $controller->name() . " à la pile.<br />\n";
					#end_debug
					$this->controllers[] = $controller;
				}
			}
		}
		return $this;
	}
	
	/**
	 * Ajoute un contrôleur à la piles des contrôleurs à traiter
	 * @param object $controller
	 */
	public function addController($controller){
		$this->controllers[] = $controller;
		$this->addCommonControllers();
		return $this;
	}
}