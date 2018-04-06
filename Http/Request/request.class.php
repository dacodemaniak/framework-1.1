<?php
/**
 * @name request.class.php Service de gestion des requêtes HTTP
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package \wp\Http\Request
 * @version 1.0
**/
namespace wp\Http\Request;

use \wp\Utilities\Pathes;
use \wp\Rest\Get\restGet as Get;
use \wp\Rest\Put\restPut as Put;
use \wp\Rest\Post\restPost as Post;
use \wp\Rest\Delete\restDelete as Delete;
use \wp\Http\Uri;
use \wp\Http\Request\requestData as RequestData;

class request {
	/**
	 * Méthode utilisée pour la requête
	 * @var string PUT, GET, POST, DELETE, XHR_REQUEST
	 */
	private $method;
	
	/**
	 * URI complète de la requête
	 * @var string
	 */
	private $uri;
	
	/**
	 * Requête GET HTTP
	 * @var unknown
	 */
	private $queryString;
	
	/**
	 * Timestamp du début de la requête
	 * @var int
	 */
	private $beginTime;
	
	/**
	 * En-tête accept si défini
	 * @var string
	 */
	private $accept;
	
	/**
	 * Jeu de caractère accepté
	 * @var string
	 */
	private $charset;
	
	/**
	 * En-tête "language" si défini
	 * @var string
	 */
	private $language;
	
	/**
	 * En-tête "encoding" de la requête HTTP
	 * @var unknown
	 */
	private $encoding;
	
	/**
	 * En-tête "Connection" de la requête HTTP
	 * @var string
	 */
	private $connexion;
	
	/**
	 * En-tête "Host" de la requête HTTP
	 * @var string
	 */
	private $host;
	
	/**
	 * Nom du serveur exécutant le script
	 * @var string
	 */
	private $serverName;
	
	/**
	 * En-tête "Referrer" de la requête HTTP
	 * @var string
	 */
	private $referrer;
	
	/**
	 * En-tête "User-Agent" de la requête HTTP
	 * @var string
	 */
	private $userAgent;
	
	/**
	 * Vrai si la requête a été initiée avec un protocole sécurisé
	 * @var boolean
	 */
	private $https;
	
	/**
	 * Adresse IP cliente accédant à la ressource
	 * @var string
	 */
	private $clientAddress;
	
	/**
	 * Hôte du client accédant à la ressource
	 * @var string
	 */
	private $clientHost;
	
	/**
	 * Port utilisé par le client accédant à la ressource
	 * @var int
	 */
	private $clientPort;
	
	/**
	 * Chemin absolu vers le fichier exécutant le script
	 * @var string
	 */
	private $scriptFileName;
	
	/**
	 * Chemin dans le système de fichier après traduction virtuel -> réel
	 * @var string
	 */
	private $pathTranslated;
	
	/**
	 * Chemin complet ayant permis l'accès à la ressource demandée
	 * @var array
	 */
	private $pathInfo;
	
	/**
	 * Origine de la demande HTTP
	 * @var string
	 */
	private $HTTPOrigin;
	
	/**
	 * Chemins définis pour l'application
	 * @var \wp\Utilities\Pathes
	 */
	private $pathes;
	
	/**
	 * Données de requête HTTP
	 * @var \wp\Http\Request
	 */
	private $requestData;
	
	/**
	 * Route à retourner pour traiter la demande
	 * @var object
	 */
	private $route;
	
	/**
	 * Instancie un objet de Requête HTTP
	 */
	public function __construct($pathes){
		$this->pathes = $pathes;
		
		$this->setData();
		
		
		$this->requestData = new RequestData();
		
		$this->route = $this->decodeURI();
		
		
		
	}
	
	/**
	 * Retourne la collection des données de requête HTTP
	 */
	public function getRequestData(){
		return $this->requestData;
	}
	
	/**
	 * Retourne la route générée par la requête
	**/
	public function getRoute(){
		return $this->route;
	}
	
	/**
	 * Retourne la valeur de $_SERVER["HTTP_HOST"]
	 * @return string
	 */
	public function getHost(){
		return $this->host;
	}
	
	/**
	 * Retourne l'état de l'hôte, vrai s'il s'agit d'une adresse IP
	 * @return boolean
	 */
	public function isIPHost(){
		if($this->serverName == "127.0.0.1" || $this->serverName == "localhost"){
			return true;
		}
		return (bool) ip2long($this->host);
	}
	
	/**
	 * Retourne le verbe HTTP utilisé pour la requête
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}
	
	/**
	 * Retourne l'origine HTTP de la demande
	 * @return string
	 */
	public function getOrigin(){
		return $this->HTTPOrigin;	
	}
	
	/**
	 * Retourne vrai si la ressource demandée est un appel Ajax
	 */
	public function isAjaxCall($isAjaxCall = null){
		if(is_null($isAjaxCall)){
			return strpos($this->pathInfo["filename"],"call") > 0 || strpos($this->pathInfo["filename"],"Dispatcher") > 0 ? true : false;
		}
		
		return is_bool($isAjaxCall) ? $isAjaxCall : false;
	}
	
