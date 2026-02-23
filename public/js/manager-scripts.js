var Config = {
	website:"https://vipintellect.ge/",
	ajax:"https://vipintellect.ge/ge/ajax/index", 
	pleaseWait:"მოთხოვნა იგზავნება...",
	mainLanguage:"ge"
};

var sign_in_try = function(){
	var ajaxFile = "/signing";
	var username = typeof $("#username").val() === "undefined" ? "" : $("#username").val();
	var password = typeof $("#password").val() === "undefined" ? "" : $("#password").val();
	var lang = typeof $("#lang").val() === "undefined" ? Config.mainLanguage : $("#lang").val();
	var errorMsg = $(".error-msg");
	errorMsg.text(Config.pleaseWait).fadeIn();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { username: username, password: password, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			errorMsg.text(obj.Error.Text).fadeIn();
		}else{
			location.href = obj.redirect;
		}
	});
	
};

var add_page = function(cid, lang){
	var ajaxFile = "/addPageForm";
	var header = "<h4>გვერდის დამატება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">დამატება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	scrollTop();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { call: true, lang: lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			// errorMsg.text(obj.Error.Text).fadeIn();
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#choosePageType").material_select();
			$("#chooseNavType").material_select();
			$("#attachModule").material_select();
			$("#modalButton").attr({"onclick": obj.attr });
			$('.tooltipped').tooltip({delay: 50});
			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});
			$("#sortableFiles-box").sortable({
				items: ".level-0, .level-2", 
				update: function( event, ui ) { 
					var subfile = "";
					var itemidx = ui.item[0].attributes[1].nodeValue;
					var level = (ui.item[0].attributes[2].nodeValue==0) ? ".level-0" : ".level-2";
					if(level==".level-0"){
						subfile = $("#subfilex-"+itemidx).detach();
						$(level+"[data-item='"+itemidx+"']").after(subfile);
						subfile.remove();
					}				
				}
			});
			
			tiny(".tinymceTextArea");
			$("#input_cid").val(cid);
		}
	});
};

var formPageAdd = function(){
	var ajaxFile = "/addPage";
	var lang = $("#lang").val();
	var input_cid = $("#input_cid").val();
	var chooseNavType = $("#chooseNavType").val();
	var choosePageType = $("#choosePageType").val();
	var title = $("#title").val();
	var slug = $("#slug").val();
	var cssClass = $("#cssClass").val();
	var attachModule = $("#attachModule").val();
	var redirect = $("#redirect").val();
	var pageDescription = (tinymce.get('pageDescription') != null) ? tinymce.get('pageDescription').getContent() : '';
	var pageText = (tinymce.get('pageText') != null) ? tinymce.get('pageText').getContent() : '';
	var random = $("#random").val();
	var file_attach_type = $("#file_attach_type").val();

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);
	/* FILE SERIALIZE */
	var files = new Array();
	var f_item = "";
	var f_cid = "";
	var f_path = "";
	var f_all = "";
	if($("#sortableFiles-box").length){
		$("#sortableFiles-box li").each(function(){
			f_item = $(this).attr("data-item");
			f_cid = $(this).attr("data-cid");
			if(typeof $(this).attr("data-file") !== "undefined"){
				f_path = $(this).attr("data-file");	
			}
			if(typeof $(this).attr("data-path") !== "undefined"){
				f_path = $(this).attr("data-path");	
			}
			f_all = file_attach_type + "," + random + "," + f_item + "," + f_cid + "," + f_path;
			files.push(f_all);
		});
	}
	var serialFiles = serialize(files);

	$(".modal-message-box").html("გთხოვთ დაიცადოთ...");
	if(
		(typeof chooseNavType === "undefined" || chooseNavType=="") || 
		(typeof choosePageType === "undefined" || choosePageType=="") || 
		(typeof title === "undefined" || title=="") || 
		(typeof slug === "undefined" || slug=="") 
	){
		$(".modal-message-box").html("ყველა ველი სავალდებულოა !");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { lang:lang, input_cid:input_cid, chooseNavType: chooseNavType, choosePageType: choosePageType, title: title, slug: slug, cssClass:cssClass, attachModule:attachModule, redirect:redirect, pageDescription: pageDescription, pageText: pageText, serialPhotos: serialPhotos, serialFiles:serialFiles }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				$("#choosePageType").val("");
				$("#title").val("");
				$("#slug").val("");
				$("#pageDescription").val("");
				$("#pageText").val("");
				// location.reload();
			}else{
				$(".modal-message-box").html("E");
			}
			scrollTop();
			setTimeout(function(){ location.reload(); }, 2000);
		});
	}
};

var formPageEdit = function(idx, lang){
	var ajaxFile = "/editPage";
	var chooseNavType = $("#chooseNavType").val();
	var choosePageType = $("#choosePageType").val();
	var title = $("#title").val();
	var slug = $("#slug").val();
	var cssClass = $("#cssClass").val();
	var attachModule = $("#attachModule").val();
	var redirect = $("#redirect").val();
	var pageDescription = (tinymce.get('pageDescription') != null) ? tinymce.get('pageDescription').getContent() : '';
	var pageText = (tinymce.get('pageText') != null) ? tinymce.get('pageText').getContent() : '';
	var random = $("#random").val();
	var file_attach_type = $("#file_attach_type").val();

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);

	var files = new Array();
	var f_item = "";
	var f_cid = "";
	var f_path = "";
	var f_all = "";
	if($("#sortableFiles-box").length){
		$("#sortableFiles-box li").each(function(){
			f_item = $(this).attr("data-item");
			f_cid = $(this).attr("data-cid");
			if(typeof $(this).attr("data-file") !== "undefined"){
				f_path = $(this).attr("data-file");	
			}
			if(typeof $(this).attr("data-path") !== "undefined"){
				f_path = $(this).attr("data-path");	
			}
			f_all = file_attach_type + "," + random + "," + f_item + "," + f_cid + "," + f_path;
			files.push(f_all);
		});
	}
	var serialFiles = serialize(files);

	$(".modal-message-box").html("გთხოვთ დაიცადოთ...");
	if(
		(typeof chooseNavType === "undefined" || chooseNavType=="") || 
		(typeof choosePageType === "undefined" || choosePageType=="") || 
		(typeof title === "undefined" || title=="") || 
		(typeof slug === "undefined" || slug=="") 
	){
		$(".modal-message-box").html("ყველა ველი სავალდებულოა !");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { idx:idx, lang: lang, chooseNavType: chooseNavType, choosePageType: choosePageType, title: title, slug: slug, cssClass:cssClass, attachModule:attachModule, redirect:redirect, pageDescription: pageDescription, pageText: pageText, serialPhotos:serialPhotos, serialFiles:serialFiles }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
			}else{
				$(".modal-message-box").html("E");
			}
			scrollTop();
			setTimeout(function(){ location.reload(); }, 2000);	
		});
	}
};

