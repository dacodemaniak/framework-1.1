<?php
/**
* @name AnnotationArrayMatcher.class.php
* @package wp\Annotations\Parser\Matchers
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\ConstantMatcher as ConstantMatcher;
use \wp\Annotations\Parser\Matchers\SimpleSerialMatcher as SimpleSerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationArrayValuesMatcher as AnnotationArrayValuesMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationArrayMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new ConstantMatcher('{}', array()));
		$values_matcher = new SimpleSerialMatcher(1);
		$values_matcher->add(new RegexMatcher('\s*{\s*'));
		$values_matcher->add(new AnnotationArrayValuesMatcher);
		$values_matcher->add(new RegexMatcher('\s*}\s*'));
		$this->add($values_matcher);
	}
}