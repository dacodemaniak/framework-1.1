<?php
/**
* @name AnnnotationSingleQuotedStringMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationSingleQuotedStringMatcher extends RegexMatcher {
	public function __construct() {
		parent::__construct("'([^']*)'");
	}
	
	protected function process($matches) {
		return $matches[1];
	}
}