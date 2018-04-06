<?php
/**
 * @name compositeField.class.php Instance d'un champ de type composite multilangue
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package \wp\Html\Fields
 * @version 1.0
 */
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;

class compositeField extends \wp\Html\Forms\Fields\field {
	
	/**
	 * Contenus des tabulations
	 * @var array
	 */
	private $contents;
	
	/**
	 * Tableau de stockage des différents tabbedFields (ou richtext) du contenu
	 * @var array
	 */
	private $tabbedFields;
	
	/**
	 * Tableau contenant les différents champs de gestion des images
	 * @var array
	 */
	private $fileFields;

	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
		
		$this->tabbedFields = array();
		$this->fileFields = array();
		
		$this->template("composite");
	}
	
	/**
	 * Définit le contenu à traiter dans le composant
	 * @param array $content Tableau d'objets avec :
	 *  un contenu éditorial : language / content
	 *  une image avec src / alt (multilangue)
	 * @return \wp\Html\Forms\Fields\tabbedField
	 */
	public function setContent($content){
		if(!is_null($content)){
			$indice = 1; // Pour déterminer l'ID à traiter
			foreach($content as $object){
				$editorial["language"] = $object->language;
				$editorial["content"] = $object->content;
				$finalContent[] = (object) $editorial;
				
				// Instancie un nouvel objet tabbedField à partir du tableau
				$tabbedField = new \wp\Html\Forms\Fields\tabbedField($this->fieldset);
				$tabbedField->label("Editorial")
					->id("editorial_" . $indice)
					->name("editorial_" . $indice)
					->cssClass("json-data")
					->setContent($finalContent)
					->addAttribute("data-type","editorial");
				
					$this->tabbedFields[] = $tabbedField;
					
				if(property_exists($object, "image")){
					$image["image"] = $object->image;
				}
				
				$indice++;
			}
		}
		return $this;
	}
	
	/**
	 * Retourne le tableau des champs de type éditorial
	 * @return array
	 */
	public function getTabbedFields(){
		return $this->tabbedFields;
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