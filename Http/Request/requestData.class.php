<?php
/**
 * @name requestData.class.php Service de gestion des données de requête
 * @author web-Projet.com (jean-luc.aubert@web-projet.com)
 * @package wp\Http\Request
 * @version 1.0
**/
namespace wp\Http\Request;

class requestData implements \Iterator {
	
	/**
	 * Stockage des données de requête HTTP
	 * @var array
	 */
	protected $datas;
	
	/**
	 * Nombre de données dans la requête HTTP
	 * @var int
	 */
	private $count;
	
	/**
	 * Nombre de données postées
	 * @var int
	 */
	private $postCount;
	
	/**
	 * Nombre de données en GET
	 * @var int
	 */
	private $getCount;
	
	/**
	 * Indice de l'itérateur sur le tableau $datas
	 * @var int
	 */
	private $iteratorIndice;
	
	/**
	 * Constructeur de l'objet de données de requête HTTP
	 */
	public function __construct(){
		$this->datas = array();
		
		$this->count = 0;
		$this->postCount = 0;
		$this->getCount = 0;
		
		
		/**
		echo "Constructeur de requestData<br />\n";
		var_dump($_GET);
		echo "<br />\n";
		**/
		
		$this->iteratorIndice = 0;
		
		$this->hydrate();
	}
	
	/**
	 * Alimente les données de requête HTTP
	 * @param object $data
	 */
	protected function addData($data){
		if(!$this->exists($data)){
			$this->datas[$data->name()] = $data;
		}
		return $this;
	}
	
	/**
	 * Alimente la liste des données de requête HTTP
	 */
	protected function hydrate(){
		foreach ($_GET as $name => $data){
			$object = new \wp\Http\Request\httpGet();
			$object->name($name)
				->value($data);
			$this->addData($object);
		}
		$this->getCount = sizeof($_GET);
		
		foreach ($_POST as $name => $data){
			if($data != ""){
				$object = new \wp\Http\Request\httpPost();
				$object->name($name)
					->value($data);
				$this->addData($object);
				$this->postCount++;
			}
		}
		
		// Traite les données directement récupérées à partir d'un appel Client type AngularJS
		$postedData = file_get_contents("php://input");
		if($postedData){
			$datas = json_decode($postedData);
			if(is_array($datas)){
				
				foreach($datas as $name => $data){
					if($data != ""){
						$object = new \wp\Http\Request\httpPost();
						$object->name($name)
						->value($data);
						$this->addData($object);
						$this->postCount++;
					}
				}
			} else {
				if (is_object($datas)) {
					foreach($datas as $name => $data){
						if($data != ""){
							$object = new \wp\Http\Request\httpPost();
							$object->name($name)
							->value($data);
							$this->addData($object);
							$this->postCount++;
						}
					}
				} else {
					// Parser manuellement l'objet et l'ajouter... en POST
					// @todo Récursivité pour alimenter correctement les structures JSON
					$postedVars = [];
					parse_str($postedData, $postedVars);
					
					foreach($postedVars as $data => $value){
						if($data === "undefined"){
							continue; // Passe au suivant
						}
						
						if(!is_array($value)){
							$object = new \wp\Http\Request\httpPost();
							$object->name($data)
							->value($value);
							$this->addData($object);
							$this->postCount++;
						} else {
							// La donnée sera convertie en chaîne JSON
							$cleanDatas = [];
							foreach ($value as $key => $value){
								if ($key === "undefined"){
									continue;
								}
								$cleanDatas[$key] = $value;
							}
							$object = new \wp\Http\Request\httpPost();
							$object->name($data)
							->value(json_encode($cleanDatas));
							$this->addData($object);
							$this->postCount++;
						}
					}
				}
			}
		}
		//$this->postCount = sizeof($_POST);
		
		$this->count = $this->getCount + $this->postCount;
	}
	
	/**
	 * Retourne l'objet de données de requête HTTP
	 * @param string $name Clé de l'objet à retourner
	 * @param string $objectType Type de l'objet à retourner si non trouvé
	 * @return object
	**/
	public function get($name,$objectType){
		if(array_key_exists($name,$this->datas))
			return $this->datas[$name];
		
		if(strtolower($objectType) == "get"){
			$data = new \wp\Http\Request\httpGet();
		} else {
			$data = new \wp\Http\Request\httpPost();
		}
		$data->name = $name;
		$this->addData($data);
		
		return $data;
	}
	
	
	public function dataExists($name){
		$exists			= true;
		
		if(!is_array($name)){
			return array_key_exists($name, $this->datas);
		}
		foreach($name as $key){
			if(!array_key_exists($key, $this->datas)){
				$exists = $exists && false;
			}
		}
		return $exists;
	}
	
	/**
	 * Détermine les données de requête HTTP à passer à la vue
	 * @return array
	 */
	public function toArray(){
		$viewDatas					= array();
		
		if(!is_null($this->datas) && sizeof($this->datas)){
			foreach ($this->datas as $data){
				$viewDatas[$data->name()] = $data->value();
			}
		}
		
		return $viewDatas;
		
	}
	
	/**
	 * Retourne la valeur d'une donnée de requête HTTP
	 * @param string $attributeName
	 * @return string|null
	 */
	public function __get($attributeName){
		if(array_key_exists($attributeName,$this->datas)){
			return $this->datas[$attributeName]->value();
		}
		return null;
	}
	
	public function count($typeOf=null){
		if(is_null($typeOf)){
			return array(
				"get" => $this->getCount,
				"post" => $this->postCount,
				"total" => $this->count
			);
		}
		
		switch (strtolower($typeOf)){
			case "get":
				return $this->getCount;
			break;
			
			case "post":
				return $this->postCount;
			break;
			
			case "total":
			case "count":
				return $this->count;
			break;
			
			default:
				return $this->count;
			break;
		}
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::current()
	 */
	public function current(){
		return current($this->datas);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::key()
	 */
	public function key(){
		return key($this->datas);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::next()
	 */
	public function next(){
		next($this->datas);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::rewind()
	 */
	public function rewind(){
		reset($this->datas);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Iterator::valid()
	 */
	public function valid(){
		return key($this->datas) !== null;
	}
	/**
	 * Détermine si la données de requête HTTP existe dans la collection
	 * @param object $data
	 */
	private function exists($data){
		if(array_key_exists($data->name(),$this->datas)){
			return true;
		}
		return false;
	}
}