<?php
/**
 * @name ajax.class.php Abstraction de classe utiles pour les appels Ajax
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Ajax
 * @version 1.0
**/
namespace wp\Http\Ajax;

abstract class ajaxRequest {
	/**
	 * Type de réponse à retourner
	 * @var string
	 */
	private $responseType;
	
	/**
	 * Structure à retourner en tant que réponse au format concerné
	 * @var mixed
	 */
	private $response;
	
	/**
	 * Définit le type de réponse à retourner
	 * @param unknown $type
	 * @return \wp\Ajax\ajax
	 */
	public function responseType($type=null){
		if(is_null($type)){
			return $this->responseType;
		}
		$this->responseType = $type;
		return $this;
	}
	
	/**
	 * Définit la réponse à retourner
	 * @param mixed $response
	 */
	protected function setResponse($response){
		$this->response = $response;
		return $this;
	}
	
	/**
	 * Retourne une réponse au format défini
	**/
	public function response(){
		if($this->responseType == "json"){
			$this->sendJSONHeaders();
			echo json_encode($this->response);
		}
	}
	
	/**
	 * Envoie les en-têtes d'une réponse de type JSON
	 */
	private function sendJSONHeaders(){
		header("Vary: Accept");
		
		if (isset($_SERVER["HTTP_ACCEPT"]) &&
				(strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false)) {
			header("Content-type: application/json");
		
		} else {
			header("Content-type: text/plain");
		}		
	}
}
