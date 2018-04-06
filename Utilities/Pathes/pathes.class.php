<?php
/**
 * @name pathes.class.php Service de définition des chemins vers les composantes
 * 	des applications
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Utilities\Pathes
 * @version 1.0
**/
namespace wp\Utilities\Pathes;

use \wp\Exceptions\Errors\error;
//use \wp\Exceptions\Classes\classPathException;

class pathes {
	/**
	 * Chemins vers les ressources à charger
	 * @var array
	 */
	private $pathes = array();
	
	/**
	 * Ajoute un chemin dans le gestionnaire de l'application
	 * @param string $virtualName
	 * @param string $path
	 */
	public function addPath($virtualName,$path){
		if(!array_key_exists($virtualName,$this->pathes)){
			if(substr($path,-1) != "/")
				$path .= "/";
			
			// On vérifie la base pour l'exécution du programme
			if($virtualName != "wp"){
				if(array_key_exists("BASE", $_SERVER)){
					$path = substr($path, 0, strlen($path)-1) . $_SERVER["BASE"]. "/";
				}
			}
			$this->pathes[$virtualName] = $path;
		}
		return $this;
	}
	
	public function getRootPath($component){
		if(array_key_exists($component, $this->pathes)){
			return str_replace("\\","/",$this->pathes[$component]);
		} else {
			// Il s'agit de classes dans Vendor
			return $this->pathes["wp"] . "Vendor/" . $component . "/";
		}
		
		// Lève une exception
		require_once($this->pathes["wp"]. "Exceptions/Errors/error.class.php");
		require_once($this->pathes["wp"]. "Exceptions/Classes/classPathException.class.php");
		
		if(!class_exists("\wp\Exceptions\Classes\classPathException")){
			die("La classe classPathException n'est pas définie");
		}
		$error = new error();
		$error->message("Le chemin pour la clé : " . $component . " est introuvable.")
			->code(-10002)
			->doLog(true)
			->doRender(true);

		throw new \wp\Exceptions\Classes\classPathException($error);
		
		return false;
	}
	
	/**
	 * Retourne le chemin relatif vers la racine à partir du script en cours d'exécution
	 * @return string
	 */
	public function relativePath(){
		$path = str_replace("\\","/",$_SERVER["SCRIPT_FILENAME"]);
		
		$relativePath = "";
		
		if($path != $_SERVER["DOCUMENT_ROOT"]){
			$strippedPath = str_replace($_SERVER["DOCUMENT_ROOT"],"",$path);
			$pathes = explode("/", $strippedPath);
			
			#begin_debug
			#var_dump($pathes);
			#end_debug
			
			if(is_null(\App\appLoader::wp()))
				$relative = sizeof($pathes) - 2;
			else 
				$relative = sizeof($pathes) - 1;
			
			for($i=0; $i<$relative; $i++){
				$relativePath .= "../";
			}
			return $relativePath;
		}
		return "./";
	}
}