<?php
/**
* @name ActiveRecord.class.php Définition d'une ligne active d'une entité
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
* @todo Modifier méthode __get pour traiter les entités manytomany
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\Entity as Entity;
use \wp\Database\Entities\Columns\Columns as Columns;
use \wp\Database\Entities\Columns\Column as Column;
use \wp\Database\SQL\CRUD as CRUD;
use \wp\Database\Interfaces\IActiveRecord;
use \wp\Database\Query\DoInsert as Insert;

abstract class ActiveRecord implements CRUD, \wp\Database\Interfaces\IActiveRecord {
	
	/**
	 * Définit l'entité de référence
	 * @var \Entity
	 */
	protected $entity;
	
	/**
	 * Chaîne de requête SQL
	 * @var string
	 */
	protected $query;
	
	/**
	 * Requête préparée
	 * \PDOStatement
	 */
	protected $statement;
	
	/**
	 * Définit le schéma de la table
	 * @param \Columns $scheme
	 * @return \wp\Database\Entities\ActiveRecord
	 */
	protected function setScheme($scheme){
		$this->scheme = $scheme;
		
		foreach($this->scheme as $column) {
			$column->value(null);
			$this->scheme->hydrate($column);
		}
		
		return $this;
	}
	
	/**
	 * Insertion des données dans la base
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\CRUD::insert()
	 */
	public function insert() {
		$dataMapper = [];
		
		$this->query = "INSERT INTO " . $this->entity->getName() . " (";
		
		// Boucle sur le schéma à l'exception de la clé primaire
		foreach($this->entity->getScheme() as $column => $definition){
			if(!($definition->primary() && $definition->auto())) {
				$this->query .= $definition->name() . ",";
				// Alimente le mapper de données
				$dataMapper[":" . $definition->name()] = $this->{$definition->name()};
			}
		}
		// Supprime la dernière virgule inutile
		$this->query = substr($this->query, 0, strlen($this->query) - 1);
		$this->query .= ") VALUES (";
		
		// Boucle sur le schéma à l'exception de la clé primaire
		foreach($this->entity->getScheme() as $column => $definition){
			if(!($definition->primary() && $definition->auto())) {
				$this->query .= ":" . $definition->name() . ",";
			}
		}
		// Supprime la dernière virgule inutile
		$this->query = substr($this->query, 0, strlen($this->query) - 1);
		$this->query .= ");";
		
		$query = \wp\Database\Query\DoInsert::get();
		
		$query->SQL($this->query);
		
		$query->queryParams($dataMapper);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}

	/**
	 * Crée et exécute une requête UPDATE pour l'enregistrement actif courant
	 * @param string $primaryCol Nom de la clé primaire de la table
	 * @return boolean
	 */
	public function update(string $primaryCol){
		$dataMapper = [];
		
		$this->query = "UPDATE " . $this->entity->getName() . " SET ";
		
		// Boucle sur le schéma à l'exception de la clé primaire
		foreach($this->entity->getScheme() as $column => $definition){
			if($definition->primary()){
				continue;
			}
			$this->query .= $definition->name() . " = :" . $definition->name() . ",";
			// Alimente le mapper de données
			$dataMapper[":" . $definition->name()] = $this->{$definition->name()};
		}
		// Supprime la dernière virgule inutile
		$this->query = substr($this->query, 0, strlen($this->query) - 1);
		
		// Ajoute la contrainte
		$this->query .= " WHERE " . $primaryCol . " = :" . $primaryCol . ";";
		$dataMapper[":" . $primaryCol] = $this->{$primaryCol};
		
		$query = \wp\Database\Query\DoUpdate::get();
		
		$query->SQL($this->query);
		
		$query->queryParams($dataMapper);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
	
	/**
	 * Exécute une requête de suppression dans la base de données
	 * {@inheritDoc}
	 * @see \wp\Database\SQL\CRUD::delete()
	 */
	public function delete(string $primaryCol) {
		$dataMapper = [];
		
		$this->query = "DELETE FROM " . $this->entity->getName();
		
		// Ajoute la contrainte
		$this->query .= " WHERE " . $primaryCol . " = :" . $primaryCol . ";";
		$dataMapper[":" . $primaryCol] = $this->{$primaryCol};
		
		$query = \wp\Database\Query\DoDelete::get();
		
		$query->SQL($this->query);
		
		$query->queryParams($dataMapper);
		
		$this->statement = $query->process();
		
		return $this->statement;
	}
	
	/**
	 * Alimente les attributs de l'objet ActiveRecord courant
	 * @param row $data Ligne de données
	 * @todo Mapper les éventuels objet JSON transmis
	 */
	public function hydrate($data){
		foreach ($data as $column => $value) {
			// Quoi qu'il arrive, on stocke la donnée dans l'activeRecord
			$this->{$column} = $value;
			
			if(!property_exists($this->entity, $column)) {
				// Cherche la colonne dans le schéma courant
				if (($sqlColumn = $this->entity->getScheme()->findBy($column)) !== false) {
					$this->{$sqlColumn->name()} = $value;
				} else {
					// On regarde s'il s'agit d'une colonne de l'entité parente
					if (property_exists($this->entity, "parentEntity")) {
						$entity = $this->entity->getParentEntity();
						if (($sqlColumn = $entity->getScheme()->findBy($column)) !== false) {
							$this->{$sqlColumn->name()} = $value;
						}
					}
				}
			}
		}
	}
	
	/**
	 * Définit la valeur d'une colonne de la ligne courante d'une entité
	 * @param string $attributeName
	 * @param mixed $value
	 * @return \wp\Database\Entities\ActiveRecord
	 */
	public function __set(string $attributeName, $value){
		$this->{$attributeName} = $value;
		
		/**
		if(!property_exists($this, $attributeName)){
			if(($column = $this->scheme->find($attributeName)) !== false){
				$column->value($value);
			}
		}
		**/
		
		return $this;
	}
	
	/**
	 * Retourne la valeur de la colonne de la ligne courante
	 * @param string $attributeName
	 * @return mixed|\wp\Database\Entities\ActiveRecord
	 */
	public function __get(string $attributeName){
		
		if(!property_exists($this, $attributeName)){
			
			if(($column = $this->entity->getScheme()->find($attributeName)) !== false){
				if (isset($this->{$column->name()})) {
					return $this->{$column->name()};
				} else {
					return $this->{$column->alias()};
				}
				//return $column->value();
			
			} else {
				// Il peut s'agir d'un élément de contenu de type JSON
				$JSONObject = $this->getJSONObject();
				
				if($JSONObject !== false){
					return $JSONObject->{$attributeName};
				}
				
				// Il peut aussi s'agir d'une colonne de l'entité parente
				if (property_exists($this->entity, "parentEntity")) {
					$entity = $this->entity->getParentEntity();
					if(($column = $entity->getScheme()->findBy($attributeName)) !== false){
						if (isset($this->{$column->name()})) {
							return $this->{$column->name()};
						} else {
							return $this->{$column->alias()};
						}
						//return $column->value();
					}
				}
			}
		}
		
		//return $this;
		return null;
	}
	
	/**
	 * Surcharge de la méthode isset pour récupérer l'état d'existence d'un attribut
	 * @param string $attribute
	 * @return bool
	 */
	public function __isset(string $attribute): bool {
		return isset($this->{$attribute});
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