var changeVisibility = function(vis, idx){
	console.log(vis + " " + idx);
	var ajaxFile = "/changeVisibility";

	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	if(typeof vis === "undefined" || typeof idx === "undefined"){
		$(".modal-message-box").html("E2");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { visibility: vis, idx: idx }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E3");
			}
		});
	}
}

var changeModuleVisibility = function(vis, idx){
	console.log(vis + " " + idx);
	var ajaxFile = "/changeModuleVisibility";

	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	if(typeof vis === "undefined" || typeof idx === "undefined"){
		$(".modal-message-box").html("E2");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { visibility: vis, idx: idx }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E3");
			}
		});
	}
};

var askRemovePage = function(navType, pos, idx, cid){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"removePage('"+navType+"', '"+pos+"', '"+idx+"', '"+cid+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
	scrollTop();
};

var askRemoveModule = function(idx){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"removeModule('"+idx+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
	scrollTop();
};

var removePage = function(navType, pos, idx, cid){
	var ajaxFile = "/removePage";
	if(typeof navType == "undefined" || typeof pos === "undefined" || typeof idx === "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { navType: navType, pos: pos, idx: idx, cid:cid }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
};

var removeModule = function(idx){
	var ajaxFile = "/removeModule";
	if(typeof idx === "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { idx: idx }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
};

var removeCity = function(idx){
	var ajaxFile = "/removeCity";
	if(typeof idx === "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { idx: idx }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
};

var changePositionsOfPages = function(navType, selector, cid){
	var ajaxFile = "/changePagePositions";
	var i = "";
	var arr = new Array(); 
	$('.'+selector).each(function(){
		if($(this).attr("data-cid")==cid){
			i = $(this).attr("data-item");
			arr.push(i);
		}		
	});
	var serialized = serialize(arr);

	//var p = $( ".sortablePagePositionChange " ).detach();

	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">მიმდინარეობს მონაცემის განახლება...</p>";
	var footer = "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { s:serialized, navType: navType, cid:cid }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".modal-message-box").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".modal-message-box").html(obj.Success.Text);
			scrollTop();
			
			setTimeout(function(){
				location.reload();
			}, 500);
		}else{
			$(".modal-message-box").html("E5");
		}
	});
};

var editPage = function(idx, lang){
	console.log(idx + " " + lang);
	var ajaxFile = "/editPageForm";
	var header = "<h4>გვერდის რედაქტირება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">რედაქტირება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { idx: idx, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#choosePageType").material_select();
			$("#chooseNavType").material_select();
			$("#attachModule").material_select();
			$("#modalButton").attr({"onclick": obj.attr });
			$('.tooltipped').tooltip({delay: 50});
			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});

			$("#sortableFiles-box").sortable({
				items: ".level-0, .level-2", 
				update: function( event, ui ) { 
					var subfile = "";
					var itemidx = ui.item[0].attributes[1].nodeValue;
					var level = (ui.item[0].attributes[2].nodeValue==0) ? ".level-0" : ".level-2";
					if(level==".level-0"){
						subfile = $("#subfilex-"+itemidx).detach();
						$(level+"[data-item='"+itemidx+"']").after(subfile);
						subfile.remove();
					}				
				}
			});

			tiny(".tinymceTextArea");
			scrollTop();		
		}
	});
};

var add_module = function(moduleSlug, lang){
	var ajaxFile = "/addModuleForm";
	var header = "<h4>დამატება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">დამატება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { moduleSlug: moduleSlug, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
			$('.datepicker').pickadate({
				selectMonths: true, 
			});
			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});
			tiny(".tinymceTextArea");
		}
	});
};

var add_parent_module = function(lang){
	var ajaxFile = "/addParentModuleForm";
	var header = "<h4>დამატება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">დამატება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
		}
	});
};

var edit_parent_module = function(lang){
	var ajaxFile = "/editParentModuleForm";
	var header = "<h4>რედაქტირება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">რედაქტირება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
			$("#chooseParentModule").material_select();

			$("#chooseParentModule").on("change", function(){
				var val = $(this).val();
				var field = $("#fields"+val).val();
				$("#title").val($("option:selected", this).text());
				$("#field").val(field);
			});
		}
	});
};

var delete_parent_module = function(lang){
	var ajaxFile = "/deleteParentModuleForm";
	var header = "<h4>წაშლა</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">წაშლა</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
			$("#chooseParentModule").material_select();
		}
	});
};

var add_city_Form = function(slug, lang){
	var ajaxFile = "/addCityForm";
	var header = "<h4>დამატება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">დამატება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { slug: slug, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
		}
	});
};

var add_city = function(slug, lang){
	var name = $("#name").val();
	var ajaxFile = "/addCity";
	if(typeof slug == "undefined" || typeof lang == "undefined" || typeof name === "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { slug: slug, lang:lang, name: name }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
			scrollTop();
		});
	}
};

