<?php
/**
 * @name asset.class.php Services pour la gestion des ressources de l'application
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Assets
 * @version 1.0
**/
namespace wp\Html\Assets;

abstract class asset {
	/**
	 * Signature de la ressource concernée
	 * @var string
	 */
	protected $signature;
	
	/**
	 * Chemin complet vers la ressource
	 * @var string
	 */
	protected $path;
	
	/**
	 * Fichier qualifié de la ressource
	 * @var string
	 */
	protected $file;
	
	/**
	 * Type de ressource
	 * @var string
	 */
	protected $type;
	
	/**
	 * Définit ou retourne le chemin complet vers la ressource
	 * @param unknown $path
	 */
	public function path($path=null){
		if(!is_null($path)){
			$this->path = "/" . $path;
			return $this;
		}
		return $this->path;
	}
	
	/**
	 * Définit ou retourne le fichier qualifié de la ressource
	 * @param unknown $file
	 */
	public function file($file=null){
		if(!is_null($file)){
			$this->file = $file;
			return $this;
		}
		return $this->file;
	}
	
	/**
	 * Génère la signature de la ressource
	 */
	public function signature(){
		$fullQualifiedResourceName = $this->path . $this->file;
		$this->signature = sha1($fullQualifiedResourceName);
	}
	
	/**
	 * Retourne la signature de la ressource
	 */
	public function getSignature(){
		return $this->signature;
	}
	
	/**
	 * Définit ou retourne le type de la ressource
	 * @param string $type
	 */
	public function type($type=null){
		if(!is_null($type)){
			$this->type = $type;
			return $this;
		}
		return $this->type;
	}
	
	/**
	 * Retourne le chemin complet vers la ressource concernée
	 */
	abstract public function get();
}