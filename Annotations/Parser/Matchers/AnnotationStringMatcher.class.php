<?php
/**
* @name AnnotationStringMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationDoubleQuotedStringMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationSingleQuotedStringMatcher;

class AnnotationStringMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new AnnotationSingleQuotedStringMatcher);
		$this->add(new AnnotationDoubleQuotedStringMatcher);
	}
}