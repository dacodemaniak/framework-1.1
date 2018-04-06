<?php
/**
* @name AnnotationKeyMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationStringMatcher as AnnotationStringMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationIntegerMatcher as AnnotationIntegerMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationKeyMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new RegexMatcher('[a-zA-Z][a-zA-Z0-9_]*'));
		$this->add(new AnnotationStringMatcher);
		$this->add(new AnnotationIntegerMatcher);
	}
}