<?php
/**
 * @name selectField.class.php Définit un champ de type select
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Field
 * @version 1.0
**/
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;
use \wp\Collections\collection as Collection;

class selectField extends \wp\Html\Forms\Fields\field{
	/**
	 * Taille visible du select
	 * @var int
	 */
	protected $size;
	
	/**
	 * Détermine la sélection multiple dans le select
	 * @var boolean
	 */
	protected $multiple;
	
	/**
	 * Collection des éléments du select
	 * @var Collection
	 */
	protected $collection;

	/**
	 * Contenu du tag "label" du champ
	 * @var string
	 */
	private $label;
	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
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
	
	public function collection(Collection $collection = null){
		if(is_null($collection)){
			return $this->collection;
		}
		$this->collection = $collection;
	}
	/**
	 * Définit ou retourne la taille visible du select
	 * @param int $size
	 */
	public function size($size = null){
		if(is_null($size)){
			return $this->size;
		}
		$this->size = $size;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut de sélection multiple du select
	 * @param boolean $multiple
	 */
	public function multiple($multiple = null){
		if(is_null($multiple)){
			return is_null($multiple) ? false : true;
		}
		if(is_bool($multiple)){
			$this->multiple = $multiple;
		} else {
			$this->multiple = false;
		}
		return $this;
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