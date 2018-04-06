<?php
/**
 * @name wp.class.php : Instance du framework global web-Projet.com
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp
 * @version 1.0
 * @version 1.0.1 Jan. 2018 - Ajout de la détection du mode d'exécution (production | developpement)
**/
namespace wp;

use \wp\Patterns\Autoload\autoloader as Loader;
use \wp\Utilities\Pathes\pathes as Pathes;
use \wp\Http\Request\request as Request;
use \wp\Http\Response\htmlResponse as Response;
use \wp\Vendor\TemplateEngine\Smarty\templater as Smarty;
use \wp\Vendor\MobileDetection\MobileDetect as MobileDetect;
use \wp\Html\Assets\assets as Assets;
use \wp\Locales\languages as Languages;
use \wp\Exceptions\Errors\error as error;
use \wp\Exceptions\App\appException as AppException;

class wp{
	/**
	 * Instance du framework
	 * @var object
	 */
	private static $wp;
	
	/**
	 * Définition des chemins de l'application
	 * @var \wp\Utilities\Pathes\pathes
	 */
	public $pathes;
	
	/**
	 * Instance d'objet de requête HTTP
	 * @var \wp\Http\Request\
	 */
	private $httpRequest;
	
	/**
	 * Instance du moteur de gestion de modèles
	 * @var object
	 */
	private $templateEngine;
	
	/**
	 * Instance de gestion des données d'en-tête html
	 * @var \wp\Html\Assets
	 */
	private $assets;
	
	/**
	 * Instance de gestion des langues de l'application
	 * @var \wp\Locales\Languages
	 */
	private $languages;
	
	/**
	 * Instance de la liste des routes
	 * @var wp\Http\Routes\routes
	 */
	private $routes;
	
	/**
	 * Instance de détection de dispositif
	 * @var \MobileDetect
	 */
	private $device;
	
	/**
	 * Mode d'exécution de l'application : prod | dev
	 * @var string
	 */
	private $runMode = "dev";
	
	private function __construct(){
		
		require_once (dirname(__FILE__) . "/Utilities/Pathes/pathes.class.php");
		$this->pathes = new Pathes();

		$this->pathes->addPath("wp", dirname(__FILE__));
		
		require_once(dirname(__FILE__) . "/Patterns/Autoload/autoloader.class.php");
		Loader::register($this->pathes);
		
		require_once(dirname(__FILE__) . "/Exceptions/Errors/error.class.php");
		
		// Détermine le type de terminal utilisé
		$this->deviceCapabilities();
		
		// Définit la structure de stockage des ressources de base
		$this->assets = new \wp\Html\Assets\assets();
	}
	
	/**
	 * Instancie ou retourne la classe principale du framework
	 */
	public static function getWp(){
		if(is_null(self::$wp)){
			self::$wp = new wp();
		}
		
		return self::$wp;
	}
	
	/**
	 * Retourne la langue par défaut à utiliser
	 */
	public function defaultLanguage(){
		return $this->languages->currentLanguage();
	}
	
	/**
	 * Détermine le type de device utilisé
	 */
	private function deviceCapabilities(){
		$this->device = new MobileDetect();
	}
	
	/**
	 * Retourne le device utilisé
	 * @return MobileDetect
	 */
	public function getDevice(){
		return $this->device;
	}
	
	/**
	 * Retourne ou définit le mode d'exécution de l'application
	 * @param string $runMode Mode d'exécution de l'application
	 * @see appLoader.class.php
	 * @return string|\wp\wp
	 */
	public function runMode(string $runMode = null){
		if(is_null($runMode)){
			return $this->runMode;
		}
		
		$this->runMode = $runMode;
		
		return $this;
	}
	
	/**
	 * Définit la liste des routes de l'application
	 */
	public function routes(){
		// Liste les routes de l'application
		$this->routes = new \wp\Http\Routes\routes();
		return $this->routes;
	}
	
	/**
	 * Retourne une route à partir de son nom
	 * @param string $name
	 */
	public function routeByName($name){
		return $this->routes->routeByName($name);
	}
	
	/**
	 * Définit ou retourne le contexte de requête HTTP
	 */
	public function request(){
		if(is_null($this->httpRequest)){
			$this->httpRequest = new Request($this->pathes);
		}
		return $this->httpRequest;
	}
	
	/**
	 * Retourne l'objet contenant les chemins racines
	 */
	public function getPathes(){
		return $this->pathes;
	}
	
	/**
	 * Définit les ressources standard
	 * @param array $assets
	 */
	public function defaultAssets($assets){
		$this->assets->process($assets);
	}
	
	/**
	 * Définit les langues utilisées dans l'application et la langue par défaut
	 * @param object $languages
	 */
	public function languages($languages=null){
		
		$this->languages = new Languages($this->request());
		
		if(is_null($languages)){
			$langue = new \wp\Locales\langue();
			$langue->iso("FR-fr")
				->isDefault(true);
			$this->languages->add($langue);
			return;
		}
		
		foreach($languages as $lang){
			$langue = new \wp\Locales\langue();
			$langue->iso($lang->iso)
				->isDefault($lang->default);
			$this->languages->add($langue);			
		}
	}
	
	/**
	 * Retourne les ressources associées
	 */
	public function assets(){
		return $this->assets;
	}
	/**
	 * Définit ou retourne le moteur de gestion de modèles
	 * @param JSONObject $params
	 */
	public function templateEngine($params=null){
		if(!is_null($params)){
			$enginePath = $this->pathes->getRootPath("wp") . "Vendor/TemplateEngine/"
				. $params->engine . "/" . $params->version . "/";
				
			if($params->engine == "Smarty"){
				define("DS",DIRECTORY_SEPARATOR);
				define("SMARTY_DIR", $enginePath . "libs" . DS);
				define("SMARTY_SYSPLUGINS_DIR", SMARTY_DIR . "sysplugins" . DS);
				define('SMARTY_PLUGINS_DIR', SMARTY_DIR . 'plugins' . DS);
				require_once(SMARTY_SYSPLUGINS_DIR . "smarty_internal_data.php");
				
				$this->templateEngine = \wp\Vendor\TemplateEngine\Smarty\templater::getEngine($this->pathes);
				$this->templateEngine->extension($params->extension);
			}
		}
		
		return $this->templateEngine;
	}
}