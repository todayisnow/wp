jQuery(window).load(function(){
	$qBox = jQuery("#wp-dwqa-custom-content-editor-wrap");
	$title = jQuery("input[name='question_title']");
	
	$qBox.after("<label>What is the difference between</label><br><input id='leftInput' type='text'> <label>and</label> <input id='rightInput' type='text'> <label>?</label>");
	var data = '';
	
	try{
		data = tinyMCE.activeEditor.getContent({format : 'text'}).trim();
	}
	catch(e){
		data = jQuery("#dwqa-custom-content-editor").val();
		console.log(e.message);
	}
	
	data = JSON.parse(data.trim());
	if(data.left != null){
		jQuery("#leftInput").val(data.left);
		jQuery("#rightInput").val(data.right);
	}
	else{
		jQuery("#leftInput").val(data.lhsI);
		jQuery("#rightInput").val(data.rhsI);
	}
	
	jQuery(".dwqa-content-edit-form").submit(function(){
		var leftInput = jQuery("#leftInput").val();
		var rightInput = jQuery("#rightInput").val();
		if(leftInput.length==0){
			showError("Left input is empty");
		}
		else if(rightInput.length==0){
			showError("Right input is empty");
		}
		else if(leftInput == rightInput){
			showError("Left input is equal to Right input");
		}
		else{
			var replaceStr = "What is the difference between " +leftInput+ " &amp; " +rightInput+ "?";
			var dataJson = {lhsI: leftInput, rhsI: rightInput};

			tinyMCE.activeEditor.setContent(JSON.stringify(dataJson));
			jQuery("#dwqa-custom-content-editor").val(JSON.stringify(dataJson));
			$title.val(replaceStr);

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