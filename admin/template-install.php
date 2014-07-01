<?php
/*
require_once("../config.php");
$TemplateName = "ProutTemplate.zip";
$TemplateUrl = "http://www.dockydocs.com/ProutTemplate.zip";

$TemplateData = file_get_contents($TemplateUrl);
$TemplateFullPath = $_SERVER["DOCUMENT_ROOT"]."/templates/$TemplateName";

file_put_contents($_SERVER["DOCUMENT_ROOT"]."/templates/$TemplateName", $TemplateData);

system("unzip ".$_SERVER["DOCUMENT_ROOT"]."/templates/$TemplateName -d ".$_SERVER["DOCUMENT_ROOT"]."/templates/");

unlink($_SERVER["DOCUMENT_ROOT"]."/templates/$TemplateName");
*/