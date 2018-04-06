<?php
/**
 * @name field.class.php Factory spécifique pour la gestion de champs de formulaire
 * @author web-Projet.com (contact@web-projet.com)
 * @package wp\Patterns\ClassFactory
 * @version 1.0
 */
namespace wp\Patterns\ClassFactory;

use \wp\Http\Request\requestData as Request;
use \wp\Http\Routes\route as Route;

class field extends \wp\Patterns\ClassFactory\factory {
	/**
	 * Détermine si on peut ajouter une instance de la classe concernée
	 * @var boolean
	 */
	private $addInstance		= true;
	
	/**
	 * Instance du fieldset parent du champ à créer
	 * @var \wp\Html\Forms\Fieldsets\
	 */
	private $fieldset;
	
	/**
	 * Type de champ à instancier
	 * @var string
	 */
	private $type;
	
	/**
	 * Instancie la création d'un nouvel objet de type Asset
	 * @param Request $request
	 */
	public function __construct($fieldset, $type){
		
		if($this->addInstance = $this->setClassName("\\wp\\Html\\Forms\\Fields\\" . $type) != false){
			$this->fieldset = $fieldset;
			$this->addInstance();
		}
	}
	
	/**
	 * Instancie la classe concernée et retourne l'objet concerné
	 * {@inheritDoc}
	 * @see \wp\Patterns\ClassFactory\factory::addInstance()
	 */
	public function addInstance(){
		if($this->addInstance){
			$this->reflection = new \ReflectionClass($this->className);
			$this->instance = $reflection->newInstanceArgs(array($this->fieldset));
		}
		return;
	}
	
	/**
	 * Retourne l'instance courante de l'objet
	 * @return \wp\Patterns\ClassFactory\Object
	 */
	public function getInstance(){
		return $this->instance;
	}
}