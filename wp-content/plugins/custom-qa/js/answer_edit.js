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
		 /*
		#Dev
		#Todayisnow
		#201703080402
		#category of answer only text box
		*/
		return "<input type='text' id='other_"+this.id+"' style='  margin-top: 35px;' class='answer-other' value='"+this.name+"'><span>ex: Defination</span>";
		var buildStrRet = "<select onchange='javascript:handel("+this.id+");' id='rowName_"+this.id+"'>";
		var isInOther = true;
		buildStrRet += "<option value='Definition'";
		if(this.name == 'Definition'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Definition</option>";
		
		buildStrRet += "<option value='Pros'";
		if(this.name == 'Pros'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Pros</option>";
		
		buildStrRet += "<option value='Cons'";
		if(this.name == 'Cons'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Cons</option>";
		
		buildStrRet += "<option value='Examples'";
		if(this.name == 'Examples'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">Examples</option>";
		
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
	show(){
		var retStr = "<tr class='i_table_row' data-cells='"+this.getCellsNumber()+"' id='row_"+this.id+"'><td class='i_row_name'>"+this.getRowName()+"</td><td  class='i_cell_left'";
		if(this.getCellsNumber() == 1){
			retStr += " colspan='2'";
		}
		retStr += "><textarea name='name_left_"+this.id+"' id='name_left_"+this.id+"' max_chars='500' ></textarea></td><td class='i_cell_right'";
		if(this.getCellsNumber() == 1){
			retStr += " style='display:none;'";
		}
		retStr += "><textarea  name='name_right_"+this.id+"' id='name_right_"+this.id+"' max_chars='500' ></textarea></td><td class='i_options'><a alt='Delete' title='Delete' href='javascript:void(0);' onclick='del(this);' id='"+this.id+"'><i class='fa fa-times-circle' aria-hidden='true'></i></a><br><a alt='Merge cells' title='Merge cells' href='javascript:void(0);' onclick='merge(this);' id='"+this.id+"'><i class='fa fa-arrows-h' aria-hidden='true'></i></a></td></tr>";
		
		return retStr;
	}
}

jQuery(window).load(function(){
    var qInfo = JSON.parse(upload.content);
	$aBox = jQuery("#wp-dwqa-custom-content-editor-wrap");
	
	var data = '';
	try{
		data = tinyMCE.activeEditor.getContent({format : 'text'}).trim();
	}
	catch(e){
		data = jQuery("#dwqa-custom-content-editor").val();
		console.log(e.message);
	}
	
	if(qInfo.lhsI == null){
		qInfo.lhsI = qInfo.left;
		qInfo.rhsI = qInfo.right;
	}
	
	var parsedData = JSON.parse(data);
	var htmlReplace = "<table id='tbl_answers'><tr><th class='i_head_labels'></th><th class='i_head_display_left'>"+qInfo.lhsI+"</th><th class='i_head_display_right'>"+qInfo.rhsI+"</th><th class='i_head_tools'></th></tr>";
	
	htmlReplace += "<tr class='i_row_add'><td class='i_cell_add' colspan='4'><a alt='Add' title='Add' href='javascript:void(0);' id='addRow'><i class='fa fa-plus' aria-hidden='true'></i> Add Row</a></td></tr></table>";
	
	$aBox.after(htmlReplace);
	
	var $cntr = parsedData.length;
	
	for(var i=0; i<$cntr; i++){
		$answerTable = jQuery("#tbl_answers tr:nth-last-child(2)");
		
		if(parsedData[i].lhsI == null){
			parsedData[i].lhsI = parsedData[i].left;
			parsedData[i].rhsI = parsedData[i].right;
		}
		
		handel_add($answerTable, i+1, parsedData[i].lhsI, parsedData[i].rhsI, parsedData[i].row, parsedData[i].cellsNumber);
	}
	
	jQuery("#addRow").click(function(){
		$answerTable = jQuery("#tbl_answers tr:nth-last-child(2)");
		$cntr++;
		handel_add($answerTable, $cntr);
	});
	/*
		#Dev
		#Todayisnow
		#201703080411
		#category of answer only text box
		*/
	jQuery(".dwqa-content-edit-form").submit(function(){
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
			var replaceStr = JSON.stringify(data);

			tinyMCE.get("dwqa-custom-content-editor").setContent(replaceStr);
			jQuery("#dwqa-custom-content-editor").val(replaceStr);

			return true;
		}
		else{
			showError("Answer fields are empty");
		}
		return false;
	});

});

function handel_add(table, id, left, right, name, cells){
	var newRow = new Row(id, left, right, name, cells);
	table.after(newRow.show());
	
	makeEditor('name_left_'+id);
	makeEditor('name_right_'+id);
	
	tinyMCE.get('name_left_'+id).setContent(newRow.getLeft());
	tinyMCE.get('name_right_'+id).setContent(newRow.getRight());
	
}
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
				
			});
		}  
	});
}

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
		$elem.children('td:nth-child(3)').css("display", "block");
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