<?php 
if(!class_exists('Plugmatter_Email_Service')){
	class Plugmatter_Email_Service {

		private $templates_tbl_name = ""; 

		public function __construct($tbl_name) {
			$this->templates_tbl_name = $tbl_name;
		}

		public function email_hooks(){
			add_action( 'wp_ajax_pmfb_cc', array($this,'pmes_cc'));
			add_action( 'wp_ajax_nopriv_pmfb_cc', array($this,'pmes_cc'));

			add_action( 'wp_ajax_pmfb_mailchimp', array($this,'pmes_mailchimp'));
			add_action( 'wp_ajax_nopriv_pmfb_mailchimp', array($this,'pmes_mailchimp'));

			add_action( 'wp_ajax_pm_jetpack', array($this,'pmes_jetpack'));
			add_action( 'wp_ajax_nopriv_pm_jetpack', array($this,'pmes_jetpack'));

			add_action( 'wp_ajax_pm_convertkit', array($this,'pmes_convertkit'));
			add_action( 'wp_ajax_nopriv_pm_convertkit', array($this,'pmes_convertkit'));
		}


		public function pmes_form_fields($params, $template_id) {

 	  		@$http_prep = substr($params->action_url, 0, 4);
 	  		if($http_prep != "http"){
 	  			@$params->action_url = "http:".$params->action_url;
 	  		}
 	  		
 	  		@$service_meta = array("Aweber" => array("action_url" => "http://www.aweber.com/scripts/addlead.pl","name" => "email", "name_field" => "name"),
						 "GetResponse" => array("action_url" => "https://app.getresponse.com/add_subscriber.html","name" => "email", "name_field" => "first_name"),
						  "iContact" => array("action_url" => "https://app.icontact.com/icp/signup.php","name" => "fields_email", "name_field" => "fields_fname"),
						  "MailChimp" => array("action_url" => $params->action_url, "name" => "MERGE0", "name_field" => "MERGE1"),
						  "MailChimp_SingleOptin" => array("action_url" => "#pm_mailchimp_singloptin", "name" => "MERGE0", "name_field" => "MERGE1"),
						  "ConvertKit" => array("action_url" => "#pm_convertkit", "name" => "email", "name_field" => "first_name"),
						  "ConstantContact" => array("action_url" => "#pm_constantcontact","name" => "cc_email", "name_field" => "cc_firstname"),
                          "CampaignMonitor" => array("action_url" => "http://".$params->cm_account_name.".createsend.com/t/r/s/".$params->cm_id."/","name" => "cm-".$params->cm_id."-".$params->cm_id, "name_field" => "cm-name"),
 	  					  "InfusionSoft" => array("action_url" => "https://".$params->account_subdomain.".infusionsoft.com/app/form/process/".$params->inf_form_xid, "name" => "inf_field_Email", "name_field" => "inf_field_FirstName"),
 	  					  "InInbox" => array("action_url" => "http://www.ininbox.com/webform_contact_add.html", "name" => "sys_vEmail", "name_field" => "sys_vFName"), 
 	  					  "Feedburner" => array("action_url" => "http://feedburner.google.com/fb/a/mailverify","name" => "email", "name_field" => "name"),	 	  				
						  "MadMimi" => array("action_url" => "https://madmimi.com/signups/subscribe/".$params->webform_id,"name" => "signup[email]", "name_field" => "signup[name]"),	 	  											
						  "MailPoet" => array("action_url" => "#pm_mailpoet","name" => "email", "name_field" => "name"),	 	  																		  
                          "Feedblitz" => array("action_url" => "http://www.feedblitz.com/f/f.fbz?Sub=".(isset($params->sub)?$params->sub:""),"name" => "email", "name_field" => "name"),
                          "Ontraport" => array("action_url" => "//forms.ontraport.com/v2.4/form_processor.php?","name" => "email", "name_field" => "firstname"),
                          "SendInBlue" => array("action_url" => "https://my.sendinblue.com/users/subscribe/js_id/".(isset($params->js_id)?$params->js_id:"")."/id/1","name" => "email", "name_field" => "NAME"),                                      
                          "Jetpack" => array("action_url" => "#pm_jetpack","name" => "email", "name_field" => "name"),     
                          "ActiveCampaign" => array("action_url" => "//".$params->domain.".activehosted.com/proc.php", "name" => "email", "name_field" => "name"),                                 
                          "Custom" => array("action_url" => (isset($params->action_url)?$params->action_url:""), "name" => (isset($params->email_field_name)?$params->email_field_name:""), "name_field" => (isset($params->name_field_name)?$params->name_field_name:""))
			);
			$service_name = $params->service;
			$pm_service_action = $service_meta[$service_name]['action_url'];	
			$pm_input_name = $service_meta[$service_name]['name'];
			$pm_input_name_field_name = $service_meta[$service_name]['name_field'];
            
            if($params->service == "Jetpack") {
                $pm_service_hiddens.="<input type='hidden' name='jetpack_subscriptions_widget' value='subscribe' />";
            }
            if($params->service == "ConstantContact" || $params->service == "MailChimp_SingleOptin" || $params->service == "ConvertKit" ||$params->service == "Jetpack") {
                $pm_service_hiddens.="<input type='hidden' name='pmfb_tid' value='".$template_id."' />";
            }
            
            if($params->service == "ActiveCampaign" ) {
                $pm_service_hiddens.="<input type='hidden' name='s' value='' />";
                $pm_service_hiddens.="<input type='hidden' name='c' value='0' />";
                $pm_service_hiddens.="<input type='hidden' name='m' value='0' />";
                $pm_service_hiddens.="<input type='hidden' name='act' value='sub' />";
                
            }

            foreach($params as $key=>$value){
			 	if($key != "service"){
					if($service_name == "Aweber" && $key == "redirect_url") {
						$key = "redirect";
					}
					if($service_name == "iContact" && $key == "redirect_url") {
						$key = "redirect";
					}						
					if($service_name == "iContact" && $key == "specialid") {
						$key = "specialid:" . $params->listid;
					}      


			 		if($service_name == "Custom") {
						if(strpos($key, "value") !== false) {
							$tmp_custom_key = substr($key,0, -6);
							$custom_key = $params->$tmp_custom_key;
							$custom_value = $value;
							$pm_service_hiddens.="<input type='hidden' name='".$custom_key."' value='".$custom_value."' />";
						}
					} else if ($params->service == "ConstantContact") {
						if($key == "cc_redirect_url") {
							$pm_service_hiddens.="<input type='hidden' name='".$key."' value='".$value."' />";					
						}
						// ignore all the other fields ... not add in hidden fields
					}else {  
                        if($key != "action_url") {
			 			    $pm_service_hiddens.="<input type='hidden' name='".$key."' value='".$value."' />";					
                        }
					}

			 	}
			}
			return array(
						"pm_service_action"	   => $pm_service_action,
						"pm_input_name"			   => $pm_input_name,
						"pm_input_name_field_name" => $pm_input_name_field_name,
						"pm_service_hiddens"	   => $pm_service_hiddens	
					);
		}

		public function pmes_cc () {
			global $wpdb;

			$table = $this->templates_tbl_name;
			$cc_url = "http://ccprod.roving.com/roving/wdk/API_AddSiteVisitor.jsp";
			$fname = $_POST["fname"];
			$email 	= $_POST["email"];

			$temp_id = intval($_POST["pmfb_tid"]);
			$temp_params = $wpdb->get_row($wpdb->prepare("SELECT params FROM $table WHERE id= %d",$temp_id)); 
		
			$pm_params = json_decode($temp_params->params);
			
			if(!empty($pm_params)) {
				foreach ($pm_params as $param_values) {
					if($param_values->type == "service") {
						if($param_values->params->service == "ConstantContact") {
							$username = $param_values->params->cc_username;
							$password = $param_values->params->cc_password;
							$category = $param_values->params->cc_list_name;

							if(trim($username) === "" || trim($password) === "" || trim($category) === "") {
								//echo "Empty Constant Contact Login Credentials";
								echo 0;
								die();
							}

							if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
								//echo "Invalid Email";
								echo 0;
							  	die();
							}

							$response = wp_remote_post( $cc_url, array(
								'method' => 'POST',
								'timeout' => 45,
								'redirection' => 5,
								'httpversion' => '1.0',
								'blocking' => true,
								'headers' => array(),
								'body' => array( 'loginName' => $username, 'loginPassword' => $password, 'First_Name' => $fname, 'ea' => $email, 'ic' => $category ),
								'cookies' => array()
							    )
							);

							if ( is_wp_error( $response ) ) {
							   $error_message = $response->get_error_message();
							   echo "Something went wrong: $error_message";
							} else {
							   echo 'Response:<pre>';
							   print_r( $response );
							   echo '</pre>';
							}
						}
					}
				}
			}
			
			die();
		}


		public function pmes_mailchimp ($table_name) {
			global $wpdb;
			
			
			$table = $this->templates_tbl_name;
			$fname = $_POST["MERGE1"];
			$email 	= $_POST["MERGE0"];

			$temp_id = intval($_POST["pmfb_tid"]);
			$temp_params = $wpdb->get_row($wpdb->prepare("SELECT params FROM $table WHERE id= %d",$temp_id));
			
			$pm_params = json_decode($temp_params->params);
			
			if(!empty($pm_params)) {
				foreach ($pm_params as $param_values) {
					if($param_values->type == "service") {
						if($param_values->params->service == "MailChimp_SingleOptin") {
							$api_key = $param_values->params->api_key;
							$list_id = $param_values->params->list_id;

							if(trim($api_key) === "" || trim($list_id) === "") {
								echo 0;
								die();
							}

							if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
								echo 0;
							  	die();
							}


							require_once('MCAPI.class.php');
							$api = new MCAPI($api_key);
							$list_id = $list_id;
							
							$merge_vars = array('MERGE1'=> $fname);
							$retval = $api->listSubscribe( $list_id, $email,$merge_vars,'','false');


							if ( !$retval ) {
								echo "Something went wrong: $error_message";
							} 
						}
					}
				}
			}
			
			die();
		}

		public function pmes_jetpack(){
			global $wpdb;
			
			$table = $this->templates_tbl_name;
			$fname = $_POST["name"];
			$email 	= $_POST["email"];
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo 0;
			 	die();
			}

			$retval = Jetpack_Subscriptions::subscribe( $email, 0, false );
			die();    	
		}


		public function pmes_convertkit(){
			$ck_id 	= $_POST["id"];	
			$ck_url = "https://app.convertkit.com/landing_pages/".$ck_id."/subscribe";
			$fname = $_POST["name"];
			$email 	= $_POST["email"];
			$response = wp_remote_get( $ck_url, array(
				'method' => 'GET',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'first_name' => $fname, 'email' => $email, 'id' => $ck_id),
				'cookies' => array()
			    )
			);

			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
			   echo "Something went wrong: $error_message";
			} else {
			   echo 'Response:<pre>';
			   print_r( $response );
			   echo '</pre>';
			}
		}
	}
}

?>