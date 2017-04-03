jQuery(document).ready(function(){
	if(typeof dwqa === 'undefined'){
		dwqa = {ajax_url: "https://www.comparlo.com/wp/wp-admin/admin-ajax.php"};
	}
});

jQuery(window).load(function(){
	
	jQuery('form#dwqa-search').find("input.ui-autocomplete-input").each(function(ev){
		if(!jQuery(this).val()) { 
     		jQuery(this).attr("placeholder", "Search for a Comparison");
  		}
  	});
  	
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
});