<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");;

// First we get the template content stored in templates/[name]/home.html
$settings = array();
$con = new SystemData($CONFIG);
$result = $con->query("SELECT id, value FROM ".$CONFIG['prefix']."cms_site");
	$i = 0;
	while($row = mysql_fetch_assoc($result)){
		$k = $row['id'];
		$settings[$k] = $row['value'];
	}
$con->close();

	// Saving the page modified
	if(isset($_POST['template_content'])){
		if(!empty($_POST['template_content'])){
	
			$filename = '../'.$settings['templ'].'/'.$_POST['file_checker'];
			$file = @fopen($filename,'w');
			

			if($file){
				@fwrite($file, $_POST['template_content']);
				@fclose($file); 
				$html .= '<div class="warning success">'.tr("Le site est bien habillé du nouveau gabarit choisi.").'</div>';
			} else {
				$html .= '<div class="warning error">'.tr("Oups, une erreur inattendue est survenue en essayant de modifier l'habillage du site.").'</div>';
			}
			
		} else {
			$html .= '<div class="warning warn">'.tr("Impossible de modifier la gabarait, vous essayez d'enregistrer une gabarit vide !").'</div>';
		}
	}

	// Check if we want to edit the home.html or style.css file
	if(isset($_GET['view'])){
		$view = $_GET['view'];
	} else {
		$view = 'home.html';
	}

	$template = @file_get_contents('../'.$settings['templ'].'/'.$view);
	
	$html .= '<h2 class="admin-title">'.tr("Modifier l'apparence").'</h2>';
	
		// The floating help text beside the editor
		$html .= '<div style="float:right;width:22%;padding:1%;border:1px solid #979797;font-size:8pt;">
			<p><b>'.tr("Liste des variables disponibles").'</b></p>
			<p>
			{{SiteTitle}}<br />'.tr("Le titre de votre site, affiché sur chaque page et dans les onglets des navigateurs.").'<br /><br />
			{{SiteMoto}}<br />'.tr("Le slogan de votre site, affiché d'habitude sous le titre de votre site.").'<br /><br />
			{{SiteFooter}}<br />'.tr("Pied de page, affiché en général sur chaque page du site.").'
			{{PageContent}}<br />'.tr("Le coeur et contenu de la page en cours : texte, images, etc.").'<br /><br />
			{{SiteHeader}}<br />'.tr("Titre de la page en cours.").'<br /><br />
			</p>
			<p><b>'.tr("Les modèles que vous pouvez modifier.").'</b></p>
			<p>';
			
				// Récupération des fichiers HTML des templates
				$currentTemplate = $settings['templ'];
				$handle = @opendir("../$currentTemplate/");
				if($handle){
					while( ($fileTitle = readdir($handle) ) !== false) {
						$ext = substr($fileTitle, -4);
						if($fileTitle != "." && $fileTitle != ".." && ($ext == "html" || $ext == ".css" || $ext == ".htm")){
							$html .= '<a href="'.$_SERVER['PHP_SELF'].'?view='.$fileTitle.'&m='.$_GET['m'].'">'.$fileTitle.'</a><br />';
						}
					}
					@closedir($handle);
				} else {
					$html .= '<div class="warning error">'.tr("Une erreur inattendue est survenue, nous n'arrivons pas à afficher les modèles de page modifiables.").'</div>';
				}
			$html .= '</p>
		</div>';
	
	if(!empty($template)){
		$html .= '<form action="'.$_SERVER['PHP_SELF'].'?view='.$view.'&m='.$_GET['m'].'" method="post" style="width:74%;">
		<input type="hidden" name="file_checker" value="'.$view.'" />
		<textarea name="template_content" rows="30" style="color:#2D2D2D;width:100%;" class="code">'.$template.'</textarea><br /><br />
		<input class="submit" type="submit" name="try_save_template" value="'.tr("Sauvegarder le gabarit").'" />
		</form>';
	} else {
		$html .= '<div class="warning error">'.tr("Impossible d'enregistrer un modèle de page qui n'existe pas.").'</div>';
	}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>