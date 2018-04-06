<?php
/**
* @name CoordsProcess.class.php Services de calculs divers à partir de coordonnées GPS
* @author web-Projet.com (contact@web-projet.com) - Mai 2017
* @package wp\Spatial
* @version 1.0
**/
namespace wp\Spatial;

class CoordsProcess {
	/**
	 * Rayon terrestre exprimé en unité métrique
	 * @const int
	 */
	const _METRIC_RADIUS = 6371;
	
	/**
	 * Rayon terrestre exprimé en miles
	 * @const integer
	 */
	const _MILES_RADIUS = 3959;
	
	/**
	 * Nombre de degrés par radian
	 * @var unknown
	 */
	const _DEG_PER_RAD = 57.223366;
	
	/**
	 * Latitude courante (point de référence)
	 * @var double
	 */
	private $currentLatitude;
	
	/**
	 * Longitude courante (point de référence)
	 * @var double
	 */
	private $currentLongitude;
	
	/**
	 * Rayon pour les calculs d'appartenance
	 * @var int
	 */
	private $radius;
	
	/**
	 * Constructeur du service de calculs à partir de coordonnées géographiques
	 */
	public function __construct(){}
	
	/**
	 * Définit la latitude courante
	 * @param double $latitude
	 * @return \wp\Spatial\CoordsProcess
	 */
	public function setCurrentLatitude($latitude = null){
		$this->currentLatitude = $latitude;
		return $this;
	}
	
	/**
	 * Définit la longitude courante
	 * @param double $longitude
	 * @return \wp\Spatial\CoordsProcess
	 */
	public function setCurrentLongitude($longitude = null){
		$this->currentLongitude = $longitude;
		return $this;
	}
	
	/**
	 * Définit le rayon pour le calcul d'appartenance
	 * @param int $radius
	 * @return \wp\Spatial\CoordsProcess
	 */
	public function setRadius(int $radius){
		$this->radius = $radius;
		return $this;
	}
	
	/**
	 * Détermine si une coordonnée et dans un rayon de n km autour du point de référence
	 * @param double $lat
	 * @param double $long
	 * @return boolean
	 */
	public function inRadius($lat, $long){
		if(!is_null($this->currentLatitude) && !is_null($this->currentLongitude)){
			$latDist = deg2rad($lat - $this->currentLatitude);
			$longDist = deg2rad($long - $this->currentLongitude);
			
			$a = sin($latDist/2) * sin($latDist/2) + cos(deg2rad($this->currentLatitude)) * cos(deg2rad($lat)) * sin($longDist/2) * sin($longDist/2);
			$c = 2 * asin(sqrt($a));
			$distance = CoordsProcess::_METRIC_RADIUS * $c;
			
			#begin_debug
			#echo "Traite la distance entre : " . $this->currentLatitude . "/" . $this->currentLongitude . " et " . $lat . "/" . $long . " : " . $distance . "<br />\n";
			#end_debug
			
			return ($distance <= $this->radius);
		}
		
		return true;
	}
	
}