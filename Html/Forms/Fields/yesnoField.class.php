<?php
/**
 * @name yesnoField.class.php Définit un champ de type Oui / Non sous forme différentes
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class yesnoField extends \wp\Html\Forms\Fields\field{
	/**
	 * Valeur associée au champ courant
	 * @var string
	 */
	private $value;
	
	/**
	 * Contenu du tag "label" du champ
	 * @var string
	 */
	private $label;
	
	/**
	 * Options des deux boutons Oui et Non
	 * @var array
	 */
	private $options;
	
	/**
	 * Collection des boutons à traiter
	 * @var array
	 */
	private $buttons;
	
	/**
	 * Vrai si contrôle doit être affiché sous forme de boutons Radio
	 * @var boolean
	 */
	private $asRadio;
	
	/**
	 * Définit la manière dont les boutons radio doivent être affichés
	 * radio-inline | radio
	 * @var string
	 */
	private $radioLayout;
	
	/**
	 * Vrai si contrôle doit être affiché sous forme de boîtes à cocher
	 * @var boolean
	 */
	private $asCheckbox;
	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
		
		$this->asRadio = true;
		$this->asCheckbox = false;
		
		// Initialise les options Oui et Non
		$this->options["yes"] = array(
			"label" => "Oui",
			"value" => "Oui",
			"name" => "-yes-content",
			"id" => "-yes-content",
			"checked" => false
		);
		$this->options["no"] = array(
				"label" => "Non",
				"value" => "Non",
				"name" => "-no-content",
				"id" => "-no-content",
				"checked" => false
		);
		$this->addAttribute("data-type","yesno");
	}
	
	/**
	 * Détermine le mode d'affichage des boutons radio dans le groupe
	 * @param string $layout
	 */
	public function radioLayout($layout = null){
		if(is_null($layout)){
			return $this->radioLayout;
		}
		$this->radioLayout = $layout;
		return $this;
	}
	
	/**
	 * Retourne les boutons radio concernés
	 */
	public function buttons(){
		return $this->buttons;	
	}
	
	/**
	 * Méthode magique pour l'accès aux options des boutons :
	 * Prototype : yesOptionName($args) => appelle la méthode spécifique
	 * @param string $method
	 * @param mixed $args
	 */
	public function __call($method,$args=null){
		$type = substr($method,0,strpos($method,"Option"));
		$property = strtolower(substr($method,strpos($method,"Option") + 6, strlen($method)));
		
		if(array_key_exists($type, $this->options)){
			if(array_key_exists($property, $this->options[$type])){
				if(is_null($args)){
					return $this->options[$type][$property];
				}
				$this->options[$type][$property] = $args[0];
				return $this;
			}
		}
	}
	
	/**
	 * Traite le groupe de boutons Oui / Non
	 */
	public function process(){
		foreach($this->options as $button => $option){
			if($this->asRadio){
				$field = new \wp\Html\Forms\Fields\radioField();
			} else {
				// Construit un objet de type checkbox
			}

			$field->name($option["name"])
				->id($option["id"])
				->isChecked($option["checked"])
				->label($option["label"])
				->value($option["value"])
				->radioLayout(is_null($this->radioLayout) ? "radio-inline" : $this->radioLayout);
			$this->buttons[] = $field;
		}
	}
	
	/**
	 * Définit ou retourne la valeur du tag "label" du champ
	 * @param string $label
	 */
	public function label($label = null){
		if(is_null($label)){
			return $this->label;
		}
		$this->label = $label;
		return $this;
	}
	
	/**
	 * Définit ou retourne la valeur du champ
	 * @param string $value
	 */
	public function value($value = null){
		if(is_null($value)){
			return $this->value;
		}
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Définit le modèle à appliquer pour la représentation du champ
	 * {@inheritDoc}
	 * @see \wp\Html\Forms\Fields\field::template()
	 */
	protected function template($template){
		$classParts = explode("\\",get_class($this));
		$class = array_pop($classParts);
		$templateName = $template . \App\appLoader::$tpl->extension();
		$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
	
		if(file_exists(\App\appLoader::wp()->getPathes()->getRootPath("App").$templateFilePath)){
			//$this->template = "file:/" . framework::getWp()->getPathes()->getRootPath("App") . $templateFilePath;
			$this->template = \App\appLoader::wp()->templateEngine()->absolutePath($templateFilePath);
		} else {
			die("Le template : " . $templateFilePath . " n'a pas pu être trouvé !");
			parent::template();
		}
	}
}