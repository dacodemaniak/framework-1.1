<?php
/**
 * @name assets.class.php Services de gestion des données d'en-tête HTML
 * 	CSS, Javascripts, etc.
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Html
 * @version 1.0
**/
namespace wp\Html\Assets;

use \wp\Exceptions\Errors\error as Error;
use \wp\Exceptions\Assets\AssetException as AssetException;

class assets {
	/**
	 * Structure de stockage des ressources CSS
	 * @var array
	 */
	private $cssResources = array();
	
	/**
	 * Structure de stockage des ressources Javascript
	 * @var array
	 */
	private $jsResources = array();
	
	/**
	 * Traite la récupération des _assets
	 * @param Object $assets
	 */
	public function process($assets){
		
		$resourceFolder = "";
		$baseFolderName = "";
		
		if(!is_null($assets) && count($assets)){
			foreach($assets as $asset){
				$resources = $asset->resources;
				foreach($resources as $resource){
					
					if($asset->type != "npm"){
						// Nom de la ressource
						if($asset->name != ""){
							$baseFolderName = "_assets/" . $resource->type . "/" . $asset->name . "/";
						} else {
							$baseFolderName = "_assets/" . $resource->type . "/";
						}
	
						// Version
						if($asset->version != ""){
							$baseFolderName .= $asset->version . "/";
						}
					} else {
						// Il s'agit d'une ressource de node_modules
						$baseFolderName = "_assets/node_modules/" . $asset->name . "/";
					}
					
					try {
						// Tester si la ressource existe...
						if(file_exists($baseFolderName)){
							$route = new \wp\Http\Routes\route();
							$route->setNamespace("::wp::Html::Assets::" .  ucfirst($resource->type) . "::")
								->setClassName($resource->type);
									
							$factory = new \wp\Patterns\ClassFactory\asset($route);
							$object = $factory->getInstance();
							#begin_debug
							#echo "Ajoute la ressource /_assets/" . $resource->type . "/" . $baseFolderName . $resource->file . "<br />\n";
							#end_debug
							$object->path($baseFolderName)
								->file($resource->file)
								->type($resource->type)
								->signature();
							$this->add($object);
						} else {
							$error = new Error();
							$error->message("[" . $baseFolderName . "] La ressource demandée n'existe pas dans le dossier _assets")
								->code(-11002)
								->file(__FILE__)
								->line(__LINE__)
								->doRender(false)
								->doLog(true);
							throw new assetException($error);
						}
					} catch(\Exception $e){ 
						// NOOP 
					}
					
					$baseFolderName = "";
					$resourceFolder = "";
				}
			}
		}
	}
	
	/**
	 * Ventile la ressource dans les structures correspondantes
	 * @param JSON object $resource
	 */
	public function add($resource){
		switch ($resource->type()){
			case "css":
				if(!array_key_exists($resource->getSignature(), $this->cssResources)){
					$this->cssResources[$resource->getSignature()] = $resource;
				}
			break;
			
			case "javascript":
				if(!array_key_exists($resource->getSignature(), $this->jsResources)){
					$this->jsResources[$resource->getSignature()] = $resource;
				}
			break;
		}
	}
	
	/**
	 * Retourne les ressources CSS à charger
	 */
	public function css(){
		if(sizeof($this->cssResources)){
			return $this->cssResources;
		}
	}
	
	/**
	 * Retourne les ressources Javascript à charger
	 */
	public function javascript(){
		if(sizeof($this->jsResources)){
			return $this->jsResources;
		}
	}
}