<?php 

	include_once ('show_featurebox.php'); 	  

	$plugmatter_enable = get_option('plugmatter_enable');	
	$pm_post_meta = "";
	$pm_box_width = "";
	
	if($plugmatter_enable != '1') {
		return;
	}	 

 	$plugmatter_show_temp_optinuser = get_option('plugmatter_show_temp_optinuser');
	if(isset($_COOKIE['plugmatter_conv_done']) && $_COOKIE['plugmatter_conv_done'] == '1' && $plugmatter_show_temp_optinuser == '0' ){	 
		 return ;
	}
	
	if(!is_home()) {
		$pm_post_meta = get_post_meta(get_the_ID(), "pm_meta_templ_id", true);
	}

	get_basic_template();
	 	  
	function get_basic_template(){
		$plugmatter_global_template = get_option('plugmatter_global_template');
  		$template_id = $plugmatter_global_template ;
	 	if($template_id != ""){
	 		show_template($template_id);
	 	}
	}
	
?>	 	