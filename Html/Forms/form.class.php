<?php
/**
 * @name form.class.php Abstraction de classe pour la définition d'un formulaire
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms
 * @version 1.0
**/
namespace wp\Html\Forms;

use \wp\Html\Attributes\attributes as Attributes;
use \wp\Html\Fieldsets\fieldset as Fieldset;

abstract class form extends \wp\Html\Attributes\attributes {
	
	/**
	 * Attribut method du formulaire
	 * @var unknown
	 */
	private $method;
	
	/**
	 * Action à réaliser pour le traitement du formulaire
	 * @var string
	 */
	private $action;
	
	/**
	 * Attribut enctype du formulaire
	 * @var string
	 */
	private $enctype;
	
	/**
	 * Collection des fieldsets composant le formulaire
	 * @var array
	 */
	private $fieldsets;
	
	/**
	 * Modèle pour l'affichage du formulaire
	 * @var string
	 */
	protected $template;
	
	/**
	 * Définit ou retourne l'attribut "name" du formulaire
	 * @param unknown $name
	 */
	public function name($name=null){
		if(is_null($name)){
			return $this->name;
		}
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Retourne l'attribut "method" du formulaire
	 * @param unknown $method
	 */
	public function method($method=null){
		if(is_null($method)){
			return is_null($this->method) ? "post" : $this->method;
		}
		$this->method = $method;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'attribut "enctype" du formulaire
	 * @param string $type
	 */
	public function enctype($type=null){
		if(is_null($type)){
			return is_null($this->enctype) ? "application/x-www-form-urlencoded" : $this->enctype;
		}
		$this->enctype = $type;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'action à traiter pour le traitement du formulaire
	 * @param unknown $action
	 */
	public function action($action=null){
		if(is_null($action)){
			if(!is_null($this->action))
				return $this->action;
			return $_SERVER["PHP_SELF"];
		}
		
		$this->action = $action;
		return $this;
	}
	
	/**
	 * Retourne l'ensemble des fieldsets du formulaire courant
	 */
	public function fieldsets($fieldset = null){
		if(is_null($fieldset))
			return $this->fieldsets;
		
		$this->fieldsets[] = $fieldset;
		return $this;
	}
	
	/**
	 * Détermine ou retourne le modèle du formulaire à traiter
	 * @param string $template
	 */
	abstract protected function template($template=null);
}