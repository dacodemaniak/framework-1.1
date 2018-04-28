<?php
/**
* @name Column.class.php Définition d'une colonne de table de base de données
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities\Columns
* @version 0.1.0
**/

namespace wp\Database\Entities\Columns;

class Column {
	/**
	 * Nom de la colonne
	 * @var string
	 */
	private $name;
	
	/**
	 * Alias de la colonne
	 * @var string
	 */
	private $alias;
	
	/**
	 * Valeur définie pour la colonne courante
	 * @var mixed
	 */
	private $value;
	
	/**
	 * Vrai si la colonne est une clé primaire
	 * @var boolean
	 */
	private $isPrimary = false;
	
	/**
	 * Vrai si la colonne est une clé étrangère
	 * @var boolean
	 */
	private $isForeignKey = false;
	
	/**
	 * Nom de la classe parente associée à la clé étrangère
	 * @var string
	 */
	private $parentEntity;
	
	/**
	 * Espace de nom de l'entité parente
	 * @var string
	 */
	private $parentEntityNS;
	
	/**
	 * Vrai si la colonne est en auto incrément
	 * @var boolean
	 */
	private $isAuto = false;
	
	/**
	 * Vrai si la colonne autorise les valeurs nulles
	 * @var boolean
	 */
	private $nullAuto = false;
	
	/**
	 * Type de la colonne
	 * @var string
	 */
	private $type;
	
	/**
	 * Longueur maximum de données acceptées
	 * @var number
	 */
	private $length;
	
	/**
	 * Objet JSON associé à une colonne de type contenu
	 * @var string
	 */
	private $jsonObject;
	
	/**
	 * Définit ou retourne le nom de la colonne
	 * @param string $name
	 * @return string|\wp\Database\Entities\Columns\Column
	 */
	public function name(string $name = null){
		if(is_null($name)){
			return $this->name;
		}
		
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne l'alias de la colonne
	 * @param string $alias
	 * @return string|\wp\Database\Entities\Columns\Column
	 */
	public function alias(string $alias = null){
		if(is_null($alias)){
			return $this->alias;
		}
		
		$this->alias = $alias;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne la valeur associée à la colone
	 * @param mixed $value
	 * @return mixed|string|\wp\Database\Entities\Columns\Column
	 * @todo S'assurer que la valeur passée est de type acceptable par rapport à la colonne
	 */
	public function value($value = null){
		if(is_null($value)){
			return $this->value;
		}
		
		$this->value = $value;
		
		return $this;
	}
	/**
	 * Définit ou retourne le statut de clé primaire de la colonne
	 * @param bool $primary
	 * @return boolean|\wp\Database\Entities\Columns\Column
	 */
	public function primary(bool $primary = null){
		if(is_null($primary)){
			return $this->isPrimary;
		}
		
		$this->isPrimary = $primary;
		
		return $this;
	}

	/**
	 * Définit ou retourne le statut de clé étrangère de la colonne
	 * @param bool $isForeignKey
	 * @return boolean|\wp\Database\Entities\Columns\Column
	 */
	public function foreignKey(bool $isForeignKey = null){
		if(is_null($isForeignKey)){
			return $this->isForeignKey;
		}
		
		$this->isForeignKey = $isForeignKey;
		
		return $this;
	}
	
	
	
	/**
	 * Définit ou retourne le statut d'auto incrément de la colonne
	 * @param bool $isAuto
	 * @return boolean|\wp\Database\Entities\Columns\Column
	 */
	public function auto(bool $isAuto = null){
		if(is_null($isAuto)){
			return $this->isAuto;
		}
		
		$this->isAuto = $isAuto;
		return $this;
	}
	
	/**
	 * Définit ou retourne le statut des valeurs nulles autorisées pour la colonne
	 * @param bool $nullAuto
	 * @return boolean|\wp\Database\Entities\Columns\Column
	 */
	public function nullAuto(bool $nullAuto = null){
		if(is_null($nullAuto)){
			return $this->nullAuto;
		}
		
		$this->nullAuto = $nullAuto;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne le type de la colonne
	 * @param string $type
	 * @return string|\wp\Database\Entities\Columns\Column
	 */
	public function type(string $type = null){
		if(is_null($type)){
			return $this->type;
		}
		
		$this->type = $type;
		
		return $this;
	}
	
	/**
	 * Définit ou retourne la longueur maximum acceptée dans la colonne
	 * @param int $length
	 * @return number|\wp\Database\Entities\Columns\Column
	 */
	public function length(int $length = null){
		if(is_null($length)){
			return $this->length;
		}
		
		$this->length = $length;
		
		return $this;
	}
	
	/**
	 * Définit la classe à instancier pour une contenu JSON ou retourne l'instance de mapping JSON
	 * @param string $jsonObject
	 * @return \wp\Database\Entities\Columns\Column
	 */
	public function jsonObject(string $jsonObject = null){
		if(is_null($jsonObject)){
			// Retourne une instance de l'objet de mapping JSON
			return $this->jsonObject;
		}
		
		$this->jsonObject = $jsonObject;
		
		return $this;
	}
	
	/**
	 * Définit l'entité de référence pour la colonne concernée
	 * @param string $parentEntity
	 * @return \wp\Database\Entities\Columns\Column
	 */
	public function parentEntity(string $parentEntity = null){
		if(is_null($parentEntity)){
			return $this->parentEntity;
		}
		
		$this->parentEntity = $parentEntity;
		
		return $this;
	}
	
	/**
	 * Définit l'espace de nom de l'entité parente
	 * @param string $namespace
	 * @return string|\wp\Database\Entities\Columns\Column
	 */
	public function ns(string $namespace = null) {
		if(is_null($namespace)){
			return $this->parentEntityNS;
		}
		
		$this->parentEntityNS = $namespace;
		return $this;
	}
}