$(document).on("change", ".materializeCheckBox input[type='checkbox']", function(){
	console.log("."+$(this).attr("id"));

	if($(this).prop('checked')){
		$("."+$(this).attr("id")).fadeIn("slow");
	}else{
		$("."+$(this).attr("id")).fadeOut("slow");
		$("."+$(this).attr("id")+" .allfields").html('');
	}
});

$(document).on("click", ".addField", function(){
	var field = $(this).attr("data-field");

	var html = "<div class=\"input-field\">";
	html += "<input type=\"text\" placeholder=\"დასახელება\" class=\"title\" value=\"\" />";
	html += "<input type=\"text\" placeholder=\"ფასი\" class=\"price\" value=\"\" />";
	html += "</div>";
	$("#"+field).append(html);
});

var askRemoveCity = function(idx){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"removeCity('"+idx+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
	scrollTop();
};

var add_catalog = function(catalogId, lang){
	var ajaxFile = "/addCatalogForm";
	var header = "<h4>დამატება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">დამატება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { catalogId: catalogId, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			
			$("#modalButton").attr({"onclick": obj.attr });

			$("#date").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");
			$("#arrival").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");
			$("#departure").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");

			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});
			$("#chooseDestination").material_select();
			$("#chooseAdvantureType").material_select();
			$("#choosevisibiliti").material_select();
			$("#chooseSpecial_offer").material_select();
			$("#chooseTouristCount").material_select();
			tiny(".tinymceTextArea");
		}
	});
};

var editModules = function(idx, lang){
	console.log(idx + " "+lang);
	var ajaxFile = "/editModules";
	var header = "<h4>რედაქტირება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">რედაქტირება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { idx: idx, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });
			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});
			$('.tooltipped').tooltip({delay: 50});
			$("#sortableFiles-box").sortable({
				items: ".level-0, .level-2", 
				update: function( event, ui ) { 
					var subfile = "";
					var itemidx = ui.item[0].attributes[1].nodeValue;
					var level = (ui.item[0].attributes[2].nodeValue==0) ? ".level-0" : ".level-2";
					if(level==".level-0"){
						subfile = $("#subfilex-"+itemidx).detach();
						$(level+"[data-item='"+itemidx+"']").after(subfile);
						subfile.remove();
					}				
				}
			});

			tiny(".tinymceTextArea");			
		}
	});
};

var editCity = function(idx, lang){
	var ajaxFile = "/editCity";
	var header = "<h4>რედაქტირება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">რედაქტირება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { idx: idx, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);
			$("#modalButton").attr({"onclick": obj.attr });		
			tiny(".tinymceTextArea");
		}
	});
	scrollTop();
};

var formModuleEdit = function(idx, lang){
	var ajaxFile = "/editFormModules";
	var date = $("#date").val();
	var title = $("#title").val();
	var file_attach_type = $("#file_attach_type").val();
	var random = $("#random").val();
	var pageText = (tinymce.get('pageText') !== null) ? tinymce.get('pageText').getContent() : "Hidden field";
	var link = (typeof $("#link").val() !== "undefined" && $("#link").val()!="") ? $("#link").val() : "empty";
	var classname = (typeof $("#classname").val() !== "undefined" && $("#classname").val()!="") ? $("#classname").val() : "";

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);

	var files = new Array();
	var f_item = "";
	var f_cid = "";
	var f_path = "";
	var f_all = "";
	if($("#sortableFiles-box").length){
		$("#sortableFiles-box li").each(function(){
			f_item = $(this).attr("data-item");
			f_cid = $(this).attr("data-cid");
			if(typeof $(this).attr("data-file") !== "undefined"){
				f_path = $(this).attr("data-file");	
			}
			if(typeof $(this).attr("data-path") !== "undefined"){
				f_path = $(this).attr("data-path");	
			}
			f_all = file_attach_type + "," + random + "," + f_item + "," + f_cid + "," + f_path;
			files.push(f_all);
		});
	}
	var serialFiles = serialize(files);

	$(".modal-message-box").html("გთხოვთ დაიცადოთ...");
	if(
		(typeof date === "undefined" || date=="") || 
		(typeof title === "undefined" || title=="") 
	){
		$(".modal-message-box").html("ყველა ველი სავალდებულოა !");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { idx:idx, lang: lang, date: date, title: title, pageText: pageText, link:link, classname:classname, serialPhotos:serialPhotos, serialFiles:serialFiles }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				scrollTop();
			}else{
				$(".modal-message-box").html("E");
			}
		});
	}
};

var editCurrentCity = function(idx, lang){
	var ajaxFile = "/editCurrentCity";
	
	var name = $("#name").val();
	$(".modal-message-box").html("გთხოვთ დაიცადოთ...");
	if(
		(typeof name === "undefined") 
	){
		$(".modal-message-box").html("ყველა ველი სავალდებულოა !");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { idx:idx, lang: lang, name: name }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				scrollTop();
			}else{
				$(".modal-message-box").html("E");
			}
		});
	}
};

