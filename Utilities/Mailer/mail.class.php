<?php
/**
 * @name mail.class.php Service pour l'envoi de mail
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Utilities\Mailer
 * @version 1.0
**/
namespace wp\Utilities\Mailer;

require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/PHPMailer/class.phpmailer.php");
require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/PHPMailer/class.phpmaileroauth.php");
require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/PHPMailer/class.phpmaileroauthgoogle.php");
require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/PHPMailer/class.pop3.php");
require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/PHPMailer/class.smtp.php");

use \PHPMailer;

class mail extends \PHPMailer {

	/**
	 * Définit les paramètres de l'émetteur par défaut
	 * @var array
	 **/
	private $defaultSender;

	/**
	 * Définit les destinataires et le type
	 * @var array
	 **/
	private $recipients;
	
	
	/**
	 * Détermine le mode de traitement des mails
	 * @var boolean
	 */
	private $debug;
	
	/**
	 * Modèle pour la génération du mail
	 * @var string
	 */
	private $template;
	
	/**
	 * Instancie un nouvel objet d'envoi de mail
	 * @param boolean $debug Vrai si le traitement doit être debugué
	 */
	public function __construct($debug=false){
		parent::__construct(true);
		$this->isSMTP();
		$this->Host = "localhost";
		$this->Port = 25;
		
		$this->isHTML();
		
		$this->CharSet = "utf-8";
		
		$this->Subject = "Subject";
		
		
		$this->debug = $debug;
		$this->SMTPDebug = 2;
		
		$this->setLanguage("fr", "language");
		
		if($this->debug){
			$this->SMTPDebug = 3;
		}
		
		$this->clearAll();
	}

	public function process(){
		if(!$this->debug){
				
			$this->setFrom($this->defaultSender["mail"],$this->defaultSender["friendlyName"]);
				
				
			foreach($this->recipients as $recipient){
				if(array_key_exists("type",$recipient)){
					switch($recipient["type"]){
						case "default":
						case "to":
							$this->addAddress($recipient["mail"],array_key_exists("friendlyName",$recipient) ? $recipient["friendlyName"] : null);
							break;
		
						case "copy":
						case "cc":
							$this->addCC($recipient["mail"],array_key_exists("friendlyName",$recipient) ? $recipient["friendlyName"] : null);
							break;
		
						case "blindcopy":
						case "bcc":
							$this->addBCC($recipient["mail"],array_key_exists("friendlyName",$recipient) ? $recipient["friendlyName"] : null);
							break;
					}
				} else {
					$this->addAddress($recipient["mail"],array_key_exists("friendlyName",$recipient) ? $recipient["friendlyName"] : null);
				}
			}
				
			return $this->send();
		}
	
		return true;
	}
	
	/**
	 * Définit ou retourne l'émetteur de l'email
	 * @param string $email adresse e-mail de l'émetteur
	 * @param string $friendlyName Nom explicite associé à l'e-mail
	 * @return \wp\mailManager\mailer|multitype:
	 */
	public function sender($email=null,$friendlyName=null){
		if(!is_null($email)){
			$this->defaultSender["mail"] = $email;
			$this->defaultSender["friendlyName"] = $friendlyName;
				
			return $this;
		}
	
		return $this->defaultSender;
	}
	
	/**
	 * Ajoute un destinataire à la pile des destinataires
	 * @param string $email
	 * @param string $friendlyName
	 * @param string $mailType Type de destinaire "To","Cc","Bcc"
	 */
	public function addRecipient($email,$friendlyName,$mailType="to"){	
		$this->recipients[] = array(
				"mail" => $email,
				"friendlyName" => $friendlyName,
				"type" => $mailType
		);
		return $this;
	}
	
	/**
	 * Définit le nom de la vue à charger
	 * La vue est stockée dans le dossier _templates/Mails du dossier de l'application
	 **/
	public function template($name=null){
		if(!is_null($name)){
			$classParts = explode("\\",get_class($this));
			$templateName = $name . \App\appLoader::$tpl->extension();
			$templateFilePath = \App\appLoader::wp()->getPathes()->getRootPath("App") . "_templates/Mails/" . $templateName;
		
			if(file_exists($templateFilePath)){
				$this->template = $templateFilePath;
			}
			return $this;
		}
		return $this->template;
	}
	
	/**
	 * Supprime toutes les données des mails précédent, le cas échéant
	 */
	private function clearAll(){
		$this->clearAllRecipients();
		$this->clearAttachments();
		$this->clearReplyTos();
	
		return;
	}
	
	/**
	 * Pour debuguer, retourne les pièces jointes intégrées
	 */
	public function attachments(){
		return $this->attachment;
	}
}