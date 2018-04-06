<?php
/**
* @name MongoDate.class.php : Services de conversion de dates MongoDB
* @author web-Projet.com (contact@web-projet.com)
* @package \Database\MongoDB\Utilities
* @version 1.0
**/
namespace wp\Database\MongoDB\Utilities;

use \MongoDB\BSON\UTCDateTime as MongoUTCDate;

class MongoDate {
	
	public static function toISODate($date){
		if(is_object($date)){
			$dateToString = $date->year . "-" . $date->month . "-" . $date->day;
		} else {
			$dateToString = substr($date,0,10);
		}
		$initDate = new \DateTime($dateToString);
		
		#begin_debug
		#echo "Date récupérée à partir de " . $dateToString . " : " . $initDate->format("d-m-Y") . " (timestamp) " . $initDate->getTimestamp() . "<br />\n";
		#end_debug
		$stampedDate = $initDate->getTimestamp() * 1000;
		
		$mongoUTCDate = new MongoUTCDate($stampedDate);
		
		return $mongoUTCDate;
	}
}