var formModuleAdd = function(moduleSlug, lang){
	var date = $("#date").val();
	var title = $("#title").val();
	var file_attach_type = $("#file_attach_type").val();
	var random = $("#random").val();
	var pageText = (tinymce.get('pageText') !== null) ? tinymce.get('pageText').getContent() : "Hidden field";
	var link = (typeof $("#link").val() !== "undefined" && $("#link").val()!="") ? $("#link").val() : "empty";
	var classname = (typeof $("#classname").val() !== "undefined" && $("#classname").val()!="") ? $("#classname").val() : "";

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);

	var files = new Array();
	var f_item = "";
	var f_cid = "";
	var f_path = "";
	var f_all = "";
	if($("#sortableFiles-box").length){
		$("#sortableFiles-box li").each(function(){
			f_item = $(this).attr("data-item");
			f_cid = $(this).attr("data-cid");
			if(typeof $(this).attr("data-file") !== "undefined"){
				f_path = $(this).attr("data-file");	
			}
			if(typeof $(this).attr("data-path") !== "undefined"){
				f_path = $(this).attr("data-path");	
			}
			f_all = file_attach_type + "," + random + "," + f_item + "," + f_cid + "," + f_path;
			files.push(f_all);
		});
	}
	var serialFiles = serialize(files);

	var ajaxFile = "/addModule";
	if(typeof moduleSlug == "undefined" || typeof date == "undefined" || typeof title === "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { moduleSlug: moduleSlug, lang:lang, date: date, title: title, pageText: pageText, link:link, classname:classname, serialPhotos:serialPhotos, serialFiles:serialFiles }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
			scrollTop();
		});
	}
};

var formParentModuleAdd = function(lang){
	var type = $("#type").val();
	var title = $("#title").val();

	var ajaxFile = "/addParentModule";
	if(typeof title == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { type:type, title:title, lang:lang }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
			scrollTop();
		});
	}
};

var formParentModuleEdit = function(lang){
	var parentModuleId = $("#chooseParentModule").val();
	var title = $("#title").val();
	var field = $("#field").val();

	var ajaxFile = "/editParentModule";
	if(typeof title == "undefined" || typeof field == "undefined" || typeof parentModuleId == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { parentModuleId:parentModuleId, title:title, field:field, lang:lang }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
			scrollTop();
		});
	}
};

var formParentModuleDelete = function(lang){
	var parentModuleId = $("#chooseParentModule").val();

	var ajaxFile = "/deleteParentModule";
	if(typeof parentModuleId == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { parentModuleId:parentModuleId, lang:lang }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
			scrollTop();
		});
	}
};

 
var searchComments = function(id){
	var ajaxFile = "/searchComments";
	var header = "<h4>განცხადება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { id: id }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var table = "<p>" + obj.table +"</p>";
			$("#modal1 .modal-content").html(header + table);
		}
		scrollTop(); 
	});
};


var viewUser = function(id){
	var ajaxFile = "/viewUser";
	var header = "<h4>განცხადება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { id: id }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var table = "<p>" + obj.table +"</p>";
			$("#modal1 .modal-content").html(header + table);
		}
		scrollTop(); 
	});
};

var viewPayment= function(id){
	var ajaxFile = "/viewPayment";
	var header = "<h4>განცხადება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { id: id }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var table = "<p>" + obj.table +"</p>";
			$("#modal1 .modal-content").html(header + table);
		}
		scrollTop(); 
	});
};

var askRemoveComments = function(id){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"removeComments('"+id+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();	
}; 

var askDeleteUser = function(id){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"deleteUser('"+id+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
}

var removeComments = function(id){
	var ajaxFile = "/removeComments";
	if(typeof id == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { id: id }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
};

var deleteUser = function(id){
	var ajaxFile = "/deleteUser";
	if(typeof id == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { id: id }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
}

var loanStatus = function(){
	var ajaxFile = "/updateLoanStatus";
	var loanStatus = $('#loan-status').prop('checked');
	var spid = $("#loan-spid").val(); 
	
	if(loanStatus){
		$('#loan-status').prop('checked', true);
		var onoff = "on";
	}else{
		$('#loan-status').prop('checked', false);
		var onoff = "off";
	}
	$("#loan-status").attr("disabled","disabled"); 

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { loanStatus:onoff, spid:spid }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			alert(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$("#loan-status").removeAttr("disabled");
		}else{
			alert("E5");
		}
	});

	console.log($("#loan-status").val() + " " +spid);
};

$(document).on("change", "#loan-status", function(){
	loanStatus();
});

/*---------------*/
var loanStatus2 = function(){
	var ajaxFile = "/updateLoanStatus";
	var loanStatus2 = $('#loan-status2').prop('checked');
	var spid2 = $("#loan-spid2").val(); 
	if(loanStatus2){
		$('#loan-status2').prop('checked', true);
		var onoff = "on";
	}else{
		$('#loan-status2').prop('checked', false);
		var onoff = "off";
	}
	$("#loan-status2").attr("disabled","disabled"); 

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { loanStatus2:onoff, spid2:spid2 }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			alert(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$("#loan-status2").removeAttr("disabled");
		}else{
			alert("E5");
		}
	});
};

$(document).on("change", "#loan-status2", function(){
	loanStatus2();
});

// $(document).on("change","#chooseRegion", function(){
// 	var v = $(this).val();
// 	loadCities(v);
// });

var elFinderDesign = function(id){
	var overlay = document.createElement("div");
	overlay.id = "overlay"+id;
	
	var boxHeader = document.createElement("p");
	boxHeader.id = "boxHeader"+id;

	var closeBox = document.createElement("p");
	closeBox.id = "closeBox"+id;

	boxHeader.append(closeBox);

	var t = document.createTextNode("აირჩიეთ ფაილი");
	boxHeader.appendChild(t);

	var box = document.createElement("div");
	box.id = "box"+id;

	var fileManager = document.createElement("div");
	fileManager.id = "fileManager"+id;

	box.append(boxHeader);
	box.append(fileManager);

	$("body").append(overlay).append(box);
	$("body").css("overflow-y","hidden"); 

	$("#overlay"+id).css({
		"background-color":"#000000",
		"opacity":"0.5",
		"position":"fixed",
		"z-index":"1100",
		"top":"0px",
		"left":"0px",
		"width":"100%",
		"height":"100%"
	});

	$("#box"+id).css({
		"background-color":"#ffffff",
		"position":"fixed",
		"z-index":"1200",
		"top":"50px",
		"left":"calc(50% - 500px)",
		"width":"1000px",
		"height":"450px"
	});

	$("#boxHeader"+id).css({
		"width":"calc(100% - 20px)",
		"height":"20px",
		"font-size":"18px",
		"line-height":"20px", 
		"margin":"10px",
		"float":"left",
		"position":"relative"
	});

	$("#closeBox"+id).css({
		"width":"15px", 
		"height":"15px", 
		"position":"absolute", 
		"right":"0px", 
		"top":"4px", 
		"background-image":"url('/public/img/cancel.png')",
		"background-size":"15px 15px",
		"background-repeat":"no-repeat", 
		"background-position":"center center",
		"cursor":"pointer" 
	});
	$("#closeBox"+id).attr("onclick", "closeFileManager('"+id+"')");
};

