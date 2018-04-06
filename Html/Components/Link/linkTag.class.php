<?php 
/**
 * @name linkTag.class.php Service de création d'un composant HTML de type a
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Components\Link
 * @version 1.0
 */

namespace wp\Html\Components\Link;

class linkTag extends \wp\Html\Components\htmlTag {
	/**
	 * Cible du lien
	 * @var string
	 */
	private $href;
	
	/**
	 * Définit le type du lien, si non défini, par défaut http://
	 * @var string
	 */
	private $type;
	
	/**
	 * Définit la cible du lien
	 * @var string
	 */
	private $target;
	
	/**
	 * Contenu du lien lui-même
	 * @var string
	 */
	private $content;
	
	/**
	 * Instancie un nouveau composant HTML de type lien
	 */
	public function __construct(){
		$this->attributes = array();
		$this->classes = array();
		
		$this->type = "http";
		
		$this->target = "_self";
		
		$this->template();
	}
	
	/**
	 * Définit ou retourne l'attribut href du composant
	 * @param string $href
	 */
	public function href($href=null){
		if(is_null($href)){
			switch($this->type){
				case "http":
					$href = $this->href;
				break;
				case "mail":
				case "mailto":
					$href = "mailto:" . $this->href;
				break;
				case "tel":
				case "phone":
					$href = "tel:" . $this->href;
				break;
				default:
					$href = "#";
				break;
			}
			return $href;
		}
		$this->href = $href;
		return $this;
	}
	
	/**
	 * Définit ou retourne le type du composant
	 * @param string $type
	 */
	public function type($type=null){
		if(is_null($type)){
			return $this->type;
		}
		$this->type = $type;
		return $this;
	}
	
	/**
	 * Définit ou retourne la cible du lien
	 * @param string $target
	 */
	public function target($target=null){
		if(is_null($target)){
			return $this->target;
		}
		$this->target = substr($type,0,1) == "_" ? $target : "_" . $target;
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