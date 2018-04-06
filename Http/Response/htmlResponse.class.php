<?php
/**
 * @name htmlResponse.class.php Service de gestion des réponses au format HTML
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Http\Response
 * @version 1.0
**/
namespace wp\Http\Response;

class htmlResponse extends \wp\Http\Response\response {

	
	/**
	 * Arguments optionnels à passer à la vue
	 * @var array
	 */
	private $args;
	
	/**
	 * Stocke les templates à charger par régions
	 * @var array
	 */
	private $regionMapper;
	
	/**
	 * Instancie une nouvelle réponse au format HTML en s'appuyant sur le moteur de template
	 */
	public function __construct(){
		$this->engine = !is_null(\App\appLoader::wp()) ? \App\appLoader::$tpl : \Backend\appLoader::$tpl;
		$this->controllers = array();
		$this->regionMapper = array();
		
	}
	
	/**
	 * Retourne la structure de stockage des mappers par régions
	 */
	public function regionMapper(){
		return $this->regionMapper;
	}
	
	/**
	 * Traite la réponse au format HTML
	 * @param array $args
	 */
	public function process($args=array()){
		if(sizeof($args)){
			$this->args = $args;
			$this->engine->setVar($this->args);
		}
		
		// Assigne les contrôleurs à la vue
		foreach($this->controllers as $controller){
			#echo "Modèle à charger pour " . $controller->name() . " : " . $controller->getTemplate() . "<br />\n";
			$this->engine->setVar($controller->name(),$controller);
			$this->regionMapper[$controller->region()][] = $controller->getTemplate();
		}
		
		// Passe l'objet lui-même
		$this->engine->setVar("response",$this);
		
		$this->engine->render();
	}
	

}