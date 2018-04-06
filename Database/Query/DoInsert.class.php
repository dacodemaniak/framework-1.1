<?php
/**
* @name DoInsert ClassFactory pour la mise à jour de données à partir d'une Entité ou une Collection
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Query
* @version 0.1.0
**/
namespace wp\Database\Query;

abstract class DoInsert {
	
	/**
	 * Retourne une instance de récupération de données d'une base de données
	 * @return \wp\Patterns\ClassFactory\Object
	 */
	public static function get(){
		$query = new \wp\Patterns\ClassFactory\DataInsert();
		$query->getInstance();
		return $query->instance();
	}
}