<?php
/**
* @name AnnotationMorePairsMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\SerialMatcher as SerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationPairMatcher as AnnotationPairMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationHashMatcher as AnnotationHashMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationMorePairsMatcher extends SerialMatcher {
	protected function build() {
		$this->add(new AnnotationPairMatcher);
		$this->add(new RegexMatcher('\s*,\s*'));
		$this->add(new AnnotationHashMatcher);
	}
	
	protected function match($string, &$value) {
		$result = parent::match($string, $value);
		return $result;
	}
	
	public function process($parts) {
		return array_merge($parts[0], $parts[2]);
	}
}