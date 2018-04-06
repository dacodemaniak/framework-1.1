<?php
/**
 * @name attributes.class.php Abstraction de classe définissant les attributs standard des balises HTML
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Html\Attributes
 * @version 1.0
**/
namespace wp\Html\Attributes;

abstract class attributes {
	/**
	 * Attribut id du fieldset courant
	 * @var string
	 */
	private $id;
	
	/**
	 * Attribut name du composant
	 * @var string
	 */
	private $name;
	
	/**
	 * Attribut title du composant
	 * @var string
	 */
	private $title;
	
	/**
	 * Collection des classes CSS à appliquer au formulaire
	 * @var array
	 */
	private $cssClasses;
	
	/**
	 * Attribut "lang" de l'élément concerné
	 * @var string
	 */
	private $lang;

	/**
	 * Structure de définition des attributs d'une balise HTML
	 * @var array
	 */
	protected $attributes;
	
	/**
	 * Définit ou retourne l'attribut id du formulaire
	 * @param string $id
	 */
	public function id($id=null){
		if(is_null($id)){
			return $this->id;
		}
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'attribut name du fieldset
	 * @param string $name
	 */
	public function name($name=null){
		if(is_null($name)){
			return $this->name;
		}
		$this->name = $name;
		return $this;
	}
	
	public function lang($lang=null){
		if(is_null($lang)){
			return is_null($this->lang) ? "fr" : $this->lang;
		}
		$this->lang = $lang;
		return $this;
	}

	/**
	 * Définit ou retourne le titre du composant
	 * @param string $title
	 */
	public function title($title=null){
		if(is_null($title)){
			return $this->title;
		}
		$this->title = $title;
		return $this;
	}

	/**
	 * Ajoute un attribut à la liste des attributs d'une balise
	 * @param string $name
	 * @param multitype $content
	 */
	public function addAttribute($name,$content){
		if(is_null($this->attributes)){
			$this->attributes[$name] = $content;
		} else {
			if(array_key_exists($name,$this->attributes)){
				if(!in_array($content,$this->attributes[$content])){
					$this->attributes[$name] = $content;
				}
			} else {
				$this->attributes[$name] = $content;
			}
		}
		return $this;
	}
	
	/**
	 * Retourne les attributs supplémentaires du tag
	 * @return string
	 */
	public function attributes(){
		$attributes = "";
		if(sizeof($this->attributes)){
			foreach($this->attributes as $name => $value){
				if(!is_null($value)){
					$attributes .= $name . "=\"" . $value . "\" ";
				} else {
					$attributes .= $name . " ";
				}
			}
			return substr($attributes,0,strlen($attributes)-1);
		}
		return "";
	}
	
	/**
	 * Ajoute une classe CSS ou retourne les classes du formulaire courant
	 * @param string $class
	 */
	public function cssClass($class = null){
		if(is_null($class)){
			if(sizeof($this->cssClasses)){
				return implode(" ", $this->cssClasses);
			}
		} else {
			if(is_null($this->cssClasses)){
				$this->cssClasses = array();
			}
			if(!in_array($class,$this->cssClasses)){
				$this->cssClasses[] = $class;
			}
			return $this;
		}
	}
	

}