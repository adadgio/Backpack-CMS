/*
	Custom scripts for BackackCMS admin
	Based on jQuery 1.2.4 min
*/
$(document).ready(function(){

	// Apparition du formulaire de changement de mot de passe dans accounts.php
	$("#change_password_link").click(function(e){
		$("#change_password_box").toggle();
		return false;
	});
	
	// Edit page et new page : permet de choisir les modèles disponibles
	$("#mini-dropdown").click(function(e){
		$("#mini-dropdown-content").toggle();
		return false;
	});
	
	// Affiche ou cache les options suplémentaire pour edit page et new page
	$("#show-options-newpage").click(function(e){
		$("#more-options-newpage").toggle("slow");
		return false;
	});
	
	// Effet gros input pour le titre d'une page (dans nouvelle page ou edit page)
	$("#new-page-title").focus(function(e){
		$(this).removeClass("tip");
		$(this).addClass("no-tip");
		var inputContent = $(this).val();
		if(inputContent != ""){$(this).val("");}
	});

});

function autoCompletePageType(Input, myVal){
	$("#"+Input).val(myVal);
	
}

function myHide(Div){
	$("#"+Div).hide();
}