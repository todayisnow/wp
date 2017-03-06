jQuery(window).load(function(){
	
	jQuery('form#dwqa-search').find("input.ui-autocomplete-input").each(function(ev){
		if(!jQuery(this).val()) { 
     		jQuery(this).attr("placeholder", "Search for a Comparison");
  		}
  	});
	
	var usersList = JSON.parse(users.list);
	var pattern = /\/\?user=([A-Za-z\d\.\-_%\+]+)$/i;

	jQuery.ajaxSetup({
		beforeSend: function(jqXHR, settings) {
			if (settings.data.indexOf("action=dwqa-auto-suggest-search-result") == 0) {
				var originalCallback = settings.success;
				settings.success = function( data ) {
					if(data.success == false){
						data = {"success": true, data: Array({"title": "Comparison does not exist. Request it now?", "url": qa.q_link})};
					}
					return originalCallback( data );
				}
			}
			return true;
    	}
,
		complete: function(jqXHR, settings) {
			$autoCompleteUl = jQuery("ul.dwqa-autocomplete").children("li");
			if($autoCompleteUl.length == 1 && $autoCompleteUl.text() === "Comparison does not exist. Request it now?"){
				$autoCompleteUl.addClass("not-found");
			}
			return true;
    	}
	});

	jQuery("div.dwqa-questions-list").find("div.dwqa-question-item").each(function(index){
		var a = jQuery(this).find("div.dwqa-question-meta span a:not(span.dwqa-question-category a)");
		var href = a.attr("href");
		var matches = pattern.exec(href);
		if (matches != null && usersList[matches[1]]) {
			a.after('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>');
		}
	});
});