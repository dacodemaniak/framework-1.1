<?php
/**
 * @name queryException.class.php Gestionnaire d'exceptions spécifiques pour les requêtes SQL
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Exceptions\Query
 * @version 1.0
 */
namespace wp\Exceptions\Database;

use \wp\Exceptions\wpException;
use \wp\Exceptions\Errors\error;

class queryException extends \wp\Exceptions\wpException{

	/**
	 * Instancie un nouvel objet spécifique de gestion d'exceptions
	 * @param Error $error
	 */
	public function __construct(Error $error){
		parent::__construct($error->message(),$error->code());
		$this->process($error);
	}
	
	/**
	 * Traite l'erreur en fonction de la définition de l'objet Error
	 * @param Error $error Définition de l'erreur levée
	 */
	protected function process(Error $error){
		if($error->doRender()){
			echo "<pre><code>\n";
			echo "<strong>" . $error->code() . "</strong><br>";
			echo $error->message();
			echo "</code></pre>";
		}
		die("Exécution interrompue");
	}
}