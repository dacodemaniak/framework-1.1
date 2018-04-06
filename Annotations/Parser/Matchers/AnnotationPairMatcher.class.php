<?php
/**
* @name AnnotationPairMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\SerialMatcher as SerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationKeyMatcher as AnnotationKeyMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationValueMatcher as AnnotationValueMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationPairMatcher extends SerialMatcher {
	protected function build() {
		$this->add(new AnnotationKeyMatcher);
		$this->add(new RegexMatcher('\s*=\s*'));
		$this->add(new AnnotationValueMatcher);
	}
	
	protected function process($parts) {
		return array($parts[0] => $parts[2]);
	}
}