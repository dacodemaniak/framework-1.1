<?php
/**
 * @name clause.class.php Service de définition d'une clause simple WHERE
 * @author web-Projet.com (contact@web-projet.com) - Oct. 2016
 * @package wp\Database\Mapper
 * @version 1.0
 */
namespace wp\Database\Mapper;

class clause {
	/**
	 * Donnée sur laquelle faire porter la clause
	 * @var \wp\Database\Mapper\dataMapper
	 */
	private $data;
	
	/**
	 * Type de restriction
	 * @var string
	 */
	private $type;
	
	/**
	 * Jonction souhaitée pour des clauses multiples
	 * @var string
	 */
	private $queue;
	
	/**
	 * Instancie un nouvel objet de restriction de requête
	 * @param \wp\Database\Mapper\dataMapper\dataMapper $data
	 */
	public function __construct(\wp\Database\Mapper\dataMapper $data){
		$this->data = $data;
		$this->queue = " AND ";
	}
	
	/**
	 * Définit le type de la restriction : equal, different, begin, end, contains, beetween, in
	 * @param string $type
	 * @return \wp\Database\Mapper\clause
	 */
	public function type($type){
		$this->type = $type;
		return $this;
	}
	
	public function queue($queue=null){
		if(!is_null($queue)){
			$this->queue = $queue;
			return $this;
		}
		return $this->queue;
	}
	
	/**
	 * Définit et retourne la clause à ajouter à la requête
	 * @param multitype $value
	 */
	public function process(){
		switch($this->type){
			case "eq":
			case "equal":
				return $this->data->getQualifiedName() . "=" . $this->data->placeholder();
			break;
			
			case "different":
			case "diff":
				return $this->data->getQualifiedName() . "!=" . $this->data->placeholder();
			break;
			
			case "begin":
			case "commence":
				return $this->data->getQualifiedName() . " LIKE " . $this->data->placeholder();
			break;
			
			case "end":
			case "termine":
				return $this->data->getQualifiedName() . " LIKE " . $this->data->placeholder();
			break;
			
			case "contains":
			case "contient":
			case "contain":
				return $this->data->getQualifiedName() . " LIKE " . $this->data->placeholder();
			break;
			
			case "in":
			case "dans":
				if(!is_array($this->data->searchValue())){
					return $this->data->getQualifiedName() . " = " . $this->data->placeholder();
				} else {
					return $this->data->getQualifiedName() . " IN (" . implode(",",$this->data->searchValue()) . ")";
				}
			break;
			
			case "entre":
			case "beetween":
				if(!is_array($this->data->searchValue())){
					return $this->data->getQualifiedName() . " = " . $this->data->placeholder();
				} else {
					$members = $this->data->searchValue();
					return $this->data->getQualifiedName() . " BEETWEEN " . $members[0] . " AND " . $members[1];
				}				
			break;
		}
	}
}