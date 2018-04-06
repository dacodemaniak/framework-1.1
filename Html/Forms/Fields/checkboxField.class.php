<?php
/**
 * @name checkboxField.class.php Instance d'un champ de type bouton checkbox
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package \wp\Html\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class checkboxField extends \wp\Html\Forms\Fields\field {
	
	/**
	 * Contenu du tag "label" du champ
	 * @var string
	 */
	private $label;
	
	/**
	 * Détermine si le bouton est coché ou non
	 * @var boolean
	 */
	private $isChecked;
	
	/**
	 * Définit le mode de représentation de la boîte à cocher : checkbox-inline | checkbox
	 * @var string
	 */
	private $checkLayout;
	
	/**
	 * Instancie un nouveau champ de type Boîte à cocher
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset = null){
		$this->fieldset = $fieldset;
		
		$this->isChecked = false;
		
		$this->template("checkbox");
	}
	
	/**
	 * Définit l'attribut "checked" du champ courant
	 * @param boolean $checked
	 */
	public function isChecked($checked = null){
		if(is_null($checked)){
			return is_null($this->isChecked) ? false : $this->isChecked;
		}
		if(is_bool($checked)){
			$this->isChecked = $checked;
		} else {
			$this->isChecked = false;
		}
		return $this;
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
	 * Détermine le mode d'affichage des boutons radio dans le groupe
	 * @param string $layout
	 */
	public function checkLayout($layout = null){
		if(is_null($layout)){
			return $this->checkLayout;
		}
		$this->checkLayout = $layout;
		return $this;
	}
	
	protected function template($template){
		$classParts = explode("\\",get_class($this));
		$class = array_pop($classParts);
		$templateName = $template . \App\appLoader::$tpl->extension();
		$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
	
		if(file_exists(\App\appLoader::wp()->getPathes()->getRootPath("App").$templateFilePath)){
			//$this->template = "file:/" . framework::getWp()->getPathes()->getRootPath("App") . $templateFilePath;
			$this->template = \App\appLoader::wp()->templateEngine()->absolutePath($templateFilePath);
		} else {
			// Va chercher le modèle à partir de la route courante
			$classParts = explode("\\",\App\appLoader::wp()->request()->getRoute()->getNameSpace());
			$class = array_pop($classParts);
			$templateName = $template . \App\appLoader::$tpl->extension();
			$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/Form/Fieldsets/Fields/_templates/" . $templateName;
			
			if(file_exists(\App\appLoader::wp()->getPathes()->getRootPath("App").$templateFilePath)){
				$this->template = \App\appLoader::wp()->templateEngine()->absolutePath($templateFilePath);
			} else {
				die("Le template : " . $templateFilePath . " n'a pas pu être trouvé !");
				parent::template();
			}
		}
	}
}