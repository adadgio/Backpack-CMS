<?php
require_once("../config.php");
require_once("../includes/inc.SystemAuth.php");
require_once("../includes/inc.AdminLayout.php");

	$html .= '<h2 class="admin-title">'.tr("Pense-bête").'</h2>';

	$html .= '<p>Retrouvez ici toutes vos informations de connexion, les liens vers votre boîte email, etc.</p>';
	
	$html .= '<h3>Mes comptes emails</h3>';
	
	$html .= '<p>Vous possédez deux boîtes emails accessibles à l\'adresse <a href="https://mail.google.com/" target="_blank">https://mail.google.com/</a>. Sur cette adresse, vous possédez deux boîtes emails dont les emails (ie. Utilisateurs) sont : </p>';
	
	$html .= '<ul>
			<li><i>arnaud.laforge@arnosteo-formation.fr</i> [Mot de passe : <b>arnosteo44</b>]</li>
			<!-- <li><i>arnosteo@arnosteo-formation.fr</i> [Mot de passe : <b>arnosteo44</b>]</li> -->
		</ul>';
		
	$html .= '<h3>Suivi des visites sur arnosteo-formation-bebe.fr</h3>';
	$html .= 'Se rendre sur <a href="https://www.google.com/analytics/reporting/?reset=1&id=47517726&pdr=20110524-20110623" target="_blank">Google Analytics</a> et se connecter avec ';
	$html .= '<ul>
				<li><i>arnaud.laforge@arnosteo-formation.fr</i> [Mot de passe : <b>arnosteo44</b>]</li>
			</ul>';
		
	$html .= '<h3>Contact et support</h3>';
	
	$html .= '<p>
		Mot de passe perdu ou problèmes de connexino ? Ecrivez à <a href="mailto:support@dockydocs.com">support@dockydocs.com</a>.
	</p>';
	
	
header("Content-Type: text/html; charset=utf-8");
adminHTML($html, $lang, false, $_GET['m']);
?>