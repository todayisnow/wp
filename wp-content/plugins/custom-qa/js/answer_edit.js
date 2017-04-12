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
		//return "<input type='text' id='other_"+this.id+"' style='  margin-top: 35px;' class='answer-other' value='"+this.name+"'><span>ex: Defination</span>";
		var buildStrRet = "<select onchange='javascript:handel("+this.id+");' id='rowName_"+this.id+"'>";
		var isInOther = true;
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

		buildStrRet += "<option value='References'";
		if(this.name == 'References'){
			buildStrRet += 'selected';
			isInOther = false;
		}
		buildStrRet += ">References</option>";
		
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
	#2017032003
	#letter count on tinymce
	*/
	
	show(){
		var retStr = "<tr class='i_table_row' data-cells='"+this.getCellsNumber()+"' id='row_"+this.id+"'><td class='i_row_name'>"+this.getRowName()+"</td><td  class='i_cell_left'";
		if(this.getCellsNumber() == 1){
			retStr += " colspan='2'";
		}
		retStr += "><textarea name='name_left_"+this.id+"' id='name_left_"+this.id+"' max_chars='1000' maxlength='1000'></textarea><div  id='name_left_"+this.id+"_div' style=' text-align: right;margin-right: 15px; color: #aaa; font-size: x-small; height:5px;'></div></td><td class='i_cell_right'";
		if(this.getCellsNumber() == 1){
			retStr += " style='display:none;'";
		}
		retStr += "><textarea  name='name_right_"+this.id+"' id='name_right_"+this.id+"' max_chars='1000' maxlength='1000'></textarea><div  id='name_right_"+this.id+"_div' style=' text-align: right;margin-right: 15px; color: #aaa; font-size: x-small; height:5px;'></div></td><td class='i_options'><a alt='Delete' title='Delete' href='javascript:void(0);' onclick='del(this);' id='"+this.id+"'><i class='fa fa-times-circle' aria-hidden='true'></i></a><br><a alt='Merge cells' title='Merge cells' href='javascript:void(0);' onclick='merge(this);' id='"+this.id+"'><i class='fa fa-arrows-h' aria-hidden='true'></i></a><br><a alt='MoveUp' title='MoveUp' href='javascript:void(0);' onclick='MoveUp(this);' id='"+this.id+"'><i class='fa fa-arrow-up' aria-hidden='true'></i></a><br><a alt='MoveDown' title='MoveDown' href='javascript:void(0);' onclick='MoveDown(this);' id='"+this.id+"'><i class='fa fa-arrow-down' aria-hidden='true'></i></a></td></tr>";
		
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
		#201703211206
		#can fill right side only of answer
		*/
	jQuery(".dwqa-content-edit-form").submit(function(){
		var data = new Array();
		for(var i=1; i<=$cntr; i++){
			if(jQuery("#row_"+i).length > 0){
				var leftInput = escape(tinyMCE.get("name_left_"+i).getContent());
				var rightInput = escape(tinyMCE.get("name_right_"+i).getContent());
				var cellsNumber = jQuery("#row_"+i).data('cells');
				var rowName = jQuery("#rowName_"+i).val();
				if (rowName=='other' && jQuery("#Others"+i).val()=="")
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
	ShowCount(newRow.getLeft(),"left",id)
	ShowCount(newRow.getRight(),"right",id)
}

function ShowCount(content,side,id)

{
	/*
#Dev
#Todayisnow
#201703080500
# show old count or char count
*/
				
  				var tx = content;
					var txt = document.createElement("textarea");
					txt.innerHTML = tx;
					var decoded = txt.value;
					var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "").trim();
					var count = decodedStripped.length;
                   
					if(count>1000)
					{
						return false;
					}
					else if(count>=900)
					{
						
						jQuery("#name_"+side+"_"+id+"_div").text((1000-count)+"/100");
					}
					else{
						jQuery("#name_"+side+"_"+id+"_div").text("");
					}
					
}
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
			
			jQuery("#rowName_"+prev).val(currentRowName);
			jQuery("#other_"+prev).val(currentOther);
			
			jQuery("#rowName_"+prev).change();
			jQuery("#rowName_"+current).change();
			
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
			var currentRowName = jQuery("#other_"+current).val();
			
			tinyMCE.get("name_left_"+current).setContent(tinyMCE.get("name_left_"+next).getContent());
			tinyMCE.get("name_right_"+current).setContent(tinyMCE.get("name_right_"+next).getContent());
			
			jQuery("#other_"+current).val(jQuery("#other_"+next).val());
			tinyMCE.get("name_left_"+next).setContent(currentLeftInput);
			tinyMCE.get("name_right_"+next).setContent(currentRightInput);
			jQuery("#other_"+next).val(currentRowName);
			
			var currentCellsNumber = jQuery("#row_"+current).data('cells');
			var nextCellsNumber = jQuery("#row_"+next).data('cells');
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
		paste_preprocess: function (plugin, args) {
			var editor = tinymce.get(tinymce.activeEditor.id);
			var tx = editor.getContent({ format: 'raw' });
			var txt = document.createElement("textarea");
			txt.innerHTML = tx;
			var decoded = txt.value;
			var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "");
			var len = decodedStripped.length;
			var newLen = len + args.content.length;
			
			if (newLen > 1000) {
				
				args.content = args.content.substring(0,1000-len);
				newLen = len+args.content.length;
			} 
			if(newLen>=900)
			{
				document.getElementById(selector+"_div").innerHTML =(1000-newLen)+"/100";
			}
			else{
				document.getElementById(selector+"_div").innerHTML ="";
			}
			
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
					var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "");
					var count = decodedStripped.length;
                   
				   if(count>=900)
					{
						
						
						document.getElementById(selector+"_div").innerHTML =(1000-count)+"/100";
					}
					else{
						document.getElementById(selector+"_div").innerHTML ="";
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
	jQuery("#rowName_"+id).attr('style','');
	if(jQuery("#rowName_"+id).val() == "Others"){
		jQuery("#other_"+id).show();
	}
	else{
		jQuery("#other_"+id).hide();
	}
}