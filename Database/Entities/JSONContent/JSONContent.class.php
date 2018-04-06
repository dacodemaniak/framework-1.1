<?php
/**
* @name JSONContent.class.php Abstraction de classe pour les données de type JSON
* @author IDea Factory (dev-team@ideafactory.fr) - Jan. 2018
* @package wp\Database\Entities\JSONContent
* @version 0.1.0
**/
namespace wp\Database\Entities\JSONContent;

use \wp\Exceptions\Errors\error as Error;
use \wp\Exceptions\JSON\JSONDecodeException as JSONDecodeException;

abstract class JSONContent{
	
	/**
	 * Contenu JSON à traiter
	 * @var string
	 */
	private $JSONContent;
	
	/**
	 * Classe standard contenant les attributs du document JSON décodé
	 * @var \stdClass
	 */
	private $JSONObject;
	
	public function __get(string $attributeName){
		if(!property_exists($this, $attributeName)){
			// Cherche dans l'objet JSON si l'attribut existe
			if(property_exists($this->JSONObject, $attributeName)){
				if(!is_array($this->JSONObject->{$attributeName})){
					if(!is_object($this->JSONObject->{$attributeName})){
						return $this->JSONObject->{$attributeName};
					} else {
						// Il s'agit d'un objet
						return "//!\\ [" . $attributeName . "] is object //!\\";
					}
				} else {
					// Il s'agit d'un tableau
					return $this->getLocale($this->JSONObject->{$attributeName}) !== false ? $this->getLocale($this->JSONObject->{$attributeName}) : "//!\\ [" . $attributeName . "] //!\\";
				}
			} else {
				// L'attribut n'existe pas dans l'objet JSON
				$attributes = $this->toArray($attributeName);
				$currentObject = $this->JSONObject;
				for($i = 0; $i < count($attributes); $i++){
					if(property_exists($currentObject, $attributes[$i])){
						if(is_array($currentObject->{$attributes[$i]})){
							return $this->getLocale($currentObject->{$attributes[$i]});
						} else {
							if(is_object($currentObject->{$attributes[$i]})){
								$currentObject = $currentObject->{$attributes[$i]};
							}
						}
					}
				}
				return "//!\\ [" . $attributeName . "] not found !";
			}
		} else {
			// L'attribut existe...
			// Exemple $attributeName <- telephoneLabel => $this->JSONObject->telephone->label
			$attributes = $this->toArray($attributeName);
			$currentObject = $this->JSONObject;
			for($i = 0; $i < count($attributes); $i++){
				if(property_exists($currentObject, $attributes[$i])){
					if(is_array($currentObject->{$attributes[$i]})){
						return $this->getLocale($currentObject->{$attributes[$i]});
					} else {
						if(is_object($currentObject->{$attributes[$i]})){
							$currentObject = $currentObject->{$attributes[$i]};
						}
					}
				}
			}
			return "//!\\ [" . $attributeName . "] not found !";
		}
	}
	
	/**
	 * Définit ou retourne le contenu JSON
	 * @param string $jsonContent
	 * @return void
	 */
	protected function content(string $jsonContent=null){
		if(is_null($jsonContent)){
			return $this->JSONContent;
		}
		
		$this->JSONContent = $jsonContent;
		
		if(!$this->decode()){
			$error = new error();
			$error->message("[" . $this->JSONContent . "]\nLe contenu JSON transmis n'est pas correct !")
				->code(-98001)
				->doLog(true)
				->file(__FILE__)
				->line(__LINE__)
				->doRender(true);
			throw new JSONDecodeException($error);
			
			$this->JSONObject = "//!\\ Unavailable JSON content //!\\";
		}
	}
	
	/**
	 * Décode le contenu JSON en un objet de classe standard
	 * @return boolean
	 */
	private function decode(){
		if(is_null($this->JSONObject = json_decode($this->JSONContent))){
			return false;
		}

		return true;
	}
	
	/**
	 * Retourne le contenu dans la langue concernée
	 * @param array $contentArray
	 * @return string|boolean
	 */
	private function getLocale($contentArray){
		foreach($contentArray as $content){
			if($content->language == \App\appLoader::wp()->defaultLanguage()){
				return $content->content;
			}
		}
		
		return false;
	}
	
	/**
	 * Découpe l'attribut camelCase en tableau des propriétés
	 * @param string $attributeName
	 * @return string[]
	 */
	private function toArray(string $attributeName){
		$camelMembers = \wp\Helpers\String\Helper::camelToArray($attributeName);
		
		$members = [];
		
		foreach ($camelMembers as $member){
			$members[] = \wp\Helpers\String\Helper::toLower($member);
		}
		
		return $members;
	}
}