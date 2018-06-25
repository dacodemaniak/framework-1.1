<?php
/**
 * @name controller.class.php Contrôleur REST pour les appels Client
 * @author IDEA Factory (dev-team@ideafactory.fr) - Jan. 2018
 * @package wp\Controller\REST
 * @version 0.1.0
**/

namespace wp\Controller\REST;

use \wp\Http\Request\request as Request;
use \wp\Http\Request\requestData as RequestData;

abstract class Controller {
	/**
	 * Instance de requête HTTP
	 * @var \wp\Http\Request\request
	 */
	private $request;
	
	/**
	 * Données transmises dans la requête HTTP
	 * @var \RequestData
	 */
	private $requestData;
	
	/**
	 * Tableau résultat à encoder
	 * @var array
	 */
	protected $result = array(
		"status" => 1
	);
	
	/**
	 * Définit ou retourne les données de requête
	 * @param \Request $request
	 */
	protected function request(){
		if(is_null($this->request)){
			$this->request = \App\appLoader::wp()->request();
			
		}
		return $this->request;
	}
	
	/**
	 * Retourne les données de la requête HTTP
	 * @return \wp\Http\RequestData
	 */
	protected function requestData(){
		if(is_null($this->requestData)){
			$this->requestData = $this->request->getRoute()->getQuery();
		}
		
		
		return $this->requestData;
	}
	
	/**
	 * Retourne le type de réponse attendu
	 * @return string
	 */
	public function responseType(){
		return "json";	
	}
	
	/**
	 * Détermine si un service est disponible dans le contrôleur
	 * @param string $service
	 */
	public function serviceExists($service){
		return method_exists($this,$service);
	}
	
	public function process(){
		return json_encode($this->result);
	}
	
	/**
	 * Envoie les en-têtes CORS
	 */
	protected function sendHeaders(){
		
		// On autorise les requêtes qui viennent d'un autre domaine (Cross Domain)
		if (!is_null($this->request()->getOrigin())) {
			// Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
			// you want to allow, and if so:
			header("Access-Control-Allow-Origin: " . $this->request()->getOrigin());
			header('Access-Control-Allow-Credentials: true');

		} else {
			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Credentials: true');
		}
		
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		
		// Access-Control headers are received during OPTIONS requests
		if ($this->request()->getMethod() == 'OPTIONS') {
			
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				// may also be using PUT, PATCH, HEAD etc
				header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
				
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
					
			exit(0);
		}
	}
}