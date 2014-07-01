<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	// Changement de l'ordre des pages et sections dans le menu
	if(isset($_POST["change_order"])){
		$chan = new SystemData($CONFIG);
		foreach($_POST as $key => $value){
			$chan->sqlQuery("UPDATE ".$CONFIG['prefix']."cms_articles SET
				menu_pos = '%d'
				WHERE id = '%d'",
				array(
					0 => $value,
					1 => $key
				)
			);
		}
		$html .= '<div class="warning">'.tr("L'ordre des pages a été modifié.").'</div>';
		$chan->close();
	}
	
	// Ajout d'un nouveau séparateur de menu
	if(isset($_POST["newseparator"])){
		if(!empty($_POST["newseparator"])){
			$sep = new SystemData($CONFIG);
				$addSeparatorQuery = $sep->sqlQuery("INSERT INTO %scms_articles
					(id, title, url, tagline, section, content, menu_pos, model)
					VALUES ('', '%s', '', '', '%s', '', '%d', '')",
					array(
						$CONFIG['prefix'],
						$_POST["newseparator"],
						"yes",
						0
					)
				);
				$html .= '<div class="warning">'.tr("Le nouveau séparateur a été ajouté au menu.").'</div>';
			$sep->close();
		} else {
			$html .= '<div class="warning">'.tr("Le titre du séparateur ne peut pas être vide.").'</div>';
		}
	}
	
	// Suppression d'une page ou d'une section du menu
	if(isset($_GET['action']) && ($_GET['action'] == 'delete') && !empty($_GET['id'])){
		$con = new SystemData($CONFIG);
			$del = $con->sqlQuery("DELETE FROM ".$CONFIG['prefix']."cms_articles
				WHERE id = '%d'
				LIMIT 1",
					array(
						0 => $_GET['id']
					)
				);
			if(mysql_affected_rows()==1){
				$html .= '<div class="warning">'.tr("L'élément a bien été supprimé.").'</div>';
			} else {
				$html .= '<div class="warning">'.tr("Un erreur inattendue est survenue, la page n'a pas pu être supprimée.").'</div>';
			}
		$con->close();
	}

	$html .= '<h2 class="admin-title">'.tr("Gestion des pages").'</h2>';

	// On va chercher toutes les pages et les sections de séparation existantes
	$con = new SystemData($CONFIG);
	$result = $con->sqlQuery("SELECT id, title, tagline, section, content, menu_pos, model FROM %scms_articles ORDER BY menu_pos", array($CONFIG['prefix']));
	
	if(mysql_num_rows($result) == 0){$html .= tr("Vous n'avez encore créé aucun page.");}
	
	$html .= '<p><img src="img/add.png" width="14" height="14" alt="AddNewPage" />&nbsp;<a href="pages-new?m=m2">'.tr("Nouvelle page").'</a></p>';
	
	$select_options = '';
	
	$html .= '<p>'.tr("Paragraphe manquant").'</p>';
	
		$html .= '<form action="'.$CONFIG["this_url"].'" method="post" style="font-style:normal;">';
		$html .= '<table class="admin_table" style="width:95%;">';
		$html .= '<tr><th>'.tr("Titre de l'élément").'</th><th>'.tr("Ordre dans le menu").'</th><th>'.tr("Modification").'</th><th>'.tr("Suppression").'</th><th>'.tr("Type d'élément").'</th><th>'.tr("Modèle graphique").'</th>'.tr("Hiérarchie").'<th></th></tr>';
			while($row = $con->fetchAssoc($result)){
			
				// Si il s'agit d'un séprateur et non d'une page
						if($row["section"] == "yes"){
							
							$elementType = tr("Séparateur de menu");
							$tdClass = ' class="separator-type"';
							/*
							$editLink = '<a href="menus-edit-separator?id">'.tr("Changer le titre").'</a>';
							*/
							$editLink = '';
						} else {
							$elementType = tr("Page de contenu");
							$tdClass = '';
							$editLink = '<a href="pages-edit?id='.$row['id'].'&m='.$_GET['m'].'">'.tr("Modifier la page").'</a>';
						}
			
				$html .= '<tr>';
				$html .= '<td'.$tdClass.'>'.$row['title'].'</td>';
				$html .= '<td'.$tdClass.'><input type="text" name="'.$row['id'].'" value="'.$row['menu_pos'].'" style="width:25px;text-align:center;margin:0px;" /></td>';
				$html .= '<td'.$tdClass.'>'.$editLink.'</td>';
				$html .= '<td'.$tdClass.'><a onclick="if(confirm(\''.tr("Êtes vous sûr de vouloir supprimer définitivement cette page ?").'\')){return;}else{return false;}" href="'.$_SERVER['PHP_SELF'].'?action=delete&id='.$row['id'].'&m='.$_GET['m'].'">'.tr("Supprimer").'</a></td>';	
				$html .= '<td'.$tdClass.'><small>'.strtoupper($elementType).'</small></td>';
				$html .= '<td'.$tdClass.'><i>'.$row['model'].'</i></td>';
				$html .= '<td'.$tdClass.'>
						<select name="page_sub_order">
							<option name="page_sub_order_option" value="0">'.tr("Menu principal").'</option>
							<option>Second menu</option>
						</select>
					</td>';
				$html .= '</tr>';
			}
		$html .= '</table>';
		$html .= '<br /><input class="submit" type="submit" name="change_order" value="'.tr("Enregistrer l'ordre des pages").'">&nbsp;&nbsp;';
		$html .= '<a href="pages-manage?m=m3&separator=edit">'.tr("Nouveau séparateur de menu").'</a>';
		$html .= '</form>';
		
	$con->close();
	
		// Si on doit ajouter ou modifier un séparateur de menu
		$html .= '<br /><br />';
		if(isset($_GET["separator"]) && $_GET["separator"] == "edit"){
			$html .= '<form action="'.$CONFIG["this_url"].'" method="post"  style="font-style:normal;">';
			$html .= '<label>'.tr("Titre du séparateur").'</label>';
			$html .= '<input type="text" name="newseparator" />&nbsp;';
			$html .= '<input class="submit" type="submit" name="try_newseparator" value="'.tr("Ajouter").'">';
			$html .= '</form>';
		}
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>