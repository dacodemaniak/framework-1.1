<?php
/**
* @name Entity.class.php Abstraction de classe de définition d'une table de SGBDR
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities
* @version 0.1.0
**/

namespace wp\Database\Entities;

use \wp\Database\Entities\Columns\Column;
use \wp\Database\Entities\Columns\Columns as Columns;
use wp\Database\Interfaces\IEntity;

abstract class Entity implements IEntity {
	
	/**
	 * Nom de la table
	 * @var string
	 */
	protected $name;
	
	/**
	 * Alias de la table
	 * @var string
	 */
	protected $alias;
	
	/**
	 * Collection des colonnes de la table
	 * @var \Collection
	 */
	protected $columns;
	

	
	/**
	 * Définit ou retourne le nom de l'entité
	 * @param string $name
	 * @return string | \Entity
	 */
	protected function name(string $name = null){
		if(is_null($name)){
			return $this->name;
		}
		
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Retourne le nom de l'entité (publique)
	 * @return string
	 */
	public function getName(){
		return $this->name();
	}
	
	/**
	 * Retourne l'espace de nom de l'entité courante
	 * @return string
	 */
	protected function getNameSpace(){
		$reflector = new \ReflectionClass($this);
		return $reflector->getNamespaceName();
	}
	
	
	/**
	 * Définit la valeur pour une colonne du schéma courant
	 * @param string $attributeName Nom de la colonne ou alias
	 * @param mixed $value
	 */
	public function __set(string $attributeName, $value){
		if(($column = $this->columns->findBy($attributeName)) !== false){
			$column->value($value);
			return true;
		}
		
		$logger = new \wp\Utilities\Logger\Logger("entity");
		$logger->add("La colonne ou l'alias " . $attributeName . " n'existe pas dans l'entité " . $this->name, __FILE__, __LINE__);
		
		return false;
	}
	
	/**
	 * Définit ou retourne l'alias de l'entité courante
	 * @param string $alias
	 * @return string|\wp\Database\Entities\Entity
	 */
	public function alias(string $alias=null){
		if(is_null($alias)){
			return $this->alias;
		}
		
		$this->alias = $alias;
		
		return $this;
	}
	
	/**
	 * Ajoute une colonne à la collection des colonnes de l'entité
	 * @param \Column $column
	 * @return \wp\Database\Entities\Entity
	 */
	public function hydrate(\wp\Database\Entities\Columns\Column $column){
		$this->columns->hydrate($column);
		
		return $this;
	}
	
	/**
	 * Retourne le nom de la table avec son alias
	 * @return string
	 */
	public function getAliasedName(){
		return $this->name . " AS " . $this->alias;	
	}
	
	/**
	 * Retourne les noms des colonnes qualifiés par le nom de la table
	 * @return string
	 */
	public function getQualifiedColumns(){
		$columns = $this->columns->names();
		
		$qualifiedColumns = array();
		
		for($index = 0; $index < count($columns); $index++){
			$qualifiedColumns[] = $this->alias . "." . $columns[$index];
		}
		
		return join(",", $qualifiedColumns);
	}
	
	/**
	 * Retourne les noms des colonnes qualifiées par le nom de la table et un alias
	 * @return string
	 */
	public function getFullQualifiedColumns(){
		$columns = $this->columns->aliasedNames();
		
		$qualifiedColumns = array();
		
		for($index = 0; $index < count($columns); $index++){
			$qualifiedColumns[] = $this->alias . "." . $columns[$index];
		}
		
		return join(",", $qualifiedColumns);
	}

	public function getPrimaryCol(){
		$column = null;
		
		foreach($this->columns as $column => $definition){
			if(!$definition->primary()){
				continue;
			}
			$column = $definition;
			break;
		}
		
		if(!is_null($definition)){
			return $definition->name();
		}
	}
	
	/**
	 * Retourne une instance d'objet ActiveRecord à partir de l'entité courante
	 * @return ActiveRecord
	 */
	abstract public function getActiveRecordInstance();
	
	/**
	 * Humanise l'affichage de l'objet courant
	 * @return string
	 */
	public function __toString(){
		$out = "<ul>\n";
		$out .= "<li>" . $this->name . "</li>\n";
		$out .= "<li>" . $this->alias . "</li>\n";
		
		foreach ($this->columns as $name => $detail){
			$out .= "\t<li>" . $name . " [" . $detail->type() . "]</li>\n";
		}
		
		$out .= "</ul>\n";
		
		return $out;
	}
	
	/**
	 * Définit la méthode de définition du schéma de l'entité
	 */
	abstract protected function setScheme();
	
	/**
	 * Définit la méthode de récupération du schéma de l'entité
	 * @return \wp\Database\Entities\Columns\
	 */
	abstract public function getScheme();
}