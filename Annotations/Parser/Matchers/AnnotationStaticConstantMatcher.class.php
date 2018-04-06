<?php
/**
* @name AnnotationStaticConstantMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationStaticConstantMatcher extends RegexMatcher {
	public function __construct() {
		parent::__construct('(\w+::\w+)');
	}
	
	protected function process($matches) {
		$name = $matches[1];
		if(!defined($name)) {
			trigger_error("Constant '$name' used in annotation was not defined.");
			return false;
		}
		return constant($name);
	}
	
}