<?php
session_start();
session_regenerate_id();

$_SESSION = array();
unset($_SESSION);
header('Location: login');
exit();
?>