<?php
/**
* @name AnnotationHashMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationPairMatcher as AnnotationPairMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationMorePairsMatcher as AnnotationMorePairsMatcher;

class AnnotationHashMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new AnnotationPairMatcher);
		$this->add(new AnnotationMorePairsMatcher);
	}
}