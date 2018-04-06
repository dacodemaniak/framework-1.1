<?php
/**
 * @name httpRequest.class.php Interface pour la gestion de la récupération
 * 	des données de requête HTTP
 * @author web-Projet.com (jean-luc.aubert@web-projet.com)
 * @package wp\Http\Request
 * @version 1.0
 */

namespace wp\Http\Request;

interface httpRequest {
	public function name($name=null);
	public function value($value=null);
}