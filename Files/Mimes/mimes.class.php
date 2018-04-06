<?php
/**
 * @name mimes.class.php Collection des types MIME
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\Files\Mimes
 * @version 1.0
**/
namespace wp\Files\Mimes;

use wp\Collections\collection;

class mimes extends \wp\Collections\collection{
	
	/**
	 * Instancie une nouvelle collection des types MIME
	 */
	public function __construct(){
		parent::__construct();
	}
}