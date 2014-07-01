<?php
function adminHTML($content, $lang, $wisig = false, $se = "", $conf = array(), $jQuery = false, $options = array("title" => "Admin")){
// $se is self page, $conf is $CONFIG
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang=".$lang.">
	<head>
	<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
	<title>BackpackCSM &middot; '.$options["title"].'</title>
	<link rel="icon" href="../favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="admin.css" />';
	
	function sel($e, $i){
		if($i==$e){
		return ' class="selected"';
		}
	}
	
	function selImg($e,$i){
		if($i==$e){
		// return '_sel';
		return '';
		}
	}
	
	// Inclusion de jQuery sur certaines pages (+ scripts perso)
	if(is_array($conf) && $jQuery == true){
		echo '<script src="http://'.$_SERVER["SERVER_NAME"].'/'.$conf["install_folder"].'javascript/jquery-1.4.2.min.js" type="text/javascript"></script>';
		echo '<script src="http://'.$_SERVER["SERVER_NAME"].'/'.$conf["install_folder"].'javascript/BackpackCMS.js" type="text/javascript"></script>';
	}
	// Inclusions du WISIG sur certaines pages
	if(is_array($conf) && $wisig == true){
		echo '<script type="text/javascript" src="http://'.$_SERVER["SERVER_NAME"].'/'.$conf["install_folder"].'javascript/nice.edit.js"></script>';
		echo '<script type="text/javascript" src="http://'.$_SERVER["SERVER_NAME"].'/'.$conf["install_folder"].'javascript/tinymce/tiny_mce.js"></script>';
		echo '<script type="text/javascript" src="http://'.$_SERVER["SERVER_NAME"].'/'.$conf["install_folder"].'javascript/WisigEditor.js"></script>';
	}
	
	echo '</head>
	<body>
	<div id="admin-container">';
		echo '<div id="admin-header">';
			// Inclusion du lien de déconnexion et rappel du login si connect"
			if($_SESSION["valid"] == true){
				echo '<div id="admin-user"><span style="color:#DFDDD0">'.$_SESSION["login"].'</span> &middot; <a href="logout">'.tr("Déconnexion").'</a></div>';
			}
		echo '<h1 class="admin-title">BackpackCMS</h1>';
		echo '</div>';
		
		$s = '';
		if($_SESSION['valid'] == true){
		echo '<div id="admin-nav-menu" class="admin-nav round">
			<div class="box-title first">
				<table>
				<tr>
					<td><img src="img/panel.png" width="16" height="16" align="left" alt="Content Image" /></td>
					<td>'.tr("Gestion du contenu").'</td>
				</tr>
				</table>
			</div>
			<ul>
				<li><a id="m1" href="index?m=m1"'.sel($se,'m1').'>'.tr("Tableau de bord").'</a></li>
				<li><a id="m2" href="pages-new?m=m2"'.sel($se,'m2').'>'.tr("Nouvelle page").'</a></li>
				<li><a id="m3" href="pages-manage?m=m3"'.sel($se,'m3').'>'.tr("Gestion de pages").'</a></li>
				<li><a id="m7" href="media-uploads?m=m7"'.sel($se,'m7').'>'.tr("Media et images").'</a></li>
			</ul>
			
			<div class="box-title">
				<table>
				<tr>
					<td><img src="img/layout.png" width="16" height="16" align="left" alt="Content Image" /></td>
					<td>'.tr("Apparence").'</td>
				</tr>
				</table></div>
			<ul>
				<li><a id="m5" href="settings?m=m5"'.sel($se,'m5').'>'.tr("Réglages et paramètres").'</a></li>
				<li><a id="m10" href="template-edit?m=m10"'.sel($se,'m10').'>'.tr("Modifier l'apparence").'</a></li>
			</ul>
			
			<div class="box-title">
				<table>
				<tr>
					<td><img src="img/list.png" width="16" height="16" align="left" alt="Content Image" /></td>
					<td>'.tr("Administration").'</td>
				</tr>
				</table></div>
			<ul>
				<li><a id="m6" href="accounts?m=m6"'.sel($se,'m6').'>'.tr("Comptes utilisateurs").'</a></li>
				<li><a id="m7" href="import-export?m=m11"'.sel($se,'m11').'>'.tr("Importer, exporter").'</a></li>
				<li><a id="m8" href="plugins-manage?m=m8"'.sel($se,'m8').'>'.tr("Gestion des extensions").'</a></li>
			</ul>
			
			<div class="box-title">
				<table>
				<tr>
					<td><img src="img/eye.png" width="16" height="16" align="left" alt="Content Image" /></td>
					<td>'.tr("Visusalisation").'</td>
				</tr>
				</table></div>
			<ul>
				<li><a id="m9" href="../index"'.sel($se,'m9').' target="_target">'.tr("Voir le site").'</a></li>
			</ul>
			
			<div class="box-title">
				<table>
				<tr>
					<td><img src="img/help.png" width="16" height="16" align="left" alt="Content Image" /></td>
					<td>'.tr("Autres liens").'</td>
				</tr>
				</table>
			</div>
			<ul>
				<li><a href="SpecialArnosteo.php">Pense-bête</a></li>
				<li><a href="https://sites.google.com/site/backpackcmsaide/presentation/modifier-le-theme" target="_blank">Modifier l\'apparence</a></li>
				<li><a href="https://sites.google.com/site/backpackcmsaide/presentation/sauvegarder-son-site" target="_blank">Sauvegarder mon site</a></li>
			</ul>
		</div>';
		
		// Enlève la marge admin content
		} else {
			$st = ' style="margin-left:0px;"';
		}
	
	echo '<div id="admin-content"'.$st.'>'.$content.'</div>';
	
	echo '<div class="clear"></div>';
	echo '<br />';
	// echo '<div id="admin-footer">&copy; Copyright 2011 BackpackCMS | <a href="logout">Déconnexion</a> | <a href="http://backpackcms.honeyshare.fr/bug">Déclarer un bug</a></div>';
echo '</div>
</body>
</html>';
}

?>