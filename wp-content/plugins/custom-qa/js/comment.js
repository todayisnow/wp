jQuery(window).load(function(){
	var data = '';
	
	try{
		data = tinyMCE.activeEditor.getContent({format : 'text'}).trim();
	}
	catch(e){
		data = jQuery("#dwqa-custom-content-editor").val();
	}
	
	jQuery('#wp-dwqa-custom-content-editor-wrap').after("<textarea id='comment_edit' class='i_comment_content'>"+data+"</textarea>");
	
	jQuery(".dwqa-content-edit-form").submit(function(){
		var commentInput = jQuery("#comment_edit").val();
		if(commentInput.length==0){
			showError("Comment field is empty");
		}
		else{
			tinyMCE.activeEditor.setContent(commentInput);
			jQuery("#dwqa-custom-content-editor").val(commentInput);
			return true;
		}
		return false;
	});
	
});

function showError(msg){
	$error = jQuery("p.dwqa-alert-error");
	$form = jQuery("form.dwqa-content-edit-form");
	if($error.length){
		$error.html(msg);
	}
	else{
		$form.before("<p class='dwqa-alert dwqa-alert-error'>"+msg+"<p>");
	}
}