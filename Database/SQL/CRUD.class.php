<?php
/**
* @name CRUD Interface pour la gestion des opérations CRUD sur les entités
* @author IDea Factory (dev-team@ideafactory.fr) - Mars 2018
* @package wp\Database\SQL
* @version 0.1.0
*/

namespace wp\Database\SQL;

interface CRUD {
	/**
	 * Méthode de suppression d'une ligne de table
	 * @param void
	 * @return boolean
	 */
	public function delete(string $primaryCol);
	
	/**
	 * Dispatche vers les méthodes insert() ou update()
	 * @param void
	 * @return boolean
	 */
	public function save();
	
	/**
	 * Crée et exécute la requête d'insertion
	 * @param void
	 * @return boolean
	 */
	public function insert();
	
	/**
	 * Crée et exécute la requête de mise à jour
	 * @param string $primaryCol Nom de la clé primaire de la table
	 * @return boolean
	 */
	public function update(string $primaryCol);
	
}