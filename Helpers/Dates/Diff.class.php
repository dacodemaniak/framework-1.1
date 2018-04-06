<?php
/**
* @name Diff Helpers pour la gestion des différences entre les dates
* @author IDea Factory (dev-team@ideafactory.fr) - Mars 2018
* @package wp\Helpers\Dates
* @version 0.1.0
*/
namespace wp\Helpers\Dates;

abstract class Diff {
	
	/**
	 * Détermine si l'intervalle correspond au même jour complet
	 * @param \DateInterval $diff
	 * @return boolean
	 */
	public static function sameFullDay(\DateInterval $diff): bool {
		return ($diff->y + $diff->m + $diff->d) === 0 ? true : false;
	}
	
	/**
	 * Détermine si l'intervalle est hors des limites de temps imposées
	 * @param \DateTime $initialDate Date d'origine
	 * @param \DateTime $targetDate Date de référence
	 * @param int $offset
	 * @return boolean Vrai si la différence est positive Date > Date de référence
	 */
	public static function outOfTime(\DateTime $initialDate, \DateTime $targetDate, int $offset=null): bool {
		if(!is_null($offset)){
			// Retranche $offset minutes à la date de référence
			$interval = new \DateInterval("PT" . $offset . "M");
			$targetDate->sub($interval);
		}
		
		#echo $initialDate->format("H:i:s") . " > " . $targetDate->format("H:i:s");
		
		$diff = $initialDate->diff($targetDate);
		
		if (($diff->y + $diff->m + $diff->d) === 0)
			return $initialDate > $targetDate;
		
		return false;
	}
}