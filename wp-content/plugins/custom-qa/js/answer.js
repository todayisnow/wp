class Row {
	constructor(id, left, right, name, cells){
		this.id = id;
		this.left = left;
		this.right = right;
		this.name = name;
		this.cellsNumber = cells;
	}
	getLeft(){
		if(this.left != null){
			return unescape(this.left);
		}
		else{
			return '';
		}
	}
	getRight(){
		if(this.right != null){
			return unescape(this.right);
		}
		else{
			return '';
		}
	}
	getRowName(){
		var buildStrRet = "<select class ='required' onchange='javascript:handel("+this.id+");' id='rowName_"+this.id+"'>";
		var isInOther = true;
		buildStrRet += "<option value='-1'>Select</option>";
		buildStrRet += "<option value='Acronym'";
		if(this.name == 'Acronym'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Acronym</option>";

		buildStrRet += "<option value='Definition'";
		if(this.name == 'Definition'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Definition</option>";

		buildStrRet += "<option value='Scope'";
		if(this.name == 'Scope'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Scope</option>";

		buildStrRet += "<option value='Examples'";
		if(this.name == 'Examples'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Examples</option>";

		buildStrRet += "<option value='Reference'";
		if(this.name == 'Reference'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Reference</option>";
		
		if(isInOther && this.name != null){
			buildStrRet += "<option selected value='Others'>Others</option></select><input type='text' id='other_"+this.id+"' class='answer-other' value='"+this.name+"'>";
		}
		else{
			buildStrRet += "<option value='Others'>Others</option></select><input type='text' id='other_"+this.id+"' style='display: none;' class='answer-other' maxlength='40' >";
		}
		return buildStrRet;
	}
	getCellsNumber(){
		if(this.cellsNumber != null){
			return this.cellsNumber;
		}
		else{
			return 2;
		}
	}
	/*
		#Dev
		#Todayisnow
		#201703080412
		#category of answer only text box
		#201703213000
		#reordering answers
		*/
	show(){
		var retStr = "<tr class='i_table_row' data-cells='"+this.getCellsNumber()+"' id='row_"+this.id+"'><td class='i_row_name'>"+this.getRowName()+"</td><td  class='i_cell_left'";
		if(this.getCellsNumber() == 1){
			retStr += " colspan='2'";
		}
		retStr += "><textarea name='name_left_"+this.id+"' id='name_left_"+this.id+"' max_chars='1000' ></textarea><div  id='name_left_"+this.id+"_div' style=' text-align: right;margin-right: 15px; color: #aaa; font-size: x-small; display:none;'></div></td><td class='i_cell_right'";
		if(this.getCellsNumber() == 1){
			retStr += " style='display:none;'";
		}
		retStr += "><textarea  name='name_right_"+this.id+"' id='name_right_"+this.id+"' max_chars='1000' ></textarea><div  id='name_right_"+this.id+"_div' style=' text-align: right;margin-right: 15px; color: #aaa; font-size: x-small; display:none;'></div></td><td class='i_options'><a alt='Delete' title='Delete' href='javascript:void(0);' onclick='del(this);' id='"+this.id+"'><i class='fa fa-times-circle' aria-hidden='true'></i></a><br><a alt='Merge cells' title='Merge cells' href='javascript:void(0);' onclick='merge(this);' id='"+this.id+"'><i class='fa fa-arrows-h' aria-hidden='true'></i></a><br><a alt='MoveUp' title='MoveUp' href='javascript:void(0);' onclick='MoveUp(this);' id='"+this.id+"'><i class='fa fa-arrow-up' aria-hidden='true'></i></a><br><a alt='MoveDown' title='MoveDown' href='javascript:void(0);' onclick='MoveDown(this);' id='"+this.id+"'><i class='fa fa-arrow-down' aria-hidden='true'></i></a></td></tr>";

		return retStr;
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
        var pattern3 = /((href|src)=["']|)(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

        if(pattern1.test(html)){
           var replacement = '<iframe width="100%" height="345" src="http://player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

           var html = html.replace(pattern1, replacement);
        }


        if(pattern2.test(html)){
              var replacement = '<iframe width="100%" height="345" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';
              var html = html.replace(pattern2, replacement);
        }


        if(pattern3.test(html)){
			html = html.replace(pattern3, function() {
	        return  arguments[1] ?
	                arguments[0] :
	                "<a href=\"" + arguments[3] + "\" target=\"_blank\">" + arguments[3] + "</a>"});
        }
		html = html.replace(/\n/g, "<br />");
        return html;
    }
}

jQuery(window).load(function(){
	var $cntr = 0;
	$qContent = jQuery("div.dwqa-question-content");
	$answerBox = jQuery("#wp-dwqa-answer-content-wrap");
	/*
		#Dev
		#Todayisnow
		#201703210500
		#redirect_to question after login
		*/
		$currentLocation = location.pathname;	
	jQuery('.dwqa-answers-login').hide().after("<div class='logged_out_i'><a href='/wp/wp-login.php?redirect_to="+$currentLocation+"' class='login_i'>Log-in to Answer</a> <a href='/wp/wp-login.php?action=register' class='reg_i'>Register</a></div>");
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

	var data = '';
	try{
		data = tinyMCE.get('dwqa-answer-content').getContent({format : 'text'}).trim();
	}
	catch(e){
		data = jQuery("#dwqa-answer-content").val();
		console.log(e.message);
	}
	if(data == null){
		data = '';
	}

	if(data != ''){
		data = data.replace(/\\(.)/mg, "$1");
		data = JSON.parse(data);
	}

	$answerBox.after("<table class='i_table_answers_form' id='tbl_answers'><tr><th class='i_head_labels'></th><th class='i_head_display_left'>"+qInfo.lhsI+"</th><th class='i_head_display_right'>"+qInfo.rhsI+"</th><th class='i_head_tools'></th></tr><tr class='i_row_add'><td class='i_cell_add' colspan='4'><a alt='Add' title='Add' href='javascript:void(0);' id='addRow'><i class='fa fa-plus' aria-hidden='true'></i> Add Row</a></td></tr></table>");

	var $cntr = data.length;

	for(var i=0; i<$cntr; i++){
		$answerTable = jQuery("#tbl_answers tr:nth-last-child(2)");

		if(data[i].lhsI == null){
			data[i].lhsI = data[i].left;
			data[i].rhsI = data[i].right;
		}

		handel_add($answerTable, i+1, data[i].lhsI, data[i].rhsI, data[i].row, data[i].cellsNumber);
	}

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
		handel_add($answerTable, $cntr);
	});
/*
		#Dev
		#Todayisnow
		#201703080412
		#category of answer only text box
		#201703211206
		#can fill right side only of answer
		*/
	jQuery("#dwqa-answer-form").submit(function(){
		var data = new Array();
		for(var i=1; i<=$cntr; i++){
			if(jQuery("#row_"+i).length > 0){
				
				var leftInput = escape(tinyMCE.get("name_left_"+i).getContent());
				var rightInput = escape(tinyMCE.get("name_right_"+i).getContent());
				var cellsNumber = jQuery("#row_"+i).data('cells');
				var rowName = jQuery("#rowName_"+i).val();
				if(rowName=='-1' || (rowName=='Others' && jQuery("#other_"+i).val()==""))
				{
					showError("Point of comparison field is empty");
					jQuery("#rowName_"+i).attr('style','background-color: #f2bebe; border: 1px solid #ebccd1;  color: #a94442;');
					return false;
				}
				else{
					jQuery("#rowName_"+i).attr('style','');
				}
				if(rowName == "Others" && jQuery("#other_"+i).val().length){
					rowName = jQuery("#other_"+i).val();
				}
				
				//var rowName = jQuery("#other_"+i).val();
				if(leftInput.length > 0 || (rightInput.length > 0 && cellsNumber==2)){
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
	if($cntr == 0){
		jQuery("#addRow").click();
	}
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
	/*
	#Dev
	#Todayisnow
	#201703200320
	# letter count for comment
	*/
	jQuery('[id=comment]').each(function(){
		jQuery(this).attr('maxlength','500')
		jQuery(this).after("<div  id='"+jQuery(this).attr('id')+"_div' style=' text-align: right; margin-right: 20px;'>0/500</div>");
		jQuery(this).keyup(function () {
		  var max = 500;
		  var len = jQuery(this).val().length;
		  if (len >= max) {
			jQuery(this).next('div').text(' you have reached the limit');
		  } else {
			
			jQuery(this).next('div').text(len + '/500');
		  }
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
	if(confirm("Are you sure you want to delete this row?")){
	jQuery("#row_"+jQuery(id).attr('id')).remove();
	}
}
/*
#Dev
#Todayisnow
#201703213000
#reordering answers
*/
function MoveUp(id){
		
		var current = jQuery(id).attr('id');
		if(current>1){
			
			var prev = parseInt(current)-1;
			
			var currentLeftInput = tinyMCE.get("name_left_"+current).getContent();
			var currentRightInput = tinyMCE.get("name_right_"+current).getContent();
			var currentRowName = jQuery("#rowName_"+current).val();
			var currentOther = jQuery("#other_"+current).val();
			
			tinyMCE.get("name_left_"+current).setContent(tinyMCE.get("name_left_"+prev).getContent());
			tinyMCE.get("name_right_"+current).setContent(tinyMCE.get("name_right_"+prev).getContent());
			
			jQuery("#rowName_"+current).val(jQuery("#rowName_"+prev).val());
			jQuery("#other_"+current).val(jQuery("#other_"+prev).val());
			tinyMCE.get("name_left_"+prev).setContent(currentLeftInput);
			tinyMCE.get("name_right_"+prev).setContent(currentRightInput);
			
			jQuery("#rowName"+prev).val(currentRowName);
			jQuery("#other_"+prev).val(currentOther);
			
			jQuery("#rowName"+prev).change();
			jQuery("#rowName"+current).change();
			
			var currentCellsNumber = jQuery("#row_"+current).data('cells');
			var prevCellsNumber = jQuery("#row_"+prev).data('cells');
			if(currentCellsNumber!=prevCellsNumber)
			{
				FixMerge(current);
				FixMerge(prev);
			}
		}
}
function MoveDown(id){
	
	var current = jQuery(id).attr('id');
	
		if(current<jQuery("[id^='row_']").length){
			var next = parseInt(current)+1;
			
			var currentLeftInput = tinyMCE.get("name_left_"+current).getContent();
			var currentRightInput = tinyMCE.get("name_right_"+current).getContent();
			var currentRowName = jQuery("#rowName"+current).val();
			var currentOther = jQuery("#other_"+current).val();
			
			tinyMCE.get("name_left_"+current).setContent(tinyMCE.get("name_left_"+next).getContent());
			tinyMCE.get("name_right_"+current).setContent(tinyMCE.get("name_right_"+next).getContent());
			
			jQuery("#rowName_"+current).val(jQuery("#rowName_"+next).val());
			jQuery("#other_"+current).val(jQuery("#other_"+next).val());
			tinyMCE.get("name_left_"+next).setContent(currentLeftInput);
			tinyMCE.get("name_right_"+next).setContent(currentRightInput);
			
			jQuery("#rowName"+next).val(currentRowName);
			jQuery("#other_"+next).val(currentOther);
			var currentCellsNumber = jQuery("#row_"+current).data('cells');
			var nextCellsNumber = jQuery("#row_"+next).data('cells');
			
			jQuery("#rowName"+next).change();
			jQuery("#rowName"+current).change();
			
			if(currentCellsNumber!=nextCellsNumber)
			{
				FixMerge(current);
				FixMerge(next);
			}
		}
	
}
function FixMerge(id)
{
	$elem = jQuery("#row_"+id);
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
	jQuery("#rowName_"+id).attr('style','');
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
			/*
			#Dev
			#Todayisnow
			#2017032003
			#letter count on tinymce
			*/
			editor.on('keyup', function (e) { 
			
					var tx = editor.getContent({ format: 'raw' });
					var txt = document.createElement("textarea");
					txt.innerHTML = tx;
					var decoded = txt.value;
					var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "").trim();
					var count = decodedStripped.length;
                   
					if(count>1000)
					{
						return false;
					}
					else if(count>=980)
					{
						
						jQuery("#"+selector+"_div").show();
						document.getElementById(selector+"_div").innerHTML =(1000-count)+"/20";
					}
					else{
						jQuery("#"+selector+"_div").hide();
					}
					
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

function handel_add(table, id, left, right, name, cells){
	var newRow = new Row(id, left, right, name, cells);
	table.after(newRow.show());

	makeEditor('name_left_'+id);
	makeEditor('name_right_'+id);

	tinyMCE.get('name_left_'+id).setContent(newRow.getLeft());
	tinyMCE.get('name_right_'+id).setContent(newRow.getRight());
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
