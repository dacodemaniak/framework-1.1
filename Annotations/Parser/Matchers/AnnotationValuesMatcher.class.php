<?php
/**
* @name AnnotationValuesMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationTopValueMatcher as AnnotationTopValueMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationHashMatcher as AnnotationHashMatcher;

class AnnotationValuesMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new AnnotationTopValueMatcher);
		$this->add(new AnnotationHashMatcher);
	}
}