<?php
/**
* @name QRCode.class.php Encapsule la librairie phpqrcode (@see /Vendor/phpqrcode)
* @author web-Projet.com (contact@web-projet.com)
* @package wp\Utilities\QRCode
* @version 1.0
**/
namespace wp\Utilities\QRCode;

require_once(\App\appLoader::wp()->getPathes()->getRootPath("wp") . "Vendor/phpqrcode/qrlib.php");

class QRCode {
	
	public function __construct(){}
	
	public function render(){}
	
	
}