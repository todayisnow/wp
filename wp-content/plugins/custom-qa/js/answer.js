class Row {
	constructor(id){
		this.id = id;
	}
	/*
		#Dev
		#Todayisnow
		#201703080412
		#category of answer only text box
		*/
	show(){
		return "<tr class='i_table_row' data-cells='2' id='row_"+this.id+"'><th class='i_label'><input type='text' id='other_"+this.id+"'  maxlength='40' class='answer-other' style='margin-top: 35px;'><span style='color:#747474'>ex: Defination</span></th><td class='i_cell_left'><textarea name='name_left_"+this.id+"' id='name_left_"+this.id+"' max_chars='500'></textarea></td><td class='i_cell_right'><textarea  name='name_right_"+this.id+"' id='name_right_"+this.id+"'  max_chars='500'></textarea></td><td class='i_options'><a alt='Delete' title='Delete' href='javascript:void(0);' onclick='del(this);' id='"+this.id+"'><i class='fa fa-times-circle' aria-hidden='true'></i></a><br><a alt='Merge cells' title='Merge cells' href='javascript:void(0);' onclick='merge(this);' id='"+this.id+"'><i class='fa fa-arrows-h' aria-hidden='true'></i></a></td></tr>";
		/*<select onchange='javascript:handel("+this.id+");' id='rowName_"+this.id+"'>
		<option value='Definition'>Definition</option>
		<option value='Pros'>Pros</option>
		<option value='Cons'>Cons</option>
		<option value='Examples'>Examples</option>
		<option value='Others'>Others</option>
		</select>
		<input type='text' id='other_"+this.id+"' style='display: none;' maxlength='40' class='answer-other' >-*/
	}
}

