<?php
// The function name MUST be the PluginName
	function ContactForm(){
		// Process what you want here.
		$plugin .= '<script type="text/javascript">
			function IopIop(){
					$("#result").html("Le plugin marche à merveille...");
			}
		</script>';
		
		$plugin .= '<form id="contact_form" action="" method="post" onsubmit="IopIop();return false;">
			<input type="text" name="test" /><br />
			<input type="submit" name="try_contact" value="Envoyer" />
		</form>';
		
		$plugin .= '<div id="result">Test...</div>';
		
		return utf8_encode($plugin);
	}
?>