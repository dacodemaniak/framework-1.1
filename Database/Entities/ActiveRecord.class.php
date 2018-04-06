<?php
/**
* @name ActiveRecord.class.php Définition d'une ligne active d'une entité
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\Entity as Entity;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\Entities\Columns\Column as Column;
use \wp\Database\SQL\CRUD as CRUD;

abstract class ActiveRecord implements CRUD {
	
	/**
	 * Définit l'entité de référence
	 * @var \Entity
	 */
	protected $scheme;
	
	/**
	 * Chaîne de requête SQL
	 * @var string
	 */
	protected $query;
	
	/**
	 * Définit le schéma de la table
	 * @param \Columns $scheme
	 * @return \wp\Database\Entities\ActiveRecord
	 */
	protected function setScheme($scheme){
		$this->scheme = $scheme;
		
		return $this;
	}
	
	/**
	 * Alimente les attributs de l'objet ActiveRecord courant
	 * @param row $data Ligne de données
	 * @todo Mapper les éventuels objet JSON transmis
	 */
	public function hydrate($data){
		foreach($this->scheme as $column => $object){
			$this->{$object->name()} = $data->{$object->alias()};
		}
	}
	
	/**
	 * Définit la valeur d'une colonne de la ligne courante d'une entité
	 * @param string $attributeName
	 * @param mixed $value
	 * @return \wp\Database\Entities\ActiveRecord
	 */
	public function __set(string $attributeName, $value){
		if(!property_exists($this, $attributeName)){
			if(($column = $this->scheme->find($attributeName)) !== false){
				$column->value($value);
			}
		}
		return $this;
	}
	
	/**
	 * Retourne la valeur de la colonne de la ligne courante
	 * @param string $attributeName
	 * @return mixed|\wp\Database\Entities\ActiveRecord
	 */
	public function __get(string $attributeName){
		if(!property_exists($this, $attributeName)){
			if(($column = $this->scheme->find($attributeName)) !== false){
				return $column->value();
			} else {
				// Il peut s'agir d'un élément de contenu de type JSON
				$JSONObject = $this->getJSONObject();
				
				if($JSONObject !== false){
					return $JSONObject->{$attributeName};
				}
			}
		}
		return $this;
	}
	
	/**
	 * Appelle une méthode de l'objet courant ou de l'entité JSON correspondante
	 * @param string $method
	 * @param array $args
	 * @return mixed|string
	 */
	public function __call($method, $args){
		if(method_exists($this, $method)){
			return $this->$method();
		}

		// Il peut s'agir d'un élément de contenu de type JSON
		$JSONObject = $this->getJSONObject();
		
		if($JSONObject !== false){
			return $JSONObject->$method();
		}
		
		return "//!\\Méthode " . $method . " non trouvée //!\\";
		
	}
	
	/**
	 * Retourne une instance de contenu de colonne de type JSON
	 */
	abstract protected function getJSONObject();
}