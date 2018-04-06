<?php
/**
 * @name wpException.class.php Service de gestion des exceptions interne
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package \wp\Exceptions
 * @version 0.1
**/
namespace wp\Exceptions;

use \wp\Exceptions\Errors\Error;

class wpException extends \Exception{
	
	/**
	 * Définit le constructeur de l'erreur levée
	 * @param object $error Instance d'erreur levée
	 */
	public function __construct($message=null,$code=null,$previous=null){
		parent::__construct($message,$code,$previous);
	}
}