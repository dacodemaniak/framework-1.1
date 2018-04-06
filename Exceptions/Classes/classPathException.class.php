<?php
/**
 * @name classPathException.class.php Gestionnaire d'exception spécifique
 * 	interceptant l'erreur de chargement de ressource de classe.
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Exceptions\Classes
 * @version 1.0
**/
namespace wp\Exceptions\Classes;

use \wp\Exceptions\wpException;
use \wp\Exceptions\Errors\error;

class classPathException extends \wp\Exceptions\wpException{
	
	/**
	 * Instancie un nouvel objet spécifique de gestion d'exceptions
	 * @param Error $error
	 */
	public function __construct(Error $error){
		parent::__construct($error->message(),$error->code());
		$this->process($error);
	}
	
	protected function process(Error $error){
		
	}
}
?>