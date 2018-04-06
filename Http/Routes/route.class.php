<?php
/**
 * @name route.class.php Service de gestion des routes
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Http\Routes
 * @version 1.0
 */
namespace wp\Http\Routes;

use \wp\Http\Request\requestData as RequestData;

class route {
	/**
	 * Nom de la route dans le fichier appPathes
	 * @var string
	 */
	private $name;
	
	/**
	 * Espace de nom pour le chargement du contrôleur
	 * @var string
	 */
	private $namespace;
	
	/**
	 * Nom de la classe à charger
	 * @var string
	 */
	private $className;
	
	/**
	 * Nom de la méthode du contrôleur à exécuter
	 * @var string
	 */
	private $method;
	
	/**
	 * Mode de rendu pour la réponse : "page", "overlay"
	 * @var string
	 */
	private $renderMode;
	
	/**
	 * Données de requête HTTP formulée
	 * @var \wp\Http\Request\requestData
	 */
	private $query;
	
	/**
	 * URL directe s'il s'agit par exemple d'un fichier en téléchargement
	 * @var string
	 */
	private $url;
	
	/**
	 * Paramètres de la route
	 * @var array
	 */
	private $params;
	
	private $injections;
	
	private $decorators;
	
	/**
	 * Retourne le nom de la route
	 */
	public function name($name=null){
		if(is_null($name))
			return $this->name;
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Définit l'espace de nom pour la classe à charger
	 * @param string $namespace
	 */
	public function setNamespace($namespace){
		$this->namespace = str_replace("::","\\",$namespace);
		return $this;
	}
	
	/**
	 * Retourne l'espace de nom de la classe à charger
	 */
	public function getNameSpace(){
		return $this->namespace;
	}
	
	/**
	 * Détermine s'il s'agit de la route par défaut de l'application
	 */
	public function isDefault(){
		return $this->namespace == "\\App\\Defaut\\Index\\" ? true : false;
	}
	
	/**
	 * Définit le nom de la classe à charger
	 * @param string $className
	 * @return \wp\Http\Routes\route
	 */
	public function setClassName($className){
		$this->className = $className;
		return $this;
	}
	
	/**
	 * Retourne le nom de la classe à charger
	 */
	public function getClassName(){
		if(!is_null($this->className))
			return $this->className;
		
		// Il peut s'agir d'un lien direct vers une ressource
		if(!is_null($this->url)){
			return $this->url;
		}
	}
	
	/**
	 * Définit ou retourne le mode de rendu de la route courante
	 * @param string $renderMode
	 */
	public function renderMode($renderMode = null){
		if(is_null($renderMode)){
			#begin_debug
			#echo is_null($this->renderMode) ? "retourne page" : "Retourne : " . $this->renderMode . "<br />\n";
			#end_debug
			return is_null($this->renderMode) ? "page" : $this->renderMode;
		}
		$this->renderMode = $renderMode;
		return $this;
	}
	
	/**
	 * Retourne l'URI du lien concerné
	 * @param unknown $params
	 */
	public function getURI($params){
		$uri					= "/" . $this->name . "/";
		
		// Il peut s'agir d'un lien direct vers une ressource
		if(!is_null($this->url)){
			return $this->url;
		}
		
		if(property_exists($this,"params") && sizeof($this->params)){
			if(!is_array($params)){
				$valueParams[] = $params;
			} else {
				$valueParams = $params;
			}
			
			if(sizeof($valueParams) == sizeof($this->params)){
				foreach($valueParams as $param){
					$uri .= $param . "/";
				}
			}
		}
		$uri = substr($uri,0,strlen($uri) - 1);
		
		return $uri;
	}
	
	/**
	 * Définit ou retourne l'URL directe à retourner le cas échéant
	 * @param unknown $url
	 */
	public function url($url=null){
		if(!is_null($url)){
			$this->url = $url;
			return $this;
		}
		return $this->url;
	}
	
	/**
	 * Définit ou retourne les paramètres de la route courante
	 * @param unknown $params
	 */
	public function params($params=null){
		if(!is_null($params)){
			$this->params = $params;
			return $this;
		}
		return $this->params;
	}
	
	/**
	 * Définit la méthode de la classe à traiter dans le contrôleur
	 * @param string $method
	 * @return \wp\Http\Routes\route
	 */
	public function setMethod($method){
		$this->method = $method;
		return $this;
	}
	
	/**
	 * Définit les paramètres de la requête HTTP
	 * @param array $query
	 */
	public function setQuery(RequestData $query){
		$this->query = $query;
		return $this;
	}
	
	/**
	 * Retourne les données de la requête HTTP
	 */
	public function getQuery(){
		return $this->query;
	}
	
	/**
	 * Ajoute une paire clé / valeur dans la requête HTTP
	 * @param string $key
	 * @param string $value
	 */
	public function addQueryParam($key,$value){
		if(!array_key_exists($key,$this->query)){
			$this->query[$key] = $value;
		}
		return $this;
	}
	
	/**
	 * Ajoute ou retourne la liste des décorateurs
	 * @param string $decorator
	 * @return array | Route
	 */
	public function decorators($decorator = null){
		if(is_null($decorator)){
			return $this->decorators;
		}
		
		if(is_array($this->decorators)){
			if(!in_array($decorator, $this->decorators)){
				$this->decorators[] = $decorator;
			}
		} else {
			$this->decorators[] = $decorator;
		}
		
		return $this;
	}
	
}