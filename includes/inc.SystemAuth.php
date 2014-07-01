<?php
session_start();
session_regenerate_id();

	if($_SESSION['valid']!=true){
		header('Location: login.php');
		exit();
	}
?>