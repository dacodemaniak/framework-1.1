<?php
/**
* @name ManyToOne.class.php Service de gestion de relations entre tables de type Many To One
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/
namespace wp\Database\Entities;

use \wp\Database\Entities\Columns\Column;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\SQL\Select as Select;
use \wp\Database\Query\Get as Get;

abstract class ManyToOne extends Entity implements Select {
	
	/**
	 * Définit une requête SELECT sur l'ensemble des colonnes de la table
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\Select::selectAll()
	 * @return \PDOStatement | false
	 * @todo Ajouter un éventuel ORDER BY, GROUP BY
	 */
	public function selectAll(){
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
	 * Retourne l'instance de l'entité parente
	 */
	private function getParentEntity(){
		
	}
}