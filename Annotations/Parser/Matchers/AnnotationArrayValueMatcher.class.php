<?php
/**
* @name AnnotationArrayValueMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationValueInArrayMatcher as AnnotationValueInArrayMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationPairMatcher as AnnotationPairMatcher;

class AnnotationArrayValueMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new AnnotationValueInArrayMatcher);
		$this->add(new AnnotationPairMatcher);
	}
}