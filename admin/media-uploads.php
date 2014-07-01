<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	// If a new upload was started
	if(isset($_POST["try_upload"])){
	
		$FileName = $_FILES["new_upload"]["name"];
		$FileInfo = pathinfo($FileName);
		$FileExt = $FileInfo["extension"];
	
		if(in_array($FileExt,$AuthExt)) {
			
			$NewPath = sha1($FileName).'.'.$FileExt;
			if(move_uploaded_file($_FILES["new_upload"]["tmp_name"], "../images/uploads/".$NewPath)) {
				
				$up = new SystemData($CONFIG);
				$insert = $up->sqlQuery("INSERT INTO %scms_uploads VALUES('%s','%s')",
					array(
						$CONFIG["prefix"],
						$NewPath,
						$FileName
					));
					if(mysql_affected_rows() == 1){
						$html .= '<div class="warning success">'.tr("Le fichier a bien été enregistré.").'</div>';
					} else {
						$html .= '<div class="warning error">'.tr("Une erreur est survenue pendant l'enregistrement du fichier dans la base de donnée.").'</div>';
					}
				
				$up->close();
				
			} else {
			$html .= '<div class="warning error">'.tr("Une erreur est survenue pendant le chargement du fichier.").'</div>';
			}
			
		} else {
			$html .= '<div class="warning warn">'.tr("Ce type de fichier n'est pas autorisé.").'</div>';
		}
	}
	
	// If a media is to be deleted
	if(isset($_GET["media"]) && $_GET["action"] == "delete"){
		$del = new SystemData($CONFIG);
		$rqt = $del->sqlQuery("DELETE FROM %scms_uploads WHERE upload_id = '%s' LIMIT 1",
			array(
				$CONFIG["prefix"],
				$_GET["media"]
			));
		
		if($del->affectedRows() == 1){
			if(@unlink("../images/uploads/".$_GET["media"])){
				$html .= '<div class="warning success">'.tr("Le fichier a bien été supprimé.").'</div>';
			} else {
				$html .= '<div class="warning error">'.tr("Une erreur est survenue pendant la suppression du fichier.").'</div>';
			}
		} else {
			$html .= '<div class="warning error">'.tr("Une erreur est survenue pendant la suppression du fichier de la base de donnée.").'</div>';
		}
		
		$del->close();
	}

	$html .= '<h2 class="admin-title">'.tr("Media et images").'</h2>';
	
	$html .= '<p>'.tr("Chargez une image ou un fichier destiné à être utiliser sur votre site, ou dans l'une de vos pages.").'</p>';
	
	// Getting list of uploads
	$con = new SystemData($CONFIG);
	$uploads = $con->query("SELECT upload_id, upload_name FROM ".$CONFIG['prefix']."cms_uploads");
		
		while($row = mysql_fetch_assoc($uploads)){
			$path = 'http://'.$_SERVER['SERVER_NAME'].'/'.$CONFIG['install_folder'].'images/uploads/'.$row['upload_id'];
			
				$html .= '<p style="margin-bottom:15px;"><a href="'.$path.'" target="_blank">'.$row['upload_name'].'</a>&nbsp;&nbsp;
				<b><a href="media-uploads.php?m=m7&action=delete&media='.$row["upload_id"].'">'.tr("Supprimer").'</a></b><br />
				<small>'.$path.'</small></p>';
			
		}
		
	$con->close();
	
	$html .= '<h3 class="admin-title">Upload a new image</h3>';
	$html .= '<form action="media-uploads.php?m=m7" method="post" enctype="multipart/form-data">
		<input type="file" name="new_upload" /><br /><br />
		<input type="submit" class="submit" name="try_upload" value="'.tr("Enregistrer le fichier").'" />
	</form>';
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html,$lang,true,$_GET['m']);
?>