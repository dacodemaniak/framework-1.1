<?php
/**
 * @name AssetException.class.php Gestionnaire d'exception spécifique relatives aux ressources.
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Exceptions\Classes
 * @version 0.1.0
**/
namespace wp\Exceptions\Assets;

use \wp\Exceptions\wpException;
use \wp\Exceptions\Errors\error;
use \wp\Utilities\Logger\Logger as Logger;

class AssetException extends \wp\Exceptions\wpException{
	
	/**
	 * Instancie un nouvel objet spécifique de gestion d'exceptions
	 * @param Error $error
	 */
	public function __construct(Error $error){
		parent::__construct($error->message(),$error->code());
		$this->process($error);
	}
	
	/**
	 * Traite l'erreur levée
	 * @param Error $error
	 * @todo Traiter les templates pour le rendu des erreurs levées
	 */
	protected function process(Error $error){
		if($error->doRender()){
			echo "<pre><code>\n";
			echo "<strong>" . $error->code() . "</strong><br>";
			echo $error->message();
			echo "</code></pre>";
		}
		
		if($error->doLog()){
			$logger = new Logger("assetException");
			$logger->add($error->message(), $error->file(), $error->line());
		}
	}
}