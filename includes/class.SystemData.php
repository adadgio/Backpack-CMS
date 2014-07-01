<?php
////////////////////////////////////////////////////////////////////////////////////////
// Class: SystemData
// Purpose: Connect to a database, MySQL version
///////////////////////////////////////////////////////////////////////////////////////

class SystemData {
    var $theQuery; //Requête courante
    var $link; // Ressource connection mySQL
	var $prefix; // Préfix des tables dans la config
	var $folder; // Dossier d'installation de Backpack dans la config
	var $template; // Template dans la base de donnée
	var $Errors;
	
	/* Other custom settings */
	var $CustomSettings = array();

    //*** Function: SystemData, Purpose: Connect to the database *** /
    function SystemData($CONFIG){

		$this->prefix = $CONFIG['prefix'];
		$this->folder = $CONFIG['install_folder'];
		
        // Connect to the database
        $this->link = @mysql_connect($CONFIG['dbhost'], $CONFIG['dbusername'], $CONFIG['dbpassword']);
        @mysql_select_db($CONFIG['dbname']);
    }
	
	/* Find and return any setting with a connection opened in cms_site */
	function getSetting($string){
		if($this->link){
			$prefix = $this->prefix;
			$request = $this->query("SELECT value FROM ".$prefix."cms_site WHERE id = '".mysql_real_escape_string($string)."' LIMIT 1");
			if(mysql_num_rows($request) == 1){
				$template = mysql_fetch_assoc($request);
				return $template["value"];
			} else {
				return false;
			}
		} else {
		return false;
		}
	}
	
	function addSettings($Settings = array()){
		if(is_array($Settings)){
			if(isset($Settings['NavMenuSettings'])){
				$this->CustomSettings['NavMenuSettings'] = $Settings['NavMenuSettings'];
			}
		}
	}

    //*** Function: query, Purpose: Execute a database query : DEPRECIATED !!! *** /
    function query($query) {
		// This function is DEPRECIATED !!!
		if($this->link){
			$this->theQuery = $query;
			return mysql_query($query, $this->link);
		} else {
		exit("Database connection error.");
		}
    }
	
