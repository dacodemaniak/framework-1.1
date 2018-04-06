<?php
/**
* @name IDecorator.class.php Interface pour les classes utilisant les décorateurs
* @author IDea Factory (dev-team@ideafactory.fr) - Fév. 2018
* @package wp\Patterns\Decorator
* @version 0.1.0
*/
namespace wp\Patterns\Decorator;

interface IDecorator {
	/**
	 * Définit l'objet associé dans pour le décorateur défini
	 * @param string $attributeName Nom de l'attribut qui contient l'objet
	 * @param Object $object Objet à attribuer au décorateur
	 */
	public function setDecorator(string $attributeName, $object);
}