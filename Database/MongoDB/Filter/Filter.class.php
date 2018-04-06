<?php
/**
* @name Filter.class.php : Service de filtres pour les requêtes MongoDB
* @author web-Projet.com (contact@web-projet.com) Juil. 2017
* @package wp\Database\MongoDB\Filter
* @version 1.0
**/
namespace wp\Database\MongoDB\Filter;

class Filter {
	
	/**
	 * Stocke les filtres définis
	 * @var array
	 */
	private $filters = [];
	
	/**
	 * Ajoute un filtre combiné à un OR
	 * @param string $property : Nom de la propriété
	 * @param mixed $value : Valeur à du filtre
	 * @param string $type : Type de valeur (absolue, regex, etc...)
	 * @return \wp\Database\MongoDB\Filter\Filter
	 */
	public function addOrFilter($property, $value, $type=null){
		if(array_key_exists('$or', $this->filters)){
			$this->filters['$or'][] = array(
				$property => $this->toExpression($value, $type)
			);
		} else {
			$this->filters['$or']= array(
				array(
					$property => $this->toExpression($value, $type)
				)
			);
		}
		return $this;
	}
	
	
	public function addSingleFilter($property, $value, $type=null){
		$this->filters = array($property => $this->toExpression($value, $type));	
	}
	
	public function getFilters(){
		return $this->filters;
	}
	
	private function toExpression($value, $type){
		if(is_null($type)){
			return $value;
		} else {
			// En fonction du type défini, on retourne une expression régulière
			switch($type){
				case "begin":
				case "commence":
					return \wp\Database\MongoDB\Utilities\MongoRegExp::beginWith($value);
				break;
				
				
			}
		}
	}
}