	function sqlQuery($rqt, $params = array()){
		if($this->link){
			if(is_array($params)){
			
				if(count($params) > 0){
					// Filtrage des valeurs passées en paramètres
					foreach($params as $key => $value){ $params[$key] = mysql_real_escape_string($value); }
					
					// Construction et passage de la requête
					array_unshift($params, $rqt);
					$okQuery = call_user_func_array("sprintf", $params);
					$this->theQuery = mysql_query( $okQuery , $this->link );
					return $this->theQuery;
				} else {
					$this->theQuery = mysql_query($rqt, $this->link);
					return $this->theQuery;
				}
				
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function numRows(){
		return mysql_num_rows($this->theQuery);
	}
	
	function affectedRows(){
		return mysql_affected_rows($this->link);
	}

	//*** Function: fetchAssoc, Purpose: get named-keyed array of query result ***/
	function fetchAssoc(){
		return mysql_fetch_assoc($this->theQuery);
	}

    //*** Function: close, Purpose: Close the connection *** /
    function close(){
        @mysql_close();
    }
	
	//*** Function GetPLugins and replaces plugin by plugin content ***/
	function parsePlugin($page_text){
		$pattern = '#\[:plugin:(.+):]#i';
		$chunks = preg_split($pattern,$page_text);
		preg_match_all($pattern,$page_text,$matches);
		$page = $chunks[0];
			foreach($matches[1] as $key => $value){
				$converted_var = $this->findPlugin($value);
				$page .= $converted_var.$chunks[($key+1)];
			}
		return $page;
	}
	
	function findPlugin($name){
		$plugin_path = 'plugins/'.$name.'/index.php';
		if(@file_exists($plugin_path)){
			include($plugin_path);
			if(function_exists($name)){
			
				$val = call_user_func($name);
				return $val;
				
			} else {
			return '[Missing or wrong format plugin]';
			}
		} else {
			return '[Missing or wrong format plugin]';
		}
	}
	
	/* Parses the page and return the values from database */
	function parsePage($template, $node, $page_type = "home"){
	
		// On enregistre le template car on en aura besoin partout
		$this->template = $template;
		
		
	
		if(empty($page_type)){$page_type = 'home';}
	
		$page = '';
		$pattern = "#{{(.+)}}#i";
		$home = $template.'/'.$page_type.'.html';
		
		if(file_exists($home)){
			
			$data = @file_get_contents($home);
			if($data){
				
				$chunks = preg_split($pattern,$data);
				preg_match_all($pattern,$data,$matches);
				
				$page = $chunks[0];
				
					foreach($matches[1] as $key => $value){
						$converted_var = $this->convertVar($value,$node);
						$page .= $converted_var.$chunks[($key+1)];
					}
					
				// $page .= end($chunks);
				return $page;

			} else {
				exit('Template error in '.$home);
			}
		} else {
			exit('The template "'.$page_type.'.html" does not exist in the template folder /'.$template);
		}
		
	}
	
	function convertVar($string, $node){
		$output = "";
		
		if($string == "PageContent"){
			$result = $this->query("SELECT tagline, content FROM ".$this->prefix."cms_articles WHERE id='".$node."'");
			
			if(mysql_num_rows($result) == 1){
				$res = mysql_fetch_assoc($result);
				return '<div id="tagline">'.$res['tagline'].'</div>'.$this->parsePlugin($res['content']);
			} else {
				return 'Erreur 404: la page que vous recherchez est introuvable.';
			}
			
		} else if($string == "PageTitle") {
			
			// Inclusion du titre de la page
			$result = $this->query("SELECT title FROM ".$this->prefix."cms_articles WHERE id='".$node."'");
				if(mysql_num_rows($result)==1){
					$res = mysql_fetch_assoc($result);
					return $res["title"];
				} else {
					return "";
				}
			
		} else if($string == "PageTitleCustom") {
			
			// Inclusion du titre de la page
			$result = $this->query("SELECT title FROM ".$this->prefix."cms_articles WHERE id='".$node."'");
				if(mysql_num_rows($result)==1){
					$res = mysql_fetch_assoc($result);
					
					// Une méthode customisée pour colorisé la première lettre du titre du site
					$returnTitle = preg_replace("#^([a-zA-Z0-9]){1}(.*)$#i", '<span class="custom-title-first-letter">$1</span>$2', $res["title"]);
					return $returnTitle;
				} else {
					return "";
				}
			
		} else if($string == "SideBar"){
			
			return $this->orderMenuPositions();	
			
		} else if($string == "SiteFooter"){
			
			$footer = $this->query("SELECT value FROM ".$this->prefix."cms_site WHERE id='".$string."'");
			$footer_text = mysql_fetch_assoc($footer);
			return $footer_text['value'];
			
		} else if($string == "TabTitle"){
		
				// Trouve le <title> de la page courante et l'affiche (TabTitle)
				$result = $this->query("SELECT title FROM ".$this->prefix."cms_articles WHERE id='".$node."'");
				if(mysql_num_rows($result)==1){
				$res = mysql_fetch_assoc($result);
				return $res['title'];
			} else {
				return 'Backpack CMS (change this)';
			}
			
		} else if($string == "SiteHeader"){
		
			// Trouve le titre du site et l'affiche (SiteHeader)
			$header = $this->query("SELECT value FROM ".$this->prefix."cms_site WHERE id='".$string."'");
			$header_text = mysql_fetch_assoc($header);
			return $header_text['value'];
			
		} else if($string == "SiteMoto"){
		
			$query = $this->query("SELECT value FROM ".$this->prefix."cms_site WHERE id='".$string."'");
			$res = mysql_fetch_assoc($query);
			return $res['value'];
			
		} else if(preg_match( "#AutoStyle:(.+)#i", $string, $CustomMatch)){
			return $this->template.'/'.$CustomMatch[1];
			
		} else {
		
			if(preg_match( "#CustomHtml:(.+)#", $string, $CustomMatch)){
				$TemplatePath = $this->template;
				$SiteFolder = $this->folder;
				$IncludeFile = $_SERVER['DOCUMENT_ROOT']."/".$SiteFolder."/".$TemplatePath."/".$CustomMatch[1].".html";
					// Lecture du ficher et on retourne le HTML correspondant
					if($FileContent = @file_get_contents($IncludeFile)){
						return $FileContent;
					} else {
						return "Impossible de lire le menu.";
					}
			} else {
				return "Impossible de charger le menu.";
			}
			
		}
	}

	// Sub function...
	function orderMenuPositions($currentPosition = 0){
		$menuQuery = $this->sqlQuery("SELECT id, title, url, section, menu_pos, menu_sub_pos FROM %scms_articles WHERE menu_sub_pos = '%d' AND menu_pos <> '0' ORDER BY menu_pos",
			array(
				$this->prefix,
				$currentPosition)
			);
		if(mysql_num_rows($menuQuery) > 0){
			$menuHtml .= '<ul>';
				while($menuRow = mysql_fetch_assoc($menuQuery)){
					if($menuRow["section"] == "yes"){
						$menuHtml .= '<li class="menu-separator">'.$menuRow["title"];
					} else {
						if(!empty($menuRow["url"])){
							$menuHtml .= '<li><a href="'.$menuRow["url"].'.html">'.$menuRow["title"].'</a>';
						} else {
							$menuHtml .= '<li><a href="index.php?node='.$menuRow["id"].'">'.$menuRow["title"].'</a>';
						}
					}
					$menuHtml .= $this->orderMenuPositions($menuRow["id"]);
					$menuHtml .= '</li>';
				}
			$menuHtml .= '</ul>';
		}
		return $menuHtml;
	}
	
}
?>