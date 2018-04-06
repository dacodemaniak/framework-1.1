<?php
/**
* @name CompositeMatcher.class.php
* @package wp\Annotations\Parser
*/

namespace wp\Annotations\Parser\Matchers;

class CompositeMatcher {
	protected $matchers = array();
	private $wasConstructed = false;
	
	public function add($matcher) {
		$this->matchers[] = $matcher;
	}
	
	public function matches($string, &$value) {
		if(!$this->wasConstructed) {
			$this->build();
			$this->wasConstructed = true;
		}
		return $this->match($string, $value);
	}
	
	protected function build() {}
}