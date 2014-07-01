<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

$do_not_show_new_page_form = false;

	// Enregistrement du formulaire et création de la nouvelle page
	if(isset($_POST['page_title'])){
		
		if(!empty($_POST['page_title'])){
	
			$con = new SystemData($CONFIG);
			$check = $con->sqlQuery("SELECT id FROM ".$CONFIG['prefix']."cms_articles WHERE url = '%s' LIMIT 1",
				array(0 => $_POST['page_url'])
			);
			
			if(mysql_num_rows($check) == 0){
			
				$result = $con->sqlQuery("INSERT INTO ".$CONFIG['prefix']."cms_articles
				(title, url, tagline, section, content, model)
				VALUES
				('%s', '%s', '%s', '%s', '%s', '%s')",
					array(
						0 => $_POST['page_title'],
						1 => $_POST['page_url'],
						2 => $_POST['page_tagline'],
						3 => "no",
						4 => $_POST['page_content'],
						5 => $_POST['page_model'],
					)
				);
				
				if($result){
				
					$do_not_show_new_page_form = true;
					$_POST = array();
					unset($_POST);
					header("Location: pages-manage?m=m3");
					
				} else {
					$html .= '<div class="warning">'.tr("Une erreur inattendue est survenue, la page n'a pas été créée.").'</div>';
				}
				
			} else {
				$html .= '<div class="warning">'.tr("Une page avec cet URL existe déjà. Chaque URL doit être unique.").'</div>';
			}
			$con->close();
		} else {
			$html .= '<div class="warning">'.tr("Le titre de la page est obligatoire.").'</div>';
		}
		
	}
	
	
	$html .= '<h2 class="admin-title">'.tr("Nouvelle page").'</h2>';
	
	// On affiche pas le formulaire de création si la nouvelle page a correctement été créée
	if($do_not_show_new_page_form == true){
		$html .= '<div class="warning">'.tr("La nouvelle page a bien été créée.").'</div>';
		$html .= '<p>'.$lang['newpage_pA'].'</p>';
	} else {

		if(!empty($_POST['page_title'])){$bigInputContent = $_POST['page_title'];} else {$bigInputContent = tr("Titre de la page...");}
		if(!empty($_POST['page_model'])){$modelInputContent = $_POST['page_model'];} else {$modelInputContent = 'home';}
		
		$html .= '<form action="'.$CONFIG["this_url"].'" method="post">
		
					<input type="text" id="new-page-title" name="page_title" class="big-input tip required" value="'.$bigInputContent.'" />
					
					<p>
					<a id="show-options-newpage" href="#">'.tr("Plus d'options").'</a>
					</p>
					
					<div id="more-options-newpage" class="hidden">
					
						<label style="margin:0px 0px 3px 0px;">'.tr("URL unique").'</label>
						<input type="text" name="page_url" value="'.$_POST['page_url'].'" /><small>.html</small>
						
						<label>'.tr("Sous titre").'</label>
						<input type="text" name="page_tagline" value="'.$_POST['page_tagline'].'" />
						
						<label>'.tr("Modèle de page").'</label>
						<input id="page-model-entry" type="text" name="page_model" value="'.$modelInputContent.'" class="required" />
						
							<noscript>'.tr("Javascript est désactivé : vous ne pourrez pas choisir de modèle de page automatiquement").'<br /></noscript>
							
							<a  href="#" id="mini-dropdown">Voir les modèles de page disponibles</a>
							<div id="mini-dropdown-content" class="round">';
									// Récupération du template en cours
									$con = new SystemData($CONFIG);
									$currentTemplate = $con->getSetting("templ");
									$con->close();
									// Récupération des fichiers HTML des templates
									$handle = @opendir("../$currentTemplate/");
									if($handle){
										while( ($fileTitle = readdir($handle) ) !== false) {
											// Get the extension (only find HTML files
												$ext = substr($fileTitle, -4);
											if($fileTitle != "." && $fileTitle != ".." && $ext == "html" && !empty($fileTitle)){
												$fileName = substr($fileTitle, 0, strpos($fileTitle, "."));
												$html .= '<a  href="#" id="auto-'.$fileName.'" class="auto-complete-page-type" onclick="autoCompletePageType(\'page-model-entry\',\''.$fileName.'\');myHide(\'mini-dropdown-content\');return false;">'.$fileName.'</a>';
											}
										}
									@closedir($handle);
									} else {
										$html .= '<div class="warning success">Impossible de charger les fichiers du template.</div>';
									}
								$html .= '</div>

					</div>
			<br />				
			<textarea id="wisig-editor" class="round" name="page_content" rows="20" cols="120">'.$_POST['page_content'].'</textarea>		
			<br />
			<input type="submit" class="submit" name="page_submit" value="'.tr("Sauvegarder la page").'" />
		</form>';
	}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, true, $_GET['m'], $CONFIG, true, array(
	"title" => tr("Nouvelle page")
));
?>