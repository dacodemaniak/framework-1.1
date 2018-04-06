<?php
/**
 * @name tabbedField.class.php Instance d'un champ de type multilangue
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package \wp\Html\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class tabbedField extends \wp\Html\Forms\Fields\field {
	
	/**
	 * Contenu du tag "label" du champ
	 * @var string
	 */
	private $label;
	
	/**
	 * Contenus des tabulations
	 * @var array
	 */
	private $contents;
	
	/**
	 * Nom de la propriété de l'objet contenant le titre de l'onglet
	 * @var string
	 */
	private $titlePropertyName;
	
	/**
	 * Nom de la propriété de l'objet contenant la valeur associée à l'onglet
	 * @var string
	 */
	private $contentPropertyName;

	/**
	 * Définit l'attribut placeholder du champ
	 * @var string
	 */
	private $placeHolder;
	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
		
		$this->titlePropertyName = "language";
		$this->contentPropertyName = "content";
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
	 * Définit le contenu à traiter dans le composant
	 * @param array $content Tableau d'objets avec :
	 *  titre de l'onglet / valeur associée
	 * @return \wp\Html\Forms\Fields\tabbedField
	 */
	public function setContent($content){
		if(!is_null($content)){
			foreach($content as $object){
				$this->contents[$object->{$this->titlePropertyName}] = $object->{$this->contentPropertyName};
			}
			
			// Définit le modèle à utiliser pour afficher le contrôle
			if(sizeof($this->contents) > 1)
				$this->template("tabbed");
			else {
				$this->template("richtext");
				if($this->titlePropertyName == "language"){
					$keys = array_keys($this->contents);
					$this->lang($keys[0]);
				}
			}
		}
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
	
	
	public function value(){
		$values = array_values($this->contents);
		return $values[0];
	}
	
	
	protected function template($template){
		$extension = !is_null(\App\appLoader::wp()) ? \App\appLoader::$tpl->extension() : \Backend\appLoader::$tpl->extension();
		
		$classParts = explode("\\",get_class($this));
		$class = array_pop($classParts);
		array_shift($classParts);

		
		$templateName = $template . $extension;
		$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
		
		$rootPath = !is_null(\App\appLoader::wp()) ? \App\appLoader::wp()->getPathes()->getRootPath("wp") : \Backend\appLoader::wp()->getPathes()->getRootPath("wp");
		
		$templateEngine = !is_null(\App\appLoader::wp()) ? \App\appLoader::wp()->templateEngine() : \Backend\appLoader::wp()->templateEngine();
		
		
		if(file_exists($rootPath.$templateFilePath)){
			//$this->template = "file:/" . framework::getWp()->getPathes()->getRootPath("App") . $templateFilePath;
			$this->template = $rootPath. $templateFilePath;
		} else {
			array_pop($classParts); // Remonte d'un niveau
			$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
			while(!file_exists($rootPath.$templateFilePath) && sizeof($classParts)){
				array_pop($classParts); // Remonte d'un niveau
				$templateFilePath = str_replace("\\","/",implode("\\",$classParts)) . "/_templates/" . $templateName;
			}
			if(sizeof($classParts)){
				//$this->template = $templateEngine->absolutePath($templateFilePath);
				$this->template = $rootPath . $templateFilePath;
			} else {
				die("Le template : " . $templateFilePath . " n'a pas pu être trouvé ! à la racine : " . $rootPath);
			}
		}
	}
}