<?php
/**
* @name AnnotationValueMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\ParallelMatcher as ParallelMatcher;
use \wp\Annotations\Parser\Matchers\ConstantMatcher as ConstantMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationStringMatcher as AnnotationStringMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationNumberMatcher as AnnotationNumberMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationArrayMatcher as AnnotationArrayMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationStaticConstantMatcher as AnnotationStaticConstantMatcher;
use \wp\Annotations\Parser\Matchers\NestedAnnotationMatcher as NestedAnnotationMatcher;

class AnnotationValueMatcher extends ParallelMatcher {
	protected function build() {
		$this->add(new ConstantMatcher('true', true));
		$this->add(new ConstantMatcher('false', false));
		$this->add(new ConstantMatcher('TRUE', true));
		$this->add(new ConstantMatcher('FALSE', false));
		$this->add(new ConstantMatcher('NULL', null));
		$this->add(new ConstantMatcher('null', null));
		$this->add(new AnnotationStringMatcher);
		$this->add(new AnnotationNumberMatcher);
		$this->add(new AnnotationArrayMatcher);
		$this->add(new AnnotationStaticConstantMatcher);
		$this->add(new NestedAnnotationMatcher);
	}
}