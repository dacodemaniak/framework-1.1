<?php
/**
* @name AnnotationMoreValuesMatcher.class.php
* @package wp\Annotations\Parser
*/
namespace wp\Annotations\Parser\Matchers;

use \wp\Annotations\Parser\Matchers\SimpleSerialMatcher as SimpleSerialMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationArrayValueMatcher as AnnotationArrayValueMatcher;
use \wp\Annotations\Parser\Matchers\AnnotationArrayValuesMatcher as AnnotationArrayValuesMatcher;
use \wp\Annotations\Parser\Utilities\RegexMatcher as RegexMatcher;

class AnnotationMoreValuesMatcher extends SimpleSerialMatcher {
	protected function build() {
		$this->add(new AnnotationArrayValueMatcher);
		$this->add(new RegexMatcher('\s*,\s*'));
		$this->add(new AnnotationArrayValuesMatcher);
	}
	
	protected function match($string, &$value) {
		$result = parent::match($string, $value);
		return $result;
	}
	
	public function process($parts) {
		return array_merge($parts[0], $parts[2]);
	}
}