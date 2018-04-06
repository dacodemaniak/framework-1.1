<?php
/**
* @name AnnotationValueInArrayMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\AnnotationValueMatcher as AnnotationValueMatcher;

class AnnotationValueInArrayMatcher extends AnnotationValueMatcher {
	public function process($value) {
		return array($value);
	}
}