<?php
/**
* @name Select.class.php Service de création de requête de type SELECT
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\SQL
* @version 0.1.0
**/

namespace wp\Database\SQL;

use \wp\Database\Interfaces\IColumn;

interface Select {
	
	/**
	 * Définit la méthode pour la création d'une requête de type SELECT sans contrainte
	 * @return string
	 */
	public function selectAll();
	
	/**
	 * Définit la méthode pour la création d'une requête de type SELECT avec contrainte
	 */
	public function selectBy();
	
	/**
	 * Définit la méthode pour l'ajout d'une clause de tri
	 * @param string $column Colonne sur laquelle réaliser le tri
	 * @param string $direction Sens du tri ASC ou DESC
	 * @return void
	 */
	public function addOrderBy(string $column, string $direction = "ASC");
	
	/**
	 * Définit la méthode pour l'ajout d'une clause GROUP BY
	 * @param string $column Colonne à regrouper
	 */
	public function addGroupBy(string $column);
	
	/**
	 * Définit la méthode pour l'ajout d'une contrainte dans la clause WHERE
	 * @param string $column Nom de la colonne
	 * @param string $operator Opérateur (=, LIKE, <=, >=, <>, ...)
	 * @param string $value Valeur pour la comparaison
	 * @param string $logical Couplage logique (AND, OR, ...)
	 */
	public function addConstraint(\wp\Database\Interfaces\IColumn $column, string $operator, string $value, string $logical=null);
	
}