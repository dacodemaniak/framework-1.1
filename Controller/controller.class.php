<?php
/**
 * @name controller.class.php Contrôleur des applications
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Controller
 * @version 1.0
**/

namespace wp\Controller;

use \wp\Http\Request\requestData as Request;
use \wp\Exceptions\Errors\error as Error;
use \wp\Exceptions\Template\TemplateException as TemplateException;
use \wp\Patterns\Decorator\IDecorator as IDecorator;

abstract class controller implements \wp\Patterns\Decorator\IDecorator {
	/**
	 * Instance des données de requête
	 * @var \wp\Http\Request\requestData
	 */
	private $requestData;
	
	/**
	 * Fichier de modèle à utiliser pour le rendu
	 * @var string
	 */
	protected $template;
	
	/**
	 * Région pour l'affichage du contrôleur dans la vue
	 * @var string
	 */
	protected $region = "_main";
	
	/**
	 * Nom du contrôleur, pour le passer à la vue
	 * @var string
	 */
	protected $name;

	/**
	 * Classe associée à la page courante
	 * @var string
	 */
	protected $pageClass;
	
	/**
	 * Définit le type de réponse attendu, par défaut HTML
	 * @var string
	 */
	protected $responseType = "html";
	
	/**
	 * Définit ou retourne les données de requête
	 * @param Request $data
	 */
	public function requestData(Request $data=null){
		if(is_null($data)){
			return $this->requestData;
		}
		$this->requestData = $data;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne la région pour l'affichage des données du contrôleur
	 * @param optional string $region
	 */
	public function region($region=null){
		if(!is_null($region)){
			$this->region = $region;
			return $this;
		}
		return $this->region;
	}
	
	/**
	 * Définit ou retourne le nom de la variable du contrôleur passé à la vue
	 * @param string $name
	 */
	public function name($name=null){
		if(is_null($name)){
			return $this->name;
		}
		$this->name = $name;
		return $this;
	}

	/**
	 * Retourne la classe associée à la page courante
	 */
	public function pageClass(){
		return $this->pageClass;
	}
	
	/**
	 * Détermine si un service est disponible dans le contrôleur
	 * @param string $service
	 */
	public function serviceExists($service){
		return method_exists($this,$service);
	}
	
	/**
	 * Retourne le chemin vers le modèle à traiter
	 * @return string
	 */
	public function getTemplate(){
		return $this->template;
	}
	
	/**
	 * Définit ou retourne le type de réponse attendu pour le contrôleur
	 * @param string $responseType
	 * @return string|\wp\Controller\controller
	 */
	public function responseType($responseType = null){
		if(is_null($responseType)){
			return $this->responseType;
		}
		$this->responseType = $responseType;
		return $this;
	}
	
	/**
	 * Définit la valeur d'un décorateur
	 * {@inheritDoc}
	 * @see \wp\Patterns\Decorator\IDecorator::setDecorator()
	 */
	public function setDecorator(string $attributeName, $object){
		#echo "Affecte l'objet à " . $attributeName . "<br>";
		$this->{$attributeName} = $object;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne le modèle à utiliser pour le rendu
	 * @param string $name
	 * @param string $directory
	 */
	protected function template(){
		$classParts = explode("\\", get_class($this));
		$class = array_pop($classParts);
		$templateName = $class . \App\appLoader::$tpl->extension();
		$templateFilePath = str_replace("\\","/",__NAMESPACE__) . "/_templates/" . strtolower($templateName);
		
		try{		
			if(file_exists(\App\appLoader::wp()->getPathes()->getRootPath("App").$templateFilePath)){
				$this->template = \App\appLoader::wp()->templateEngine()->absolutePath($templateFilePath);
			} else {
				$error = new Error();
				$error->message("[" . $templateFilePath . "] Le modèle n'existe pas dans le dossier : " . str_replace("\\","/",__NAMESPACE__) . "/_templates/ \nCréez le fichier ou supprimez l'appel à la méthode template() du constructeur de votre classe")
					->code(-12000)
					->file(__FILE__)
					->line(__LINE__)
					->doRender(false)
					->doLog(true);
				throw new TemplateException($error);
			}
		
			// Ajoute les javascripts potentiellement présents aussi dans le dossier _templates
			if (file_exists(str_replace("\\","/",__NAMESPACE__) . "/_templates/javascript")) {
				$scriptFileName = $class . ".js";
				if (file_exists(str_replace("\\","/",__NAMESPACE__) . "/_templates/javascript/" . $scriptFileName)) {
					$asset = new \wp\Html\Assets\Javascript\javascript();
					
					$asset->path(str_replace("\\","/",__NAMESPACE__) . "/_templates/javascript/")
					->file($scriptFileName)
					->type("javascript")
					->signature();
					
					\App\appLoader::wp()->assets()->add($asset);
				}
			}
			
		} catch(\TemplateException $e){
			// NOOP
		}
	}
}