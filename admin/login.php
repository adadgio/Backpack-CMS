<?php
session_start();
session_regenerate_id();
require_once("../config.php");
require_once("../includes/inc.AdminLayout.php");

	if(isset($_POST['login']) && isset($_POST['password'])){
	
		$CleanInput = cleanInput($_POST);
	
		if(!empty($CleanInput['login']) && !empty($CleanInput['password'])){
			
			$con = new SystemData($CONFIG);
			$log = $con->sqlQuery("SELECT id, login, password FROM ".$CONFIG['prefix']."cms_accounts WHERE login='%s' LIMIT 1", array($CleanInput["login"]));
				if($con->numRows() == 1){
					$user = $con->fetchAssoc();
					// Now check his password
						if($user['password'] == sha1($CleanInput['password'])){
							// On enregistre les variables de sessions et on redirige vers l'index
							$_SESSION = array(
								'valid' => true,
								'id' => $user['id'],
								'login' => $user['login'],
							);
							header('Location: index?m=m1');
							exit();
							
						} else {
							$html .= '<div class="warning warn login-warning">'.tr("Votre mot de passe n'est pas valide.").'</div>';
						}
					
				} else {
					$html .= '<div class="warning error login-warning">'.tr("Votre identifiant n'est pas reconnu.").'</div>';
				}
				
			$con->close();
			
		} else {
			$html .= '<div class="warning warn login-warning">'.tr("Tous les champs ne sont pas correctement remplis.").'</div>';
		}
	}
	
	if($_SESSION['valid']==true){
		header("Location: index");
		exit();
	}
	
	$html .= '<div id="login-content" class="rounded box-shadow">';
		$html .= '<h2 class="admin-title">'.tr("Connexion").'</h2>';
		$html .= '<form id="login-form" action="login.php" method="post">
			<label>'.tr("Identifiant").' ('.tr("Email").')</label>
			<input type="text" name="login" value="'.$CleanInput['login'].'" />
			<label>'.tr("Mot de passe").'</label>
			<input type="password" name="password" />
			<input type="submit" class="submit" name="try_login" value="'.tr("Connexion").'" />
		</form>';
	$html .= '</div>';

header("Content-Type: text/html; charset=utf-8");
adminHTML($html,$lang,false);
?>