var openFileManagerForProductCover = function(id){
	elFinderDesign(id); 
	$("#fileManager"+id).elfinder({
		url : '/public/elfinder/php/connector.minimal.php', 
		docked: false,
        dialog: { width: 400, modal: true },
        closeOnEditorCallback: true, 
		getFileCallback: function(url) {
            $(".coverphoto").attr("style","background-image: url(/public/"+url.path+");");
            $(".cover").val("/public/"+url.path);
            closeFileManager(id); 
        }
	});
	$("#fileManager"+id).css({
		"width":"calc(100% - 20px)",
		"margin":"0px 10px",
		"float":"left"
	});
};

var openFileManager = function(photosBox, id){
	elFinderDesign(id); 
	$("#fileManager"+id).elfinder({
		url : '/public/elfinder/php/connector.minimal.php', 
		docked: false,
        dialog: { width: 400, modal: true },
        closeOnEditorCallback: true, 
		getFileCallback: function(url) {
            $("#"+id+" .card .card-image .activator").attr("src",Config.website+Config.mainLanguage+"/image/loadimage?f="+Config.website+"public/"+url.path+"&w=215&h=173");
            $("#"+id+" .card .card-image .managerFiles").val("/public/"+url.path);
            photoUploaderBox(photosBox);
            closeFileManager(id); 
        }
	});
	$("#fileManager"+id).css({
		"width":"calc(100% - 20px)",
		"margin":"0px 10px",
		"float":"left"
	});
};

var closeFileManager = function(id){
	$("#box"+id).remove();
    $("#overlay"+id).remove();
};

var photoUploaderBox = function(photosBox){
	console.log(photosBox+" in");
	var count = $("#"+photosBox+" .imageItem").length + 1;
	var out = "<div class=\"col s4 imageItem noImageSelected\" id=\"img"+count+"\">";
	out += "<div class=\"card\">";
	out += "<div class=\"card-image waves-effect waves-block waves-light\">";
	out += "<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"\" />";
	out += "<img class=\"activator\" src=\"/public/img/noimage.png\" />";
	out += "</div>";
	out += "<div class=\"card-content\">";
	out += "<p>";
	out += "<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img"+count+"')\" class=\"large material-icons\">mode_edit</a>";
	out += "<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img"+count+"')\" class=\"large material-icons\">delete</a>";
	out += "</p>";
	out += "</div>";
	out += "</div>";
	out += "</div>";
	$("#" + photosBox).append(out);
};

var removePhotoItem = function(imageBoxId){
	$("#"+imageBoxId).fadeOut().remove();
}

var openFileManagerForFiles = function(id){
	elFinderDesign(id); 
	$("#fileManager"+id).elfinder({
		url : '/public/elfinder/php/connector.minimal.php', 
		docked: false,
        dialog: { width: 400, modal: true },
        closeOnEditorCallback: true, 
		getFileCallback: function(url) {
            var ajaxFile = "/file_system";
            var random = $("#random").val(); 
            var file_attach_type = $("#file_attach_type").val(); 
            var path = url.path; 
            $.ajax({
				method: "POST",
				url: Config.ajax + ajaxFile,
				data: { random:random, path:path, file_attach_type:file_attach_type }
			}).done(function( msg ) {
				var obj = $.parseJSON(msg);
				if(obj.Error.Code==1){
					alert(obj.Error.Text);
				}else if(obj.Success.Code==1){
					var f = filebox(url.path, obj.Success.insert_id);
            		$("#sortableFiles-box").append(f); 
				}else{
					alert("E5");
				}
			});
          
            closeFileManager(id); 
        }
	});
	$("#fileManager"+id).css({
		"width":"calc(100% - 20px)",
		"margin":"0px 10px",
		"float":"left"
	});
};

var openFileManagerForSubFiles = function(id, item){
	elFinderDesign(id); 
	$("#fileManager"+id).elfinder({
		url : '/public/elfinder/php/connector.minimal.php', 
		docked: false,
        dialog: { width: 400, modal: true },
        closeOnEditorCallback: true, 
		getFileCallback: function(url) {
			var ajaxFile = "/file_system";
            var random = $("#random").val(); 
            var file_attach_type = $("#file_attach_type").val(); 
            var path = url.path; 
			$.ajax({
				method: "POST",
				url: Config.ajax + ajaxFile,
				data: { random:random, path:path, item:item, file_attach_type:file_attach_type }
			}).done(function( msg ) {
				var obj = $.parseJSON(msg);
				if(obj.Error.Code==1){
					alert(obj.Error.Text);
				}else if(obj.Success.Code==1){
     				addsubfile(item, url.path, obj.Success.insert_id);
				}else{
					alert("E5");
				}
			});
            
            closeFileManager(id); 
        }
	});
	$("#fileManager"+id).css({
		"width":"calc(100% - 20px)",
		"margin":"0px 10px",
		"float":"left"
	});
};

var removeAttachedFile = function(classes, item, sub){
	var ajaxFile = "/removeFile";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { item:item }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			$("#sortableFiles-box ."+classes+"[data-item='"+item+"']").remove();
			if($("#subfilex-"+item).length && sub){
				$("#subfilex-"+item).remove();
			}
		}
	});	
	
};

