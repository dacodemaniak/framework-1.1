<?php
/**
 * @name uri.class.php Service de décodage des URI
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Http\Uri
 * @version 1.0
 */
namespace wp\Http\Uri;

use \wp\Utilities\Pathes;
use \wp\Http\Routes\route as Route;
use \wp\Http\Request\request as Request;
use \wp\Http\Request\requestData as RequestData;
use \wp\Exceptions\Errors\error;
use \wp\Exceptions\Routes\routeException as routeException;
use \wp\Utilities\Logger\Logger as Logger;

class uri {
	/**
	 * URI de référence
	 * @var string
	 */
	private $uri;
	
	/**
	 * Chemins généraux de l'application
	 * @var \wp\Utilities\Pathes
	 */
	private $pathes;
	
	/**
	 * Couples clé <=> données de la requête HTTP
	 * @var array
	 */
	private $query;
	
	/**
	 * Routes vers les contrôleurs
	 * @var object
	 */
	private $routes;
	
	/**
	 * Vrai si l'application tourne en mode IP (adresse_ip/path/)
	 * @var boolean
	 */
	private $ipRun = false;
	
	/**
	 * Instancie un nouvel objet d'encodage / décodage d'URI
	 * @param string $uri
	 */
	public function __construct($uri, Request $request){
		if($uri == ""){
			$uri = "/";
		}
		// Ote l'éventuelle requête associée
		if(strpos($uri,"?") !== false){
			$uri = substr($uri,0,strpos($uri,"?"));
		}
		
		$this->ipRun = $request->isIPHost();
		
		$this->uri = $uri;
	}
	
	/**
	 * Définit les chemins généraux de l'application
	 * @param Pathes $pathes
	 */
	public function setPathes($pathes){
		$this->pathes = $pathes;
		return $this;
	}
	
	/**
	 * @deprecated Préférez l'utilisation de setQuery(RequestData $request)
	 * @param unknown $query
	 * @return \wp\Http\Uri\uri
	 */
	public function setHttpQuery($query){
		if(!is_null($query) && strlen($query)){
			if(strpos($query,"&") !== false){
				$queryParts = explode("&", $query);
				foreach($queryParts as $part){
					$parts = explode("=",$part);
					$this->query[$parts[0]] = $parts[1];
				}
			} else {
				$queryParts = explode("=",$query);
				$this->query[$queryParts[0]] = $queryParts[1];
			}
			
		}
		return $this;
	}
	
	/**
	 * Définit les données de la requête HTTP
	 * @param RequestData $requestData
	 */
	public function setQuery(RequestData $requestData){
		$this->query = $requestData;
	}
	
