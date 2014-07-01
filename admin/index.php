<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

$html .= '<h2 class="admin-title">'.tr("Tableau de bord").'</h2>';

// Ouverture du système
$con = new SystemData($CONFIG);

	// Récupération du nombre de pages
	$pages = $con->query("SELECT id, title FROM ".$CONFIG['prefix']."cms_articles WHERE section <> 'yes'");
	$PageNumber = mysql_num_rows($pages);
	
	// Récuparation du template en cours
	$currentTemplate = $con->getSetting("templ");

	// Récupération des comptes utilsateurs
	
$con->close();

$html .= '<table>
		<tr>
			<td><img src="img/DashboardIcons/pages.png" alt="Image manquante" />&nbsp;</td>
			<td>'.tr("Vous avez créé et publié").' <a href="pages-manage?m=m3"><strong>'.$PageNumber.' '.tr("pages").'</strong></a>.</td>
		</tr>
		<tr>
			<td><img src="img/DashboardIcons/template.png" alt="Image manquante" />&nbsp;</td>
			<td>'.tr("Votre site est habillé avec le modèle graphique intitulé").' <a href="settings?m=m5"><strong>'.$currentTemplate.'</strong></a>.</td>
		</tr>
		<tr>
			<td><img src="img/DashboardIcons/tip.png" alt="Image manquante" />&nbsp;</td>
			<td>'.tr("<strong>Astuce :</strong>Pour insérer une image dans le contenu d'une page, uploadez d'abord une image dans \"Images et Documents\", puis copize-collez son lien dans le corps de votre page lorsque vous la modifiez.").'</td>
		</tr>
		<tr>
			<td><img src="img/DashboardIcons/tip.png" alt="Image manquante" />&nbsp;</td>
			<td>'.tr("<strong>Astuce :</strong> Les seules options délicates à utiliser dans BackpackCMS sont celles du menu \"Changer l'apparence\", car vous pouvez y modifier le code source HTML. La plus grande prudence est donc de mise avant de commencer à éditer un modèle graphique : \"Exportez votre site\" au préalable.").'</td>
		</tr>
</table>';

header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m'], $CONFIG , false, array(
	"title" => tr("Tableau de bord")
));
?>