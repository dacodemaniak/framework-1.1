<?php
/**
* @name Helper.class.php : Méthodes diverses d'aide à la gestion des dates
* @author web-Projet.com (contact@web-projet.com)
* @package wp\Helpers\Dates
* @version 1.0
**/
namespace wp\Helpers\Dates;

class Helper {
	/**
	 * Date de référence
	 * @var \DateTime
	 */
	private $date;
	
	public function __construct(){
		$this->date = new \DateTime();
	}
	
	/**
	 * Retourne la date définie
	 * @return \DateTime
	 */
	public function get(): \DateTime {
		return $this->date;
	}
	
	/**
	 * Définit un objet de type DateTime à partir d'un format
	 * @param string $date
	 * @param string $format
	 * @return Helper
	 */
	public function fromFormat(string $date, string $format){
		$this->date = \DateTime::createFromFormat($format, $date);
		return $this;
	}
	
	/**
	 * Définit une date à partir d'un objet de type \DateTime
	 * @param \DateTime $date
	 * @return \wp\Helpers\Dates\Helper
	 */
	public function fromDateTime($date){
		if($date instanceof \DateTime){
			$this->date = $date;
		}
		return $this;
	}
	
	/**
	 * Détermine si la date de l'objet courant est après la date passée en paramètre
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function isAfter(\DateTime $date){
		return $this->date > $date;
	}
	
	/**
	 * Détermine si la date de l'objet courant est avant la date passée en paramètre
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function isBefore(\DateTime $date){
		return $this->date < $date;
	}
	
	/**
	 * Détermine si la date de l'objet courant est la même que la date passée en paramètre
	 * @param \DateTime $date
	 * @return boolean
	 */
	public function isSame(\DateTime $date){
		return $this->date == $date;
	}
	
}