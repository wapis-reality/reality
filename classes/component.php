<?php
class Component {
	var $controller;
	
	function init(&$controller) {}
		
	function startup(&$controller) {
		$this->controller = $controller;			
	}        

        function _set($var,$value){           
		if(isset($this->$var)){ //if isset($var), set $var only if $value not empty
			if($value != ''){ $this->$var = $value;}
		} else {
			$this->$var = $value;
		}
        }
	
        function _get($var){
		if(isset($this->{$var}))
			return $this->{$var};
		else
			return false;    
		}
}
?>