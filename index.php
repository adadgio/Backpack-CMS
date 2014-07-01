<?php
if(!file_exists("config.php")){
	include("errors/BackpackNotInstalled.html");
	exit();
}

require_once("config.php");
require_once("includes/class.SystemData.php");


/**
*	Spécifique au formulaire de contact
**/
if(isset($_POST["nom"]) && isset($_POST["email"])){
	$Destinataire = "EMAIL@DOMAIN.com";
	$Sujet = "Nouvelle demande de contact";
	
	$From  = "From:EMAIL@DOMAIN.com\n";
	$From .= "MIME-version: 1.0\n";
	$From .= "Content-type: text/html; charset= iso-8859-1\n";
	
	$Message = "<p>De ".$_POST["nom"]." : <b>".$_POST["email"]."</b></p><p>".$_POST["question"]."</p>";
	
	@mail($Destinataire,$Sujet,$Message,$From);
	$MailMsg = 'Votre message nous a bien été envoyé, nous vous contacterons dans les plus brefs délais. <br /><br /><a href="http://www.arnosteo-formation-bebe.fr/contact.html">Retour au site<a/>';
	echo utf8_encode($MailMsg);
	exit();
}

	// We find the template of the site
	// Getting site settings first...
	$settings = array();
	$con = new SystemData($CONFIG);
	if($con->link == false){
		include("errors/MysqlError.html");
		exit();
	}
	
		$result = $con->query("SELECT id, value FROM ".$CONFIG['prefix']."cms_site");
		if(!$result){
			include("errors/BackpackNotInstalled.html");
			exit();
		}
		
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
			$k = $row['id'];
			$settings[$k] = $row['value'];
		}
	$con->close();

	// We get the current page ID
	if(isset($_GET['node']) && !empty($_GET['node'])){
		
		if(is_numeric($_GET['node'])){
			$fetch = $_GET['node'];
		} else {
			$getu = new SystemData($CONFIG);
			$res_url = $getu->sqlQuery("SELECT id, model FROM ".$CONFIG['prefix']."cms_articles WHERE url = '%s'",
				array(
					0 => $_GET['node']
				)
			);
				
				if(mysql_num_rows($res_url)==1){
				;
					$temp = mysql_fetch_assoc($res_url);
					$fetch = $temp['id'];
					$type = $temp['model'];
					
				} else {
					include("errors/404.html");
					exit();
				}
			
			$getu->close();
		}
		
	} else {
		$fetch = 1;
	}
	
	// Affichage de la page
	header("Content-type: text/html; charset=UTF-8");
		// Ajout de paramètres HTML à la page (ici classe du menu optionnelle)
		$AddSettings = array(
			'NavMenuSettings' => 'class="round"'
		);
	
	$s = new SystemData($CONFIG);
	$s->addSettings($AddSettings);
	$output = $s->parsePage($settings['templ'],$fetch,$type);
	$s->close();
	echo $output;
?>