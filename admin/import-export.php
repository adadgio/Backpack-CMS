<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	// Si la sauvegarde a bien été crée, on envoie le lien
	if($_GET["error"] == "none" && !empty($_GET["link"])){
		$ZipLink = '<a href="dump/'.$_GET["link"].'">'.tr("Télécharger la sauvegarde").'</a>';
	} else {
		$ZipLink = '';
		// Si pas de sauvegarde prête, on suppriùme tous les fichiers. Cela permet de ne pas avoir trop de sauvegardes stockées
		$repertoire = @opendir("dump/"); // On définit le répertoire dans lequel on souhaite travailler.
			while(false !== ($fichier = @readdir($repertoire))){
				$chemin = $repertoire.$fichier; 
				if ($fichier != ".." AND $fichier != "." AND !is_dir($fichier)){
					@unlink("dump/".$fichier);
				   }
			}
			closedir($repertoire);
	}

	// $html .= '<div class="inline_nav"><a href="index.php?m=m1">'.$lang['menu']['home'].'</a> > '.$lang['managepages_h2'].'</div>';
	$html .= '<h2 class="admin-title">'.tr("Exporter le site").'</h2>';

	if(!empty($ZipLink)){
			$html .= '<div class="warning success">'.tr("Sauvegarde réussie").'</div>';
		$html .= '<p>
			<table>
				<tr>
					<td><img src="img/save.png" width="22" height="22" alt="SaveIcon" /></td>
					<td>'.$ZipLink.'</td>
				</tr>
			</table>
		</p>';
		$html .= '<p><a href="import-export?m=m11">'.tr("Annuler").'</a>
			<span class="info">'.tr("La sauvegarde sera supprimée du serveur dès que vous aurez quitterez cette page.").'</span></p>';
	} else {
		$html .= '<p><a href="z-export?m=m11&action=export">'.tr("Créer une sauvegarde complète du site").'</a></p>';
	}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>