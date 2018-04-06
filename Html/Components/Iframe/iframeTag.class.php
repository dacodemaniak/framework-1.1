<?php 
/**
 * @name iframeTag.class.php Service de création d'un composant HTML de type iframe
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Components\Link
 * @version 1.0
 */

namespace wp\Html\Components\Iframe;

class iframeTag extends \wp\Html\Components\htmlTag {
	/**
	 * Cible du lien
	 * @var string
	 */
	private $src;
	
	/**
	 * Contenu de l'iframe
	 * @var string
	 */
	private $content;
	
	/**
	 * Hauteur de l'iframe
	 * @var int
	 */
	private $height;
	
	/**
	 * Largeur de l'iframe
	 * @var int
	 */
	private $width;
	
	/**
	 * Instancie un nouveau composant HTML de type iframe
	 */
	public function __construct(){
		$this->attributes = array();
		$this->classes = array();
		
		$this->type = "http";
		
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
	 * Définit ou retourne la hauteur du composant
	 * @param int $height
	 */
	public function height($height=null){
		if(is_null($height)){
			return is_null($this->height) ? 315 : $this->height;
		}
		$this->height = $height;
		return $this;
	}
	
	/**
	 * Définit ou retourne la hauteur du composant
	 * @param int $width
	 */
	public function width($width=null){
		if(is_null($width)){
			return is_null($this->width) ? 560 : $this->width;
		}
		$this->width = $width;
		return $this;		
	}
	/**
	 * Définit ou retourne le contenu du composant
	 * @param string $content
	 */
	public function content($content=null){
		if(is_null($content)){
			return $this->content;
		}
		$this->content = $content;
		return $this;
	}
}