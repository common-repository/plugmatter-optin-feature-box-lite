<?php 
global $wpdb;
$siteurl = get_option('siteurl');
$table = $wpdb->prefix.'plugmatter_templates';
// global params
$id="";
$temp_name="";
$base_temp_name="";
$pm_box_width="0";
$pm_box_tmargin="0";
$pm_box_bmargin="0";
$params = "";
$pm_custom_css = "\" \"";
$pm_display_fields = '';


if($_GET['action']=="edit" && $_GET['template_id']!='') {
	$temp_id= intval($_GET['template_id']);
	$fivesdrafts = $wpdb->get_results($wpdb->prepare("SELECT id,temp_name,base_temp_name,params FROM $table WHERE id=%d", $temp_id));
	foreach ( $fivesdrafts as $fivesdraft ) {
	 	$id=$fivesdraft->id;
	 	$temp_name=$fivesdraft->temp_name;
	 	$base_temp_name=$fivesdraft->base_temp_name;
    	$params = $fivesdraft->params;
    	if(!empty($params)){
    		$getalign = json_decode($params);       	
       		foreach($getalign as $align) {
				if($align->type == "alignment") {
					$pm_box_width = $align->width;
					$pm_box_tmargin = $align->top_margin;
					$pm_box_bmargin = $align->bottom_margin;					
				
				}
				if($align->type == "pm_custom_css"){
				 	$pm_custom_css = json_encode($align->pm_custom_css); 
					break;
				}
			}	
       	}     	
	}
}

function get_pages_list() {
	$pages = get_pages();
	$list = array();
	foreach ($pages as $page_data) {
		$list[] = array("id"=>$page_data->ID,"title"=>escapeJsonString(addslashes($page_data->post_title)));
	}
	print json_encode($list);
}

function escapeJsonString($value) {  
    $escapers =     array("\'");
    $replacements = array("\\u0027");
    return str_replace($escapers, $replacements, $value);
}

?>


