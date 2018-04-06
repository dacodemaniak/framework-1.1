<?php
/**
* @name NestedAnnotationMatcher.class.php
* @namespace wp\Annotations\Parser\Matchers
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\AnnotationMatcher as AnnotationMatcher;
use \wp\Annotations\AnnotationsBuilder as AnnotationsBuilder;

class NestedAnnotationMatcher extends AnnotationMatcher {
	protected function process($result) {
		$builder = new AnnotationsBuilder;
		return $builder->instantiateAnnotation($result[1], $result[2]);
	}
}	