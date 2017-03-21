jQuery(document).ready(function(){
	$qBox = jQuery("#wp-question-content-wrap");
	$title = jQuery(".dwqa-search");
	jQuery('.dwqa-answers-login').hide().after("<div class='logged_out_i'><a href='http://www.comparlo.com/wp/wp-login.php' class='login_i'>Log-in to Request Comparison</a> <a href='http://www.comparlo.com/wp/wp-login.php?action=register' class='reg_i'>Register</a></div>");
	$qBox.after("<label>What is the difference between</label><br><input id='leftInput' type='text' maxlength='70'> <label>and</label> <input id='rightInput' type='text' maxlength='70'> <label>?</label>");
	
	jQuery(".dwqa-content-ask-form").submit(function(){
		var leftInput = jQuery("#leftInput").val();
		var rightInput = jQuery("#rightInput").val();
		var catSelect = jQuery("#question-category").val();
		
		if(leftInput.length==0){
			showError("Left input is empty");
		}
		else if(rightInput.length==0){
			showError("Right input is empty");
		}
		else if(leftInput == rightInput){
			showError("Left input is equal to Right input");
		}
		else if(catSelect == -1){
			showError("Please select category");
		}
		else{
			var replaceStr = "What is the difference between " +leftInput+ " and " +rightInput+ "?";
			var data = {lhsI: leftInput, rhsI: rightInput};

			tinyMCE.activeEditor.setContent(JSON.stringify(data));

			jQuery("#question-content").val(replaceStr);
			jQuery("#question-title").val(replaceStr);

			return true;
		}
		return false;
	});

});


function showError(msg){
	$error = jQuery("p.dwqa-alert-error");
	$form = jQuery("form.dwqa-content-ask-form");
	if($error.length){
		$error.html(msg);
	}
	else{
		$form.before("<p class='dwqa-alert dwqa-alert-error'>"+msg+"<p>");
	}
}