//---------------------------------------------------------
//		Global Declaration
//---------------------------------------------------------

var service_list = [];

service_list.push({"service":"Aweber","fields":["listname","redirect_url","meta_adtracking"]});
service_list.push({"service":"CampaignMonitor","fields":["cm_account_name","cm_id"]});
service_list.push({"service":"ConstantContact","fields":["cc_username","cc_password","cc_list_name","cc_redirect_url"]});
service_list.push({"service":"ConvertKit","fields":["id","redirect_url"]});
service_list.push({"service":"Feedburner","fields":["uri"]});
service_list.push({"service":"Feedblitz","fields":["sub"]});
service_list.push({"service":"GetResponse","fields":["campaign_token", "start_day", "thankyou_url"]});
service_list.push({"service":"iContact","fields":["listid","clientid","specialid","formid","redirect_url"]});
service_list.push({"service":"InfusionSoft","fields":["account_subdomain","inf_form_xid", "infusionsoft_version"]});
service_list.push({"service":"InInbox","fields":["mid","lid", "wid"]});
service_list.push({"service":"Jetpack","fields":["redirect_url"]});
service_list.push({"service":"MadMimi","fields":["webform_id"]});
service_list.push({"service":"MailChimp","fields":["action_url","SIGNUP"]});
service_list.push({"service":"MailChimp_SingleOptin","fields":["api_key","list_id",'redirect_url']});
service_list.push({"service":"MailPoet","fields":["list_id", "redirect_url"]});
service_list.push({"service":"Ontraport","fields":["uid"]});
service_list.push({"service":"SendInBlue","fields":["js_id","listid"]});
service_list.push({"service":"ActiveCampaign","fields":["domain","u","f"]});
service_list.push({"service":"Custom","fields":["action_url","email_field_name","name_field_name", "hidden_filed1","hidden_filed1_value","hidden_filed2","hidden_filed2_value","hidden_filed3","hidden_filed3_value"]});

var email_service_option = {};

//---------------------------------------------------------


var fileref = ""; 
var curfont = Array;
var curfont2 = "";
curfont["pm_h1"] = "";
curfont["pm_h2"] = "";

//---------------------------------------------------------

var oDoc, sDefTxt;


//---------------------------------------------------------
//		Document Ready
//---------------------------------------------------------

jQuery(document).ready(function() {	
	
	pm_style1=document.createElement("link");
	pm_style1.setAttribute("rel", "stylesheet");
	pm_style1.setAttribute("type", "text/css");
	document.getElementsByTagName("head")[0].appendChild(pm_style1);

	pm_style2=document.createElement("link");
	pm_style2.setAttribute("rel", "stylesheet");
	pm_style2.setAttribute("type", "text/css");+
	document.getElementsByTagName("head")[0].appendChild(pm_style2);
	
	pmfb_custom_style = document.createElement("STYLE");
  	pmfb_custom_style.setAttribute("id", "pmfb_custom_btn_style");
  	pmfb_custom_style.type = 'text/css';
  	document.getElementsByTagName('head')[0].appendChild(pmfb_custom_style);
	
	jQuery(document).keyup(function(e) {		 
	  	if(e.keyCode == 27) {
		  jQuery(document).click();
	  	}	  
	});
	
	jQuery("#pm_box_width, #pm_box_tmargin, #pm_box_bmargin").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ( jQuery.inArray(event.keyCode,[46,45,8,9,27,13,190,173]) !== -1 ||
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39 && event.keyCode == 173)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
		
});




//---------------------------------------------------------
//		Inline Edit Plugin 
//---------------------------------------------------------


