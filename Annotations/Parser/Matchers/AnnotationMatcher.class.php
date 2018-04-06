<?php
/**
* @name AnnotationMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\SerialMatcher as SerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationParametersMatcher as AnnotationParametersMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationMatcher extends SerialMatcher {
	protected function build() {
		$this->add(new RegexMatcher('@'));
		$this->add(new RegexMatcher('[A-Z][a-zA-Z0-9_\\\\]*'));
		$this->add(new AnnotationParametersMatcher);
	}
	
	protected function process($results) {
		return array($results[1], $results[2]);
	}
}