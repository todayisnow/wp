
jQuery(window).load(function(){


	var usersList = JSON.parse(users.list);
	var pattern = /\/([A-Za-z\d\.\-_%\+]+)$/i;

	jQuery("div.dwqa-questions-list").find("div.dwqa-question-item").each(function(index){
		var a = jQuery(this).find("div.dwqa-question-meta span a:not(span.dwqa-question-category a)");
		var href = a.attr("href");
		var matches = pattern.exec(href);
		if (matches != null && usersList[matches[1]]) {
			a.after('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>');
		}
	});

});