jQuery.fn.inlineEdit = function(edit_type) {
	
	if(edit_type != "color"){
		jQuery(this).addClass("pm_hover_icon_h");
		jQuery(this).hover(function(e) {
			jQuery(this).addClass("pm_hover_icon");
			jQuery(this).removeClass("pm_hover_icon_h");

		}, function() {			
			jQuery(this).removeClass("pm_hover_icon");
			jQuery(this).addClass("pm_hover_icon_h");
		}
		);
	}

	if(edit_type == "service") {
		jQuery(this).append("<img src='"+pm_plugin_url+"/images/exclamation.png' id='pm_exclamation_icon' />");
	}
	
	if(edit_type == "image") {
		jQuery("#pm_image").append("<div id='pm_change_picture'><img src='"+pm_plugin_url+"images/change_picture.png' title='Change Picture' /> <div id='pm_picture_size_sug'>"+jQuery("#pm_image").attr("pm_image_size")+"</div></div>");
	}
	
	if(edit_type == "video") {
		jQuery("#pm_video_con").prepend("<img src='"+pm_plugin_url+"/images/exclamation.png' style='position:absolute;left:-40px;' id='pm_video_edit'/>");
	}	
	
	if(edit_type == "button") {			
    }	
	
	if(edit_type == "color"){
		var elem = jQuery(this);
		var elem_id = jQuery(elem).attr("id");		
		var bgcolr = jQuery(elem).css("background-color");		
		
		var colorpicker_div = "<div class='pmie_bgcolor_btn' id="+ elem_id+"_cpbtn >" +
		"<div class='pmie_bgcolorpicker_con' id="+ elem_id+"_cpc >" +
		"<div class='pmie_bgcolorpicker' id="+ elem_id+"_cp ></div>" +
		"</div>" +
		"</div>";		 
		jQuery(this).prepend(colorpicker_div);

		jQuery('#'+elem_id+"_cpbtn").click(function(e) {
			
			if(jQuery('#'+elem_id+"_cpc").css("display") == "none"){
				jQuery(document).click(function () { 
					jQuery('#'+elem_id+"_cpc").hide();
				});
				jQuery('#'+elem_id+"_cpc").show();
				if(jQuery(elem).css("background-image") == "") {
					var hexclr = colorToHex(bgcolr)
					jQuery(".farbtastic_txt_hex").val(hexclr);
				}
				 
				e.stopPropagation();
			}	else {
				jQuery('#'+elem_id+"_cpc").hide();
				jQuery(document).unbind('click');
			}		
		});

		jQuery('#'+elem_id+"_cp").farbtastic(function() {
			var theColorIs =jQuery.farbtastic('#'+elem_id+"_cp").color; 
			if(jQuery(elem).attr("gradient") != null) { 
				jQuery(elem).attr("gradient",theColorIs);	
				var rules = jQuery(elem).css("background-image"); 
			 	var new_rules = rules.replace(/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/,theColorIs);		 		
	 	    	jQuery(elem).css("background-image",new_rules); 	
		 	} else {		 		
		 		jQuery(elem).css("background-color", theColorIs);
		 	}
		}); 

		jQuery('#'+elem_id+"_cpc").click(function(e) {
			e.stopPropagation();
		});
	 
	}
	 
	jQuery(this).click(function(e) {
		var elem = jQuery(this);

	   jQuery(document).click();	
	
		if(edit_type == "text") {
			elem.after(jQuery("<div id='pmie_h1_div'>" +
				     "<input id='pmie_h1_txt' type='text' maxlength='72' />" +
				     "<div id='pmie_text_tblr_con'>" +
				         "<div id='pmie_text_tblr'>" +
				             "<div id='pmie_text_fontcolor' >&nbsp;</div>" +
				             "<div id='pmie_text_colorpick'></div>" +
						 "</div>" +
					 "</div>" +
						 "</div>"));
			jQuery("#pmie_h1_txt").val(jQuery(this).text());
			
			jQuery("#pmie_h1_txt").focus();
            
			jQuery("#pmie_h1_txt").css("font-family",curfont[elem.attr("id")]);

			get_fonts("text", elem.attr("id"));
			
			var color = jQuery(this).css("color");
			jQuery('#pmie_h1_txt').css("color",color);
			var fontsize = jQuery(this).css("font-size");
			jQuery('#pmie_h1_txt').css("font-size",fontsize);
			var fontfamily = jQuery(this).css("font-family");
			jQuery('#pmie_h1_txt').css("font-family",fontfamily);
			var width = jQuery(this).css("width");
			jQuery('#pmie_h1_txt').css("width",width);
			var height = jQuery(this).css("height");
			jQuery('#pmie_h1_div').css("height",height);			
			var line_height = jQuery(this).css("line-height");
			jQuery('#pmie_h1_div').css("line-height",line_height);
			var text_align = jQuery(this).css("text-align");
			jQuery('#pmie_h1_txt').css("text-align",text_align);            
			var font_weight = jQuery(this).css("font-weight");
			jQuery('#pmie_h1_txt').css("font-weight",font_weight);   			
			
			jQuery(this).hide();
			e.stopPropagation();
			
			jQuery("#pmie_text_fontcolor").click(function() {
				if(color != "transparent") {
					 var hexclr = colorToHex(color)
					 jQuery(".farbtastic_txt_hex").val(hexclr);
				 }
				jQuery('#pmie_text_colorpick').toggle();
			});
			
			jQuery('#pmie_text_colorpick').farbtastic(function() {
				var theColorIs =jQuery.farbtastic('#pmie_text_colorpick').color;
				  jQuery("#pmie_h1_txt").css("color",theColorIs);				  
				  elem.css("color",theColorIs); 
			}); 			

			jQuery("#pmie_h1_div").click(function(e) {
				e.stopPropagation();
			});

			jQuery(document).click(function(){
				if (jQuery("#pmie_h1_txt").val() != "") {
					elem.text(jQuery("#pmie_h1_txt").val());
					
				}
				jQuery("#pmie_h1_div").remove();
				elem.show();
				jQuery(document).unbind('click');
			});

			jQuery('#pmie_h1_txt').keypress(function(event){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					jQuery(document).click();
				}		 
			});	
			
		} else if(edit_type == "textarea") {

			var textarea_div = "<div id='pmie_rt_div' >" +
							       "<div id='pmie_rt_txtbox' contenteditable='true' class='pm_description'></div>" +
							       "<div id='pmie_rt_tblr_con'>" +
							           "<div id='pmie_rt_tblr'>" +
							               "<div class='pmie_rt_tblr' id='pmie_bold' onclick=\"formatDoc('bold');\" ><img src='"+pm_plugin_url+"/images/px.png' /></div>" +
							               "<div class='pmie_rt_tblr' id='pmie_italic'  onclick=\"formatDoc('italic');\" ><img src='"+pm_plugin_url+"/images/px.png' /></div>" +
							               "<div class='pmie_rt_tblr' id='pmie_underline'  onclick=\"formatDoc('underline');\" ><img src='"+pm_plugin_url+"/images/px.png' /></div>" +
							               "<div class='pmie_rt_tblr' id='pmie_list' onclick=\"formatDoc('insertunorderedlist');\" ><img src='"+pm_plugin_url+"/images/px.png' /></div>" +
							               "<div class='pmie_rt_tblr' id='pmie_fontcolor'>&nbsp;</div>" +
							               
							               "<div id='pmie_rt_colorpick'></div>" +
							           "</div>" +
							       "</div>" +
							   "</div>";


			elem.after(textarea_div);			
			var html =jQuery(this).html();
			jQuery('#pmie_rt_txtbox').html(html);
			jQuery('#pmie_rt_txtbox').focus();
			jQuery('#pmie_rt_txtbox').css("font-family",curfont2);
			
			var width = jQuery('#pm_description').css("width");
			jQuery('#pmie_rt_txtbox').css("width",width);
			var height = jQuery('#pm_description').css("height");
			jQuery('#pmie_rt_txtbox').css("height",height);			
			var height = jQuery('#pm_description').css("height");
			jQuery('#pmie_rt_txtbox').css("height",height);	

			var color = jQuery('#pm_description').css("color"); 
			jQuery("#pmie_fontcolor").click(function() {
				if(color != "transparent" && color != undefined) {
					 var hexclr = colorToHex(color)
					 jQuery(".farbtastic_txt_hex").val(hexclr);
				 }
				
				jQuery('#pmie_rt_colorpick').toggle();
			});
			
			jQuery('#pmie_rt_colorpick').farbtastic(function() {
				var theColorIs =jQuery.farbtastic('#pmie_rt_colorpick').color; 
				  jQuery('#pmie_rt_txtbox').focus().select();
				  jQuery('#pmie_rt_txtbox').css("color",theColorIs);				  
				  jQuery('#pm_description').css("color",theColorIs); 
				 //formatDoc('forecolor',theColorIs);
				  elem.css("color",theColorIs); 
			}); 
			
			get_fonts("textarea");
			jQuery(this).hide();
			
			
			initDoc();
			e.stopPropagation();


			jQuery("#pmie_rt_div").click(function(e) {
				e.stopPropagation();
			});


			jQuery(document).click(function(){
				jQuery('#pmie_rt_txtbox li').css('background-image','');
				if (jQuery('#pmie_rt_txtbox').text() != "") {
					elem.html(oDoc.innerHTML);
				}
				jQuery("#pmie_rt_div").remove();
				
				elem.show();
				jQuery(document).unbind('click');
			});
			
			
			jQuery('#font_color').click(function(){
				jQuery('#font_picker').toggle();
			}); 
	
		}else if(edit_type == "service"){
			jQuery('.pm_cta_button').click(function(event){
				event.preventDefault();
				event.stopPropagation();
			});

				
			if(document.getElementById("pmie_email_service_list_con") == null){
				var service_select_div = "<div id='pmie_email_service_list_con' ><div id='pm_email_service_list' >" +
				"</div></div> ";
				elem.append(service_select_div);	
				
				var select_service = document.createElement("select");
				select_service.setAttribute("id", "email_service_list");
				jQuery('#pm_email_service_list').prepend(select_service);	
				jQuery('#email_service_list').append(jQuery("<option></option>").attr("value","").text("Select Service"));
				for (var i = 0; i < service_list.length; i++) { 
					var ser = service_list[i]["service"];
					if(email_service_option["service"] == ser) {
						jQuery('#email_service_list').append(jQuery("<option selected></option>").attr("value",ser).text(ser));
					} else {
						jQuery('#email_service_list').append(jQuery("<option></option>").attr("value",ser).text(ser));
					}
				}
				var service_help = document.createElement('a');
				service_help.setAttribute("href","http://plugmatter.com/docs/");
				service_help.setAttribute("id", "service_help");
				service_help.innerHTML = 'HELP';
				jQuery("#pm_email_service_list").append(service_help);
			}
			if(email_service_option["service"] != undefined) { email_service_select_change(); }
				e.stopPropagation();
				jQuery('#email_service_list').change(function() { 
				email_service_select_change();
			});	
			
			jQuery("#pmie_email_service_list_con").click(function(e) {
				e.stopPropagation();
			});

			jQuery(document).click(function(){
				jQuery("#pmie_email_service_list_con").remove();
				elem.show();
				jQuery(document).unbind('click');
			});
	    } else if(edit_type == "image") {
	    	
	    } else if(edit_type == "video") {
			jQuery("#pmie_video_edit_con").remove();
			var curvideo_url = jQuery("#pm_video").attr("video_url");
    		var video_edit_div = "<div id='pmie_video_edit_con' ><div id='pmie_video_edit_inner' >Enter YouTube, Vimeo Or Wistia URL:<br>" +
								 "<input type='text' id='pm_video_url' size='30' value='"+curvideo_url+"'>&nbsp; <input type='button' value='save' id='pm_video_save'>" +
								 "</div></div>";
   			elem.append(video_edit_div);
			e.stopPropagation();			
			jQuery("#pmie_video_edit_con").mousedown(function(e) {
				e.stopPropagation();
			});	
			jQuery("#pmie_video_edit_con").click(function(e) {
				e.stopPropagation();
			});				
			jQuery(document).mousedown(function(e){
				if(e.which != 3) {
			    jQuery("#pmie_video_edit_con").remove();
			    elem.show();
			    jQuery(document).unbind('click');	
				}
			});				
			jQuery("#pm_video_url").keyup(function(event) {
				if(event.keyCode == "13") {
					var pm_vid_embed_url = pm_is_video(jQuery("#pm_video_url").val())
					if(pm_vid_embed_url) {
						jQuery("#pm_video").attr("src",pm_vid_embed_url);
						jQuery("#pm_video").attr("video_url", jQuery("#pm_video_url").val());
						jQuery("#pmie_video_edit_con").remove();
						jQuery(document).unbind('click');								
						event.stopPropagation();
					} else {
						alert("Invalid video URL, please enter a YoutTube or Vimeo video URL");
					}
				}
			});
			jQuery("#pm_video_save").click(function(e) {
				var pm_vid_embed_url = pm_is_video(jQuery("#pm_video_url").val())
				if(pm_vid_embed_url) {
					jQuery("#pm_video").attr("src",pm_vid_embed_url);
					jQuery("#pm_video").attr("video_url", jQuery("#pm_video_url").val());
					jQuery("#pmie_video_edit_con").remove();
					jQuery(document).unbind('click');							
					e.stopPropagation();
				} else {
					alert("Invalid video URL, please enter a YoutTube or Vimeo video URL");
				}
			});
        } else if(edit_type == "button"){
			e.preventDefault();
			e.stopPropagation();
   			if(document.getElementById("pmie_cta_button_selection_con") == null || jQuery('#pmie_cta_button_selection_con').css('display') == 'none'){
    		   jQuery("#pmie_cta_button_selection_con").remove();
    		   jQuery("#pm_button_div").find('a').button_editor();	    		   
	   		} else {
	   			jQuery("#pmie_cta_button_selection_con").remove();
	   		}

        } else if(edit_type == "cta_button") {
        	e.preventDefault();
			e.stopPropagation();
   			if(document.getElementById("pmie_cta_button_selection_con") == null || jQuery('#pmie_cta_button_selection_con').css('display') == 'none'){
    		   jQuery("#pmie_cta_button_selection_con").remove();
    		   jQuery("#pm_cta_button_div").find('a').button_editor();	    		   
	   		} else {
	   			jQuery("#pmie_cta_button_selection_con").remove();
	   		}
   		}
	});	 

	jQuery('#pm_change_picture').click(function(e){				
        var media_pick_shown = (jQuery('.media-modal').is(':visible')) ? true : false;
         e.preventDefault();
        if(media_pick_shown == false) {
        var file_frame;

            file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                        text: jQuery( this ).data( 'uploader_button_text' ),
                    },
            multiple: false
        });

        file_frame.on( 'select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();
            jQuery('#pm_image').css("background-image", "url("+attachment.url+")");
            file_frame.remove();
        });     
        file_frame.open();
        }
	});
};




