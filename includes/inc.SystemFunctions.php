<?php
/* Security */
function safeOutput(){
	
}

function outputHtml($input){
	if(is_array($input)){
		foreach($input as $key => $value){
			$input[$key] = htmlspecialchars($value);
		}
		return $input;
	} else {
		return htmlspecialchars($input);
	}
}

function cleanInput($input = array(), $option = "DISPLAY"){
	switch($options){
		case "DISPLAY":
			return array_map("htmlentities", $input);
		break;
		case "LOOSE":
			return array_map("htmlentities", $input);
		break;
		case "STRICT":
			if(!get_magic_quotes_gpc()){
				return array_map("strip_tags", array_map("addslashes", $input));
			} else {
				return array_map("strip_tags", $input);
			}
		break;
		default:
			return array_map("htmlentities", $input);
		break;
	}
}

/* Other functions */

function selected($a,$b){
	if($a==$b){
		return ' selected="selected"';
	} else {
		return '';
	}
}

function get_file($file){
	include($file);
}

function findPlugins($html){
	
	// Define plugin insertions pattern
	$pattern = '#\[:(.+):\]#i';
	
		// Get the plugin and return some string (plugin executes everything)
		function exePlugin($plugin_name){
			// Check if folder and plugin exist...
			if(file_exists('plugins/'.$plugin_name.'/index.php')){
					include('plugins/'.$plugin_name.'/index.php');
				if (function_exists('plugin_'.$plugin_name)){
					$plugin_data = call_user_func('plugin_'.$plugin_name);
				} else {
					$plugin_data = '';
				}
			} else {
				$plugin_data = '';
			}
			return $plugin_data;
		}
	
	// Split the page into two part on side of the plugin
	$result = preg_split($pattern,$html);
	$split_text = count($result);
	$number_of_plugins = count($result)-1;
	
		// echo 'Number of plugins: '.$number_of_plugins.'<br />';
		// echo 'There are: '.$split_text.' page parts.';
	
	// Find the plugin name...
	preg_match_all($pattern,$html,$matches);
	
	// echo 'Plugin found: '.count($matches);
	
	// print_r($matches);
	
		$html = $result[0];
			foreach($matches[1] as $key => $value){
				$ReS = exePlugin($value);
				// Check the return format of the plugin (string or number)
				if((is_numeric($ReS) || is_string($ReS)) && !empty($ReS)){
					$html .= $ReS;
				} else {
					$html .= '<i>Plugin missing...</i>';
				}
				
				$html .= $result[($key+1)];
			}


	// Return all the Html
	return $html;
}

/** INSTALL FUNCTIONS */

function givePosts($matches){
		$key = $matches[1];
		if($key == "r_password"){
			return sha1($_POST["$key"]);
		} else {
			return $_POST["$key"];
		}
}

function createTables($Url, $conn){
  $file = @file_get_contents($Url);
  if($file === false){
	return false;
  } else {
	$requetes = explode(';', $file);
		  foreach($requetes as $request){
			// Parse de la requête
			$pattern = "/\[(.*?)\]/";
			$request = preg_replace_callback($pattern, "givePosts", $request);
			$conn->query($request);
		  }
	return true;
	}
}

function createConfig($Url){
	$fileContent = @file_get_contents($Url);
  if($fileContent === false){
	return false;
  } else {
	$filename = "config.php";
	$chmod = "0777";
	$file = @fopen($filename,'w+');
		if($file){
			// Parse content
			$NewFileContent = preg_replace_callback("/\{(.*?)\}/", "givePosts", $fileContent);
			// Write the file
			@fwrite($file, $NewFileContent);
			@fclose($file);
			return true;
		} else {
			return false;
		}
	}
}
?>