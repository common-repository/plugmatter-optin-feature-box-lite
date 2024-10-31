<?php
function show_template($template_id, $ab_meta="") {
		global $wpdb;
	  	$custom_css=" ";
	 	$pm_service_action=" ";
	 	$pm_service_hiddens=" ";
	 	$pm_meta_tid = " ";
	 	$pm_load_style = "";
	 	$pm_custom_css = "";

	 	     
	 	$table = $wpdb->prefix.'plugmatter_templates';

	 	$template_id = intval($template_id);

	 	$result = $wpdb->get_row($wpdb->prepare("SELECT id,temp_name,base_temp_name,params FROM $table WHERE id=%d", $template_id));
	 	if(!$result) return;
	 	 
	 	$id=$result->id;
	 	$temp_name=$result->temp_name;
  		$base_temp_name=$result->base_temp_name;
  		$params=$result->params;

  		if(get_option("pmfb_track_analytics")){
  			$pm_meta_tid = $id."_".$temp_name;	
  		}  		
	    
  	    $obj = json_decode($params);  	 
  	      	   	 
        $gwf1arr = array();

		foreach($obj as $doc){
			if($doc->type == "alignment") {
				if($doc->width != 0) $pm_box_width = "max-width:".$doc->width."px;max-width:".$doc->width."px;";
				@$custom_css.= "#pm_featurebox { $pm_box_width margin: ".$doc->top_margin."px auto ".$doc->bottom_margin."px; }"; 
			} else if($doc->type == "pm_form_fields") {
				$pm_load_style  = $doc->fields_required;
			} else if($doc->type == "pm_custom_css") {
				$pm_custom_css  = $doc->pm_custom_css;
			} else if($doc->type == "user_designed_template"){
				 $page_id = $doc->id;
				 $thispost = get_post( $page_id );
				 $content = do_shortcode($thispost->post_content);
                 echo "<style>$custom_css</style>";
				 echo "<div  class='pmedit' pm_meta='color' id='pm_featurebox' ab_meta='$ab_meta' >".$content."</div>";				
			} else	if($doc->type == "text") {
                $objid = $doc->id;
	 	  		$$objid = $doc->params->text;		 	  			  			
                $elem_type = explode("_", $objid);
                $pre_selector = $elem_type[1]."#";
	 	  		@$custom_css.= $pre_selector.$doc->id."{color:".$doc->params->color."; font-family:".$doc->params->font_family."; font-weight:".$doc->params->variant." }" ;
	 	  		$gwf1arr[] = urlencode($doc->params->font_family);
	 	  	} else if($doc->type == "textarea") {
	 	  		$pm_description = $doc->params->html;	 	  			  
	 	  		$custom_css.= "#".$doc->id."{color:".$doc->params->color."; font-size:". $doc->params->font_size ."; font-family:".$doc->params->font_family."; }" ;
	 	  		$gwf2 = urlencode($doc->params->font_family);	 	  			  
	 	  	} else if($doc->type == "color") {
				if($doc->params->gradient == "yes"){
					$custom_css.= "#".$doc->id."{
						background: linear-gradient(to right,  ".$doc->params->bgcolor." 57%,rgba(255,255,255,0.48) 72%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); /* W3C */
						background: -moz-linear-gradient(left,  ".$doc->params->bgcolor." 57%, rgba(255,255,255,0.48) 72%, rgba(255,255,255,0.03) 85%, rgba(255,255,255,0) 86%); /* FF3.6+ */
						background: -webkit-gradient(linear, left top, right top, color-stop(57%,".$doc->params->bgcolor."), color-stop(72%,rgba(255,255,255,0.48)), color-stop(85%,rgba(255,255,255,0.03)), color-stop(86%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
						background: -webkit-linear-gradient(left,  ".$doc->params->bgcolor." 57%,rgba(255,255,255,0.48) 72%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); /* Chrome10+,Safari5.1+ */
						background: -o-linear-gradient(left,  ".$doc->params->bgcolor." 57%,rgba(255,255,255,0.48) 72%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); /* Opera 11.10+ */
						background: -ms-linear-gradient(left,  ".$doc->params->bgcolor." 57%,rgba(255,255,255,0.48) 72%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); /* IE10+ */						
						filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=".$doc->params->bgcolor.", endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 */
	 	  			}";
	 	  			$custom_css.="@media only screen and (min-width : 768px) and (max-width : 1023px) { ".
	 	  				"#".$doc->id. " {
							    background: linear-gradient(to right, ".$doc->params->bgcolor." 60%, rgba(255, 255, 255, 0.48) 70%, rgba(255, 255, 255, 0.03) 85%, rgba(255, 255, 255, 0) 86%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
							    background: -moz-linear-gradient(left,  ".$doc->params->bgcolor.") 60%, ".$doc->params->bgcolor.") 70%, rgba(255,255,255,0.03) 85%, rgba(255,255,255,0) 86%) !important;
								background: -webkit-gradient(linear, left top, right top, color-stop(60%,rgba(255,255,255,1)), color-stop(70%,rgba(255,255,255,0.48)), color-stop(85%,rgba(255,255,255,0.03)), color-stop(86%,rgba(255,255,255,0))) !important;
								background: -webkit-linear-gradient(left,  ".$doc->params->bgcolor." 60%,rgba(255,255,255,0.48) 70%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%) !important;
								background: -o-linear-gradient(left,  ".$doc->params->bgcolor." 60%,rgba(255,255,255,0.48) 70%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); !important;
								background: -ms-linear-gradient(left,  ".$doc->params->bgcolor." 60%,rgba(255,255,255,0.48) 70%,rgba(255,255,255,0.03) 85%,rgba(255,255,255,0) 86%); !important;
								height: 338px !important;

							}
							#pm_content { background-color :  ".$doc->params->bgcolor." }
	 	  			}  ";
	 	  			$custom_css.= "@media only screen and (min-width : 480px) and (max-width : 767px) {".
	 	  				"#".$doc->id. " {
							padding-top: 292px !important;
							background: linear-gradient(to top, ".$doc->params->bgcolor." 1%, rgba(255, 255, 255, 0.48) 28%, rgba(255, 255, 255, 0.03) 80%, rgba(255, 255, 255, 0) 86%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important; /* W3C */
						    background: -moz-linear-gradient(center bottom,  ".$doc->params->bgcolor." 13%, rgba(255,255,255,0.48) 28%, rgba(255,255,255,0.03) 80%, rgba(255,255,255,0) 86%) !important; 
							background: -webkit-gradient(linear, bottom top, right top, color-stop(1%,".$doc->params->bgcolor."), color-stop(28%,rgba(255,255,255,0.48)), color-stop(80%,rgba(255,255,255,0.03)), color-stop(86%,rgba(255,255,255,0))) !important; /* Chrome,Safari4+ */
							background: -webkit-linear-gradient(bottom,  ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* Chrome10+,Safari5.1+ */
							background: -o-linear-gradient(bottom, ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* Opera 11.10+ */
							background: -ms-linear-gradient(bottom, ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* IE10+ */
							top: 0px !important;
							left: 0px !important;
						}
						#pm_content { background-color :  ".$doc->params->bgcolor." }
						#pm_h1_div { padding: 10px 0px; }
						#pm_featurebox { height: 572px !important; }
					   .pm_description { height: 78px;}
	 	  			}";
	 	  			$custom_css.= "@media only screen and (min-width : 320px) and (max-width : 479px) { ".
	 	  				"#".$doc->id. " {
							padding-top:300px;
							background: linear-gradient(to top, ".$doc->params->bgcolor." 1%, rgba(255, 255, 255, 0.48) 28%, rgba(255, 255, 255, 0.03) 80%, rgba(255, 255, 255, 0) 86%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
						    background: -moz-linear-gradient(center bottom,  ".$doc->params->bgcolor." 13%, rgba(255,255,255,0.48) 28%, rgba(255,255,255,0.03) 80%, rgba(255,255,255,0) 86%) !important; 
							background: -webkit-gradient(linear, bottom top, right top, color-stop(1%, ".$doc->params->bgcolor." ), color-stop(28%,rgba(255,255,255,0.48)), color-stop(80%,rgba(255,255,255,0.03)), color-stop(86%,rgba(255,255,255,0))) !important;
							background: -webkit-linear-gradient(bottom,  ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important;
							background: -o-linear-gradient(bottom, ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important;
							background: -ms-linear-gradient(bottom, ".$doc->params->bgcolor." 1%,rgba(255,255,255,0.48) 28%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important;
							top: 0px !important;
							left: 0px !important;
						}
						#pm_featurebox { height: 680px !important; }
						#pm_content { background-color :  ".$doc->params->bgcolor." }
					}";
					$custom_css.= "@media only screen and (min-width : 240px) and (max-width: 319px){ ".
	 	  				"#".$doc->id. " {
							padding-top:350px;
							background: linear-gradient(to top, ".$doc->params->bgcolor." 52%, rgba(255, 255, 255, 0.48) 65%, rgba(255, 255, 255, 0.03) 80%, rgba(255, 255, 255, 0) 86%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important; /* W3C */
						    background: -moz-linear-gradient(center bottom,  ".$doc->params->bgcolor." 13%, rgba(255,255,255,0.48) 45%, rgba(255,255,255,0.03) 80%, rgba(255,255,255,0) 86%) !important; /* FF3.6+ */
							background: -webkit-gradient(linear, bottom top, right top, color-stop(52%,".$doc->params->bgcolor."), color-stop(65%,rgba(255,255,255,0.48)), color-stop(80%,rgba(255,255,255,0.03)), color-stop(86%,rgba(255,255,255,0))) !important; /* Chrome,Safari4+ */
							background: -webkit-linear-gradient(bottom,  ".$doc->params->bgcolor." 52%,rgba(255,255,255,0.48) 65%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* Chrome10+,Safari5.1+ */
							background: -o-linear-gradient(bottom, ".$doc->params->bgcolor." 52%,rgba(255,255,255,0.48) 65%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* Opera 11.10+ */
							background: -ms-linear-gradient(bottom, ".$doc->params->bgcolor." 52%,rgba(255,255,255,0.48) 65%,rgba(255,255,255,0.03) 80%,rgba(255,255,255,0) 86%) !important; /* IE10+ */
							top: 0px !important;
							left: 0px !important;
						}
						#pm_content { background-color :  ".$doc->params->bgcolor." }
	 	  			}";
				} else {
					$custom_css.= "#".$doc->id."{background-color:".$doc->params->bgcolor."; }" ;
				}	 	  			 
	 	  	} else if($doc->type == "image") {
				$img_url = $doc->params->img_url;
	 	  		$custom_css .= "#".$doc->id."{background-image:".$img_url.";}" ;
	 	  	} else if($doc->type == "video") {
				$pm_video_src = $doc->params->video_src;			
	 	  	} else if($doc->type == "button") {
				$pm_btn_txt = $doc->params->text;
				$pm_email_input_txt = $doc->params->email_input;
                $pm_name_input_txt = $doc->params->name_input;    
                @$pm_btn_class 	= $doc->params->btn_class;
                @$pm_btn_url 	= $doc->params->url;
                @$pm_btn_lead_id 	= $doc->params->lead_id; 

				$check_type 		 = $doc->params->button_fluid;
				if($check_type!='1'){
					$pm_btn_inline_style = 'auto';
					$pm_btn_sub_btn_txt = $doc->params->sub_text;	
					$pm_btn_left_icon 	= $doc->params->left_icon;
					$pm_btn_right_icon	= $doc->params->right_icon;
				}else{
					$pm_btn_inline_style = '100%';	
				}
				            	
            	$pm_btn_style  	= json_decode($doc->params->button_style);

				echo "<style>$pm_btn_style</style>";


	 	  	}  else if($doc->type == "cta_button") {
				$pm_cta_btn_txt      = $doc->params->text;
				$pm_email_input_txt = $doc->params->email_input;
                $pm_name_input_txt = $doc->params->name_input;                    				
				@$pm_cta_btn_class 	= $doc->params->btn_class;
                @$pm_cta_btn_url 	= $doc->params->url;
                @$pm_cta_lead_id 	= $doc->params->lead_id; 
                             
				$check_type 		 = $doc->params->button_fluid;

				if($check_type != '1'){
					$pm_cta_inline_style = 'auto';
					$pm_cta_sub_btn_txt = $doc->params->sub_text;	
					$pm_cta_left_icon 	= $doc->params->left_icon;
					$pm_cta_right_icon	= $doc->params->right_icon;
				}else{
					$pm_cta_inline_style = '100%;text-align:center';	
				}
            	
            	$pm_cta_style  	= json_decode($doc->params->button_style);

				echo "<style>$pm_cta_style</style>";				
			   
	 	  	} else if($doc->type == "service") {

	 	  		$pm_es = new Plugmatter_Email_Service($wpdb->prefix.'plugmatter_templates');
				$pm_form_fields = $pm_es->pmes_form_fields($doc->params, $template_id);
				$pm_service_action  	  = $pm_form_fields["pm_service_action"];
				$pm_input_name 			  = $pm_form_fields["pm_input_name"];
				$pm_input_name_field_name = $pm_form_fields["pm_input_name_field_name"];
                $pm_service_hiddens 	  = $pm_form_fields["pm_service_hiddens"];
	 	  	}
	 	}
		
		if($pm_custom_css) $custom_css .= $pm_custom_css;
		
	 	if($doc->type != "user_designed_template") {

	 		wp_register_style('pm_bootstrap', plugins_url('/css/pm_bootstrap.css', __FILE__));
	 		wp_enqueue_style('pm_bootstrap');

	 		wp_enqueue_style('pm_button_style', plugins_url('/css/pm_btn_style.css', __FILE__));	 		
	 		wp_register_style('pm_custom-style', plugins_url('/templates/'.$base_temp_name.'/style.css', __FILE__));
			wp_enqueue_style('pm_custom-style');

			if($pm_load_style == "pm_email_fname") {
	 			wp_register_style('pm_custom-style2', plugins_url('/templates/'.$base_temp_name.'/twofields.css', __FILE__));
	 		} 

	 		if($pm_load_style == "pm_email_only") {
	 			wp_register_style('pm_custom-style2', plugins_url('/templates/'.$base_temp_name.'/onefield.css', __FILE__));
	 		}

	 		if($pm_load_style == "pm_cta_btn") {
	 			wp_register_style('pm_custom-style2', plugins_url('/templates/'.$base_temp_name.'/cta_btn.css', __FILE__));
	 		}

	 		wp_enqueue_style('pm_custom-style2');

	 		wp_register_style('pm_temp_custcss', plugins_url('/css/custom.css', __FILE__));
	 	  	wp_add_inline_style('pm_temp_custcss', $custom_css );
	 	  	wp_enqueue_style('pm_temp_custcss');            
	 	  	  
	 		wp_register_style('pm_gwf1', "//fonts.googleapis.com/css?family=".implode("|", $gwf1arr));
	 		wp_enqueue_style('pm_gwf1');
	 	  	  
	 		wp_register_style('pm_gwf2', "//fonts.googleapis.com/css?family=$gwf2");
	 		wp_enqueue_style('pm_gwf2');	 	  	  	 	
	 		 	  	 	  	  
	 		include_once (Plugmatter_FILE_PATH."/templates/".$base_temp_name."/template.php");	 	  	
	    }
	    wp_register_script('pm_frontend_js',plugins_url('js/frontend.js', __FILE__), array('jquery'));
	    wp_enqueue_script('pm_frontend_js');
	    wp_enqueue_style('pm_fontawesome','//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
	}

?>

