<?php
require_once('includes/class.SystemData.php');
require_once('includes/inc.SystemFunctions.php');
require_once('includes/inc.Translation.php');
header("Content-Type: text/html; charset=utf-8");

if(isset($_GET["lang"])){
	setLanguage($_GET["lang"], "./languages");
} else {
	setLanguage("fr_FR", "./languages");
}

// On vérifie que BackpackCMS n'est pas déjà installé
if(@file_exists("config.php") || is_array($CONFIG)){
	// $con = new SystemData($CONFIG);
	// if(checkInstall() == false){
		include("errors/BackpackAlreadyInstalled.html");
		exit();
	// }
	// $con->close();
}

$success = false;
$message = "";

if(isset($_POST["r_login"]) && isset($_POST["r_password"])){

	if($_POST["r_password"] == $_POST["r_password_confirm"] && filter_var($_POST["r_login"], FILTER_VALIDATE_EMAIL) == true){
	
			// First try to connect to database
			$con = new SystemData($_POST);
			if($con->link == true){

				// Création des tables dans la base de donnée7
				$DatabaseInfo = array();
				foreach($_POST as $key => $value){
					$DatabaseInfo["$key"] = $value;
				}

				// Création des tables dans la base de donnée
				$tablesOK = createTables("winstall/winstall.database.txt", $con);
				// Create the fconfiguration file
				$configOK = createConfig("winstall/winstall.config.txt");
				
					// Send an email to your inbox to confirm installation
					@mail($_POST["r_login"], tr("BackpackCMS a été installé"), tr("Vous pouvez vous connecter à votre interface d'aministration avec l'identifiant suivant").' : '.$_POST["r_login"].'.');
					$success = true;
					$message = tr("L'installation s'est terminée avec succès.");

			} else if($con->link == false){
				$success = false;
				$message = tr("La connexion à la base de donnée a échouée. Vérifiez les informations de connexion.");
			}
			
			$con->close();
		
	} else {
		$message = tr("Vos information de compte administrateur ne sont pas correctes.");
	}
}	

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang=".$lang.">
<head>
<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
<title>Backpack CMS &middot; '.tr("Installation").'</title>
<link rel="icon" href="../favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="admin/admin.css" />
</head>
<body>
	
	<div id="admin-header">
		<h1>'.tr("Installation de BackpackCMS").'</h1>
	</div>
	
	<div id="lang-bar">
		<small>Choose language : <a href="install?lang=fr_FR">fr_FR</a> | <a href="install?lang=en_US">en_US</a></small>
	</div>
	
	<div id="install">';
	
	if($success == true){
		include("config.php");
		echo '<h2>'.tr("Installation réussie").'</h2>
			<p>
			'.tr("Vous pouvez maintenant gérer votre site à l'adresse <a href=\"/admin\">/admin</a> en vous connectant avec votre mon d'utilisateur et mot de passe.").'
			</p>
			<p>
			'.tr("Pour des raisons de sécurité, il est conseillé de supprimer les fichiers et dossiers suivants :").'
			</p>
			<ul>
				<li>/winstall/</li>
				<li>install.php</li>
				<li>config-sample.php</li>
			</ul>';
	} else {
	
		echo '<h2>'.tr("Pré-requis").'</h2>
			<ul>
				<li>'.tr("PHP version 4.2 ou au dessus <b>&middot;</b> Votre version : ").'<strong>'.phpversion().'</strong>;</li>
				<li>'.tr("Un accès à une base de donnée MySQL.").'</li>
			</ul>';
			
		// Install form
		echo '<form action="install.php" method="post">
			<table class="form">
				<tr>
					<td colspan="2">
						<h2>'.tr("Création d'un compte administrateur").'</h2>
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Identifiant").' ('.tr("Email").')
					</td>
					<td>
					<input type="text" name="r_login" value="'.$_POST["r_login"].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Mot de passe").'
					</td>
					<td>
					<input type="password" name="r_password" value="'.$_POST["r_password"].'" />
					</td>
				</tr>
				<tr>
					<td>'.
					tr("Confirmez le mot de passe")
					.'</td>
					<td>
					<input type="password" name="r_password_confirm" value="'.$_POST["r_password_confirm"].'" />
					</td>
				</tr>

				<tr>
				<td colspan="2">
					<h2>'.tr("Chemin d'installation").'</h2>
				</td>
				</tr>
				<tr>
					<td>
					'.tr("Dossier d'installation").' ('.tr("Optionnel").')
					</td>
					<td>
					<input type="text" name="r_subfolder" value="'.$_POST["r_subfolder"].'" />
					</td>
				</tr>';
		
		echo '<tr>
				<td colspan="2">
					<h2>'.tr("Informations de connexion à la base de donnée").'</h2>
				</td>
				</tr>
				<tr>
					<td>
					'.tr("Hôte MySQL").'
					</td>
					<td>
					<input type="text" name="dbhost" value="'.$_POST["dbhost"].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Nom d'utilisateur").'
					</td>
					<td>
					<input type="text" name="dbusername" value="'.$_POST["dbusername"].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Mot de passe").'
					</td>
					<td>
					<input type="text" name="dbpassword" value="'.$_POST["dbpassword"].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Nom de la base de donnée").'
					</td>
					<td>
					<input type="text" name="dbname" value="'.$_POST["dbname"].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Préfixe des tables").' ('.tr("Optionnel").')
					</td>
					<td>
					<input type="text" name="prefix" value="'.$_POST["prefix"].'" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td><br />
					<input type="submit" name="try_install" value="'.tr("Installer BackpackCMS").'" style="padding:3px 5px 3px 5px;" />
					</td>
				</tr>
			</table>
		</form>';
	
	}
	
	if(!empty($message)){
		echo '<div class="warning error">'.$message.'</div>';
	}
	
	echo '<div id="admin-footer" style="text-align:center;font-size:11px;margin:30px 0px 20px 0px;">
		&copy; Copyright BackpackCMS &middot; '.tr("Logiciel sous licence libre").' '.tr("GNU Public Licence").'
	</div>';
	
echo '</div>
</body></html>';
?>