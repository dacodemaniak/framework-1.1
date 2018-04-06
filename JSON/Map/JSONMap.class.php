<?php
/**
 * @name JSONMap.class.php Collection des définitions de données d'un objet JSON
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\Database\Mapper
 * @version 1.0
**/
namespace wp\JSON\Map;

use \wp\Collections\collection as Collection;
use \wp\Collections\item as Item;

class JSONMap extends \wp\Collections\collection {
	
	/**
	 * Instancie une nouvelle collection JSON de définition de colonnes
	 */
	public function __construct(){
		parent::__construct();
	}
}