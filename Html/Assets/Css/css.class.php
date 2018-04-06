<?php
/**
 * @name css.class.php Service de gestion des ressources de type CSS
 * @author web-Projet.com (contact@web-projet.com) - Sept 2016
 * @package wp\Html\Assets\Javascript
 * @version 1.0
**/
namespace wp\Html\Assets\Css;

use \wp\Html\Assets\asset as Asset;

class css extends \wp\Html\Assets\asset {
	/**
	 * Structure de stockage des medias associés à la ressource CSS
	 * @var unknown
	 */
	private $medias;
	
	public function __construct(){
		$this->medias = array(
			"screen"
		);
	}
	
	/**
	 * Retourne le chemin complet vers la ressource
	 * @return string
	 */
	public function get(){
		return $this->path . $this->file;
	}
}