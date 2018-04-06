<?php
/**
* @name Logger.class.php Service de gestion des logs des applications
* @author IDEA Factory (jean-luc.a@ideafactory.fr) - Jan. 2018
* @package \wp\Utilities\Logger
* @version 0.1.0
*/

namespace wp\Utilities\Logger;

use \wp\Files\File\File as File;

class Logger {
	
	/**
	 * Instance d'objet \wp\Files\File
	 * @var \File
	 */
	private $fileManager;
	
	/**
	 * Vrai si on doit forcer l'écriture du log
	 * @var boolean
	 */
	private $forceLog = false;
	
	/**
	 * Chemin vers le dossiers de logs
	 * @var string
	 */
	const LOG_PATH = "./_logs/";
	
	/**
	 * Séparateur de champs "tabulation"
	 * @var string
	 */
	const FS = "\t";
	
	/**
	 * Séparateur de lignes "retour chariot"
	 * @var string
	 */
	const LS = "\r\n";
	
	/**
	 * Constructeur de la classe courante
	 * @param string $fileName Nom du fichier de log
	 */
	public function __construct(string $fileName, $force=false){
		$this->forceLog = $force;
		
		if(!is_null(\App\appLoader::wp()) && \App\appLoader::wp()->runMode() == "prod"){
			if($this->forceLog){
				$this->fileName = $this->_makeFile($fileName);
			}
		} else {
			$this->fileName = $this->_makeFile($fileName);
		}
		
	}
	
	/**
	 * Ecrit une nouvelle ligne de log
	 * @param string $content
	 */
	public function add(string $content, string $fileName, int $lineNumber){
		if(!is_null(\App\appLoader::wp()) && \App\appLoader::wp()->runMode() == "prod"){
			if($this->forceLog){
				$this->_add($content, $fileName, $lineNumber);
			}
		} else {
			$this->_add($content, $fileName, $lineNumber);
		}
	}
	
	/**
	 * Crée ou récupère l'instance de \File et ajoute le cas échéant l'en-tête
	 * @param string $fileName
	 */
	private function _makeFile(string $fileName){
		$this->fileManager = new File(self::LOG_PATH, $fileName . ".log", "r+");
		if($this->fileManager->isNew()){
			$fileContent = "Date" . self::FS;
			$fileContent .= "Nom du fichier" . self::FS;
			$fileContent .= " N° de ligne" . self::FS;
			$fileContent .= "Contenu" . self::LS;
			
			$this->fileManager->write($fileContent);
		}
	}
	
	/**
	 * Ecrit une nouvelle ligne dans le fichier de log
	 * @param string $content
	 * @param string $fileName
	 * @param int $lineNumber
	 */
	private function _add(string $content, string $fileName, int $lineNumber){
		$date = new \DateTime();
	
		$fileContent = $date->format("d-m-Y H:i:s") . self::FS;
		$fileContent .= $fileName . self::FS;
		$fileContent .= " [" . $lineNumber . "]" . self::FS;
		$fileContent .= $content . self::LS;
	
		$this->fileManager->write($fileContent);
	}
}