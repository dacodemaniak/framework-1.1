<?php
/**
 * @name templater.class.php Service de chargement du moteur de template Smarty
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Vendor\TemplateEngine\Smarty
 * @version 1.0
**/
namespace wp\Vendor\TemplateEngine\Smarty;

use \wp\Utilities\Pathes\pathes as Pathes;

class templater {
	/**
	 * Chemins de l'application
	 * @var \wp\Utilities\Pathes
	 */
	private $pathes;
	
	/**
	 * Définit l'extension du fichier de modèle
	 * @var string
	 */
	private $extension;
	
	/**
	 * Instance de la classe \wp\Vendor\TemplateEngine
	 * @var \wp\Vendor\TemplateEngine\modifier
	 */
	private $modifier;
	
	/**
	 * Instance courante
	 * @var object
	 */
	public static $engine;
	
	/**
	 * Instance du moteur de gestion de template
	 * @var \Smarty
	 */
	private $tpl;
	
	/**
	 * Objet de données du modèle Smarty
	 * @var object
	 */
	private $datas;
	
	/**
	 * Objet de modèle
	 * @var object
	 */
	private $renderer;
	
	/**
	 * Instancie un nouvel objet Smarty pour la gestion des modèles
	 * @param Pathes $pathes
	 */
	private function __construct($pathes){
		$this->pathes = $pathes;
		
		require_once(SMARTY_DIR . "Smarty.class.php");
		
		$this->tpl = new \Smarty();
		
		$this->setDirectories();
		
		$this->tpl->debugging = false;
		
		$this->modifier = new \wp\Vendor\TemplateEngine\modifier();
		
		$this->tpl->registerPlugin("modifier","stripTags",array(&$this->modifier,"stripTags"));
		
		$this->datas = $this->tpl->createData();
		
		// En fonction du mode d'exécution App ou Backend, on change le template de base
		if(!is_null(\App\appLoader::wp()))
			$this->renderer = $this->tpl->createTemplate("./layout.tpl",$this->datas);
		else
			$this->renderer = $this->tpl->createTemplate("./Backend/layout.tpl",$this->datas);
		//$this->renderer = $this->tpl->createTemplate("layout.tpl",$this->datas);
		
		
	}

	public static function getEngine(Pathes $pathes){
		if(!isset(self::$engine)){
			self::$engine = new templater($pathes);
		}
		return self::$engine;
	}
	
	public function render(){
		// Envoie les en-têtes HTML
		header("Content-Type: text/html");
		$this->renderer->display();
	}
	
	public function capture($template,$datas){
		if(!is_null($datas) && sizeof($datas)){
			$this->tpl->assign($datas);
		}
		return $this->tpl->fetch($template);
	}
	
	/**
	 * Retourne l'extension utilisée pour les modèles Smarty
	 * @param string $extension
	 * @return string|\wp\Vendor\TemplateEngine\Smarty\templater
	 */
	public function extension($extension=null){
		if(is_null($extension)){
			return $this->extension;
		}
		$this->extension = $extension;
		return $this;
	}
	
	/**
	 * Définit les variables à passer à la vue
	 * @param multitype $var
	 * @param multitype $varContent
	 */
	public function setVar($var,$varContent=null){
		if(!is_array($var) && is_null($varContent)){
			return false;
		}
		
		if(is_null($varContent)){
			$this->datas->assign($var);
		} else {
			$this->datas->assign($var,$varContent);
		}
	
		return true;
	}
	
	/**
	 * Retourne le chemin absolu vers un template
	 * @param string $templateName
	 */
	public function absolutePath($templateName){
		$path = ""; // Chemin en fonction du mode d'exécution
		if(!is_null(\App\appLoader::wp())){
			$path = \App\appLoader::wp()->getPathes()->getRootPath("App");
		} else {
			$path = \Backend\appLoader::wp()->getPathes()->getRootPath("App");
		}
		
		if(strtoupper(substr(PHP_OS,0,3)) == "WIN"){
			return "file:" . $path . $templateName;
		}
		
		return "file:/" .$path . $templateName;
	}
	
	/**
	 * Définit les dossiers de stockage et de compilation des modèles
	 */
	private function setDirectories(){
		$this->tpl->setTemplateDir($this->pathes->getRootPath("App") . "/_templates");
		$this->tpl->setCacheDir($this->pathes->getRootPath("App") . "/engine/cache");
		$this->tpl->setCompileDir($this->pathes->getRootPath("App") . "/engine/templates_c");
		$this->tpl->setConfigDir($this->pathes->getRootPath("App") . "/engine/configs");
	
		return;
	}
}
