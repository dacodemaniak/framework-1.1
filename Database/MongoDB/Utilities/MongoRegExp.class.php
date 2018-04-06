<?php
/**
* @name MongoRegExp.class.php : Services de création d'une expression régulière MongoDB
* @author web-Projet.com (contact@web-projet.com)
* @package \Database\MongoDB\Utilities
* @version 1.0
**/
namespace wp\Database\MongoDB\Utilities;

use \MongoDB\BSON\RegEx as RegEx;

class MongoRegExp {
	
	public static function beginWith($value){
		return new RegEx( "^" . $value , "i");
	}
}