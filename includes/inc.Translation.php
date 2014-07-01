<?php
/** Fonction de traduction */
function tr($string){
	if (function_exists("gettext")){
		return gettext($string);
	} else {
		return $string;
	}
	
}

function setLanguage($currentLanguage, $path){
	// Variables d'environnement de language
	putenv("LANG = $currentLanguage");
	setlocale(LC_ALL, $currentLanguage);
	$FileNames = "traductions";
	bindtextdomain($FileNames , $path);
	textdomain($FileNames);
}
?>