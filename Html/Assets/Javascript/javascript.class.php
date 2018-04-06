<?php
/**
 * @name javascript.class.php Service de gestion des ressources de type Javascript
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Assets\Javascript
 * @version 1.0
 */
namespace wp\Html\Assets\Javascript;

use \wp\Html\Assets\asset as Asset;

class javascript extends \wp\Html\Assets\asset {
	/**
	 * Détermine le jeu de caractère à utiliser
	 * @var string
	 */
	private $charset;
	
	public function __construct(){
		$this->charset = "utf-8";
	}
	
	/**
	 * Définit le jeu de caractère associé à la source Javascript courante
	 * @param string $charset
	 * @return \wp\Html\Assets\Javascript\javascript|string
	 */
	public function charset($charset=null){
		if(!is_null($charset)){
			$this->charset = $charset;
			return $this;
		}
		return $this->charset;
	}
	
	/**
	 * Retourne le chemin complet vers la ressource
	 * @return string
	 */
	public function get(){
		return $this->path . $this->file;
	}
}