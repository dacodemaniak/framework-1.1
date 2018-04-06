<?php
/**
 * @name mime.class.php Définit un type MIME
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\Files\Mimes
 * @version 1.0
**/
namespace wp\Files\Mimes;

use wp\Collections\collection as Collection;
use wp\Collections\item;

class mime extends \wp\Collections\item{
	
	/**
	 * Instancie une nouvelle collection des types MIME
	 * @param Collection $mimes Collection des types MIME
	 */
	public function __construct(Collection $mimes){
		parent::__construct($mimes);
	}
}