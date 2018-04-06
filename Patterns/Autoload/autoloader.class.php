<?php
/**
 * @name autoloader.class.php : Service de chargement automatique de classes
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package \wp\Patterns\Autoload
 * @version 0.1
 * @version 0.2.0 Jan. 2018 Utiliser les chemins en dur pour le chargement des classes
**/
namespace wp\Patterns\Autoload;

require_once(dirname(__FILE__) . "/../../Files/File/File.class.php");
require_once(dirname(__FILE__) . "/../../Utilities/Logger/Logger.class.php");

use \wp\Utilities\Pathes\pathes;
use \wp\Exceptions\Errors\error;
use \wp\Exceptions\Classes\classLoaderException as classException;
use \wp\Utilities\Logger\Logger as Logger;

class autoloader {
	/**
	 * Définition des chemins racines des composantes
	 * @var object
	 */
	private static $pathes;
	
	/**
	 * Enregistre la fonction de chargement automatique de classes
	 * @param array $pathes : Chemins racines des composantes de l'application
	 */
	public static function register(Pathes $pathes){
		spl_autoload_register(array(__CLASS__,"autoload"));
		self::$pathes = $pathes;
	}
	
	/**
	 * Charge la ressource correspondant à la classe passée avec ou sans
	 * 	espace de nom.
	 * @param string $class Nom complet de la classe à charger
	 */
	public static function autoload($className){
		
		$logger = new Logger("autoload");
		$logger->add($className, __FILE__, __LINE__);
		
		$namespace		= "";
		$rootNameSpace 	= "";
		
		$reflectionClass	= null; // Instance de ReflectionClass
		
		$saveClassName	= $className;
		
		$classParts = explode("\\", $className);
		
		$className = array_pop($classParts);
		$namespace = implode("\\",$classParts);
		
		$rootNameSpace = array_shift($classParts);
		
		if(!is_null($rootNameSpace)){
			$rootPath = self::$pathes->getRootPath($rootNameSpace);
			if($rootNameSpace == "App"){
				$rootPath .= $rootNameSpace . "/";
			}
		} else 
			$rootPath = "";
		
		$logger->add("Chemin racine : " . $rootPath, __FILE__, __LINE__);
			
		$fullPath = $rootPath . implode("/",$classParts) . "/";
		
		$fullClassPath = $fullPath . $className;
		
		//echo "Cherche le fichier : " . $fullClassPath . "\n";
		
		if(file_exists($fullPath . $className . ".class.php")){
			require_once($fullPath . $className . ".class.php");
			$reflectionClass = new \ReflectionClass($saveClassName);
			if(class_exists("\\" . $namespace . "\\" . $className) || $reflectionClass->isInterface()){
				return true;
			}
		} else {
			if(file_exists($fullPath . "class" . $className . ".php")){
				require_once($fullPath . "class" . $className . ".php");
				if(class_exists($className) || $reflectionClass->isInterface()){
					return true;
				}
			} else {
				if(file_exists($fullPath . $className . ".php")){
					require_once($fullPath . $className . ".php");
					$reflectionClass = new \ReflectionClass($saveClassName);
					if(class_exists($className) || $reflectionClass->isInterface()){
						return true;
					}
				}
			}
		}
		
		// Il peut s'agir aussi de classes externes dans Vendor
		$folderRoot = self::$pathes->getRootPath("wp") . "Vendor/";
		$className = strtolower($className);
		
		if($completeClassPath = self::locate($folderRoot,$className . ".php")){
			require_once($completeClassPath);
			return true;
		}
		
		if($completeClassPath = self::locate($folderRoot,$className . ".class.php")){
			require_once($completeClassPath);
			return true;
		}


		
		// Lève une exception
		require_once(self::$pathes->getRootPath("wp") . "Exceptions/Errors/error.class.php");
		require_once(self::$pathes->getRootPath("wp") . "Exceptions/Classes/classLoaderException.class.php");
		
		
		$error = new error();
		$error->message("Impossible de charger la ressource : " . $fullClassPath . " pour la classe : " . $className . " [" . $saveClassName . "])")
			->code(-10001)
			->doLog(true)
			->doRender(true);
			
		throw new classException($error);
	
	}
	
	/**
	 * Détermine si la ressource chargée est une interface
	 * @param unknown $className
	 */
	private static function isInterface($className){
		$reflexionClass = new \ReflectionClass($className);
		return $reflexionClass->isInterface();
	}
	
	private static function locate($directory,$resource){
		
		// Reformater correctement le dossier de référence
		$lastSeparator = $directory[strlen($directory)-1];
		
		if($lastSeparator != "/"){
			$directory .= "/";
		}
		
		if($directory == "/"){
			$directory = "./";
		}
		
		
		// Instancie un objet de type DirectoryIterator
		$fileList = new \DirectoryIterator($directory);
		
		foreach($fileList as $file){
			// On ne traite pas les dossiers . et ..
			if($file->isDot()){
				continue;
			}
		
			if($file->isDir() && substr($file->getFileName(),0,1) != "."){
				if($res = self::locate($directory . $file->getFileName(),$resource)){
					return $res;
				} else {
					continue;
				}
			} else {
				#begin_debug
				#echo "Trouvé : " . $file->getFileName() . " à comparer à $resource dans $directory<br />\n";
				#end_debug
				 
				if(strtolower($file->getFileName()) == $resource){
					#begin_debug
					#echo "Trouvé $directory" . $file->getFileName() . " à vérifier dans l'espace de nom " . $params["nameSpace"] . "<br />\n";
					#end_debug
					return $directory . $file->getFileName();
				}
			}
		}
		
		return false;		
	}
}