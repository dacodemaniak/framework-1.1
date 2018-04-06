<?php
/**
 * @name field.class.php Abstraction de définition d'un champ de formulaire
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Html\Fields
 * @version 1.0
**/
namespace wp\Html\Forms\Fields;

use \wp\Html\Attributes\attributes as Attributes;

abstract class field extends \wp\Html\Attributes\attributes {
	/**
	 * Type du champ à générer
	 * @var string
	 */
	private $type;
	
	/**
	 * Définit le statut obligatoire du champ courant
	 * @var boolean
	 */
	private $isRequired;
	
	/**
	 * Fieldset de rattachement du champ
	 * @var \wp\Html\Forms\Fieldsets\fieldset
	 */
	protected $fieldset;
	
	/**
	 * Nom du fichier de modèle associé au champ de formulaire
	 * @var string
	 */
	protected $template;
	
	/**
	 * Collection des classes associée au groupe de champ
	 * @var array
	 */
	protected $groupCss;

	/**
	 * Texte d'aide associé au label (sous le label lui-même)
	 * @var string
	 */
	private $helpMsg;
	
	/**
	 * Définit le type du champ à traiter. Charge le template associé
	 * @param string $type
	 * @return string|\wp\Html\Fields\fields
	 */
	public function type($type=null){
		if(is_null($type)){
			return $this->type;
		}
		$this->type = $type;
		
		$this->template = $type;
		
		return $this;
	}
	
	/**
	 * Détermine ou retourne le statut obligatoire du champ concerné
	 * @param boolean $required
	 */
	public function isRequired($required = null){
		if(is_null($required)){
			return is_null($this->isRequired) ? false : $this->isRequired;
		}
		if(is_bool($required)){
			$this->isRequired = $required;
		} else {
			$this->isRequired = false;
		}
		return $this;
	}
	
	/**
	 * Définit ou retourne la valeur du tag "help" du champ
	 * @param string $help
	 */
	public function helpMsg($help = null){
		if(is_null($help)){
			return $this->helpMsg;
		}
		$this->helpMsg = $help;
		return $this;
	}
	
	/**
	 * Ajoute le champ courant au fieldset courant
	 */
	public function hydrate(){
		$this->fieldset->fields($this);
	}
	
	/**
	 * Retourne le modèle associé au champ courant
	 */
	public function getTemplate(){
		return $this->template;
	}

	/**
	 * Ajoute une classe CSS ou retourne les classes du groupe de champ
	 * @param string $class
	 */
	public function groupCss($class = null){
		if(is_null($class)){
			if(sizeof($this->groupCss)){
				return implode(" ", $this->groupCss);
			}
		} else {
			if(is_null($this->groupCss)){
				$this->groupCss = array();
			}
			if(!in_array($class,$this->groupCss)){
				$this->groupCss[] = $class;
			}
			return $this;
		}
	}
	
	/**
	 * Définit le modèle pour l'affichage du champ concerné
	 * @param unknown $template
	 */
	abstract protected function template($template);
}