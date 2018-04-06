<?php
/**
 * @name consumer.class.php Abstraction de client SOAP pour la consommation de webservices
 * @author web-Projet.com (contact@web-projet.com) - Nov. 2016
 * @package wp\Webservices\Consumer\Soap
 * @version 1.0
**/
namespace wp\Webservices\Consumer\Soap;


abstract class consumer {
	/**
	 * Client SOAP généré
	 * @var \SoapClient
	 */
	protected $client;
	
	/**
	 * URL du contrat WSDL pour l'appel au WebService
	 * @var string
	 */
	private $wsdlUrl;
	
	/**
	 * Options pour le constructeur SOAP Client
	 * @var array
	 */
	private $options;
	
	/**
	 * Variables SOAP
	 * @var array
	 */
	private $vars;
	
	/**
	 * En-têtes SOAP
	 * @var array
	 */
	private $headers;
	
	
	/**
	 * Définit ou retourne le contrat WSDL à passer au client SOAP
	 * @param string $url
	 */
	public function wsdlUrl($url = null){
		if(is_null($url)){
			return $this->wsdlUrl;
		}
		$this->wsdlUrl = $url;
		return $this;
	}
	
	/**
	 * Retourne une variable SOAP identifiée
	 * @param string $name
	 */
	public function getVar($name){
		if(array_key_exists($name, $this->vars)){
			return $this->vars[$name];
		}
		return false;
	}
	
	/**
	 * Retourne une en-tête SOAP identifiée
	 * @param string $name
	 */
	public function getHeader($name){
		if(array_key_exists($name, $this->headers)){
			return $this->headers[$name];
		}
		return false;
	}
	/**
	 * Ajoute une clé de paramètres à passer au constructeur SOAP
	 * @param string $name
	 * @param mixed $value
	 * @return \wp\Webservices\Consumer\Soap\consumer
	 */
	public function addOption($name,$value){
		if(is_array($this->options)){
			if (!array_key_exists($name, $this->options)){
				$this->options[$name] = $value;
			}
		} else {
			$this->options[$name] = $value;
		}
		return $this;
	}
	
	/**
	 * Ajoute une variable SOAP à la pile des variables
	 * @param string $name
	 * @param string $content
	 * @param array $params
	 */
	public function addVar($name,$content,$params=null){
		$encoding = XSD_ANYXML;
		$typeName = null;
		$typeNameSpace = null;
		$nodeName = null;
		$nodeNameSpace = null;

		if(!is_null($params)){
			$encoding = array_key_exists("encoding",$params) ? $params["encoding"] : $encoding;
			$typeName = array_key_exists("typeName", $params) ? $params["typeName"] : $typeName;
			$nodeName = array_key_exists("nodeName", $params) ? $params["nodeName"] : $nodeName;
			$nodeNameSpace = array_key_exists("nodeNameSpace", $params) ? $params["nodeNameSpace"] : $nodeNameSpace;
		}
		
		if(is_array($this->vars)){
			if(!array_key_exists($name, $this->vars)){
				$this->vars[$name] = new \SoapVar($content, $encoding, $typeName, $typeNameSpace, $nodeName, $nodeNameSpace);
			}
		} else {
			$this->vars[$name] = new \SoapVar($content, $encoding, $typeName, $typeNameSpace, $nodeName, $nodeNameSpace);
		}
		return $this;
	}
	
	/**
	 * Ajoute une en-tête SOAP
	 * @param string $name
	 * @param string $namespace
	 * @param \SoapVar $data
	 * @param array $params
	 */
	public function addHeader($name,$namespace,$data,$params=null){
		$mustUnderstand = false;
		$actor = "";
		
		if(!is_null($params)){
			$mustUnderstand = array_key_exists("mustUnderstand",$params) ? $params["mustUnderstand"] : $mustUnderstand;
			$actor = array_key_exists("actor", $params) ? $params["actor"] : "";
		}
	
		if(is_array($this->headers)){
			if(!array_key_exists($name, $this->headers)){
				$this->headers[$name] = new \SoapHeader($namespace, $name, $data, $mustUnderstand);
			}
		} else {
			$this->headers[$name] = new \SoapHeader($namespace, $name, $data, $mustUnderstand);
		}
		return $this;
	}
}