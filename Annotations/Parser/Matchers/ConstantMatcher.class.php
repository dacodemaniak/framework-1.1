<?php
/**
* @name ConstantMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class ConstantMatcher extends RegexMatcher {
	private $constant;
	
	public function __construct($regex, $constant) {
		parent::__construct($regex);
		$this->constant = $constant;
	}
	
	protected function process($matches) {
		return $this->constant;
	}
}