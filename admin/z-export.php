<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

// Initialisation des chemins de sauvegarde
$SavePath = "dump";
$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

// Initialisation des message d'erreur
$SuccessCounter = array(
	"dump" => false,
	"config" => false,
	"media" => false,
	"zip" => false
);

	// Exportation de la base de donnée vers /dump/sql
	$sqlFile = "$SavePath/".$CONFIG['dbname']."-BD-SAVE.sql";
	if(system("mysqldump --host=".$CONFIG['dbhost']." --user=".$CONFIG['dbusername']." --password=".$CONFIG['dbpassword']." ".$CONFIG['dbname']." > $sqlFile")){
		$SuccessCounter["dump"] = true;
	} else {
		$SuccessCounter["dump"] = false;
	}
	
	// Exportation des images et médias uploadés vers dump/media
	
	
	// Exportation des fichiers de thème
	$themesPath = "$DocumentRoot/templates/";
	$ThemesZipName = "MesTemplates-".date("d-M-Y");
	system("zip -qr -5 $DocumentRoot/admin/dump/$ThemesZipName $DocumentRoot/templates");
	
	
	// Exportation de la configuration (copie du fichier config.php) dump/config.txt
	$source = "$DocumentRoot/config.php";
	$destination = "$SavePath/config.txt";
	if(@copy($source, $destination)){
		$SuccessCounter["config"] = true;
	} else {
		$SuccessCounter["config"] = false;
	}
	
	// Zip de tous les fichiers et redirection pour téléchargement
	$ZipName = "MaSauvegarde-".date("d-M-Y");
	if(system("zip -qr -5 $DocumentRoot/admin/dump/$ZipName $DocumentRoot/admin/dump")){
		// Do nothing
		$SuccessCounter["zip"] = true;
	} else {
		$SuccessCounter["zip"] = false;
	}

	// Suppression des fichiers temporaires si l'archive a bien été créée
	unlink($sqlFile);
	unlink($destination);
	
	$returnStringToURL = "import-export?m=m11&error=none&link=".urlencode("$ZipName")."&tempaltes=".urlencode("$ThemesZipName");
	header("Location: $returnStringToURL");
?>