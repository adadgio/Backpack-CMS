<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	// Temporaire
	$html .= '<div class="warning error">La gestion des plugins n\'est pas encore implémentée dans cette version.</div>';

	// $html .= '<div class="inline_nav"><a href="index.php?m=m1">'.$lang['menu']['home'].'</a> > '.$lang['managepages_h2'].'</div>';
	$html .= '<h2 class="admin-title">Plugins et extensions</h2>';
	
	$html .= "<p>Voici la liste des plugins installés. Ajoutez [:plugin:<i>NomDuPlugin</i>:] pour insérer le plugin dans une de vos pages</p>";

	$html .= '<h3 class="admin-title">Mes plugins</h3>';
	
	// On va chercher les plugins...
	$dir = '../plugins';
	if(is_dir($dir)){
		$d = opendir($dir);
		while($element = readdir($d)) {
			if($element != '.' && $element != '..'){
				// Le plugin
				$html .= '<strong>'.$element.' :</strong> ajouter [:plugin:'.$element.':] pour faire apparaitre le plugin dans une page (en mode édition).<br />';
				
				// Ensuite sa description
			}
		}
	}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>