	/**
	 * Traite l'URI
	 * @return JSON Object
	 */
	public function process(){
		$base = array_key_exists("BASE", $_SERVER) ? $_SERVER["BASE"] : "";
		
		//$this->read();
		$this->routes = \App\appLoader::wp()->routes();
		
		$route = new Route();
		
		$logger = new Logger("uri");
		$logger->add("Inspecte l'URI : " . $this->uri, __FILE__, __LINE__);
		
		if($this->uri == "/"){
			/*$route
				->name("root")
				->setNamespace($this->routes->root->namespace)
				->setClassName($this->routes->root->class)
				->setMethod($this->routes->root->method)
				->renderMode($this->routes->root->rendermode)
				->setQuery($this->query);
			return $route;
			*/
			return \App\appLoader::wp()->routeByName("root");
		}
		
		$uriParts = explode("/",substr($this->uri,1,strlen($this->uri)));
		
		/**
		 * @update Déc. 2016 : intégration du backend
		 */
		
		if($uriParts[0] == "Backend" && sizeof($uriParts) > 2 || $base != "" || $this->ipRun){
			// La requête provient du back-office : supprimer la première valeur
			array_shift($uriParts);
		}
		
		// Premier membre du tableau : contrôleur
		//$controller = array_shift($uriParts);
		$notFound = true; // Vrai si aucune route n'est trouvée
		$allFetched = false; // Toutes les routes ont été parcourue
		$index = 0; // Indice pour le parcours de l'URI
		$motif = $uriParts[0];
		while($notFound || $allFetched){
			$notFound = $this->_find($motif);
			if($notFound){
				$index++;
				if($index < count($uriParts) - 1){
					$motif .= "/" . $uriParts[$index];
					$logger->add("Motif en cours : " . $motif, __FILE__, __LINE__);
					#echo "Chercher $motif dans les routes<br>";
				} else 
					$allFetched = true;
			} else {
				break;
			}
		}
		
		#begin_debug
		$logger->add("Contrôleur à charger : " . $motif, __FILE__, __LINE__);
		#die("Motif : " . $motif);
		#end_debug
		
		//$controller = join("/", $uriParts);
		$controller = $motif;
		
		// On redéfinit le tableau des membres de l'URI sans le motif
		$motifParts = explode("/", $motif);
		#var_dump($motifParts);
		
		// Recalcule l'URI sans le motif
		$uriParts = array_diff($uriParts, $motifParts);
		
		#begin_debug
		#var_dump($this->routes);
		#end_debug
		
		//if(property_exists($this->routes, $controller)){
		if($this->routes->routeByName($controller)){
			/*$route
				->name($controller)
				->setNamespace($this->routes->{$controller}->namespace)
				->setClassName($this->routes->{$controller}->class)
				->setMethod($this->routes->{$controller}->method)
				->renderMode($this->routes->{$controller}->rendermode);
			*/
			$route = $this->routes->routeByName($controller);
			
			// Contrôle les paramètres définis dans le tableau des paramètres
			//if(property_exists($this->routes->{$controller},"params")){
			
			if($route->params() && count($route->params())){
				//$params = $this->routes->{$controller}->params;
				$params = $route->params();
				#begin_debug
				#var_dump($params);
				#die();
				#end_debug
				
				// Déterminer les paramètres facultatifs (* en fin de chaîne)
				$nbFacultatifs = $this->getNbMandatoryParams($params);
				
				#begin_debug
				#echo "Paramètres facultatifs : " . $nbFacultatifs . "<br />\n";
				#end_debug
				
				if(count($params) < (count($uriParts) - $nbFacultatifs)){
					$error = new error();
					$error->message("[" . $this->uri . "] Le nombre de paramètres attendus de " . $controller . " [" . count($params) . "] est différent du nombre de paramètres transmis : [" . count($uriParts) . "]")
						->code(-11002)
						->doLog(true);
					throw new routeException($error);
				} else {
					
					#begin_debug
					#var_dump($uriParts);
					#end_debug
					
					$paramIndex = 0; // Pour gérer les paramètres
					
					foreach($uriParts as $index => $value){
						#begin_debug
						#echo "Lecture du paramètre [" . $index . "]: " . $uriParts[$index] . "<br />\n";
						#end_debug
						// Redétermine le nom du paramètre sans l'étoile si c'est le cas
						if(substr($params[$paramIndex],-1) == "*"){
							$param = substr($params[$paramIndex],0,strlen($params[$paramIndex])-1);
						} else {
							$param = $params[$paramIndex];
						}
						$_GET[$param]= $uriParts[$index];
						$paramIndex++;
					}
					
					/** Old way
					$beginIndex = count($motifParts);
					
					for($i=$beginIndex;$i<=count($uriParts);$i++){
						#begin_debug
						echo "Lecture du paramètre [" . $i . "]: " . $uriParts[$i] . "<br />\n";
						#end_debug
						// Redétermine le nom du paramètre sans l'étoile si c'est le cas
						if(substr($params[$i],-1) == "*"){
							$param = substr($params[$i],0,strlen($params[$i])-1);
						} else {
							$param = $params[$i];
						}
						$_GET[$param]= $uriParts[$i];
					}
					**/
					
					// Détermine les autres paramètres manquants, le cas échéant
					if(count($params) > count($uriParts)){
						foreach ($params as $param){
							if(substr($param,-1) == "*"){
								$param = substr($param,0,strlen($param)-1);
							}
							if(!array_key_exists($param, $_GET)){
								$_GET[$param] = "";
							}
						}
					}
				}
			}
			
			$query = new RequestData();
			$route->setQuery($query);
			
			return $route;
		} else {
			// S'agit-il d'un appel ajax
			if(strpos($this->uri,"call") !== false || strpos($this->uri, "Dispatcher") !== false){
				return;
			}
		}
		
		$error = new error();
		$error->message("La route pour l'URI : " . $this->uri . " n'a pas été définie")
			->code(-11001)
			->doLog(true)
			->doRender(true);
		throw new routeException($error);
	}
	
	/**
	 * Lit le fichier des routes de l'application
	 */
	private function read(){
		$routes = file_get_contents($this->pathes->getRootPath("App") . "_commun/configs/appPathes.json");
		$this->routes = json_decode($routes);
	}
	
	/**
	 * Compte le nombre de paramètres optionnels
	 * @param array $params
	 * @return number
	 */
	private function getNbMandatoryParams($params){
		$nbMandatory = 0;
		foreach ($params as $param){
			if(substr($param,-1) == "*"){
				$nbMandatory++;
			}
		}
		return $nbMandatory;
	}
	
	/**
	 * Cherche la route à partir d'un motif
	 * @param string $motif
	 * @return boolean
	 */
	private function _find($motif){
		//return !property_exists($this->routes, $motif);
		return $this->routes->routeByName($motif) === false ? true : false;
	}
}