<?php
/**
* @name AnnotationArrayValuesMatcher.class.php
* @package wp\Annotations\Parser\Matchers
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationArrayValueMatcher as AnnotationArrayValueMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationMoreValuesMatcher as AnnotationMoreValuesMatcher;

class AnnotationArrayValuesMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new AnnotationArrayValueMatcher);
		$this->add(new AnnotationMoreValuesMatcher);
	}
}