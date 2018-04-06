<?php
/**
 * @name iterator.class.php Service d'itération à partir d'un dossier
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\Files\Directory\Iterator
 * @version 1.0
**/
namespace wp\Files\Directory\Iterator;

use \wp\Files\Mimes\mimes as Mimes;
use wp\Collections\collection as Collection;

class iterator{
	
	/**
	 * Dossier source à partir duquel récupérer les fichiers à collectionner
	 * @var string
	 */
	private $src;
	
	/**
	 * Instance de l'objet contenant les masques de fichiers à lister dans le dossier
	 * @var \Mimes
	 */
	private $masks;
	
	/**
	 * Collection des fichiers trouvés
	 * @var \Collection
	 */
	private $collection;
	
	/**
	 * Instancie un nouvel objet de type iterator
	 */
	public function __construct(){
		$this->collection = new Collection();
	}
	
	/**
	 * Définit ou retourne le dossier racine à partir duquel remonter les informations
	 * @param string $directory
	 */
	public function src($directory=null){
		if(is_null($directory)){
			return $this->src;
		}
		
		$this->src = $directory;
		return $this;
	}
	
	/**
	 * Définit ou retourne la collection des types de fichiers à traiter
	 * @param Collection $mask Types des fichiers à traiter
	 */
	public function mask(Collection $mask = null){
		if(is_null($mask)){
			return $this->masks;
		}
		$this->masks = $mask;
		return $this;
	}
	
	/**
	 * Parcourt de manière non récursive le dossier source et alimente la collection
	 */
	public function process(){
		$rootPath = \App\appLoader::wp()->getPathes()->getRootPath("App") . $this->src;
		
		$fileList = new \DirectoryIterator($rootPath);
		
		foreach($fileList as $file){
			// On ne traite pas les dossiers . et ..
			if($file->isDot()){
				continue;
			}
			
			// Ne traite pas récursivement le dossier
			if(!$file->isDir()){
				if ($this->masks->contains($file->getExtension())){
					$item = new \wp\Collections\item($this->collection);
					$item->id($file->getFilename())
						->value($this->src . $file->getFilename())
						->hydrate();
				}
			}
		}
	}
	
	/**
	 * Retourne la collection des fichiers concernés
	 * @return Collection
	 */
	public function getCollection(){
		return $this->collection;
	}
}