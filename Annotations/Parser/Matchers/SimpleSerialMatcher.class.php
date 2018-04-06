<?php
/**
* @name SimpleSerialMatcher.class.php
* @package wp\Annotations\Parser
*/

namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\SerialMatcher as SerialMatcher;

class SimpleSerialMatcher extends SerialMatcher {
	private $return_part_index;
	
	public function __construct($return_part_index = 0) {
		$this->return_part_index = $return_part_index;
	}
	
	public function process($parts) {
		return $parts[$this->return_part_index];
	}
}