var filebox = function(path, returnid){
	var split = path.split("/");
	var file = "<li class=\"collection-item level-0 popupfile0\" data-item=\""+returnid+"\" data-cid=\"0\" data-file=\""+path+"\">";
	file += "<div>";
	file += split[split.length - 1];
	file += "<a href=\"javascript:void(0)\" onclick=\"removeAttachedFile('level-0','"+returnid+"', true)\" class=\"secondary-content tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"წაშლა\"><i class=\"material-icons\">delete</i></a>";
	// file += "<a href=\"\" class=\"secondary-content tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"კომენტარი (5)\"><i class=\"material-icons\">comment</i></a>";
	file += "<a href=\"javascript:void(0)\" onclick=\"openFileManagerForSubFiles('subfilex"+returnid+"','"+returnid+"')\" class=\"secondary-content tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"დამატება\"><i class=\"material-icons\">note_add</i></a>";
	file += "</div>";
	file += "</li>";
	return file;
};

var addsubfile = function(item, path, inserted_id){
	// var length = $("#subfilex-"+item+" .level-2").length + 1;
	var split = path.split("/");
	var file = "";
	if(!$("#subfilex-"+item).length){
		file += "<ul id=\"subfilex-"+item+"\" class=\"collection with-header sortableFiles-box2\" data-cid=\""+item+"\" style=\"margin:10px;\">";
	}
	file += "<li class=\"collection-item level-2\" data-item=\""+inserted_id+"\" data-cid=\""+item+"\" data-path=\""+path+"\">";
	file += "<div>";
	file += split[split.length - 1];
	file += "<a href=\"javascript:void(0)\" onclick=\"removeAttachedFile('level-2','"+inserted_id+"', false)\"  class=\"secondary-content tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"წაშლა\"><i class=\"material-icons\">delete</i></a>";
	// file += "<a href=\"\" class=\"secondary-content tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"კომენტარი (5)\"><i class=\"material-icons\">comment</i></a>";
	file += "</div>";
	file += "</li>";
	if(!$("#subfilex-"+item).length){
		file += "</ul>";
		$(".popupfile0[data-item='"+item+"']").after(file);
	}else{
		$("#subfilex-"+item+"").append(file);
	}	
};


$(document).ready(function(){
    $('.collapsible').collapsible({
      accordion : false 
    });
    $('.tooltipped').tooltip({delay: 50});
    // main navigation
    $('.sortablePagePositionChange').sortable({
    	items: ".level-0, .level-2, .level-3",
		update: function( event, ui ) { 
			var itemidx = ui.item[0].attributes[1].nodeValue;
			var cid = ui.item[0].attributes[2].nodeValue;
			// var level = (ui.item[0].attributes[2].nodeValue==0) ? ".level-0" : ".level-2";
			var level = "";
			if(ui.item[0].attributes[3].nodeValue==0){
				level = ".level-0";
			}else if(ui.item[0].attributes[3].nodeValue==2){
				level = ".level-2";
			}else if(ui.item[0].attributes[3].nodeValue==3){
				level = ".level-3";
			}
			var subnavigations = $(".sub-"+itemidx).detach();

			changePositionsOfPages(0, 'sortablePagePositionChange '+level, cid); 
			$(level+"[data-item='"+itemidx+"']").after(subnavigations);
			subnavigations.remove();
		}
	});
	$('.sortablePagePositionChange').disableSelection();



	$('.sortablePagePositionChange2').sortable({
    	items: ".level2-0",
		update: function( event, ui ) { 
			var cid = ui.item[0].attributes[1].nodeValue;
			console.log(cid);
			changePositionsOfPages(1, 'sortablePagePositionChange2 .level2-0', cid); 
		}
	});
	$('.sortablePagePositionChange2').disableSelection();

	$("#language-chooser").material_select();

 });

/* Additional functions */
var tiny = function(selector){
	tinymce.remove();
	tinymce.init({
		selector: selector, 
		theme: "modern",
	    plugins: [
	        "autolink lists link image hr pagebreak",
	        "wordcount visualblocks",
	        "insertdatetime save table contextmenu directionality",
	        "paste textcolor colorpicker textpattern",
	        "code", 
	        "textcolor"
	    ],
	    toolbar1: "insertfile undo redo | styleselect | bold italic | link image | numlist | bullist | table | code | forecolor | backcolor",
	    image_advtab: true, 
	    extended_valid_elements : "iframe[src|width|height|name|align]", 
	    relative_urls : 0, 
		remove_script_host : 0, 
		body_class: 'myTineMce', 
		file_browser_callback : elFinderBrowser
	});
};

var elFinderBrowser = function(field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: Config.website+'public/elfinder/elfinder.php',// use an absolute path!
    title: 'elFinder 2.0',
    width: 900,  
    height: 450,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
};

