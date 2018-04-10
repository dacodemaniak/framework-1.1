<?php
/**
 * @name jsonResponse.class.php Service de gestion des réponses au format JSON
 * @author web-Projet.com (contact@web-projet.com) - Sept. 2016
 * @package wp\Http\Response
 * @version 1.0
 **/
namespace wp\Http\Response;

class jsonResponse extends \wp\Http\Response\response {
	
	/**
	 * Instancie une nouvelle réponse au format HTML en s'appuyant sur le moteur de template
	 */
	public function __construct(){
		$this->controllers = array();
	}
	
	/**
	 * Traite la réponse au format HTML
	 * @param array $args
	 */
	public function process($args=array()){
		// Envoyer les en-têtes JSON
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 14 Jul 1997 00:00:01 GMT"); // Expiration dans le passé
		header("Content-type: application/json");
		
		// Assigne les contrôleurs à la vue
		foreach($this->controllers as $controller){
			//echo "Modèle à charger pour " . $controller->name() . " : " . $controller->getTemplate() . "<br />\n";
			//echo "Contrôleur courant : " . $controller->name() . "<br>";
			echo $controller->process();
		}
	}
}