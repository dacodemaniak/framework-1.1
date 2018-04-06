<?php 
/**
 * @name buttonTag.class.php Service de création d'un composant HTML de type button
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Components\Link
 * @version 1.0
 */

namespace wp\Html\Components\Button;

class buttonTag extends \wp\Html\Components\htmlTag {
	
	/**
	 * Définit le type du bouton, si non défini, par défaut "button"
	 * @var string
	 */
	private $type;

	
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
		
		$this->type = "button";
		
		$this->template();
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