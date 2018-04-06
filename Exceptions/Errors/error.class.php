<?php
/**
 * @name error.class.php Définition des erreurs levées
 * @author web-Projet.com (jean-luc.aubert@web-projet.com) - Juin 2016
 * @package wp\Exceptions
 * @version 0.1
**/
namespace wp\Exceptions\Errors;

class error {
	/**
	 * Code de l'erreur levée
	 * @var int
	 */
	private $code;
	
	/**
	 * Message associé à l'exception levée
	 * @var string
	 */
	private $message;
	
	/**
	 * Exception précédente
	 * @var \Exception
	 */
	private $previous;
	
	/**
	 * Détermine si l'exception doit être tracée
	 * @var boolean
	 */
	private $doLog;
	
	/**
	 * Détermine si l'erreur doit rediriger vers une page spécifique
	 * @var boolean
	 */
	private $doRender;
	
	/**
	 * Date et heure de la levée de l'erreur
	 * @var \DateTime
	 */
	private $datetime;
	
	/**
	 * Nom du fichier ayant généré l'erreur
	 * @var string
	 */
	private $fileName;
	
	/**
	 * N° de ligne du fichier ayant levé l'exception
	 * @var number
	 */
	private $line;
	
	/**
	 * Instancie un nouvel objet de définition d'erreur
	 */
	public function __construct(){
		$this->datetime = new \DateTime();
	}
	
	/**
	 * Retourne la date et l'heure de l'erreur
	 */
	public function getDateTime(){
		return $this->datetime;
	}
	
	/**
	 * Définit ou retourne le code de l'erreur levée
	 * @param unknown $code
	 * @return number|\wp\Exceptions\Errors\error
	 */
	public function code($code=null){
		if(is_null($code)){
			return $this->code;
		}
		$this->code = $code;
		return $this;
	}
	
	/**
	 * Définit ou retourne le message personnalisé d'erreur
	 * @param string $message
	 * @return string|\wp\Exceptions\Errors\error
	 */
	public function message($message=null){
		if(is_null($message)){
			return $this->message;
		}
		$this->message = $message;
		return $this;
	}
	
	/**
	 * Définit ou retourne la précédente exception levée
	 * @param \Exception $previous
	 * @return \wp\Exceptions\Errors\error
	 */
	public function previous($previous=null){
		if(is_null($previous)){
			return $this->previous();
		}
		$this->previous = $previous;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut de logging de l'exception
	 * @param boolean $doLog
	 */
	public function doLog($doLog=null){
		if(is_null($doLog)){
			return $this->doLog;
		}
		$this->doLog = $doLog;
		return $this;
	}
	
	/**
	 * Définit ou retourne le nom du fichier à l'origine de l'erreur
	 * @param string $filename
	 * @return \wp\Exceptions\Errors\unknown|\wp\Exceptions\Errors\error
	 */
	public function file(string $filename = null){
		if(is_null($filename)){
			return $this->fileName;
		}
		
		$this->fileName = $filename;
		return $this;
	}

	/**
	 * Définit ou retourne le numéro de ligne du fichier ayant provoqué l'erreur
	 * @param int $line
	 * @return number|\wp\Exceptions\Errors\error
	 */
	public function line(int $line = null){
		if(is_null($line)){
			return $this->line;
		}
		
		$this->line = $line;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut de redirection vers une page d'erreur
	 * @param boolean $doRender
	 */
	public function doRender($doRender=null){
		if(is_null($doRender)){
			return $this->doRender;
		}
		$this->doRender = $doRender;
		return $this;
	}
}
