document.addEventListener("DOMContentLoaded", function(event) { 

	var pmfb_box_elem = document.getElementById("pm_featurebox"); 

  		document.getElementsByClassName("pm_form_track").onsubmit = function(event){

			var pmfb_conv_data = "action=pm_ab_track&track=conv&ab_meta="+pmfb_box_elem.getAttribute("ab_meta");

			pmfb_xhr("POST", pm_site_url, pmfb_conv_data, {success:function(data) {}});
		}; 
				
		var pmfb_imp_data  = "action=pm_ab_track&track=imp&ab_meta="+pmfb_box_elem.getAttribute("ab_meta");	
		pmfb_xhr("POST", pm_site_url, pmfb_imp_data, {success:function(data) {}});	
  		
	if(pm_getCookie("plugmatter_num_of_revisits") == "undefined") {
		pm_setCookie("plugmatter_num_of_revisits",1,365);
	} else {	
		var cvcnt = +pm_getCookie("plugmatter_num_of_revisits") + 1;
		pm_setCookie("plugmatter_num_of_revisits",cvcnt,365);
	}
	
	pmfb_box_elem.style.display = 'block';
});
	
	var pmfb_box_elem = document.getElementById("pm_featurebox");	
	pmfb_box_elem.style.display = 'none';
	
	var pmfb_form_elem = document.getElementById("pm_form_submit");

	// submit form using pm_button_div anchor tag
	var pmfb_btn = document.getElementById("pm_button_div").querySelector('a');

	pmfb_btn.onclick = function(event){
		event.preventDefault();		
		if(pmfb_form_elem.onsubmit()){
			pmfb_form_elem.submit();
		}
	}

	// submit form when enter key is pressed on input field
	var pm_input = document.getElementById("pm_input");

	pm_input.onkeyup = function(event){

		if(event.which == 13 || event.keyCode == 13){
			if(pmfb_form_elem.onsubmit()){
				pmfb_form_elem.submit();
			}				
		}
	}

	pmfb_form_elem.onsubmit = function(){
		var email = document.getElementById('pm_input').value;
		var e_patt = new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);

		if (e_patt.test(email)) { 
			pm_setCookie("plugmatter_conv_done",1,365);

			/* Analytics Tracking */
				var tid = pmfb_box_elem.getAttribute("pm_meta_tid");
				
				if (typeof ga !== 'undefined') {
					if(tid) {
						var	temp_name = tid.split("_")[1];	
						ga('send','event', 'Plugmatter Feature Box', 'Subscription', temp_name);
					}	
				}

				if (typeof _gaq !== 'undefined') {
					if(tid) {
						var	temp_name = tid.split("_")[1];	
						_gaq.push(['_trackEvent', 'Plugmatter Feature Box', 'Subscription', temp_name]);
					}	
				}
			/*--------------------------*/

			var pmfb_form_action = pmfb_form_elem.getAttribute("action");			

      		var pmfb_ab_track_data = "action=pm_ab_track&track=conv&ab_meta="+pmfb_box_elem.getAttribute("ab_meta");
      		
      		pmfb_xhr("POST", pm_site_url, pmfb_ab_track_data, {success:pmfb_ab_track_callback});

      		if(pmfb_form_action == "#pm_mailpoet" || pmfb_form_action == "#pm_constantcontact" || pmfb_form_action == "#pm_mailchimp_singloptin" || pmfb_form_action == "#pm_jetpack" || pmfb_form_action == "#pm_convertkit") {  
        		return false;
      		}

      		return true;
		} else {
			alert("You have entered an invalid email address!");  
			return false;
		}    	
	};
				