var videoEmbed = {
    invoke: function(){

        $('body').html(function(i, html) {
            return videoEmbed.convertMedia(html);
        });

    },
    convertMedia: function(html){
		html = html.replace(/<br\s*[\/]?>/gi, "\n");
		var pattern1 = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
        var pattern2 = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
        var pattern3 = /([-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?(?:jpg|jpeg|gif|png))/gi;

        if(pattern1.test(html)){
           var replacement = '<iframe width="100%" height="345" src="http://player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

           var html = html.replace(pattern1, replacement);
        }


        if(pattern2.test(html)){
              var replacement = '<iframe width="100%" height="345" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';
              var html = html.replace(pattern2, replacement);
        } 


        if(pattern3.test(html)){
            var replacement = '<a href="$1" target="_blank"><img class="sml" src="$1" /></a><br />';
            var html = html.replace(pattern3, replacement);
        }      
		html = html.replace(/\n/g, "<br />");
        return html;
    }
}

jQuery(document).ready(function(){
	var $cntr = 0;
	$qContent = jQuery("div.dwqa-question-content");
	$answerBox = jQuery("#wp-dwqa-answer-content-wrap");
	jQuery('.dwqa-answers-login').hide().after("<div class='logged_out_i'><a href='http://www.comparlo.com/wp/wp-login.php' class='login_i'>Log-in to Answer</a> <a href='http://www.comparlo.com/wp/wp-login.php?action=register' class='reg_i'>Register</a></div>");
	var back = new RegExp('“', 'g');
	var forw = new RegExp('”', 'g');
	var forw1 = new RegExp('″', 'g');
	
	var qInfo = $qContent.find("p").text().trim();
	qInfo = qInfo.replace(back, '"').replace(forw, '"').replace(forw1, '"').replace(/\\\\/g, "");
	qInfo = JSON.parse(qInfo);
	
	if(qInfo.lhsI == null){
		qInfo.lhsI = qInfo.left;
		qInfo.rhsI = qInfo.right;
	}
	
	$answerBox.after("<table class='i_table_answers_form' id='tbl_answers'><tr><th class='i_head_labels'></th><th class='i_head_display_left'>"+qInfo.lhsI+"</th><th class='i_head_display_right'>"+qInfo.rhsI+"</th><th class='i_head_tools'></th></tr><tr class='i_row_add'><td class='i_cell_add' colspan='4'><a alt='Add' title='Add' href='javascript:void(0);' id='addRow'><i class='fa fa-plus' aria-hidden='true'></i> Add Row</a></td></tr></table>");
	
	var $loopUsers = 0;
	var $loopComments = 0;
	var vList = JSON.parse(list.verified);
	
	q = jQuery("div.dwqa-question-item").children("div.dwqa-question-meta");
	if(vList.question_info.user_state == true){
		tmp = q.html();
		q.html( '<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
	}
	q = q.siblings("div.dwqa-comments");
	q = q.children("div.dwqa-comments-list");
	q = q.children("div.dwqa-comment");
	q = q.children("div.dwqa-comment-meta");
	
	handle_comments(q, 1, vList);
	
	is_best_answer = false;
	make_minus = false;
	if(typeof(vList.best_answer_info) != "undefined" && vList.best_answer_info !== null){
		is_best_answer = true;
		make_minus = true;
	}
	
	jQuery("div.dwqa-answer-item").each(function(indexUsers, item){
		if(is_best_answer == true){
			var ba = jQuery(item).children("div.dwqa-answer-meta");
			var bac = jQuery(item).children("div.dwqa-comments");

			bac = bac.children("div.dwqa-comments-list");
			bac = bac.children("div.dwqa-comment");
			bac = bac.children("div.dwqa-comment-meta");
		
			if(vList.best_answer_info.user_state == true){
				tmp = ba.html();
				ba.html('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
			}

			handle_comments(bac, 2, vList);
			is_best_answer = false;
		}
		else{
			index = (make_minus === true) ? indexUsers-1 : indexUsers;
			var answerMeta = jQuery(item).children("div.dwqa-answer-meta");
			var commentsMeta = jQuery(item).children("div.dwqa-comments");
		
			commentsMeta = commentsMeta.children("div.dwqa-comments-list");
			commentsMeta = commentsMeta.children("div.dwqa-comment");
			commentsMeta = commentsMeta.children("div.dwqa-comment-meta");
			
			if(vList.answers_info[index].user_state == true){
				tmp = answerMeta.html();
				answerMeta.html('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
			}
			commentsMeta.each(function(indexComments, item){
				$comment = jQuery(item);
				
				if(vList.answers_info[index].comments_state[indexComments] == true){
					tmp = $comment.html();
					$comment.html('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
				}
			});
		}

		var element = jQuery(this).children("div.dwqa-answer-content").children("p");
		
		var text = element.html().replace(back, '"').replace(forw, '"').replace(forw1, '"').replace(/\\\\/g, "");
		if(isJSON(text)){
			var parsedData = JSON.parse(text);
			var htmlReplace = "<table class='i_table_answers_display' ><tr><th class='i_head_labels'></th><th class='i_head_display_left'>"+qInfo.lhsI+"</th><th class='i_head_display_right'>"+qInfo.rhsI+"</th></tr>";
			for(var i=0; i<parsedData.length; i++){
				if(parsedData[i].lhsI == null){
					parsedData[i].lhsI = parsedData[i].left;
					parsedData[i].rhsI = parsedData[i].right;
				}
				var leftData = videoEmbed.convertMedia(unescape(parsedData[i].lhsI));
				var rightData = videoEmbed.convertMedia(unescape(parsedData[i].rhsI));
				if(parsedData[i].cellsNumber == 2 || parsedData[i].cellsNumber == null){
					htmlReplace += "<tr class='i_row_display'><th class='i_label'>"+parsedData[i].row+"</th><td class='i_cell_display_left'>"+leftData+"</td><td class='i_cell_display_right'>"+rightData+"</td></tr>";
				}
				else{
					htmlReplace += "<tr class='i_row_add_display'><th class='i_label'>"+parsedData[i].row+"</th><td class='i_cell_display_merged' colspan='2'>"+leftData+"</td></tr>";
				}
			}
			htmlReplace += "</table>";
			element.html(htmlReplace);
			element.show();
		}
	});
	
	
	jQuery("#addRow").click(function(){
		$answerTable = jQuery("#tbl_answers tr:nth-last-child(2)");
		$cntr++;
		var newRow = new Row($cntr);
		$answerTable.after(newRow.show());
		
		makeEditor('name_left_'+$cntr);
		makeEditor('name_right_'+$cntr);
		
	});
	/*
		#Dev
		#Todayisnow
		#201703080412
		#category of answer only text box
		*/
	jQuery("#dwqa-answer-form").submit(function(){
		var data = new Array();
		for(var i=1; i<=$cntr; i++){
			if(jQuery("#row_"+i).length > 0){
				var leftInput = escape(tinyMCE.get("name_left_"+i).getContent());
				var rightInput = escape(tinyMCE.get("name_right_"+i).getContent());
				var cellsNumber = jQuery("#row_"+i).data('cells');
				/*var rowName = jQuery("#rowName_"+i).val();				
				if(rowName == "Others" && jQuery("#other_"+i).val().length){
					rowName = jQuery("#other_"+i).val();
				}*/
				var rowName = jQuery("#other_"+i).val();
				if(leftInput.length > 0){
					data.push({"cellsNumber": cellsNumber, "row": rowName ,"lhsI": leftInput, "rhsI": rightInput});
				}
			}
		}
		if(data.length > 0){
			tinyMCE.get("dwqa-answer-content").setContent(JSON.stringify(data));
			jQuery("#dwqa-answer-content").val(JSON.stringify(data));
			return true;
		}
		else{
			showError("Answer fields are empty");
		}
		return false;
	});
	
	jQuery("#addRow").click();
	/*
	#Dev
	#Todayisnow
	#201703080333
	#wrap image to use lightbox
	*/
	jQuery('.dwqa-answer-content').each(function (){
		jQuery(this).find('img').each(function(){
			var d = new Date();
			var n = d.getTime(); 
			jQuery(this).wrap("<a href='"+jQuery(this).attr('src')+"' data-lightbox='"+n+"'></a>")
			
		});
	});
});
/*
#Dev
#Todayisnow
#201703080500
#confirm before delete
*/
function del(id){
	if(confirm("Are you sure you want to delete this section?")){
	jQuery("#row_"+jQuery(id).attr('id')).remove();
	}
}

function merge(id){
	$elem = jQuery("#row_"+jQuery(id).attr('id'));
	if($elem.data('cells') == 2){
		$elem.data('cells', 1);
		$elem.children('td:nth-child(2)').attr('colspan', '2');
		$elem.children('td:nth-child(3)').hide();
	}
	else if($elem.data('cells') == 1){
		$elem.data('cells', 2);
		$elem.children('td:nth-child(2)').removeAttr('colspan');
		$elem.children('td:nth-child(3)').css("display", "block");;
	}
}

function handel(id){
	if(jQuery("#rowName_"+id).val() == "Others"){
		jQuery("#other_"+id).show();
	}
	else{
		jQuery("#other_"+id).hide();
	}
}

function makeEditor(selector){
	tinyMCE.init({
		selector: '#'+selector,
		menubar: false,
		plugins: "wplink wordpress image fullscreen paste",
		toolbar: 'link | image | bullist numlist | bold italic underline | fullscreen',
		default_link_target: "_blank",
		forced_root_block : false,
		paste_as_text : true,
		paste_word_valid_elements: "b,strong,i,em",
		paste_auto_cleanup_on_paste: true,
		paste_remove_styles: true,
		browser_spellcheck: true,
		automatic_uploads: true,
		images_upload_url: upload.url,
		image_description: false,
		image_dimensions: false,
		file_picker_types: 'image',
		file_picker_callback: function(cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');
			input.onchange = function() {
			  var file = this.files[0];
			  var id = 'blobid' + (new Date()).getTime();
			  var blobCache = tinymce.get(selector).editorUpload.blobCache;
			  var blobInfo = blobCache.create(id, file);
			  blobCache.add(blobInfo);
			  cb(blobInfo.blobUri(), { title: file.name });
			};
			input.click();
		},
		statusbar: false,
		content_css : upload.css,
		setup: function (editor) {
			editor.on('init', function(args) {
				editor = args.target;
				editor.on('NodeChange', function(e) {
					if (e && e.element.nodeName.toLowerCase() == 'img') {
						tinyMCE.DOM.setAttribs(e.element, {'width': null, 'height': null});
						tinyMCE.DOM.setAttribs(e.element, 
							{'style': 'width: 100%; height:auto;'});
					}
				});
			});
			editor.onKeyDown.add(function(ed, evt) {
				if(evt.which != 8 && evt.which != 37 && evt.which != 38 && evt.which != 39 && evt.which != 40){
					if ( jQuery(ed.getBody()).text().length+1 > jQuery(tinyMCE.get(tinyMCE.activeEditor.id).getElement()).attr('max_chars')){
						evt.preventDefault();
						evt.stopPropagation();
						return false;
					}
				}
			});
			
			editor.on('ExecCommand', function(e) {
				if(e.command == "InsertOrderedList" || e.command == "InsertUnorderedList"){
					var text = tinyMCE.get(tinyMCE.activeEditor.id).getContent();
					text = text.replace(new RegExp("style\=\"font-size: 0.8em;\"", 'g'), " ");
					tinyMCE.get(tinyMCE.activeEditor.id).setContent(text);
					tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody(), true);
					tinyMCE.activeEditor.selection.collapse(false);
				}
			});
		}  
	});
}

function isJSON(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function showError(msg){
	$error = jQuery("p.dwqa-alert-error");
	$form = jQuery("form#dwqa-answer-form");
	if($error.length){
		$error.html(msg);
	}
	else{
		$form.before("<p class='dwqa-alert dwqa-alert-error'>"+msg+"<p>");
	}
}

function handle_comments(commentsMeta, type, vList){
	commentsMeta.each(function(indexComments, item){
		$comment = jQuery(item);
		if(type == 1){
			if(vList.question_info.comments_state[indexComments] == true){
				tmp = $comment.html();
				$comment.html('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
			}
		}
		else if(type == 2){
			if(vList.best_answer_info.comments_state[indexComments] == true){
				tmp = $comment.html();
				$comment.html('<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>' + tmp);
			}
			
		}
	});
}