<?php
/**
* @name AnnotationTopValueMatcher.class.php
* @package wp\Annotations\Parser
*/

namespace wp\Annotations\Parser\Matchers;

use wp\Annotations\Parser\Matchers\AnnotationValueMatcher as AnnotationValueMatcher;

class AnnotationTopValueMatcher extends AnnotationValueMatcher {
	protected function process($value) {
		return array('value' => $value);
	}
}