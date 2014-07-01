<?php
require_once('../includes/class.SystemComponent.php');

class Validator extends SystemComponent{
	var $errors;
	
	function isNumber($input,$description=''){
		if (is_numeric($input)){
			return true; // The value is numeric, return true
		} else {
        $this->errors[] = $description; // Value not numeric! Add error description to list of errors
			return false; // Return false
		}
	}
}
?>