<?php 
/**
 * @name imageTag.class.php Service de création d'un composant HTML de type image
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Components\Image
 * @version 1.0
 */

namespace wp\Html\Components\Image;

class imageTag extends \wp\Html\Components\htmlTag {
	/**
	 * Source de l'image
	 * @var string
	 */
	private $src;
	
	/**
	 * Texte alternative de l'image
	 * @var string
	 */
	private $alt;
	
	/**
	 * Instancie un nouveau composant HTML de type image
	 */
	public function __construct(){
		$this->attributes = array();
		$this->classes = array();
		$this->template();
	}
	
	/**
	 * Définit ou retourne l'attribut src du composant
	 * @param string $src
	 */
	public function src($src=null){
		if(is_null($src)){
			return $this->src;
		}
		$this->src = $src;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'attribut alt du composant
	 * @param string $alt
	 */
	public function alt($alt=null){
		if(is_null($alt)){
			return $this->alt;
		}
		$this->alt = $alt;
		return $this;
	}
}