//---------------------------------------------------------
//		Functions 
//---------------------------------------------------------

function pm_is_video(video_url) {
    var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if (pattern.test(video_url)) {
    } else {
        return false;
    } 
	   
	if(video_url.indexOf("youtube.com/") != -1) {
		var params = pm_get_url_params(video_url);
		if(params.v != undefined) {
			return "http://www.youtube.com/embed/" + params.v;	
		} else {
			return false;
		}
	} else if(video_url.indexOf("vimeo.com/") != -1) {
		var match = video_url.match(/(http|https):\/\/(www\.)?vimeo.com\/(\d+)($|\/)/);
		if (match){
			return "//player.vimeo.com/video/" + match[3] + "?badge=0&byline=0&portrait=0&title=0";
		}else{
			return false;
		}
	} else if(video_url.indexOf("fast.wistia.net/embed/iframe/") != -1) {
		return video_url;
	} else {
		return false;
	}
}

function pm_get_url_params(url) {
  var query_string = {};
  var query = url.split("?")[1];
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    	// If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = pair[1];
    	// If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]], pair[1] ];
      query_string[pair[0]] = arr;
    	// If third or later entry with this name
    } else {
      query_string[pair[0]].push(pair[1]);
    }
  } 
  return query_string;
}

function get_fonts(area, id) {

	if(area == "text"){

		//var select1 = document.createElement("select");
		//select1.setAttribute("name", "mySelect");
		//select1.setAttribute("id", "variants");
		//jQuery('#pmie_text_tblr').prepend(select1);
		//jQuery('#variants').append(jQuery("<option></option>").attr("value","").text("Select Variant"));
		var select = document.createElement("select");
		select.setAttribute("name", "family");
		select.setAttribute("id", "family");
        select.setAttribute("class", "family-"+id);
		jQuery('#pmie_text_tblr').prepend(select);
		jQuery('#family').append(jQuery("<option></option>").attr("value","").text("Select font"));

		for (var i = 0; i < fonts.length; i++) { 
			var fam = fonts[i]["family"];
			var variants = fonts[i]["variants"];
			jQuery('#family').append(jQuery("<option></option>").attr("value",fam ).text(fam).attr("variants",variants ));
		}
		var fontfamily = jQuery("#"+id).css("font-family");
		jQuery("#pmie_text_tblr select#family option[value='"+fontfamily+"']").attr('selected', 'selected');
		
	} else if(area == "textarea") {
		
		var select_size = document.createElement("select");
		select_size.setAttribute("id", "select_size_textarea");
		select_size.setAttribute("onchange", "formatDoc('fontsize',this[this.selectedIndex].value);");
		jQuery('#pmie_rt_tblr').prepend(select_size);
		jQuery('#select_size_textarea').append(jQuery("<option></option>").attr("value","").text("Font Size"));
		for (var i = 10; i <= 26; i++) { 
			jQuery('#select_size_textarea').append(jQuery("<option></option>").attr("value",i+"px" ).text(i));
		}
		
		var select = document.createElement("select");
		select.setAttribute("name", "family");
		select.setAttribute("id", "family_textarea");
		jQuery('#pmie_rt_tblr').prepend(select);	
		jQuery('#family_textarea').append(jQuery("<option></option>").attr("value","").text("Select font"));
		for (var i = 0; i < fonts.length; i++) { 
			var fam = fonts[i]["family"];
			jQuery('#family_textarea').append(jQuery("<option></option>").attr("value",fam ).text(fam));
		}
		
		var color = jQuery('#pm_description').css("color"); 
		jQuery('#pmie_rt_txtbox').css("color",color);
		var fontsize = jQuery('#pm_description').css("font-size");
		fontsize = Math.round(fontsize.substring(0, fontsize.length - 2)) + "px";
		jQuery('#pmie_rt_txtbox').css("font-size",fontsize);
		jQuery('#pmie_rt_tblr select#select_size_textarea option[value='+fontsize+']').attr('selected', 'selected');
		var fontfamily = jQuery('#pm_description').css("font-family");

		fontfamily = fontfamily.replace(/["']/g, "");

		jQuery('#pmie_rt_txtbox').css("font-family",fontfamily);
		jQuery("#pmie_rt_tblr select#family_textarea option[value='"+fontfamily+"']").attr('selected', 'selected');
	}

	jQuery('#family').change(function(e) { 
		//var vr=jQuery("select#family option:selected").attr("variants");
		//var n=vr.split(",");
		//var sel = jQuery("select#variants");
		//sel.find("option").remove();
		//jQuery('#variants').append(jQuery("<option></option>").attr("value","").text("Select Varient"));
		//for (var i = 0; i < n.length; i++) { 
		//	jQuery('#variants').append(jQuery("<option></option>").attr("value",n[i] ).text(n[i]));
		//}
		var txt_family=jQuery("#family option:selected").val();
		font_txt_family(txt_family, jQuery(e.target).attr("class"));
	});		

	jQuery("#variants").change(function() {	
		var vari=jQuery("#variants option:selected").val();
		var fam=jQuery("#family option:selected").val();
		//alert(vari + " "+ fam);
		fun(fam,vari);
	});

	jQuery('#family_textarea').change(function() { 
		var fam=jQuery("select#family_textarea option:selected").val();
		font_family(fam);
	});		
}


function email_service_select_change() {
	var val=jQuery("select#email_service_list option:selected").val();
	if(val == ""){alert("Please select any Email Marketing Service.");jQuery('#email_service_form').remove();return false;}
	jQuery('#email_service_form').remove();
	jQuery('#pm_email_service_list').append("<div id='email_service_form' ></div>");
	for (var i = 0; i < service_list.length; i++) { 
		if(service_list[i]["service"] == val) {
			for(var j=0;j<service_list[i]["fields"].length; j++) {
				jQuery('#email_service_form').append("<div id=service_lable_"+service_list[i]["fields"][j]+ "> "+service_list[i]["fields"][j]+"</div>");
				if(email_service_option[service_list[i]["fields"][j]] != undefined) {
					jQuery('#email_service_form').append('<div id= "service_field_'+service_list[i]["fields"][j]+'"><input type="text" value="'+email_service_option[service_list[i]["fields"][j]]+'" class="'+val+'_form"  id="'+service_list[i]['fields'][j]+'" ></div>');
				} else {
					jQuery('#email_service_form').append('<div id="service_field_'+service_list[i]["fields"][j]+'" ><input type="text" class="'+val+'_form" id="'+service_list[i]["fields"][j]+'"></div>');
				}
			}
			jQuery("#service_help").attr({href:"http://plugmatter.com/docs/feature-box/email-services-configuration",title:val, target:"_balnk"});
		}
	}
	jQuery('#email_service_form').append("<input type='button' value='save' id='pm_email_save_service'>");

	
	jQuery("#pm_email_save_service").click(function(e){
		var error = '0'; 
		var val = jQuery("select#email_service_list option:selected").val();
		email_service_option = {};	
		email_service_option["service"] = val;
		jQuery('.'+val+"_form").each(function(){
			var name = jQuery(this).attr("id");
			var value = jQuery(this).val();				
			if(value == ""){						
				jQuery('#'+"service_lable_"+name).css("color","red");
				error = '1';
			}else if(name.split("_")[1] == "url"){ 
				
	    		value = value.replace(/^["'](.*)["']$/, '$1');
	    		   			
				var check = validate(value);
				if(check == true){
					jQuery('#'+"service_lable_"+name).css("color","black");
					email_service_option[name] = value;
					error = '0';
				}else{
					jQuery('#'+"service_lable_"+name).css("color","red");
					error = '1';
				}
			} else{
					jQuery('#'+"service_lable_"+name).css("color","black");
					email_service_option[name] = value;
					error = '0';
			}				
		});
		
		if(error == '0'){
			jQuery("#pm_email_service_list").hide();
			jQuery("#pm_exclamation_icon").attr('src',pm_plugin_url+"/images/tick-icon.png");
			jQuery("#pm_send_email").removeAttr('disabled');
			//jQuery("#pmie_email_service_list_con").remove();
			jQuery("#pmie_email_service_list_con").hide();
		}else{
			alert("All fields are mandatory.");
		}
	});		
	
}


function fun(font,variant) {	
	curfont1 = font.replace(/ /g,"+");
	curfont1 = curfont1.replace(/["']/g, "");
	var filename = "http://fonts.googleapis.com/css?family="+curfont1+":"+variant;			
	pm_style1.setAttribute("href", filename);			
	document.getElementById("pmie_h1_txt").style.fontFamily=curfont1;	
	document.getElementById("pm_h1").style.fontFamily=curfont1;
}

function get_font_h1(font) {	
	curfont1 = font.replace(/ /g,"+");
	curfont1 = curfont1.replace(/["']/g, "");
	var filename = "http://fonts.googleapis.com/css?family="+curfont1;			
	pm_style1.setAttribute("href", filename);		
	document.getElementById("pm_h1").style.fontFamily=font;
}

function get_font_txtarea(font) {	
	curfont2 = font.replace(/ /g,"+");
	curfont2 = curfont2.replace(/["']/g, "");
	var filename = "http://fonts.googleapis.com/css?family="+curfont2;			
	pm_style2.setAttribute("href", filename);				
	document.getElementById("pm_description").style.fontFamily=font;
}

function update_fun() {	
    var fnt_list = "";
    	
    for(fnt in curfont) {
	var add_new_font = curfont[fnt];
	add_new_font = add_new_font.trim();	
	if(add_new_font){
		add_new_font = add_new_font.replace(/["']/g, "");
	       	fnt_list += (fnt_list)?  "|"+ add_new_font : add_new_font;
	}
    }
    //fnt_list = fnt_list.substring(0,fnt_list.length - 1);
	var filename = "http://fonts.googleapis.com/css?family="+fnt_list;			
	pm_style1.setAttribute("href", filename);			
}

function update_font_family(fam) {
	curfont2 = fam.replace(/ /g,"+");
	curfont2 = curfont2.replace(/["']/g,'');
	var filename = "//fonts.googleapis.com/css?family="+curfont2;			
	pm_style2.setAttribute("href", filename);			
	document.getElementById("pm_description").style.fontFamily=fam;
}

function font_family(fam) {
	curfont2 = fam.replace(/ /g,"+");
	curfont2 = curfont2.replace(/["']/g,'');
	var filename = "//fonts.googleapis.com/css?family="+curfont2;			
	pm_style2.setAttribute("href", filename);			
	document.getElementById("pmie_rt_txtbox").style.fontFamily=fam;
	document.getElementById("pm_description").style.fontFamily=fam;
}

function font_txt_family(family, pid){
    id = pid.split("-")[1];
	curfont[id] = family.replace(/ /g,"+");
    var fnt_list = "";
    for(fnt in curfont) {
    	var add_new_font = curfont[fnt];
	add_new_font = add_new_font.trim();	
	if(add_new_font){
		add_new_font = add_new_font.replace(/["']/g, "");
	       	fnt_list += (fnt_list)?  "|"+ add_new_font : add_new_font;
	}
    }
    //fnt_list = fnt_list.substring(0,fnt_list.length - 1);
	var filename = "//fonts.googleapis.com/css?family="+fnt_list;			
	pm_style1.setAttribute("href", filename);			
	document.getElementById("pmie_h1_txt").style.fontFamily=family;	
	document.getElementById(id).style.fontFamily=family;
}

function validate(url) {		 

    var pattern = /((ftp|http|https):\/\/)?(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
       if (pattern.test(url)) {
           return true;
       }else{
       	alert("Please enter a valid URL");
           return false;
       } 
           
}

function initDoc() {
	oDoc = document.getElementById("pmie_rt_txtbox");
	sDefTxt = oDoc.innerHTML;

}

function formatDoc(sCmd, sValue) {
	if(sCmd == "fontsize") {
		jQuery('#pm_description').css("font-size", sValue);
		jQuery('#pmie_rt_txtbox').css("font-size", sValue);
	} else {
		document.execCommand(sCmd, false, sValue); 
		oDoc.focus();
	}
}

function colorToHex(color) {
	if(color == undefined) { return "#000"; }
	if (color.substr(0, 1) === '#') {
        return color;
    }
    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
    
    var red = parseInt(digits[2]);
    var green = parseInt(digits[3]);
    var blue = parseInt(digits[4]);
    
    var rgb = blue | (green << 8) | (red << 16);
    //return digits[1] + "#" +rgb.toString(16);  
	return digits[1]+"#" + (function(h){
	return new Array(7-h.length).join("0")+h
	})(rgb.toString(16).toUpperCase())
}