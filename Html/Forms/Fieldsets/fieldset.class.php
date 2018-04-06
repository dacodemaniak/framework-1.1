<?php
/**
 * @name fieldset.class.php Abstraction de classe de création de fieldset
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Fieldsets
 * @version 1.0
 */
namespace wp\Html\Forms\Fieldsets;

use \wp\Html\Attributes\attributes as Attribute;
use \wp\Html\Forms\form as Form;

abstract class fieldset extends \wp\Html\Attributes\attributes {
	
	/**
	 * Légende associée au fieldset courant
	 * @var string
	 */
	private $legend;
	
	/**
	 * Formulaire de référence pour l'attachement du fieldset
	 * @var Form
	 */
	private $form;
	
	/**
	 * Statut d'activation / désactivation du fieldset courant
	 * @var boolean
	 */
	private $isDisabled;
	
	/**
	 * Collection des champs composant le fieldset
	 * @var array
	 */
	private $fields;
	
	/**
	 * Texte éventuel sous la légende du fieldset
	 * @var string
	 */
	private $intro;
	
	/**
	 * Modèle associé au fieldset courant
	 * @var string
	 */
	protected $template;
	
	/**
	 * Définit ou retourne le formulaire dans lequel sera injecté le fieldset
	 * @param Form $form
	 */
	public function form(Form $form = null){
		if(is_null($form)){
			return $this->form;
		}
		$this->form = $form;
		return $this;
	}
	
	/**
	 * Ajoute le fieldset courant aux fieldsets du formulaire parent
	 */
	public function hydrate(){
		$this->form->fieldsets($this);
	}
	
	/**
	 * Ajoute un nouveau champ à la collection ou retourne la collection des champs
	 * @param \wp\Html\Fields\field $field
	 */
	public function fields($field = null){
		if(is_null($field)){
			return $this->fields;
		}
		$this->fields[$field->name()] = $field;
	}
	/**
	 * Définit ou retourne la valeur du tag "legend" du formulaire
	 * @param string $legend
	 */
	public function legend($legend = null){
		if(is_null($legend)){
			return $this->legend;
		}
		$this->legend = $legend;
		return $this;
	}

	/**
	 * Définit ou retourne la valeur du texte d'introduction du fieldset
	 * @param string $intro
	 */
	public function intro($intro = null){
		if(is_null($intro)){
			return $this->intro;
		}
		$this->intro = $intro;
		return $this;
	}
	
	/**
	 * Définit ou retourne la valeur de l'attribut "disabled" du fieldset
	 * @param boolean $isDisabled
	 */
	public function isDisabled($isDisabled = null){
		if(is_null($isDisabled)){
			return is_null($this->isDisabled) ? false : $this->isDisabled;
		}
		$this->isDisabled = is_bool($isDisabled) ? $isDisabled : false;
		return $this;
	}
	
	/**
	 * Retourne le modèle associé au fieldset courant
	 */
	public function getTemplate(){
		return $this->template;
	}
	
	/**
	 * Détermine ou retourne le modèle du fieldset à traiter
	 * @param string $template
	 */
	abstract protected function template($template=null);
}