<?php
/**
* @name AnnotationParametersMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\ConstantMatcher as ConstantMatcher;
use \wp\Annotations\Parser\Matchers\SimpleSerialMatcher as SimpleSerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationValuesMatcher as AnnotationValuesMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationParametersMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new ConstantMatcher('', array()));
		$this->add(new ConstantMatcher('\(\)', array()));
		$params_matcher = new SimpleSerialMatcher(1);
		$params_matcher->add(new RegexMatcher('\(\s*'));
		$params_matcher->add(new AnnotationValuesMatcher);
		$params_matcher->add(new RegexMatcher('\s*\)'));
		$this->add($params_matcher);
	}
}