var serialize = function(mixed_value) {
	var val, key, okey,
	ktype = '',
	vals = '',
	count = 0,
	_utf8Size = function(str) {
		var size = 0,
		i = 0,
		l = str.length,
		code = '';
		for (i = 0; i < l; i++) {
			code = str.charCodeAt(i);
			if (code < 0x0080) {
				size += 1;
			} else if (code < 0x0800) {
				size += 2;
			} else {
			size += 3;
			}
		}
		return size;
	};
	_getType = function(inp) {
		var match, key, cons, types, type = typeof inp;

		if (type === 'object' && !inp) {
			return 'null';
		}
		if (type === 'object') {
			if (!inp.constructor) {
				return 'object';
			}
			cons = inp.constructor.toString();
			match = cons.match(/(\w+)\(/);
			if (match) {
				cons = match[1].toLowerCase();
			}
			types = ['boolean', 'number', 'string', 'array'];
			for (key in types) {
				if (cons == types[key]) {
					type = types[key];
					break;
				}
			}
		}
		return type;
	};
	type = _getType(mixed_value);

	switch (type) {
		case 'function':
			val = '';
			break;
		case 'boolean':
			val = 'b:' + (mixed_value ? '1' : '0');
			break;
		case 'number':
			val = (Math.round(mixed_value) == mixed_value ? 'i' : 'd') + ':' + mixed_value;
			break;
		case 'string':
			val = 's:' + _utf8Size(mixed_value) + ':"' + mixed_value + '"';
			break;
		case 'array':
			case 'object':
				val = 'a';

				for (key in mixed_value) {
					if (mixed_value.hasOwnProperty(key)) {
						ktype = _getType(mixed_value[key]);
						if (ktype === 'function') {
							continue;
						}
						okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
						vals += this.serialize(okey) + this.serialize(mixed_value[key]);
						count++;
					}
				}
				val += ':' + count + ':{' + vals + '}';
				break;
		case 'undefined':
			default:
				val = 'N';
				break;
	}

	if (type !== 'object' && type !== 'array') {
		val += ';';
	}
	return val;
};

var scrollTop = function(){
	var body = $("html, body");
	body.stop().animate({scrollTop:0}, '500', 'swing', function() { });
};


var updateCol = function(col, val, pid){
	//alert(col + " " + val + " " + pid); 
	var div = "editable_"+col;

	 
	var form = '<form action="" method="post" style="padding-right: 10px">'; 
	form += '<div class="input-field">';
	form += '<input type="text" class="updatable" value="" onblur="updateMe(\''+col+'\', \''+pid+'\', \''+val+'\')" />';
	form += '</div>';
	form += '</form>';


	$("."+div).removeAttr("onclick");
	$("."+div).html(form);
	$(".updatable").focus();
	$(".updatable").val(val);

};

var updateColSelect = function(col, val, pid, cityId){
	var div = "editable_"+col;
	var form = "<form action=\"\" method=\"post\">";
	form += "<select class=\"materialize_form_select\" id=\"updatable\" onchange=\"updateMeSelect('"+col+"','"+pid+"')\">";
	form += "</select>";

	var ajaxFile = "/citiesOption";
	var options = "";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { selected:cityId }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			console.log(obj.Success.html);
			$("."+div).removeAttr("onclick");
			$("."+div).html(form);
			$("#updatable").html(obj.Success.html);

			$(".materialize_form_select").material_select();
		}
	});	
	
};

var updateMe = function(col, pid, oldValue){
	var div = "editable_"+col;
	var val = $(".updatable").val(); // new value

	$("."+div).html(Config.pleaseWait);

	/* SEND AJAX REQUEST TO UPDATE DB */
	if(oldValue==val){
		$("."+div).attr("onclick", "updateCol('"+col+"', '"+val+"', '"+pid+"')").html(val);	
	}else{
	var ajaxFile = "/updateColume";
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { col:col, pid:pid, value:val }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Success.Code==1){
				$("."+div).attr("onclick", "updateCol('"+col+"', '"+val+"', '"+pid+"')").html(val);	
			}else{
				$("."+div).attr("onclick", "updateCol('"+col+"', '"+val+"', '"+pid+"')").html(obj.Error.Text);	
			}
		});	
	}
};

var changeLanguage = function(lang){
	var selected = $("#language-chooser").val(); 
	var u = window.location.href;
	var newUrl = u.replace("/"+lang+"/", "/"+selected+"/");
	location.href = newUrl;
};

var updateMeSelect = function(col, pid){
	var div = "editable_"+col;
	var val = $("#updatable").val(); // new value
	$("."+div).html(Config.pleaseWait);

	var ajaxFile = "/citiesName";

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { id:val }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			var cityname = obj.Success.cityname;
			$("."+div).attr("onclick", "updateColSelect('"+col+"', '"+cityname+"', '"+pid+"', '"+val+"')").html(cityname);
		}
	});	

	/* SEND AJAX REQUEST TO UPDATE DB */
	var ajaxFile2 = "/updateColume";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile2,
		data: { col:col, pid:pid, value:val }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			
		}else{
			alert("error");
		}
	});	

	
};

var formCatalogAdd = function(catalogId, lang){
	var date = $("#date").val();
	var title = $("#title").val();
	var cover = $(".cover").val();
	var chooseDestination = serialize($("#chooseDestination").val());
	var chooseAdvantureType = serialize($("#chooseAdvantureType").val());
	var arrivaldeparture = $("#arrivaldeparture").val();
	var daysAndNights = $("#daysAndNights").val();
	var chooseTouristCount = $("#chooseTouristCount").val();
	var guests = $("#guests").val();
	var price = $("#price").val();

	var shortDescription = tinymce.get('shortDescription').getContent();
	var longDescription = tinymce.get('longDescription').getContent();

	var locations = $("#locations").val();

	var services = new Array();
	$(".subServices").each(function(e){
		var service = $(".allfields", this).attr("data-service");
		$(".allfields .input-field", this).each(function(){
			var title = (typeof $(".title", this).val() !== "undefined") ? $(".title", this).val() : ""; 
			var price = (typeof $(".price", this).val() !== "undefined") ? $(".price", this).val() : "";  
			if(typeof service !== "undefined"){
				services.push(service+"@@"+title+"@@"+price);
	    	}
		});	
	});
	var serialServices = serialize(services);
	var choosevisibiliti = $("#choosevisibiliti").val();
	var chooseSpecial_offer = $("#chooseSpecial_offer").val();

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);

	var ajaxFile = "/insertCatalog";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			catalogId:catalogId, 
			date:date, 
			title:title, 
			cover:cover, 
			chooseDestination:chooseDestination, 
			chooseAdvantureType:chooseAdvantureType, 
			arrivaldeparture:arrivaldeparture, 
			daysAndNights:daysAndNights, 
			chooseTouristCount:chooseTouristCount, 
			guests:guests, 
			price:price, 
			shortDescription:shortDescription, 
			longDescription:longDescription, 
			locations:locations, 
			choosevisibiliti:choosevisibiliti, 
			chooseSpecial_offer:chooseSpecial_offer, 
			serialServices:serialServices, 			
			serialPhotos:serialPhotos, 
			lang:lang			 
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			$(".modal-message-box").html(obj.Success.Text);
			scrollTop(); 
			setTimeout(function(){
				location.reload();
			}, 1000); 
		}else{
			$(".modal-message-box").html(obj.Error.Text);
		}
	});	

};


