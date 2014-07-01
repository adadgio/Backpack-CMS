<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");;

	// Suppression d'un nouveau compte
	if(isset($_GET["action"]) && $_GET["action"] == "delete" && is_numeric($_GET["user"])){
		if($_GET["user"] != $_SESSION["id"]){
			$con = new SystemData($CONFIG);
			$delete = $con->query("DELETE FROM ".$CONFIG['prefix']."cms_accounts WHERE id = '".mysql_real_escape_string($_GET["user"])."' AND id <> '".mysql_real_escape_string($_SESSION["id"])."' LIMIT 1");
			$con->close();
		} else {
			$html .= '<div class="warning warn">'.tr("Vous ne pouvez pas supprimer votre propre compte.").'</div>';
		}
	}
	
	// Ajout d'un nouveau compte
	if(isset($_POST['new_user']) && isset($_POST['new_password'])){
		if(!empty($_POST['new_user']) && !empty($_POST['new_password']) && !empty($_POST['new_password_confirm'])){
		
			if($_POST['new_password'] == $_POST['new_password_confirm']){
				$con = new SystemData($CONFIG);
				$check = $con->query("SELECT id FROM ".$CONFIG['prefix']."cms_accounts WHERE login = '".$_POST['new_user']."'");
				
				if(mysql_num_rows($check) == 0){
					
					$add = $con->query("INSERT INTO ".$CONFIG['prefix']."cms_accounts VALUES('','".$_POST['new_user']."','".sha1($_POST['new_password'])."')");
					if(mysql_insert_id()==0){
						$html .= '<div class="warning error">'.tr("Une erreur inconnue est survenue pendant la création du compte.").'</div>';
					} else {
						$html .= '<div class="warning success">'.tr("Le nouveau compte a été crée.").'</div>';
					}
					
				} else {
				$html .= '<div class="warning warn">'.tr("Ce compte existe déjà.").'</div>';
				}
				
				$con->close();
			} else {
				$html .= '<div class="warning warn">'.tr("Les deux mot de passe ne correspondent pas.").'</div>';
			}
			
		} else {
			$html .= '<div class="warning warn">'.tr("Tous les champs ne sont pas remplis.").'</div>';
		}
	}
	
	// Changement de mot de passe
	if(isset($_POST["a_newpassword"]) && isset($_POST["a_newpasswordconfirm"])){
		if(!empty($_POST["a_newpassword"]) && !empty($_POST["a_newpasswordconfirm"])){
			if($_POST["a_newpassword"] == $_POST["a_newpasswordconfirm"]){
				$con = new SystemData($CONFIG);
				$changePassword = $con->query("UPDATE ".$CONFIG['prefix']."cms_accounts SET password = '".mysql_real_escape_string(sha1($_POST["a_newpassword"]))."' WHERE id = '".$_SESSION["id"]."'");
				$html .= '<div class="warning success">'.tr("Votre mot de passe a bien été changé.").'</div>';
				$con->close();
			} else {
				$html .= '<div class="warning warn">'.tr("Les deux mot de passe ne correspondent pas.").'</div>';
			}
		} else {
			$html .= '<div class="warning warn">'.tr("Tous les champs ne sont pas remplis.").'</div>';
		}
	}

	
	// Début du HTML
	$html .= '<h2 class="admin-title">'.tr("Gestion des comptes utilisateurs").'</h2>';

	// Liste des utilisateurs
	$html .= '<p>';
	$html .= '<table class="admin_table">';
		$con = new SystemData($CONFIG);
		$users = $con->query("SELECT id, login FROM ".$CONFIG['prefix']."cms_accounts");
		while($row = mysql_fetch_assoc($users)){
			// On ne peut supprimer que l'utilisateur non connecté
			if($_SESSION["id"] != $row["id"]) {
				$deleteLink = '<a class="account_delete_confirm" href="accounts?m=m6&action=delete&user='.$row["id"].'" onclick="if(!confirm(\''.tr("Êtes vous certain de vouloir supprime définitivement ce compte ?").'\')) return false;">'.tr("Supprimer ce compte").'</a>';
			} else {
				$deleteLink = '<strong>'.tr("Actuellement connecté").'</strong>';
			}
			$html .= '<tr>
				<td>'.$row["login"].'&nbsp;&nbsp;&nbsp;</td>
				<td><small>'.$deleteLink.'</small></td>
			</tr>';
		}
		$con->close();
	$html .= '</table>';
	$html .= '</p>';
	
	
	$html .= '<h2 class="admin-title">'.tr("Ajouter un compte").'</h2>';
	
	$html .= '<form action="accounts?m=m6" method="post">';
	$html .= '<table class="form">
				<tr>
					<td>
					'.tr("Identifiant").' ('.tr("Email").')
					</td>
					<td>
					<input type="text" name="new_user" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Mot de passe").'
					</td>
					<td>
					<input type="password" name="new_password" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Confirmation du mot de passe").'
					</td>
					<td>
					<input type="password" name="new_password_confirm" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
					<input class="submit" type="submit" name="add_user" value="'.tr("Créer le compte").'" />
					</td>
				</tr>
		</table>
		
		</form>';
		
	$html .= '<h2 class="admin-title">'.tr("Changer mon mot de passe").'</h2>';
	// Pour changer mon mot de passe ou mon email
	$html .= '<p><a href="accounts?m=m6" id="change_password_link">'.tr("Changer mon mot de passe").'</a></p>
		<div id="change_password_box" style="display:none">
			<form action="accounts?m=m6" method="post">
				<table class="form">
					<tr>
						<td>'.tr("Nouveau mot de passe").'</td>
						<td><input type="text" name="a_newpassword" /></td>
					</tr>
					<tr>
						<td>'.tr("Confirmer le mot de passe").'</td>
						<td><input type="text" name="a_newpasswordconfirm" /></td>
					</tr><tr>
						<td></td>
						<td><input type="submit" name="try_changepassword" value="'.tr("Modifier le mot de passe").'" /></td>
					</tr>
				</table>
			</form>
		</div>';
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m'], $CONFIG, true);
?>