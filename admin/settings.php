<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	$html .= '<h2 class="admin-title">'.tr("Réglages et paramètres").'</h2>';

	// If values are changed...
	if(!empty($_POST['site_name']) && !empty($_POST['site_template'])){
		$changes = '';
		$con = new SystemData($CONFIG);
		$con->query("UPDATE ".$CONFIG['prefix']."cms_site SET value='".mysql_real_escape_string($_POST['site_name'])."' WHERE id='SiteHeader' LIMIT 1");
			if(mysql_affected_rows()==1){$changes = '<div class="warning success">'.tr("Le titre du site a été modifié.").'</div>';}
		$con->query("UPDATE ".$CONFIG['prefix']."cms_site SET value='".mysql_real_escape_string($_POST['site_template'])."' WHERE id='templ' LIMIT 1");
			if(mysql_affected_rows()==1){$changes = '<div class="warning success">'.tr("Le gabarit du site a été modifié.").'</div>';}
		$con->query("UPDATE ".$CONFIG['prefix']."cms_site SET value='".mysql_real_escape_string($_POST['site_moto'])."' WHERE id='SiteMoto' LIMIT 1");
			if(mysql_affected_rows()==1){$changes = '<div class="warning success">'.tr("Le slogan dun site a été modifié.").'</div>';}
		$con->query("UPDATE ".$CONFIG['prefix']."cms_site SET value='".mysql_real_escape_string($_POST['site_footer'])."' WHERE id='SiteFooter' LIMIT 1");
			if(mysql_affected_rows()==1){$changes = '<div class="warning success">'.tr("Le pied de page du site a été modifié.").'</div>';}
		if(empty($changes)){$changes = '<div class="warning warn">'.tr("Vous n'avez effectué aucun changement.").'</div>';}
		$html .= $changes;
	}

	// Getting site settings...
	$settings = array();
	$con = new SystemData($CONFIG);
	$currentTemplate = $con->getSetting("templ");
	$result = $con->query("SELECT id, value FROM ".$CONFIG['prefix']."cms_site");
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
			$k = $row['id'];
			$settings[$k] = $row['value'];
		}
	$con->close();
	
	// We get all the css files inside the /template folder
	$dir = '../templates/';
	$opt = '';
	if($dh = opendir($dir)) {
        while(($file = readdir($dh))!==false) {
			
            if($file!='.' && $file!='..'){
				$subdir = $dir.$file;
				
				if(is_dir($subdir)){
					$dirname = substr($subdir,3);
					$opt .= '<option value="'.$dirname.'"'.selected($dirname,$settings['templ']).'>'.$dirname.'</option>';
				}

			}

        }
        closedir($dh);
    }

	$html .= '<p>'.tr("Votre site est habillé du gabarit graphique :").' <i>/'.$currentTemplate.'</i>. '.tr("Vous pouvez changer de gabarit ici, ou modifier le gabarit actuel dans le menu <i>Changer l'apparence</i>.").'</p>';
	
	$html .= '<form action="'.$_SERVER['PHP_SELF'].'?m=m5" method="post">
	
		<table class="form">
				<tr>
					<td>
					'.tr("Titre du site").'
					</td>
					<td>
					<input type="text" name="site_name" value="'.$settings['SiteHeader'].'" /> <span class="info"></span>
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Pied de page").'
					</td>
					<td>
					<input type="text" name="site_footer" value="'.$settings['SiteFooter'].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Slogan").'
					</td>
					<td>
					<input type="text" name="site_moto" value="'.$settings['SiteMoto'].'" />
					</td>
				</tr>
				<tr>
					<td>
					'.tr("Gabarit").'
					</td>
					<td>
						<select name="site_template">
						'.$opt.'
						</select>&nbsp;<a href="template-install">'.tr("Télécharger plus de gabarits").'</a>
					</td>
				</tr>
				<tr>
					<td>
					
					</td>
					<td>
					<input class="submit" type="submit" name="submit_settings" value="'.tr("Enregistrer les modifications").'" />
					</td>
				</tr>
		</table>
	</form>';
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>