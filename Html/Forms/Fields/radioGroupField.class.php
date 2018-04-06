<?php
/**
 * @name radioGroupField.class.php Définit un champ groupe de boutons radio
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;
use \wp\Html\Forms\Fields\radioField as Radio;
class radioGroupField extends \wp\Html\Forms\Fields\field{
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
	 * Collection des boutons radios
	 * @var array
	 */
	private $radios;
	
	/**
	 * Collection des boutons à traiter
	 * @var array
	 */
	private $buttons;
	
	/**
	 * Définit la manière dont les boutons radio doivent être affichés
	 * radio-inline | radio
	 * @var string
	 */
	private $radioLayout;
	
	
	/**
	 * Instancie un nouveau champ de type radio group
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;

		$this->addAttribute("data-type","radio-group");
	}

	/**
	 * Ajoute un bouton radio à la collection
	 * @param Radio $radio
	 */
	public function add(Radio $radio){
		$this->radios[] = $radio;
		return;
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
	public function radios(){
		return $this->radios;	
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