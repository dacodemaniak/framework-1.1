<?php
/**
 * @name modifier.class.php Services de modificateurs utilisés dans les modèles
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Vendor\TemplateEngine
 * @version 1.0
**/
namespace wp\Vendor\TemplateEngine;

class modifier {
	public function stripTags($string){
		return preg_replace("/<[^>]*>/", "", $string);
	}
}