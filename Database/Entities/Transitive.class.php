<?php
/**
* @name Transitive.class.php Service de gestion de relations ManyToOne sur une seule table
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/
namespace wp\Database\Entities;

use \wp\Database\Entities\Columns\Column;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\SQL\Select as Select;
use \wp\Database\Query\Get as Get;

abstract class Transitive extends Entity implements Select {
	
	/**
	 * Instance de l'entité parente de la transitivité
	 * @var \Entity
	 */
	protected $parentEntity;
	
	/**
	 * Vrai pour un SELECT simple sur l'entité courante (sans relation)
	 * @var boolean
	 */
	protected $entityOnly = true;
	
	/**
	 * Vrai pour un SELECT remontant uniquement les parents de la transitivité
	 * @var boolean
	 */
	protected $parentOnly = false;
	
	/**
	 * Vrai pour un SELECT remontant uniquement les enfants de la transitivité
	 * @var boolean
	 */
	protected $childrenOnly = false;
	
	/**
	 * Définit le mode du SELECT sur la table comme étant un SELECT simple sur l'entité
	 * @param boolean $status
	 * @return boolean|\wp\Database\Entities\Transitive
	 */
	public function entityOnly(boolean $status = null){
		if(is_null($status)){
			return $this->entityOnly;
		}
		
		$this->entityOnly = $status;
		
		return $this;
	}
	
	/**
	 * Définit le mode du SELECT sur la table comme étant un SELECT sur les parents uniquement
	 * @param boolean $status
	 * @return boolean|\wp\Database\Entities\Transitive
	 */
	public function parentOnly(boolean $status = null){
		if(is_null($status)){
			return $this->parentOnly;
		}
		
		$this->parentOnly = $status;
		
		return $this;
	}
	
	/**
	 * Définit le mode du SELECT sur la table comme étant un SELECT sur les enfants uniquement
	 * @param boolean $status
	 * @return boolean|\wp\Database\Entities\Transitive
	 */
	public function childrenOnly(boolean $status = null){
		if(is_null($status)){
			return $this->childrenOnly;
		}
		
		$this->childrenOnly = $status;
		
		return $this;
	}
	
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return \PDOStatement | false
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
		if($this->entityOnly){
			return parent::selectAll();
		}
		
		$this->query = "SELECT ";
		
		// Ajoute les colonnes de l'entité courante
		$this->query .= $this->getFullQualifiedColumns();
		
		// Ajoute les colonnes de l'entité parente
		$this->query .= "," . $this->getParentEntity()->getFullQualifiedColumns();
		
		// Définit l'origine de la requête
		$this->query .= " FROM " . $this->getAliasedName();
		
		$query = Get::get();
		
		$query->SQL($this->query);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \wp\Database\Entities\Entity::selectBy()
	 */
	public function selectBy(){
		if($this->entityOnly){
			return parent::selectBy();
		}
	}
	
	/**
	 * Retourne l'instance de l'entité parente
	 */
	private function getParentEntity(){
		if(is_null($this->parentEntity)){
			$this->parentEntity = clone($this);
		}
		
		return $this->parentEntity;
	}
	
	/**
	 * Modifie l'alias lors du clonage de l'entité
	 */
	private function __clone(){
		$this->alias = "anc";
	}
}