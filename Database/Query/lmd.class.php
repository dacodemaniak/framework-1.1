<?php
/**
 * @name lmd.class.php Abstraction de langage de manipulation de données
 * @author web-Projet.com (contact@web-projet.com) - Juil 2016
 * @package wp\Database\Query
 * @version 1.0
 */
namespace wp\Database\Query;

abstract class lmd {
	protected $useRelations = true;
	
	public function useRelations($useRelations=null){
		if(is_null($useRelations)){
			return $this->useRelations;
		}
		
		if(is_bool($useRelations)){
			$this->useRelations = $useRelations;
		}
		
		return $this;
	}
}