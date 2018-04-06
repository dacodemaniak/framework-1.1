<?php 
/**
 * @name restAPI.class.php : Services REST
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Rest
 * @version 1.0
 */
namespace wp\Rest;

abstract class restAPI {
	/**
	 * Clé privée de l'API REST pour authentifier les demandes
	 * @var string
	 */
	private $apiKey;
	
	/**
	 * URI du service à appeler
	 * @var string
	 */
	protected $service;
	
	
}