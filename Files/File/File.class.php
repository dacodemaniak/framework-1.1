<?php
/**
* @name File.class.php Service de gestion de fichiers
* @author IDEAFactory (jean-luc.a@ideafactory.fr)
* @package wp\Files\File
* @version 0.1.0
*/
namespace wp\Files\File;

class File {
	
	/**
	 * Dossier de stockage du fichier
	 * @var string
	 */
	private $path;
	
	/**
	 * Nom du fichier à traiter
	 * @var string
	 */
	private $name;
	
	/**
	 * Mode d'ouverture du fichier
	 * @var string
	 */
	private $mode;
	
	/**
	 * Handler sur le fichier à gérer
	 * @var Resource
	 */
	private $handler;
	
	/**
	 * Définit s'il s'agit d'un nouveau fichier ou non
	 * @var boolean
	 */
	private $isNew = false;
	
	/**
	 * Constructeur de la classe courante
	 * @param string $path
	 * @param string $name
	 */
	public function __construct(string $path, string $name, string $mode="a+"){
		$this->path = $path;
		$this->name = $name;
		
		$this->mode = $mode;
		
		$this->_checkFile();
	}
	
	/**
	 * Retourne l'état du fichier Nouveau ou Déjà existant
	 * @return boolean
	 */
	public function isNew(){
		return $this->isNew;
	}
	
	/**
	 * Destructeur de la classe courante
	 */
	public function __destruct(){
		fclose($this->handler);	
	}
	
	/**
	 * Ecrit une nouvelle données dans le fichier
	 * @param string $content
	 */
	public function write(string $content){
		fwrite($this->handler, $content);
	}
	
	/**
	 * Vérifie et crée le fichier si nécessaire
	 */
	private function _checkFile(){
		$fullFilePath = $this->path . $this->name;
		if(!file_exists($fullFilePath)){
			$this->handler = fopen($fullFilePath, "x");
			$this->isNew = true;
		} else {
			$this->handler = fopen($fullFilePath, $this->mode);
		}
	}
}