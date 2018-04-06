<?php
/**
* @name DeviceInfo.class.php Service de détection du dispositif client
* @author IDEAFactory (dev-team@ideafactory.fr) - Déc. 2017
* @package wp\Utilities\Client
* @version 0.1.0
**/

use wp\Vendor\MobileDetection\MobileDetect;

class DeviceInfo extends MobileDetect {
	
	private $isDesktop;
	
}