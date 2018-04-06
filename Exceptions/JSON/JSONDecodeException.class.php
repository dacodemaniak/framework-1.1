<?php
/**
 * @name JSONDecodeException.class.php Gestionnaire d'exceptions pour le décodage des contenus JSON
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Exceptions\JSON
 * @version 0.1.0
 */
namespace wp\Exceptions\JSON;

use \wp\Exceptions\wpException;
use \wp\Exceptions\Errors\error;
use \wp\Utilities\Logger\Logger as Logger;

class JSONDecodeException extends \wp\Exceptions\wpException{
	
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
		
		if($error->doLog()){
			$logger = new \wp\Utilities\Logger\Logger("json");
			$logger->add("[" . $error->code() . "] " . $error->message(), $error->file(), $error->line());
		}
	}
}