var askRemoveCatalog = function(val){
	var header = "<h4>შეტყობინება</h4><p class=\"modal-message-box\">გნებავთ წაშალოთ მონაცემი ?</p>";
	var footer = "<a href=\"javascript:void(0)\" onclick=\"removeProducts('"+val+"')\" class=\"waves-effect waves-green btn-flat\">დიახ</a>";
	footer += "<a href=\"javascript:void(0)\" class=\"waves-effect waves-green btn-flat modal-close\">დახურვა</a>";

	$("#modal1 .modal-content").html(header);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();
	scrollTop();
};

var removeProducts = function(val){
	var ajaxFile = "/removeProducts";
	if(typeof val == "undefined"){
		$(".modal-message-box").html("E4");
	}else{
		$.ajax({
			method: "POST",
			url: Config.ajax + ajaxFile,
			data: { val: val }
		}).done(function( msg ) {
			var obj = $.parseJSON(msg);
			if(obj.Error.Code==1){
				$(".modal-message-box").html(obj.Error.Text);
			}else if(obj.Success.Code==1){
				$(".modal-message-box").html(obj.Success.Text);
				location.reload();
			}else{
				$(".modal-message-box").html("E5");
			}
		});
	}
};

var editCatalog = function(idx, lang){
	var ajaxFile = "/editCatalog";
	var header = "<h4>რედაქტირება</h4><p class=\"modal-message-box\"></p>";
	var content = "<p>გთხოვთ დაიცადოთ...</p>";
	var footer = "<a href=\"javascript:void(0)\" id=\"modalButton\" class=\"waves-effect waves-green btn-flat\">რედაქტირება</a>";

	$("#modal1 .modal-content").html(header + content);
	$("#modal1 .modal-footer").html(footer);
	$('#modal1').openModal();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { idx: idx, lang:lang }
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			var errorText = "<p>" + obj.Error.Text +"</p>";
			$("#modal1 .modal-content").html(header + errorText);
		}else{
			var form = "<p>" + obj.form +"</p>";
			$("#modal1 .modal-content").html(header + form);

			$("#modalButton").attr({"onclick": obj.attr });

			$("#date").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");
			$("#arrival").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");
			$("#departure").datepicker({ dateFormat: "dd-mm-yy"}).attr("readonly","readonly");

			$("#photoUploaderBox").sortable({
		    	items: ".imageItem",
				update: function( event, ui ) {  }
			});
			$("#chooseDestination").material_select();
			$("#chooseAdvantureType").material_select();
			$("#choosevisibiliti").material_select();
			$("#chooseSpecial_offer").material_select();
			$("#chooseTouristCount").material_select();
			tiny(".tinymceTextArea");		
		}
	});
};

var formCatalogEdit = function(idx, lang){
	var date = $("#date").val();
	var title = $("#title").val();
	var cover = $(".cover").val();
	var chooseDestination = serialize($("#chooseDestination").val());
	var chooseAdvantureType = serialize($("#chooseAdvantureType").val());
	var arrivaldeparture = $("#arrivaldeparture").val();
	var daysAndNights = $("#daysAndNights").val();
	var chooseTouristCount = $("#chooseTouristCount").val();
	var guests = $("#guests").val();
	var price = $("#price").val();

	var shortDescription = tinymce.get('shortDescription').getContent();
	var longDescription = tinymce.get('longDescription').getContent();

	var locations = $("#locations").val();

	var services = new Array();
	$(".subServices").each(function(e){
		var service = $(".allfields", this).attr("data-service");
		$(".allfields .input-field", this).each(function(){
			var title = (typeof $(".title", this).val() !== "undefined") ? $(".title", this).val() : ""; 
			var price = (typeof $(".price", this).val() !== "undefined") ? $(".price", this).val() : "";  
			if(typeof service !== "undefined"){
				services.push(service+"@@"+title+"@@"+price);
	    	}
		});	
	});
	var serialServices = serialize(services);
	var choosevisibiliti = $("#choosevisibiliti").val();
	var chooseSpecial_offer = $("#chooseSpecial_offer").val();

	var photos = new Array();
	if($(".imageItem").length){
		$(".imageItem").each(function(){
			if($(".card .card-image .managerFiles", this).val()!=""){
				photos.push($(".card .card-image .managerFiles", this).val());
			}
		});
	}
	var serialPhotos = serialize(photos);

	var ajaxFile = "/updateCatalog";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: {  
			idx:idx, 
			date:date, 
			title:title, 
			cover:cover, 
			chooseDestination:chooseDestination,
			chooseAdvantureType:chooseAdvantureType,
			arrivaldeparture:arrivaldeparture, 
			daysAndNights:daysAndNights, 
			chooseTouristCount:chooseTouristCount, 
			guests:guests, 
			price:price, 
			shortDescription:shortDescription, 
			longDescription:longDescription, 
			locations:locations, 
			serialServices:serialServices, 
			choosevisibiliti:choosevisibiliti, 
			chooseSpecial_offer:chooseSpecial_offer, 
			serialPhotos:serialPhotos, 
			lang:lang			 
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			$(".modal-message-box").html(obj.Success.Text);
			scrollTop(); 
		}else{
			$(".modal-message-box").html(obj.Error.Text);
		}
	});	
};