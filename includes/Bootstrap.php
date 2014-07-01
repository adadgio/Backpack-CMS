<?php
/**
	* This file's job is to include all the librairies and classes
	* that we don't want to show in the config file for simple 
	* user friendliness reasons.
*/

// Defines the current URL including optionnal GET vars
$CONFIG['this_url'] = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

// Defines the clean current URL in admin without any GET vars
$CONFIG['this_url_noget'] = "http://".$_SERVER['SERVER_NAME']."/admin";
	
// Include all classes and functions
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/inc.Translation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/class.SystemData.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/inc.SystemFunctions.php");
setLanguage($CONFIG["lang"], "../languages");
?>