function pmfb_ab_track_callback(response_data){

	var pmfb_form_action = pmfb_form_elem.getAttribute("action");
	

	switch(pmfb_form_action){
		case "#pm_mailpoet":
				var email = pmfb_box_elem.querySelector('input[name="email"]').value;
				var fname;
				var fname_element =  pmfb_box_elem.querySelector('input[name="name"]');

				if (typeof(fname_element) != 'undefined' && fname_element != null) {
				  fname = pmfb_box_elem.querySelector('input[name="name"]').value;
				}
				
				var list_id = pmfb_box_elem.querySelector('input[name="list_id"]').value;
				var redirect_url = pmfb_box_elem.querySelector('input[name="redirect_url"]').value;

				var pmfb_mailpoet_data =  "action=wysija_ajax&controller=subscribers&task=save&data[0][name]=wysija[user][firstname]&data[0][value]="+fname+"&data[1][name]=wysija[user][email]&data[1][value]="+email+"&data[2][name]=wysija[user_list][list_ids]&data[2][value]="+list_id;
				
				pmfb_xhr("POST", pm_site_url, pmfb_mailpoet_data,{success:function(data) {
																	if(data.result === true) {
																		location.href = redirect_url;
																	} else {
																		alert("Error Subscribing User");
																	}
																}
															});
			break;		
		case "#pm_constantcontact" :
				var pmcc_email = pmfb_box_elem.querySelector('input[name="cc_email"]').value;
				var pmcc_fname;
				var fname_element =  pmfb_box_elem.querySelector('input[name="cc_firstname"]');

				if (typeof(fname_element) != 'undefined' && fname_element != null) {
					pmcc_fname = pmfb_box_elem.querySelector('input[name="cc_firstname"]').value;
				}
								
				var pmfb_tid = pmfb_box_elem.querySelector('input[name="pmfb_tid"]').value;
				var pmcc_redirect_url = pmfb_box_elem.querySelector('input[name="cc_redirect_url"]').value;

				var pmfb_cc_data = "action=pmfb_cc&email="+pmcc_email+"&fname="+pmcc_fname+"&pmfb_tid="+pmfb_tid;

				pmfb_xhr("POST", pm_site_url, pmfb_cc_data,{success:function(data) {
																	if(data === "0") {
																		alert("Error Subscribing User");	
																		return false;
																	} else {
																		location.href = pmcc_redirect_url;
																	}
																}
															});
				break;
				
		case "#pm_mailchimp_singloptin" :
				var pm_mailchimp_email = pmfb_box_elem.querySelector('input[name="MERGE0"]').value;
				var pm_mailchimp_fname;
				var fname_element =  pmfb_box_elem.querySelector('input[name="MERGE1"]');

				if (typeof(fname_element) != 'undefined' && fname_element != null) {
					pm_mailchimp_fname = pmfb_box_elem.querySelector('input[name="MERGE1"]').value;
				}
				
				var pmfb_tid = pmfb_box_elem.querySelector('input[name="pmfb_tid"]').value;
				var pm_mailchimp_redirect_url = pmfb_box_elem.querySelector('input[name="redirect_url"]').value;

				var pmfb_mailchimp_data = "action=pmfb_mailchimp&MERGE0="+pm_mailchimp_email+"&MERGE1="+pm_mailchimp_fname+"&pmfb_tid="+pmfb_tid;

				pmfb_xhr("POST", pm_site_url, pmfb_mailchimp_data,{success:function(data) {
																if(data === "0") {
																	alert("Error Subscribing User");	
																	return false;
																} else {
																	location.href = pm_mailchimp_redirect_url;
																}
															}
														});
				break;

	 	case "#pm_jetpack" :
				var pm_jetpack_email = pmfb_box_elem.querySelector('input[name="email"]').value;
				var pm_jetpack_fname;
				var fname_element =  pmfb_box_elem.querySelector('input[name="name"]');

				if (typeof(fname_element) != 'undefined' && fname_element != null) {
					pm_jetpack_fname = pmfb_box_elem.querySelector('input[name="name"]').value;
				}
				
				var pmfb_tid = pmfb_box_elem.querySelector('input[name="pmfb_tid"]').value;
				var pm_jetpack_redirect_url = pmfb_box_elem.querySelector('input[name="redirect_url"]').value;

				var pmfb_jetpack_data = "action=pm_jetpack&email="+pm_jetpack_email+"&name="+pm_jetpack_fname+"&pmfb_tid="+pmfb_tid;
				
				pmfb_xhr("POST", pm_site_url, pmfb_jetpack_data,{success:function(data) {
																			if(data === "0") {
																				alert("Error Subscribing User");	
																				return false;
																			} else {
																				location.href = pm_jetpack_redirect_url;
																			}
																		}
																	});
				break;

		case "#pm_convertkit" : 
				
				var pm_convertkit_email = pmfb_box_elem.querySelector('input[name="email"]').value;
				var pm_convertkit_fname;
				var fname_element =  pmfb_box_elem.querySelector('input[name="first_name"]');

				if (typeof(fname_element) != 'undefined' && fname_element != null) {
					pm_convertkit_fname = pmfb_box_elem.querySelector('input[name="first_name"]').value;
				}

				var pmfb_tid = pmfb_box_elem.querySelector('input[name="pmfb_tid"]').value;
				var pm_convertkit_redirect_url = pmfb_box_elem.querySelector('input[name="redirect_url"]').value;
				var pm_convertkit_id = pmfb_box_elem.querySelector('input[name="id"]').value;

				var pmfb_converkit_data =  "action=pm_convertkit&email="+pm_convertkit_email+"&name="+pm_convertkit_fname+"&pmfb_tid="+pmfb_tid+"&id="+pm_convertkit_id;
							
				pmfb_xhr("POST", pm_site_url, pmfb_converkit_data,{success:function(data) {
																			if(data === "0") {
																				alert("Error Subscribing User");	
																				return false;
																			} else {
																				location.href = pm_convertkit_redirect_url;
																			}
																		}
																	});
				break;
	}

}



function pmfb_xhr (type, url, data, options) {
	
  options = options || {};
  var request = new XMLHttpRequest();
  request.open(type, url, true);

  if(type === "POST"){
      request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  }

  request.onreadystatechange = function () {

  	
    if (this.readyState === 4) {
      if (this.status >= 200 && this.status < 400) {
      	
        options.success && options.success(pmfb_xhr_parse(this.responseText));
      } else {
        options.error && options.error(this.status);
      }
    }
  };

  request.send(data);
}


function pmfb_xhr_parse(text){
  try {
     return JSON.parse(text);
  } catch(e){
     return text;
  }
}


function pm_setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
//	alert("done");
}

function pm_getCookie(c_name){
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1) {
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1) {
		c_value = null;
	} else {
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1) {
			c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}