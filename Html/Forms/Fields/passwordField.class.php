<?php
/**
 * @name passwordField.class.php Instance d'un champ de type Password
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package \wp\Html\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class passwordField extends \wp\Html\Forms\Fields\field {
	/**
	 * Définit l'attribut size du champ
	 * @var int
	 */
	private $size;
	
	/**
	 * Définit l'attribut maxlength du champ
	 * @var int
	 */
	private $maxLength;
	
	/**
	 * Définit l'attribut placeholder du champ
	 * @var string
	 */
	private $placeHolder;
	
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
	 * Définit ou retourne l'attribut size du champ
	 * @param int $size
	 */
	public function size($size=null){
		if(is_null($size)){
			return is_null($this->size) ? 30 : $this->size;
		}
		$this->size = $size;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'attribut placeholder du champ concerné
	 * @param string $placeHolder
	 */
	public function placeHolder($placeHolder = null){
		if(is_null($placeHolder)){
			return is_null($this->placeHolder) ? ucfirst($this->name()) : $this->placeHolder;
		}
		$this->placeHolder = $placeHolder;
		return $this;
	}
	
	/**
	 * Définit ou retourne l'attribut maxlength du champ
	 * @param int $maxLength
	 */
	public function maxLength($maxLength = null){
		if(is_null($maxLength)){
			return is_null($this->maxLength) ? 75 : $this->maxLength;
		}
		$this->maxLength = $maxLength;
		return $this;
	}
	
	protected function template($template){
		$extension = !is_null(\App\appLoader::wp()) ? \App\appLoader::$tpl->extension() : \Backend\appLoader::$tpl->extension();
		
		$classParts = explode("\\",get_class($this));
		$class = array_pop($classParts);
		$templateName = $template . $extension;
		$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
		
		$rootPath = !is_null(\App\appLoader::wp()) ? \App\appLoader::wp()->getPathes()->getRootPath("App") : \Backend\appLoader::wp()->getPathes()->getRootPath("App") . "/";
		
		$templateEngine = !is_null(\App\appLoader::wp()) ? \App\appLoader::wp()->templateEngine() : \Backend\appLoader::wp()->templateEngine();
		
		if(file_exists($rootPath.$templateFilePath)){
			//$this->template = "file:/" . framework::getWp()->getPathes()->getRootPath("App") . $templateFilePath;
			$this->template = $templateEngine->absolutePath($templateFilePath);
		} else {
			die("Le template : " . $templateFilePath . " n'a pas pu être trouvé ! à la racine : " . $rootPath);
			parent::template();
		}
	}
}