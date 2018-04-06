<?php
/**
 * @name popupField.class.php Définit un champ de type popup
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Field
 * @version 1.0
**/
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\selectField;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class popupField extends \wp\Html\Forms\Fields\selectField{
	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
		$this->size = 1;
		$this->multiple = false;
	}
	
	/**
	 * Définit le modèle associé au champ select
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