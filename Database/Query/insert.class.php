<?php
/**
 * @name insert.class.php Service d'instanciation d'une requête de type INSERT en fonction du fournisseur
 * @author web-Projet.com (contact@web-projet.com) - Juin 2017
 * @package wp\Database\Query
 * @version 1.0
 */
namespace wp\Database\Query;

use \wp\Database\Collection\activeRecord as ActiveRecord;

class insert extends \wp\Database\Query\lmd{
	
	/**
	 * Instance d'un enregistrement actif
	 * @var \wp\Database\Collection\activeRecord
	 */
	private $activeRecord;
	
	/**
	 * Instance d'un objet INSERT du fournisseur concerné
	 * @var Object
	 */
	private $insert;
	
	/**
	 * Instancie un nouvel objet de requête SELECT
	 * @param Store $store
	 */
	public function __construct(ActiveRecord $activeRecord){
		$this->activeRecord = $activeRecord;
		
		$insert = new \wp\Patterns\ClassFactory\insert($activeRecord);
		$insert->addInstance();
		$this->insert = $insert->instance();
	}
	
	/**
	 * Retourne l'objet contenant les données
	 */
	public function insert(){
		return $this->insert->insert();
	}
	
	
	public function __toString(){
		return $this->insert->__toString();
	}
}