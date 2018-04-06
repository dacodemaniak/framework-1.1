<?php
/**
 * @name datePickerField.class.php Gestion d'un champ de type date avec sélection
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Forms\Fields
 * @version 1.0
**/
namespace wp\Html\Forms\Fields;

use \wp\Html\Forms\Fields\field;
use \wp\Html\Forms\Fieldsets\fieldsets as Fieldset;
use \wp\Html\Assets\assets as Assets;
use \wp\Html\Assets\asset as Asset;

class datePickerField extends \wp\Html\Forms\Fields\field {
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
	 * Déterime si l'image est embarquée ou non dans le contrôle
	 * @var boolean
	 */
	private $embed;
	
	/**
	 * Lien vers l'image qui doit déclencher le calendrier
	 * @var string
	 */
	private $triggerIcon;
	
	/**
	 * Instancie un nouveau champ de type text
	 * @param Fieldset $fieldset
	 */
	public function __construct($fieldset){
		$this->fieldset = $fieldset;
		//$this->addAsset();
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
	 * Détermine si l'image associée est incluse dans le contrôle ou non
	 * @param mixed|boolean $embed
	 */
	public function isEmbeded($embed = null){
		if(is_null($embed)){
			if(is_null($this->embed)){
				$this->embed = true;
				$this->cssClass("embed");
			}
			return $this->embed;
		}
		if(is_bool($embed)){
			$this->embed = $embed;
			if($this->embed){
				$this->cssClass("embed");
			}
		}
		return $this;
	}
	
	/**
	 * Détermine l'icône associée au déclencheur du contrôle
	 * @param unknown $icon
	 * @return string|\wp\Html\Forms\Fields\datePicker
	 */
	public function triggerIcon($icon=null){
		if(is_null($icon)){
			return $this->triggerIcon;
		}
		$this->triggerIcon = $icon;
		return $this;
	}
	
	/**
	 * Ajoute les ressources nécessaires au fonctionnement du champ
	 */
	private function addAsset(){
		$assets = \App\appLoader::wp()->assets();
		
		$route = new \wp\Http\Routes\route();
		$route->setNamespace("::wp::Html::Assets::Javascript::")
				->setClassName("javascript");
					
		$factory = new \wp\Patterns\ClassFactory\asset($route);
		$asset = $factory->addInstance();
		
		$asset->path("/_assets/javascript/datePick/5.0.1/")
			->file("jquery.datepick.min.js")
			->type("javascript")
			->signature();
		$assets->add($asset);

		$factory = new \wp\Patterns\ClassFactory\asset($route);
		$asset = $factory->addInstance();
		
		$asset->path("/_assets/javascript/datePick/5.0.1/")
			->file("jquery.datepick-fr.js")
			->type("javascript")
			->signature();
		$assets->add($asset);

		$route = new \wp\Http\Routes\route();
		$route->setNamespace("::wp::Html::Assets::Javascript::")
			->setClassName("css");

		$factory = new \wp\Patterns\ClassFactory\asset($route);
		$asset = $factory->addInstance();
		$asset->path("/_assets/css/datePick/5.0.1/")
			->file("jquery.datepick.css")
			->type("css")
			->signature();
		$assets->add($asset);
	}
	
	/**
	 * Définit le modèle à utiliser pour l'affichage du contrôle courant
	 * @param unknown $template
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
