<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	// If page is modified
	if(isset($_POST['page_title']) && isset($_POST['page_id'])){
	
		if(!empty($_POST['page_title'])){
	
			$con = new SystemData($CONFIG);
			$con->sqlQuery("UPDATE ".$CONFIG['prefix']."cms_articles 
				SET
				title = '%s',
				url = '%s',
				tagline = '%s',
				section = '0',
				content = '%s',
				model = '%s'
				WHERE
				id = '%d'
				",
					array(
						$_POST['page_title'],
						$_POST['page_url'],
						$_POST['page_tagline'],
						$_POST['page_content'],
						$_POST['page_model'],
						$_POST['page_id']
					)
				);
				
				if($con->affectedRows() == 1){
					$html .= '<div class="warning success">'.tr("La page a été modifiée.").'</div>';
				} else {
					$html .= '<div class="warning warn">'.tr("Vous n'avez rien changé.").'</div>';
				}
			
			$con->close();
			unset($con);
			$_GET['id'] = $_POST['page_id'];
			
		} else {
			$html .= '<div class="warning">'.tr("Le champ 'Titre' ne doit pas être vide !").'</div>';
		}
	}
	
	$html .= '<h2 class="admin-title">'.tr("Modifier la page").'</h2>';

	if(!empty($_GET['id'])){
	
		$con = new SystemData($CONFIG);
		$result = $con->query("SELECT id, title, url, tagline, section, content, model FROM ".$CONFIG['prefix']."cms_articles WHERE id='".$_GET['id']."' LIMIT 1");
		
		$PageInfo = outputHtml(mysql_fetch_assoc($result));

		$html .= '<form action="'.$CONFIG["this_url"].'" method="post" style="font-style:normal;">
				<input type="hidden" name="page_id" value="'.$_GET['id'].'" />
			
				<input type="text" name="page_title" class="big-input required" value="'.$PageInfo['title'].'" />
				
				<p>
				<a id="show-options-newpage" href="#">'.tr("Plus d'options").'</a>
				</p>
				
				<div id="more-options-newpage" class="hidden">
					
					<label style="margin:0px 0px 3px 0px;">'.tr("URL unique").'</label>
					<input type="text" name="page_url" value="'.$PageInfo['url'].'" /><small>.html</small>
					
					<label>'.tr("Sous titre").'</label>
					<input type="text" name="page_tagline" value="'.$PageInfo['tagline'].'" />
					
					<label>'.tr("Modèle de page").'</label>
					<input id="page-model-entry" type="text" name="page_model" value="'.$PageInfo['model'].'" class="required" />
						
					<noscript>'.tr("Javascript est désactivé : vous ne pourrez pas choisir de modèle de page automatiquement").'<br /></noscript>
					<a  href="#" id="mini-dropdown">'.tr("Voir les modèles de pages disponibles").'</a>
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
							$html .= '<div class="warning warn">'.tr("Impossible de charger les modèles de page.").'</div>';
						}
					$html .= '</div>

				</div>
			
			<br />
			<textarea id="wisig-editor" class="round" name="page_content" rows="20" cols="120">'.$PageInfo["content"].'</textarea>
			<br />
			<input type="submit" class="submit" name="page_submit" value="'.tr("Enregistrer les modifications").'" />
			</form>';
		
		$con->close();
	
	} else {
		include("errors/404.html");
		exit();
	}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, true, $_GET['m'], $CONFIG, true, array(
	"title" => tr("Modifier la page")
));
?>