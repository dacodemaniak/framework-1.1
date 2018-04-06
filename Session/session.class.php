<?php
/**
 * @name session.class.php Gestionnaire de session
 * @author web-Projet.com (contact@web-projet.com) - Déc. 2016
 * @package wp\Session
 * @version 1.0
**/
namespace wp\Session;

class session {
	
	/**
	 * Ajoute ou remplace une variable de session
	 * @param string $key : Clé du tableau _SESSION
	 * @param mixed $content : Données à mettre à jour dans le tableau _SESSION pour la clé concernée
	 */
	public static function add($key, $content){
		if(array_key_exists($key, $_SESSION)){
			$_SESSION[$key] = null;
		}
		// Vérifie si la clé est un objet...
		if(array_key_exists("o_" . $key, $_SESSION)){
			$_SESSION["o_" . $key] = null;
		}
		if(is_object($content)){
			$key = "o_" . $key;
			$_SESSION[$key] = serialize($content);
		} else {
			$_SESSION[$key] = $content;
		}
	}
	
	public static function remove($key){
		if(array_key_exists($key, $_SESSION)){
			unset($_SESSION[$key]);
		}
		$key = "o_" . $key;
		if(array_key_exists($key, $_SESSION)){
			unset($_SESSION[$key]);
		}
	}
	
	/**
	 * Retourne la données de session associée à la clé $key
	 * @param string $key
	 * @return mixed
	**/
	public static function get($key){
		if(array_key_exists($key, $_SESSION)){
			return $_SESSION[$key];
		}
		$key = "o_" . $key;
		if(array_key_exists($key, $_SESSION)){
			return unserialize($_SESSION[$key]);
		}
	}
	
	/**
	 * Teste si une variable de session existe ou non
	 * @param string $key
	 * @return boolean
	 */
	public static function exists($key){
		if(array_key_exists($key, $_SESSION)){
			return true;
		}
		$key = "o_" . $key;
		if(array_key_exists($key, $_SESSION)){
			return true;
		}
		return false;
	}
}