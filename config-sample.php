<?php
/* Makes sure the config file is not accessible directly */
if (basename($_SERVER["PHP_SELF"]) == "config.php") { 
	exit("This page cannot be loaded directly.");
};
	
// Fill if you install BackpackCMS in a subfolder inside your "/www" folder
$CONFIG["install_folder"] = "";

// Edit your database information
$CONFIG["dbhost"] = "host";
$CONFIG["dbusername"] = "login";
$CONFIG["dbpassword"] = "xxxxx";
$CONFIG["dbname"] = "database";

$CONFIG["prefix"] = "";

// Choose a language for the admin interface (and errors message on your public site)
$lang = "fr_FR";

// Extensions de fichiers autorisées à l"upload... il vaut mieux ne pas changer cela...
$AuthExt = array(
	"doc",
	"docx",
	"xls",
	"xlx",
	"ppt",
	"pptx",
	"txt",
	"png",
	"pdf",
	"jpeg",
	"jpg"
);

// Does all the inclusions, do not remove
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/Bootstrap.php");
?>