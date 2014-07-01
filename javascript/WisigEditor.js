tinyMCE.init({
	// Basic options
	language : "fr",
	mode : "exact",
	elements : "wisig-editor", // Textarea target id
	theme : "advanced",
	skin : "o2k7",
	skin_variant : "silver",
	plugins : "save,table,fullscreen,contextmenu",
	
	// Theme options
	theme_advanced_buttons1 : "save,|,bold,italic,underline,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,blockquote",
	theme_advanced_buttons2 : "undo,redo,removeformat,ltr,rtl,|,outdent,indent,|,forecolor,backcolor,|,link,unlink,image,cleanup,code,|,visualaid,fullscreen",
	theme_advanced_buttons3 : "tablecontrols",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_resizing : false,
	
	// Format options
	formats : {
		alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
		aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
		alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
		alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
		/*
		bold : {inline : 'span', 'classes' : 'bold'},
		italic : {inline : 'span', 'classes' : 'italic'},
		underline : {inline : 'span', 'classes' : 'underline', exact : true},
		strikethrough : {inline : 'del'}
		*/
	},
	
	// Matching your CSS with the editor (fix auto img margin with this)
	content_css : "fix/tinyMCECustom.css"
});