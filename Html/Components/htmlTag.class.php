<?php
/**
 * @name htmlTag.class.php Abstraction de composant HTML standard
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Components
 * @version 1.0
 */
namespace wp\Html\Components;


use \wp\Html\Attributes\attributes as Attribute;

abstract class htmlTag extends \wp\Html\Attributes\attributes {
	
	/**
	 * Modèle à utiliser pour générer le composant
	 * @var string
	 */
	protected $template;
	
	/**
	 * Définit la variable objet et retourne le modèle compilé
	 */
	public function render(){
		if(!is_null(\App\appLoader::wp()))
			return \App\appLoader::wp()->templateEngine()->capture($this->template,array("component" => $this));
		
		return \Backend\appLoader::wp()->templateEngine()->capture($this->template,array("component" => $this));
		
	}
	
	/**
	 * Définit le nom de la vue à charger
	 * La vue est stockée dans le dossier _templates/Components du dossier de l'application
	 **/
	protected function template(){
		$extension = !is_null(\App\appLoader::wp()) ? \App\appLoader::$tpl->extension() : \Backend\appLoader::$tpl->extension();
		
		$classParts = explode("\\",get_class($this));
		$templateName = array_pop($classParts) . $extension;
		$templateFilePath = "_templates/Components/" . $templateName;
		
		$finalTemplatePath = "./Components/" . $templateName;
		
		$rootPath = !is_null(\App\appLoader::wp()) ? \App\appLoader::wp()->getPathes()->getRootPath("App") : \Backend\appLoader::wp()->getPathes()->getRootPath("App");
		
		if(file_exists($rootPath.$templateFilePath)){
			//$this->template = $templateFilePath;
			$this->template = $finalTemplatePath;
		}
	}
}