	/**
	 * Prépare l'affichage de l'objet lui-même
	 * @return string
	 */
	public function __toString(){
		$htmlOutput = "<ul>\n";
		$htmlOutput .= "\t<li>Méthode : " . $this->method . "</li>\n";
		$htmlOutput .= "\t<li>URI : " . $this->uri . "</li>\n";
		$htmlOutput .= "\t<li>Requête : " . $this->queryString . "</li>\n";
		$htmlOutput .= "\t<li>Début : " . $this->beginTime . "</li>\n";
		$htmlOutput .= "\t<li>Accept : " . $this->accept . "</li>\n";
		$htmlOutput .= "\t<li>Charset : " . $this->charset . "</li>\n";
		$htmlOutput .= "\t<li>Language : " . $this->language . "</li>\n";
		$htmlOutput .= "\t<li>Encoding : " . $this->encoding . "</li>\n";
		$htmlOutput .= "\t<li>Connexion : " . $this->connexion . "</li>\n";
		$htmlOutput .= "\t<li>Hôte : " . $this->host . "</li>\n";
		$htmlOutput .= "\t<li>Référent : " . $this->referrer . "</li>\n";
		$htmlOutput .= "\t<li>Connexion sécurisée : " . ($this->https ? "Oui" : "Non") . "</li>\n";
		$htmlOutput .= "\t<li>IP Client : " . $this->clientAddress . "</li>\n";
		$htmlOutput .= "\t<li>Domaine Client : " . $this->clientHost . "</li>\n";
		$htmlOutput .= "\t<li>Port Client : " . $this->clientPort . "</li>\n";
		$htmlOutput .= "\t<li>Fichier en cours : " . $this->scriptFileName . "</li>\n";
		$htmlOutput .= "\t<li>Chemin physique : " . $this->pathTranslated . "</li>\n";
		$htmlOutput .= "\t<li>Dossier racine : " . $this->pathInfo["dirname"] . "</li>\n";
		$htmlOutput .= "\t<li>Nom de base : " . $this->pathInfo["basename"] . "</li>\n";
		$htmlOutput .= "\t<li>Extension : " . $this->pathInfo["extension"] . "</li>\n";
		$htmlOutput .= "\t<li>Nom du fichier : " . $this->pathInfo["filename"] . "</li>\n";
		$htmlOutput .= "\t<li>Taille du tableau GET : " . $this->requestData->count("get") . "</li>\n";
		$htmlOutput .= "\t<li>Taille du tableau POST : " . $this->requestData->count("post") . "</li>\n";
		$htmlOutput .= "</ul>\n";
		
		return $htmlOutput;
	}
	
	/**
	 * Définit les propriétés de la requête
	 */
	private function setData(){
		$beginTime = new \DateTime();
		if(array_key_exists("REQUEST_TIME_FLOAT",$_SERVER))
			$beginTime->setTimestamp($_SERVER["REQUEST_TIME_FLOAT"]);
		else 
			$beginTime->setTimestamp($beginTime->getTimestamp());
				
		/**
		 * Méthode utilisée pour le transfert de données
		 * @var \wp\Http\Request\request $method
		 */
		$this->method = $_SERVER["REQUEST_METHOD"];
		
		$this->uri = $_SERVER["REQUEST_URI"];
		
		$this->queryString = $_SERVER["QUERY_STRING"];
		
		$this->beginTime = $beginTime->format("d-m-Y H:i:s.u");
		
		$this->accept = $_SERVER["HTTP_ACCEPT"];
		$this->charset = isset($_SERVER["HTTP_ACCEPT_CHARSET"]) ? $_SERVER["HTTP_ACCEPT_CHARSET"] : "Inconnu";
		$this->language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		$this->encoding = $_SERVER["HTTP_ACCEPT_ENCODING"];
		$this->connexion = array_key_exists("HTTP_CONNECTION",$_SERVER) ? $_SERVER["HTTP_CONNECTION"] : "unknown";
		
		$this->host = $_SERVER["HTTP_HOST"];
		$this->referrer = $_SERVER["HTTP_USER_AGENT"];
		$this->https = isset($_SERVER["HTTPS"]) ? true : false;
		$this->serverName = $_SERVER["SERVER_NAME"];
		
		$this->clientAddress = $_SERVER["REMOTE_ADDR"];
		$this->clientHost = isset($_SERVER["REMOTE_HOST"]) ? $_SERVER["REMOTE_HOST"] : "Inconnu";
		$this->clientPort = $_SERVER["REMOTE_PORT"];
		
		$this->scriptFileName = $_SERVER["SCRIPT_FILENAME"];
		$this->pathTranslated = isset($_SERVER["PATH_TRANSLATED"]) ? $_SERVER["PATH_TRANSLATED"] : "";
		
		$this->pathInfo = pathinfo($this->scriptFileName);
		
		// Récupère l'origine HTTP de la demande
		if(array_key_exists("HTTP_ORIGIN", $_SERVER)){
			$this->HTTPOrigin = $_SERVER["HTTP_ORIGIN"];
		}
	}
	
	/**
	 * Décode l'URI pour effectuer l'appel
	 */
	private function decodeURI(){
		$uri = new \wp\Http\Uri\uri($this->uri, $this);
		$uri->setPathes($this->pathes)
			->setQuery($this->requestData);
		$route = $uri->process();
		
		return $route;
	}
	
}