<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>Template Editor</h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url("/images/logo.png", __FILE__ ); ?>' height='35'></div>
		<div class='pmadmin_body'>
			<form name='form1' action="<?php echo admin_url("admin.php?page=pmfb_template"); ?>" method="POST">
				<table class="pm_form_table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="name">
								Name Your Template:
								</label>
							</th>
							<td>
								<input id="title" class="regular-text" type="text" required="true" value="<?php if($temp_name){echo $temp_name;} ?>" name="temp_name">
								<input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" >
								<input type="hidden" name="template_id" value="<?php echo $id; ?>" >
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="base_template">
								Select a Base Template:
								</label>
							</th>
							<td>
								<?php if($base_temp_name != ""){echo "<div id='base_temp_name' >".$base_temp_name."</div>";}else{ ?>
								<select name="base_temp_name" id="base_temp_name">
								<option selected=selected value='' ></option>
								<?php
								$dir = plugin_dir_path(__FILE__) . "templates/";
								$list = scandir($dir);
								foreach ($list as $v) {	
								if(($v != ".") && ($v != "..")){
								?>
								<option value="<?php echo $v; ?>"  <?php if($base_temp_name == $v){echo "selected=selected" ;} ?> ><?php echo $v; ?></option>
								<?php 
								} }
								?>
								<option value="user_designed_template">Your Custom Design</option>
								</select>
								<?php } ?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="base_template">
								Alignment (In Pixels):
								</label>
							</th>
							<td>
								Width: <input type='text' size='4' maxlength='4' name='pm_box_width' id='pm_box_width' value='<?php echo $pm_box_width; ?>' >&nbsp;&nbsp;&nbsp;&nbsp;
							    Top Margin: <input type='text' size='3' maxlength='3' name='pm_box_tmargin' id='pm_box_tmargin' value='<?php echo $pm_box_tmargin; ?>' >&nbsp;&nbsp;&nbsp;&nbsp;
								Bottom Margin: <input type='text' size='3' maxlength='3' name='pm_box_bmargin' id='pm_box_bmargin' value='<?php echo $pm_box_bmargin; ?>'>&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
						</tr>		
						<tr valign="top" id="pm_sel_fields">
							<th scope="row">
								<label for="base_template">
									Select Fields to display
								</label>
							</th>
							<td>
								<input type='radio' class="pm_display_fields" id="pm_email_fname" name='pm_display_fields' value='pm_email_fname' > Email and First Name &nbsp;&nbsp;&nbsp;&nbsp;
								<input type='radio' class="pm_display_fields" id="pm_email_only" name='pm_display_fields' value='pm_email_only' > Email  &nbsp;&nbsp;&nbsp;&nbsp;
								<input type='radio' class="pm_display_fields" id="pm_cta_btn" name='pm_display_fields' value='pm_cta_btn' > CTA Button  &nbsp;&nbsp;&nbsp;&nbsp;			    
							</td>
						</tr> 			
						<tr>
							<td colspan="2">
								<div id="ajax_load_temp"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2"><a id="pm_add_custom_css" href="#">Custom CSS (Advance Users)</a><br/>
							<textarea name='pm_custom_css' id='pm_custom_css' rows="10" cols="45" style="display:none;"></textarea></td>
						</tr>
						<tr>
							<td colspan="2"><br>
								<input class="pm_primary_buttons" id="save_btn" type="button" value="     Save Template    "> &nbsp;&nbsp;
								<input class="pm_secondary_buttons" id="cancel_btn" type="button" value=" Cancel " onclick="location.href='<?php echo admin_url("admin.php?page=pmfb_template"); ?>'">
								<div class="clear"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<div  id='pm_hover_icon' >&nbsp;</div>
		</div>        
		<div class="clear"></div>	
		<script type="text/javascript">
			jQuery(document).ready(function(){ 

				pm_temp_style=document.createElement("link");
				pm_temp_style.setAttribute("rel", "stylesheet");
				pm_temp_style.setAttribute("type", "text/css");
				document.getElementsByTagName("head")[0].appendChild(pm_temp_style);


				pm_temp_img_style=document.createElement("link");
				pm_temp_img_style.setAttribute("rel", "stylesheet");
				pm_temp_img_style.setAttribute("type", "text/css");
				pm_temp_img_style.setAttribute("id", "pm_img_style");
				document.getElementsByTagName("head")[0].appendChild(pm_temp_img_style);

				pm_temp_style2=document.createElement("link");
				pm_temp_style2.setAttribute("rel", "stylesheet");
				pm_temp_style2.setAttribute("type", "text/css");
				document.getElementsByTagName("head")[0].appendChild(pm_temp_style2);

				pm_custom_style = document.createElement("STYLE");
				pm_custom_style.setAttribute("id", "pm_custom_style");
				pm_custom_style.type = 'text/css';
				document.getElementsByTagName('head')[0].appendChild(pm_custom_style);

				var temp_name = '<?php echo $temp_name; ?>';
				var pm_site_url = '<?php echo get_option('siteurl');?>';
				var admin_url = '<?php echo admin_url("admin-ajax.php?action=plug_load_template&data=$base_temp_name") ?>';
				

				var pm_codemirror = CodeMirror.fromTextArea(document.getElementById("pm_custom_css"), {
									            lineNumbers: true,
			    						        mode: "css"
			  						        });
				
				if(String(<?php echo $pm_custom_css; ?>) !== ""){
			  		var pm_codemirror_value= <?php echo $pm_custom_css; ?>; 
			 	} else{
			  		var pm_codemirror_value = " "; 
			 	}
				
				pm_codemirror.setValue(pm_codemirror_value); 
				
				jQuery("#pm_add_custom_css").click(function(event){
					event.preventDefault();
					jQuery(".CodeMirror").toggle();
				});

				jQuery(".CodeMirror").hide();	

			   	pm_codemirror.on("blur", function(pm_codemirror){
				    var pm_icss = pm_codemirror.getValue();
				    if (pm_custom_style.styleSheet){
				        pm_custom_style.styleSheet.cssText = pm_icss;
				    } else {
				        pm_custom_style.appendChild(document.createTextNode(pm_icss));
				    }
				    document.getElementsByTagName('head')[0].appendChild(pm_custom_style);
			 	});

				if(temp_name != ""){
					var base_temp_name = '<?php echo $base_temp_name; ?>';
					var page_id = "";
			        var params_string = '<?php echo trim(addslashes($params));?>';

			        if(params_string){
					var params = JSON.parse(params_string);
					
					for(var i=0;i<params.length;i++) {
						if(params[i]["type"] == "user_designed_template") {
							base_template = params[i]["type"];
							page_id = params[i]["id"];
							jQuery.post("<?php echo admin_url("admin-ajax.php?action=plug_get_page_content") ?>",{"page_id":page_id},function(result){
								jQuery('#ajax_load_temp').html(result).show();
							});
			                var user_designed_template = true;
						}	
					}	
			    
			   
				    if(user_designed_template != true) {
				      	var pm_fields_required = jQuery("input[type=radio][name='pm_display_fields']:checked").val();
				    	
				    	var filename = pm_plugin_url+'templates/'+base_temp_name+"/style.css";
						pm_temp_style.setAttribute("href", filename); 

				    	var img_styles = pm_plugin_url+'templates/'+base_temp_name+"/img-styles.css";
						pm_temp_img_style.setAttribute("href", img_styles);
				

				    	var pmformfields;      

						//edit one
				      jQuery('#ajax_load_temp').html("<div class ='pm_loading' style='width:100%;height:300px; background:url("+pm_plugin_url+"images/loading.gif"+") no-repeat scroll center;'>&nbsp;</div>").show();
				      setTimeout(function() {
				      jQuery('#ajax_load_temp').load(admin_url,function(){			
				          for(var i=0;i<params.length;i++) {
				              if(params[i]["type"] == "text") {
				                  var id = params[i]["id"];
				                  var text = params[i]["params"]["text"];                        
				                  var color = params[i]["params"]["color"];
				                  jQuery("#"+id).css("color",color);
				                  var font_family = params[i]["params"]["font_family"];
				                  var font_weight =  params[i]["params"]["font_weight"];
				                  curfont[id] = font_family.replace(/ /g,"+");
				                  jQuery("#"+id).css("font-family", font_family);
				                  jQuery("#"+id).text(text);
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);
				              } else if(params[i]["type"] == "pm_form_fields") {
				              		var fields_required_select = params[i]["fields_required"];
				              		if(fields_required_select == "pm_email_only") {
							    		pmformfields = pm_plugin_url+'templates/'+base_temp_name+"/onefield.css";
							    		jQuery("#pm_email_only").prop("checked", true);	
							    	} 
							    	if(fields_required_select == "pm_email_fname") {
							    		pmformfields = pm_plugin_url+'templates/'+base_temp_name+"/twofields.css";
							    		jQuery("#pm_email_fname").prop("checked", true);
							    	}
							    	if(fields_required_select == 'pm_cta_btn') {
							    		pmformfields = pm_plugin_url+'templates/'+base_temp_name+"/cta_btn.css";
										jQuery("#pm_cta_btn").prop("checked", true);	
										jQuery("#cta_wrapper").hide();
									}
									pm_temp_style2.setAttribute("href", pmformfields);					

									
				              } else if(params[i]["type"] == "textarea") {
				                  var html = params[i]["params"]["html"];
				                  jQuery('#pm_description').html(html);
				                  var color = params[i]["params"]["color"];
				                  jQuery('#pm_description').css("color", color);										
				                  var font_size = params[i]["params"]["font_size"];
				                  jQuery('#pm_description').css("font-size", font_size);
				                  var font_family = params[i]["params"]["font_family"];
				                  update_font_family(font_family);	
				  
				                  var id = params[i]["id"];	 	   	
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);				  	
				              } else if(params[i]["type"] == "service") {
				                  var html = params[i]["params"];
				                  jQuery.each(html, function(name,value) {
				                      email_service_option[name] = value;
				                  });
				                  var id = params[i]["id"]; 
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);
				               jQuery("#pm_exclamation_icon").attr('src',pm_plugin_url+"/images/tick-icon.png").css("opacity","1");
				              } else if(params[i]["type"] == "color") {							   		
				                  var bgcolor = params[i]["params"]["bgcolor"];			           
				                  var id = params[i]["id"]; 		                       
				                  if(jQuery("#"+id).attr("gradient") != null) {				       		 		
				                      var rules = jQuery("#"+id).css("background-image");				 		
				                      var new_rules = rules.replace(/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/,bgcolor);							 	
				                      jQuery("#"+id).css("background-image",new_rules);
				                      jQuery("#"+id).attr("gradient",bgcolor);		    			 	    				    
				                   } else {			
				                      jQuery("#"+id).css("background-color", bgcolor);					 		
				                   }
				                   jQuery("#"+id).inlineEdit(params[i]["type"]);					 
				              } else if(params[i]["type"] == "image") {
				                  var img_url = params[i]["params"]["img_url"];					
				                  jQuery("#pm_image").css('background-image',img_url);
				                  var id = params[i]["id"];	   		
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);	
				              } else if(params[i]["type"] == "video") {
				                  var id = params[i]["id"];	 
				                  var video_src = params[i]["params"]["video_src"];		
				                  var video_url = params[i]["params"]["video_url"];		
				                  jQuery("#pm_video").attr("src", video_src);
				                  jQuery("#pm_video").attr("video_url", video_url);
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);
				              } else if(params[i]["type"] == "button") {
				              	
				                  var email_input = params[i]["params"]["email_input"];
				                  var name_input = params[i]["params"]["name_input"];
				                  jQuery("#pm_input").val(email_input);
				                  jQuery("#pm_name_field").val(name_input);                        
				                  
				                  var id = params[i]["id"];		
				              	  var txt        = params[i]["params"]["text"];
				                  var sub_txt    = params[i]["params"]["sub_text"];
				                  var btn_class  = params[i]["params"]["btn_class"];	
				                  var url 		 = params[i]["params"]["url"];
				                  var lead_id 	 = params[i]["params"]["lead_id"];
				                  var left_icon  = params[i]["params"]["left_icon"];
				                  var right_icon = params[i]["params"]["right_icon"];
				                  var button_type= params[i]["params"]["button_fluid"];
				                  if(params[i]["params"]["button_style"]){
					                var button_style = JSON.parse(params[i]["params"]["button_style"]);
					   				pmfb_custom_style.appendChild(document.createTextNode(button_style));
									document.getElementsByTagName('head')[0].appendChild(pmfb_custom_style);	
				                  }
				                  
								  if(button_type=='1'){
								  	button_fluid = button_type;
								  	jQuery("#"+id).find('a').css({'width':'100%','text-align':'center'});
								  	jQuery("#"+id).find('.pmfb_btn_ico').hide();
								  	jQuery("#"+id).find('.pmfb_btn_txt_sub').hide();
								  }else{
								  	button_fluid=button_type;
								  	jQuery("#"+id).find('a').css({'width':'auto','text-align':'center'});
								  }
				                  jQuery("#"+id).find('a').attr('href', url);
				                  jQuery("#"+id).find('a').data('leadbox', lead_id);

				                  jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class',left_icon);
				                  jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class',right_icon);
				                  jQuery("#"+id).find('.pmfb_btn_txt_main').text(txt);
				                  jQuery("#"+id).find('.pmfb_btn_txt_sub').text(sub_txt);
				                  
				                  jQuery("#"+id).find('a').removeClass();
				     
				                  jQuery("#"+id).find('a').addClass(btn_class);			           
				                  
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);
					              jQuery("#"+id).find('a').click(function(e){
								    e.preventDefault();
								  });

				              } else if(params[i]["type"] == "cta_button") {
				              	  var id = params[i]["id"];		
				              	  var txt        = params[i]["params"]["text"];
				                  var sub_txt    = params[i]["params"]["sub_text"];
				                  var btn_class  = params[i]["params"]["btn_class"];	
				                  var url 		 = params[i]["params"]["url"];
				                  var lead_id 	 = params[i]["params"]["lead_id"];
				                  var left_icon  = params[i]["params"]["left_icon"];
				                  var right_icon = params[i]["params"]["right_icon"];
				                  var button_type= params[i]["params"]["button_fluid"];
				                  var button_style = JSON.parse(params[i]["params"]["button_style"]);
				   				  pmfb_custom_style.appendChild(document.createTextNode(button_style));
								  document.getElementsByTagName('head')[0].appendChild(pmfb_custom_style);
								  if(button_type == '1'){
								  	button_fluid = button_type;
								  	jQuery("#"+id).find('a').css({'width':'100%','text-align':'center'});
								  	jQuery("#"+id).find('.pmfb_btn_ico').hide();
								  	jQuery("#"+id).find('.pmfb_btn_txt_sub').hide();
								  }else{
								  	button_fluid=button_type;
								  	jQuery("#"+id).find('a').css({'width':'100%','text-align':'center'});
								  }
				                  jQuery("#"+id).find('a').attr('href', url);
				                  jQuery("#"+id).find('a').data('leadbox', lead_id);

				                  jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class',left_icon);
				                  jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class',right_icon);
				                  jQuery("#"+id).find('.pmfb_btn_txt_main').text(txt);
				                  jQuery("#"+id).find('.pmfb_btn_txt_sub').text(sub_txt);
				                  
				                  jQuery("#"+id).find('a').removeClass();
				     
				                  jQuery("#"+id).find('a').addClass(btn_class);			           
				                  
				                  jQuery("#"+id).inlineEdit(params[i]["type"]);
					              jQuery("#"+id).find('a').click(function(e){
								    e.preventDefault();
								  });
				              }
				          }	  
				          update_fun();
				      }).show();	
				      }, 2000);	
				    }
					}else{
						alert("Something went wrong! Template could not be loaded, please delete this template");
						location.href = '<?php echo admin_url("admin.php?page=pmfb_template"); ?>';
					}
			  	}

				jQuery("select#base_temp_name").change(function(){
					pm_load_template();
				});

			  	if (!jQuery("input[name='pm_display_fields']:checked").val()) {
			  		jQuery('input:radio[name=pm_display_fields][value=pm_email_fname]').click();
				}

				jQuery("input:radio[name=pm_display_fields]").click(function() {
					jQuery(document).click();
					var pm_temp_data = pm_temp_values();
					pm_load_template(pm_temp_data);	
				}); 

				function pm_load_template(pm_temp_arr) {
					jQuery('#ajax_load_temp').html("<div class ='pm_loading' style='width:100%;height:300px;"+ 
							"background:url("+pm_plugin_url+"images/loading.gif"+") no-repeat scroll center;'>&nbsp;</div>").show();

					jQuery('#pm_sel_fields').show();
					
					var pm_element = jQuery("#base_temp_name").get(0).tagName;
					var template;
					if(pm_element == "SELECT") {
						template = jQuery('#base_temp_name :selected').val();
					}

					if(pm_element == "DIV") {
						template = jQuery("#base_temp_name").text();
					}

					if(document.getElementById("select_page") != null){
						jQuery('#select_page').remove();
						jQuery('#ajax_load_temp').css("border","solid 0px grey");
					}		
					var template_type = template.split("_")[0];
					if(template_type == "mini") {
						jQuery('#pm_sel_fields').hide();
					}
					if(template == null || template == "" ){
						alert("Select a base template");
						jQuery('#ajax_load_temp').html(" ").show();
						return false;
					} else if(template == "user_designed_template") {
						jQuery('#pm_sel_fields').hide();
						jQuery('#ajax_load_temp').html(" ").show();
						var page_list = jQuery.parseJSON('<?php  get_pages_list() ; ?>');			
						var select_page = document.createElement("select");
						select_page.setAttribute("id", "select_page");
						<?php if(get_option("Plugmatter_PACKAGE") != "plug_featurebox_pro" && get_option("Plugmatter_PACKAGE") != "plug_featurebox_dev")  {
							echo "jQuery('#base_temp_name').after(\"&nbsp;&nbsp;&nbsp;".Plugmatter_UPNOTE."\");";
						} else {
							echo "jQuery('#base_temp_name').after(select_page);";
						} ?>
						jQuery('#select_page').append(jQuery("<option></option>").attr("value","").text("Select Page"));
						for (var i = 0; i < page_list.length; i++) {
							var id = page_list[i]["id"];
							var title = page_list[i]["title"];
							jQuery('#select_page').append(jQuery("<option></option>").attr("value",id ).text(title));
						}
						
						jQuery("select#select_page").change(function(){				
							var page_id = jQuery("select#select_page").val();
							jQuery('#ajax_load_temp').html("<div class ='pm_loading' style='width:100%;height:300px; background:url("+pm_plugin_url+"images/loading.gif"+") no-repeat scroll center;'>&nbsp;</div>").show();
							jQuery.post("<?php echo admin_url("admin-ajax.php?action=plug_get_page_content") ?>",{"page_id":page_id},function(result){
								jQuery('#ajax_load_temp').html(result).show();
							});						
						});
						
						//return false;

					} else {		
						var pmtempcss = pm_plugin_url+'templates/'+template+"/style.css";
						pm_temp_style.setAttribute("href", pmtempcss);

						var img_styles = pm_plugin_url+'templates/'+template+"/img-styles.css";
						pm_temp_img_style.setAttribute("href", img_styles); 
						
						//this is new code
				    	var pm_fields_required = jQuery("input[type=radio][name='pm_display_fields']:checked").val();
				    	var filename;
				    	if(pm_fields_required == "pm_email_only") {
				    		filename = pm_plugin_url+'templates/'+template+"/onefield.css";
				    	} 
				    	if(pm_fields_required == "pm_email_fname") {
				    		filename = pm_plugin_url+'templates/'+template+"/twofields.css";
				    	}

				    	if(pm_fields_required == "pm_cta_btn") {

				    		filename = pm_plugin_url+'templates/'+template+'/cta_btn.css';
				    	}

						//dropdown
						pm_temp_style2.setAttribute("href", filename);
						if(!pm_temp_arr) {
							jQuery('#ajax_load_temp').load('<?php echo admin_url("admin-ajax.php?action=plug_load_template&data=") ?>'+template,function(){
								if(template_type != "mini") {
									jQuery("#pm_h1").text("Lorem ipsum dolor sit amet, consectetur adipisicing elit");
									jQuery("#pm_description").html("<ul><li>Fusce vel sapien vehicula, consequat massa eu, pellentesque mauris.</li><li>Ut fermentum dui nec neque blandit, a consequat tortor vestibulum.</li><li>Aenean et nibh rutrum, faucibus sapien non, placerat lectus.</li></ul>");
								} else {
									jQuery("#pm_h1").text("Lorem ipsum dolor sit amet");
									jQuery("#pm_description").html("Fusce vel sapien vehicula, consequat massa eu, pellentesque mauris.");			
								}
					            jQuery("#pm_input").val("Enter Your Email Address");	
					            jQuery("#pm_name_field").val("Enter Your First Name");	                

					            jQuery(".pm_cta_button").find('a').removeClass();				
					 		    jQuery(".pm_cta_button").find('a').addClass("pm_default_btn pmfb_btn");
					 		   
								jQuery(".pm_cta_button").find("span.pmfb_btn_ico").first().children('i').attr('class','fa');
				                jQuery(".pm_cta_button").find("span.pmfb_btn_ico").last().children('i').attr('class','fa');
				                
				                jQuery(".pm_cta_button").find('.pmfb_btn_txt_main').text('Subscribe Now');
				                jQuery(".pm_cta_button").find('.pmfb_btn_txt_sub').text('');

								jQuery("#pm_video").attr("src", "//player.vimeo.com/video/79277917?badge=0&byline=0&portrait=0&title=0");
								jQuery("#pm_video").attr("video_url", "http://vimeo.com/79277917");
													
								jQuery(".pmedit").each(function(){				
								    var edit_type = jQuery(this).attr("pm_meta");
							        if(edit_type == "text") {
								        var def_font = jQuery(this).attr("def_font");//alert(def_font);
								        get_font_h1(def_font);
							        	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "textarea") {
							   			var def_font = jQuery(this).attr("def_font");//alert(def_font);
							   			get_font_txtarea(def_font);
							   		  	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "service") {
							   		  	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "color") {	
							   		  	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "image") {
							   		  	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "video") {
							   		  	jQuery("#"+this.id).inlineEdit(edit_type);
							   		} else if(edit_type == "button") {			   		
							   		  	//jQuery("#"+this.id).inlineEdit(edit_type);
							   		  	if(button_fluid==1){
							   				jQuery('#pmfb_button_editor').find('#pmfb_nrml_btn').attr('checked','check');
			          						jQuery('#pmfb_button_editor').find('#pmfb_fluid_btn').removeAttr('checked');
			          						jQuery('#pmfb_button_editor').find('#pmfb_nrml_btn').trigger('click');
							   			}
							   			jQuery("#"+this.id).inlineEdit(edit_type);
							   		  	jQuery("#"+this.id).find('a').click(function(e){
										    e.preventDefault();
										});
							   		} else if(edit_type == "cta_button") {
							   			if(button_fluid==1){
							   				jQuery('#pmfb_button_editor').find('#pmfb_nrml_btn').attr('checked','check');
			          						jQuery('#pmfb_button_editor').find('#pmfb_fluid_btn').removeAttr('checked');
			          						jQuery('#pmfb_button_editor').find('#pmfb_nrml_btn').trigger('click');
							   			}
							   			jQuery("#"+this.id).inlineEdit(edit_type);
							   		  	jQuery("#"+this.id).find('a').click(function(e){
										    e.preventDefault();
										});
							   		}				
								});   
							}).show();	
						} else {
							//switching btwn radio
							jQuery('#ajax_load_temp').load('<?php echo admin_url("admin-ajax.php?action=plug_load_template&data=") ?>'+template,function(){
								var params = pm_temp_arr;
								var pm_element = jQuery("#base_temp_name").get(0).tagName;
								var base_temp_name;
								if(pm_element == "SELECT") {
									base_temp_name = jQuery('#base_temp_name :selected').val();
								}
								if(pm_element == "DIV") {
									base_temp_name = jQuery("#base_temp_name").text();
								}
							
								for(var i=0;i<params.length;i++) {
									if(params[i]["type"] == "text") {
									  var id = params[i]["id"];
									  var text = params[i]["params"]["text"];                        
									  var color = params[i]["params"]["color"];
									  jQuery("#"+id).css("color",color);
									  var font_family = params[i]["params"]["font_family"];
									  var font_weight =  params[i]["params"]["font_weight"];
									  curfont[id] = font_family.replace(/ /g,"+");
									  jQuery("#"+id).css("font-family", font_family);
									  jQuery("#"+id).text(text);
									  jQuery("#"+id).inlineEdit(params[i]["type"]);
									} else if(params[i]["type"] == "pm_form_fields") {
										var fields_required_select = params[i]["fields_required"];
										if(fields_required_select == 'pm_email_fname') {
											jQuery("#pm_email_fname").prop("checked", true);
										}
										if(fields_required_select == 'pm_email_only') {
											jQuery("#pm_email_only").prop("checked", true);	
										}
										if(fields_required_select == 'pm_cta_btn') {
											jQuery("#pm_cta_btn").prop("checked", true);	
											jQuery("#pm_form").hide();
										}
									
									} else if(params[i]["type"] == "textarea") {
										var html = params[i]["params"]["html"];
										jQuery('#pm_description').html(html);
										var color = params[i]["params"]["color"];
										jQuery('#pm_description').css("color", color);										
										var font_size = params[i]["params"]["font_size"];
										jQuery('#pm_description').css("font-size", font_size);
										var font_family = params[i]["params"]["font_family"];
										update_font_family(font_family);	
							
										var id = params[i]["id"];	 	   	
										jQuery("#"+id).inlineEdit(params[i]["type"]);				  	
									} else if(params[i]["type"] == "service") {
									  	var html = params[i]["params"];
									  	jQuery.each(html, function(name,value) {
									    	email_service_option[name] = value;
									  	});
									  	var id = params[i]["id"]; 
									  	jQuery("#"+id).inlineEdit(params[i]["type"]);
										jQuery("#pm_exclamation_icon").attr('src',pm_plugin_url+"/images/tick-icon.png").css("opacity","1");
									} else if(params[i]["type"] == "color") {							   		
									  	var bgcolor = params[i]["params"]["bgcolor"];			           
									  	var id = params[i]["id"]; 		                       
									  	if(jQuery("#"+id).attr("gradient") != null) {				       		 		
									      var rules = jQuery("#"+id).css("background-image");				 		
									      var new_rules = rules.replace(/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/,bgcolor);							 	
									      jQuery("#"+id).css("background-image",new_rules);
									      jQuery("#"+id).attr("gradient",bgcolor);		    			 	    				    
									   	} else {			
									      jQuery("#"+id).css("background-color", bgcolor);					 		
									   	}
									   	jQuery("#"+id).inlineEdit(params[i]["type"]);					 
									} else if(params[i]["type"] == "image") {
									  	var img_url = params[i]["params"]["img_url"];					
									  	jQuery("#pm_image").css('background-image',img_url);
									  	var id = params[i]["id"];	   		
									  	jQuery("#"+id).inlineEdit(params[i]["type"]);	
									} else if(params[i]["type"] == "video") {
									  	var id = params[i]["id"];	 
									  	var video_src = params[i]["params"]["video_src"];		
									  	var video_url = params[i]["params"]["video_url"];		
									  	jQuery("#pm_video").attr("src", video_src);
									  	jQuery("#pm_video").attr("video_url", video_url);
									  	jQuery("#"+id).inlineEdit(params[i]["type"]);
									} else if(params[i]["type"] == "button") {
									   	var email_input = params[i]["params"]["email_input"];
									    var name_input = params[i]["params"]["name_input"];
									  	jQuery("#pm_input").val(email_input);
									  	jQuery("#pm_name_field").val(name_input);                        
									 
									    var id = params[i]["id"];				
									 
										var txt 		 	= params[i]["params"]["text"];							
									  	var sub_txt    	= params[i]["params"]["sub_text"];
									  	var btn_class 	= params[i]["params"]["btn_class"];	

									  	var url        	= params[i]["params"]["url"];
									  	var lead_id    	= params[i]["params"]["lead_id"];
									  	var left_icon  	= params[i]["params"]["left_icon"];
									  	var right_icon 	= params[i]["params"]["right_icon"];
									  	var button_style = params[i]["params"]["button_style"];
									  	var button_type  = params[i]["params"]["button_fluid"];
									  	if(jQuery.trim(txt) == "") {
									  		txt = "Main Text";
									  	}

										jQuery("#"+id).find('a').attr('href', url);
										jQuery("#"+id).find('a').data('leadbox', lead_id);
										jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class',left_icon);
										jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class',right_icon);
										
										jQuery("#"+id).find('.pmfb_btn_txt_main').text(txt);
										
										jQuery("#"+id).find('.pmfb_btn_txt_sub').text(sub_txt);
										jQuery("#"+id).find('a').removeClass();
										
										if(btn_class == "") {
											var btn_class = "pm_default_btn pmfb_btn";
										}
										jQuery("#"+id).find('a').addClass(btn_class);			           
											   									
										jQuery("#"+id).inlineEdit(params[i]["type"]);
										jQuery("#"+id).find('a').click(function(e){
										    e.preventDefault();
										});

									} else if(params[i]["type"] == "cta_button") {
										var email_input = params[i]["params"]["email_input"];
									    var name_input = params[i]["params"]["name_input"];
									  	jQuery("#pm_input").val(email_input);
									  	jQuery("#pm_name_field").val(name_input);
									  	
										var id = params[i]["id"];			
												
										var txt 		 	= params[i]["params"]["text"];							
									  	var sub_txt    	= params[i]["params"]["sub_text"];
									  	var btn_class 	= params[i]["params"]["btn_class"];	

									  	var url        	= params[i]["params"]["url"];
									  	var lead_id    	= params[i]["params"]["lead_id"];
									  	var left_icon  	= params[i]["params"]["left_icon"];
									  	var right_icon 	= params[i]["params"]["right_icon"];
									  	var button_style = params[i]["params"]["button_style"];
									  	var button_type  = params[i]["params"]["button_fluid"];
									  	if(jQuery.trim(txt) == "") {
									  		txt = "Main Text";
									  	}

										jQuery("#"+id).find('a').attr('href', url);
										jQuery("#"+id).find('a').data('leadbox', lead_id);
										jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class',left_icon);
										jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class',right_icon);
										
										jQuery("#"+id).find('.pmfb_btn_txt_main').text(txt);
										
										jQuery("#"+id).find('.pmfb_btn_txt_sub').text(sub_txt);
										jQuery("#"+id).find('a').removeClass();
										
										if(btn_class == "") {
											var btn_class = "pm_default_btn pmfb_btn";
										}
										jQuery("#"+id).find('a').addClass(btn_class);			           
											   									
										jQuery("#"+id).inlineEdit(params[i]["type"]);
										jQuery("#"+id).find('a').click(function(e){
										    e.preventDefault();
										});
									}
						        }	  
						        update_fun();
							}).show();
						}
					}	
				} 	

				// save all the changes
				jQuery("#save_btn").click(function() { 
					jQuery(document).click();
					
					var title = jQuery("input#title").val();
					if(title == ""){alert("Enter the Template name");return false;}
					var template = jQuery("select#base_temp_name option:selected").val();
					if(template == undefined) template = base_temp_name;
					if(template == "null"){alert("Select any Base Template");return false;}
					if(!jQuery('#pm_cta_btn').is(':checked')) {
			
						if(email_service_option["service"] == undefined && template != "user_designed_template") {		
							alert("Please select an email service");
							jQuery("#pm_form").click();
							return false;
						}
					}
					hidden=document.createElement("input");
					hidden.setAttribute("type", "hidden");
					hidden.setAttribute("name", "params");
					jQuery("#title").append(hidden);
					var json_params = pm_temp_values();
			      
					json_params = JSON.stringify(json_params);
					hidden.setAttribute("value", json_params);
			        document.forms["form1"].submit();
				});
				
				function pm_temp_values() {
					var json_params = [];
					if(jQuery("#pm_box_width").val() != "") { var pm_box_width = jQuery("#pm_box_width").val(); } else { var pm_box_width = 0;}
					if(jQuery("#pm_box_tmargin").val() != "") { var pm_box_tmargin = jQuery("#pm_box_tmargin").val(); } else { var pm_box_tmargin = 0;}
					if(jQuery("#pm_box_bmargin").val() != "") { var pm_box_bmargin = jQuery("#pm_box_bmargin").val(); } else { var pm_box_bmargin = 0;}
					var pm_code = pm_codemirror.getValue();
					if(pm_code != ""){var pm_custom_css = pm_code;} else { var pm_custom_css = "";}
					var pm_fields_required = jQuery("input[type=radio][name='pm_display_fields']:checked").val();
					json_params.push({"type":"pm_form_fields","fields_required": pm_fields_required});
					json_params.push({"type":"alignment","width": pm_box_width, "top_margin":pm_box_tmargin, "bottom_margin":pm_box_bmargin});
					json_params.push({"type": "pm_custom_css","pm_custom_css": String(pm_custom_css) });
					var template = jQuery("select#base_temp_name option:selected").val();
					
					if(template == "user_designed_template") {
						sel_page_id = jQuery("select#select_page").val();
						if(sel_page_id == undefined) sel_page_id = page_id;
			    	    json_params.push({"type":"user_designed_template","id":sel_page_id});
					} else {
						
						jQuery(".pmedit").each(function(){
					        var edit_type = jQuery(this).attr("pm_meta");

					        if(edit_type == "text") {
						        var id = jQuery(this).attr('id');
						        var text = jQuery(this).text();
					        	var color = jQuery("#"+this.id).css("color");
					        	var font_family = jQuery("#"+this.id).css("font-family"); 
					        	font_family = font_family.replace(/'/g, "");	        	
							        	
					        	json_params.push({"type":edit_type,"id":id,"params":{"text":text,"color":color,"font_family":font_family}});  
					   		} else if(edit_type == "textarea") {
					   		 	var id = jQuery(this).attr('id');
					   			var color = colorToHex(jQuery("#"+this.id).css("color"));
					        	var font_family = jQuery("#"+this.id).css("font-family");	
								var font_size = jQuery("#"+this.id).css("font-size");
					        	font_family = font_family.replace(/'/g, "");	        	   
					        	var html = jQuery("#"+this.id).html();
					        	json_params.push({"type":edit_type,"id":id,"params":{"color":color,"font_family":font_family,"font_size":font_size,"html":html}});
					   		} else if(edit_type == "service") {	   
					   				var id = jQuery(this).attr('id');			
						   		    JSON.stringify(email_service_option);
							   		json_params.push({"type":edit_type,"id":id,"params":email_service_option});
							   		
							} else if(edit_type == "color") {
								var id = jQuery(this).attr('id');
					   			var bgcolor = "";	  
					   			var gradient = ""; 			
					   		 if(jQuery("#"+this.id).attr("gradient") != null) { 			   		 	     			 				
					   			
					   			bgcolor = jQuery("#"+this.id).attr("gradient");
					   			gradient = "yes";
					   			   			
						 	 }else{
						 		gradient = "no";
						 		bgcolor = jQuery("#"+this.id).css("background-color");
							 }
					   			json_params.push({"type":edit_type,"id":id,"params":{"bgcolor":bgcolor,"gradient":gradient}});
					   		} else if(edit_type == "image") {
					   			var id = jQuery(this).attr('id');
			
					   			var img_url = jQuery("#pm_image").css('background-image');
					   			json_params.push({"type":edit_type,"id":id,"params":{"img_url":img_url}});
					   		} else if(edit_type == "video") {
								var id = jQuery(this).attr('id');			
					   			var vid_src = jQuery("#pm_video").attr("src");
								var vid_url = jQuery("#pm_video").attr("video_url");
					   			json_params.push({"type":edit_type,"id":id,"params":{"video_src":vid_src, "video_url":vid_url}});
					   		} else if(edit_type == "button") {
					            var email_input =  jQuery("#pm_input").val();
				                var name_input =  jQuery("#pm_name_field").val();
					  
					   			var id 		   	 = jQuery(this).attr('id');
					   			var txt 	   	 = jQuery("#"+id).find(".pmfb_btn_txt_main").text();		   			
					   			var sub_text   	 = jQuery("#"+id).find(".pmfb_btn_txt_sub").text();
					   			var left_icon  	 = jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class');
					   			var right_icon 	 = jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class');
					   			var btn_class  	 = jQuery("#"+id).find('a').attr("class") ;	
				                var url 		 = jQuery("#"+id).find('a').attr('href');
				                var lead_id      = jQuery("#"+id).find('a').data('leadbox');
				                var button_type  = button_fluid;
				                var button_style = JSON.stringify(jQuery("#pmfb_custom_btn_style").html());
				                json_params.push({"type":edit_type,"id":id,"params":{"right_icon":right_icon,"left_icon":left_icon,"sub_text":sub_text,"text":txt,"btn_class":btn_class, "url":url, "lead_id":lead_id,"button_fluid":button_type,"button_style":button_style,"email_input":email_input,"name_input":name_input }});


						   	} else if(edit_type == "cta_button") {
						   		var email_input =  jQuery("#pm_input").val();
				                var name_input =  jQuery("#pm_name_field").val();

					   			var id 		   	 = jQuery(this).attr('id');
					   			var txt 	   	 = jQuery("#"+id).find(".pmfb_btn_txt_main").text();		   			
					   			var sub_text   	 = jQuery("#"+id).find(".pmfb_btn_txt_sub").text();
					   			var left_icon  	 = jQuery("#"+id).find("span.pmfb_btn_ico").first().children('i').attr('class');
					   			var right_icon 	 = jQuery("#"+id).find("span.pmfb_btn_ico").last().children('i').attr('class');
					   			var btn_class  	 = jQuery("#"+id).find('a').attr("class") ;	
				                var url 		 = jQuery("#"+id).find('a').attr('href');
				                var lead_id      = jQuery("#"+id).find('a').data('leadbox');
				                var button_type  = button_fluid;
				                var button_style = JSON.stringify(jQuery("#pmfb_custom_btn_style").html());
				                json_params.push({"type":edit_type,"id":id,"params":{"right_icon":right_icon,"left_icon":left_icon,"sub_text":sub_text,"text":txt,"btn_class":btn_class, "url":url, "lead_id":lead_id,"button_fluid":button_type,"button_style":button_style,"email_input":email_input,"name_input":name_input }});
						   	}			
						}); 
					}
					return json_params;
				}
			});
		</script>

		<?php if(get_option("Plugmatter_PACKAGE") != "plug_featurebox_pro") { ?>
		    <div style='background:#fff;border:#ddd;padding:20px;margin:30px;'>
		        <div class='plug_enable_lable' style='margin-top:10px;width:100%;margin-bottom:20px;'>Need more base templates? Check them out:</div>    
		        <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") { ?>
		            <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_3.png", __FILE__); ?>' width='225'></div>
		            <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_4.png", __FILE__); ?>' width='225'></div>
		            <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_5.png", __FILE__); ?>' width='225'></div>
		            <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_6.png", __FILE__); ?>' width='225'></div>
		        <?php } ?>
		        <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_7.png", __FILE__); ?>' width='225'></div>
		        <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_8.png", __FILE__); ?>' width='225'></div>
		        <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_9.png", __FILE__); ?>' width='225'></div>
		        <div class="upgrade_templates"><img src='<?php echo plugins_url("/images/up_preview_10.png", __FILE__); ?>' width='225'></div>  
		        <div style='margin:10px;text-align:center;'><input id="submit" class="pm_primary_buttons" type="button" value="Upgrade To Get More Templates!" onclick="location.href='http://plugmatter.com/feature-box#plans&pricing'" name="submit"></div>    
		    </div>
		<?php } ?>
	</div>
</div> 