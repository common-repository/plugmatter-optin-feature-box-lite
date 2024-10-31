<?php 
if(!class_exists('Plugmatter_FeatureBox')){

require_once("class.email_services.php");

class Plugmatter_FeatureBox {

	public function __construct() {
		global $wpdb;

		add_action('admin_menu', array($this, 'create_menu'));
				
		// Ajax Functions
		add_action( 'wp_ajax_plug_load_template', array($this, 'load_template'));
		add_action( 'wp_ajax_plug_get_page_content', array($this,'get_page_content'));
		
		$pm_es = new Plugmatter_Email_Service($wpdb->prefix.'plugmatter_templates');
		$pm_es->email_hooks();

		add_action( 'wp_ajax_pm_ab_track', array($this,'pm_ab_track'));
		add_action( 'wp_ajax_nopriv_pm_ab_track', array($this,'pm_ab_track'));

		// Styles and Scripts
		add_action('admin_enqueue_scripts', array($this,'admin_styles'));
		add_action('admin_enqueue_scripts', array($this,'admin_scripts'));

		// meta boxes
		add_action( 'add_meta_boxes', array($this,'add_meta_box'));
		add_action( 'post_updated', array($this,'save_metabox_data'));
	
		add_action('after_switch_theme', array($this,'pmfb_check_code_ready'));
		add_action( 'upgrader_process_complete', array($this,'pmfb_check_theme_upgrade'), 10, 2 );		
	}

	public function pmfb_check_theme_upgrade( $upgrader_object, $options ) {
		
        if($options['type'] == 'theme'){
			$cur_theme = wp_get_theme();	
			
			if(in_array($cur_theme->get( 'TextDomain' ), $options['themes']) &&  get_option('PMFB_CODE_READY')){
				update_option('PMFB_CODE_READY', false);	
			}	
		}
	}	

	public function pmfb_check_code_ready() {
		$new_theme = wp_get_theme();
		
		if(get_option('PMFB_CODE_READY') && ($new_theme->get( 'TextDomain' ) != get_option('plugmatter_codeready_theme'))){
			update_option('PMFB_CODE_READY', false);
		}
	}

	public function redirect_oninstall() {
		if ( is_plugin_active( dirname(plugin_basename( __FILE__ ) )."/main.php" )) {
	  		remove_action( 'update_option_active_plugins', array('Plugmatter_FeatureBox','redirect_oninstall'));
	  		update_option('Plugmatter_PACKAGE', Plugmatter_PACKAGE);
	  		update_option('PMFB_Plugin_Slug',dirname(plugin_basename(__FILE__)));
	      	exit( wp_redirect(admin_url('admin.php?page=pmfb_settings')));	
	    }
	}

	static function on_install() {
	    global $wpdb;

	    self:: check_pmfb_pkg_version();
	    	   
	    $template_tbl_name = $wpdb->prefix.'plugmatter_templates';
	    $abtest_tbl_name = $wpdb->prefix.'plugmatter_ab_test';
	    $ab_stats_tbl_name = $wpdb->prefix.'plugmatter_ab_stats';
	    
	    $template_tbl = "CREATE TABLE IF NOT EXISTS $template_tbl_name (
	        id INT(9) NOT NULL AUTO_INCREMENT,
	        temp_name VARCHAR(80) NOT NULL,
	        base_temp_name VARCHAR(80) NOT NULL,
	        params TEXT,
			UNIQUE KEY id (id)
	    ) DEFAULT CHARSET=utf8;";
	    $wpdb->query($template_tbl);

	    $abtest_tbl = "CREATE TABLE IF NOT EXISTS $abtest_tbl_name (
	    id INT(9) NOT NULL AUTO_INCREMENT,
	    compaign_name VARCHAR(80) NOT NULL,
	    boxA VARCHAR(80) NOT NULL,
	    boxB VARCHAR(100),
	    home VARCHAR(8),
	    page VARCHAR(8),
	    post VARCHAR(8),
	    archieve VARCHAR(8),
	    start_date VARCHAR(80) NOT NULL,
	    active VARCHAR(8),
	    UNIQUE KEY id (id)
	    ) DEFAULT CHARSET=utf8;";
	    $wpdb->query($abtest_tbl);
	    
	    $ab_stats_tbl = "CREATE TABLE IF NOT EXISTS $ab_stats_tbl_name (
	    id INT(9) NOT NULL AUTO_INCREMENT,
	    date DATE NOT NULL,
	    ab_id INT(9) NOT NULL,
	    a_imp INT(9),
	    b_imp INT(9),
	    a_conv INT(9),
	    b_conv INT(9),
	    UNIQUE KEY id (id)
	    ) DEFAULT CHARSET=utf8;";
	    $wpdb->query($ab_stats_tbl); 
	    

	    if(get_option("Plugmatter_PACKAGE") != Plugmatter_PACKAGE) {
	        delete_option('Plugmatter_PACKAGE');
	        delete_option('Plugmatter_Featurebox_License');	        
	    }

	    add_action('update_option_active_plugins', array('Plugmatter_FeatureBox','redirect_oninstall'));
	}

	static function on_uninstall() {
	    global $wpdb;
	    $template_tbl_name = $wpdb->prefix.'plugmatter_templates';
	    $abtest_tbl_name = $wpdb->prefix.'plugmatter_ab_test';
	    $ab_stats_tbl_name = $wpdb->prefix.'plugmatter_ab_stats';
	   
	    if(get_option('plugmatter_remove_data')){
	    	$structure = "drop table if exists $template_tbl_name";
	    	$wpdb->query($structure); 
	    	$structure2 = "drop table if exists $abtest_tbl_name";
	    	$wpdb->query($structure2);
	    	$structure3 = "drop table if exists $ab_stats_tbl_name";
	    	$wpdb->query($structure3);	
	    }
	    
	    $delete_options = "DELETE FROM wp_options WHERE option_name LIKE 'plugmatter_%' ";
	    $wpdb->query($delete_options);
	    delete_option('Plugmatter_PACKAGE');
	    delete_option('Plugmatter_Featurebox_License');	
	}


	/*
	-----------------------------
		Menu & pages 
	-----------------------------
	*/

	public function create_menu() {
	    if(Plugmatter_PACKAGE == "plug_featurebox_lite") {
	        $plug_menu_lable = "Feature Box Lite";
	    } else if(Plugmatter_PACKAGE == "plug_featurebox_single"){
	        $plug_menu_lable = "Feature Box Single";
	    } else {
	        $plug_menu_lable = "Feature Box Pro";
	    }
		
		add_menu_page("Plugmatter Featurebox - Settings",$plug_menu_lable,'manage_options',"pmfb_settings", array($this,'page_settings'), plugins_url( Plugmatter_DIR_NAME.'/images/icon.png')	);		
		add_submenu_page("pmfb_settings","Plugmatter Featurebox - Settings","General Settings",'manage_options',"pmfb_settings");
		add_submenu_page("pmfb_settings", 'Plugmatter Featurebox - Templates', 'Templates','manage_options', 'pmfb_template',array($this,'page_template') ); 	
		add_submenu_page("pmfb_settings", 'Plugmatter Featurebox - Split-Testing','Split-Testing','manage_options', 'pmfb_ab_test',array($this,'page_abtest') ); 
		
		add_submenu_page( '', 'Plugmatter Featurebox - Edit Template','','manage_options', 'pmfb_edit_template',array($this,'page_edit_template') );
		add_submenu_page( '', 'Plugmatter Featurebox - New A/B Split-Test Campaign','','manage_options', 'pmfb_add_ab_test',array($this,'page_add_abtest'));
		
		add_submenu_page( '', 'Plugmatter Featurebox - View A/B Split-Test Stats','','manage_options', 'pmfb_ab_test_stats',array($this,'page_abtest_stats') );
		
		add_submenu_page( '', 'Plugmatter Featurebox - Register for Support','','manage_options', 'pmfb_license',array($this,'page_license'));		
	}

	public function page_license() {
		$this->set_js_globals();
		include('license.php'); 
	}
	
	public function page_abtest_stats(){
		$this->set_js_globals();
		include('ab_statistics.php');
	}

	public function page_settings() {
		$this->set_js_globals();
		if(get_option('Plugmatter_Featurebox_License') == "") {
			include('license.php'); 
		} else {
			include('settings.php'); 
		}
	}

	public function page_abtest() {
		$this->set_js_globals();
	    include('ab_test.php'); 
	}

	public  function page_template() {
		$this->set_js_globals();
	    include('templates.php'); 
	}

	public  function page_edit_template() {
		$this->set_js_globals();
		include('edit_template.php');
	}

	public function page_add_abtest() {
		$this->set_js_globals();
		include('ab_test_add.php');
	}

	public function add_meta_box() {
		$post_types = get_post_types( array( 'public' => true ), 'names' ); 
				
		foreach ($post_types  as $post_type ) {
    		add_meta_box('myplugin_sectionid',__('Plugmatter Feature Box',''),array($this,'pm_post_inner_box'),$post_type);
		}

	}

	public function pm_post_inner_box( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'pm_meta_templ_id' );

		$value = get_post_meta($post->ID, "pm_meta_templ_id", true);
	
		global $wpdb;	
		$table = $wpdb->prefix.'plugmatter_templates';
		$templt_list = $wpdb->get_results("SELECT id,temp_name,base_temp_name,params FROM $table");	
		
		echo "Select Template for this Post: ";
		if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_pro" || get_option("Plugmatter_PACKAGE") == "plug_featurebox_dev") {
			echo "<select name ='pm_meta_templ_sel' >";	
			echo "<option value='-1' ";
			if($value == "-1") {
				echo "selected=selected";
			}
			echo " > Default </option>";
		
			echo "<option value='-2' ";
			if($value == "-2") {
				echo "selected=selected";
			}
			echo " > Disable </option>";	
		
			foreach ( $templt_list as $templ_list ) {
				echo "<option value=".$v = $templ_list->id;
				if($value == $v) {
					echo " selected='selected'";
				}
				echo " >".$templ_list->temp_name."</option>";		
			}
			echo "</select>";
		} else {
			echo Plugmatter_UPNOTE;
		}
	}


	public function save_metabox_data( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ('page' == $_POST['post_type'] )	{
			if ( !current_user_can( 'edit_page', $post_id ) )
				return;
		}
		else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}
		$pm_meta_templ_id = $_POST['pm_meta_templ_sel'];
		if( get_post_meta( $post_id, 'pm_meta_templ_id') == "") {
			add_post_meta($post_id, 'pm_meta_templ_id', $pm_meta_templ_id, true );
				
		} else if($pm_meta_templ_id != get_post_meta($post_id, $pm_meta_templ_id, true)) {
			update_post_meta($post_id, 'pm_meta_templ_id', $pm_meta_templ_id);
				
		} elseif($pm_meta_templ_id == "") {
			delete_post_meta($post_id, 'pm_meta_templ_id', get_post_meta($post_id, 'pm_meta_templ_id', true));
				
		}	
	}



	/*
	-----------------------------
		AJAX functions
	-----------------------------
	*/
	
	public function load_template() {
		$tem_name = $_GET["data"];
		include(Plugmatter_FILE_PATH . "/templates/". $tem_name . "/template.php");
		die();
	}

	public function get_page_content() {
		$page_id = $_POST['page_id'];
	    $thispost = get_post( $page_id );
	    $content = $thispost->post_content;
	    echo $content;
		die();
	}

	public function pm_ab_track() {
		global $wpdb;	
		$ab_stats_tbl = $wpdb->prefix.'plugmatter_ab_stats';
		$ab_test_tbl = $wpdb->prefix.'plugmatter_ab_test';	
		
	  	if(empty($_POST['ab_meta'])){
	    	die();
	 	}
	  
		$joined = explode( ':', $_POST['ab_meta'] );
		$ab_id = $joined[0];
		$templ_no = $joined[1];
		$date=date("Y-m-d");
		$box = "";
		
		if(isset($_POST['track']) && $_POST['track'] == "conv"){	
			$date=date("Y-m-d");
			$results = $wpdb->get_row("SELECT * FROM $ab_test_tbl WHERE active = 'yes' AND id = '".$ab_id."' ");
			if($results){
				if($results->boxA == $templ_no){
					$box = "a_conv";
				}else{
					$box = "b_conv";
				}
			}				
			$upq="UPDATE $ab_stats_tbl SET $box = $box+1 WHERE date = '$date' AND ab_id = '$ab_id'";
			$wpdb->query($upq);
		
		} else if(isset($_POST['track']) && $_POST['track'] == "imp"){			 	
			$results = $wpdb->get_row("SELECT * FROM $ab_test_tbl WHERE active = 'yes' AND id = '".$ab_id."' ");
			if($results){			
				if($results->boxA == $templ_no){				
					$upq="UPDATE $ab_stats_tbl SET a_imp = a_imp+1 WHERE date = '$date' AND ab_id = '$ab_id'";
					$wpdb->query($upq);
				}else{				
					$upq="UPDATE $ab_stats_tbl SET b_imp = b_imp+1 WHERE date = '$date' AND ab_id = '$ab_id'";
					$wpdb->query($upq);
				}			
			}
		}		
		die();
	}


	/*
	-----------------------------------
		Enqueue Scripts and Styles
	-----------------------------------
	*/
	public function admin_styles($hook) {	
		
		if('admin_page_pmfb_edit_template' == $hook ) {
			wp_enqueue_style('thickbox');
			wp_register_style('pm_inline_edit_style', plugins_url('css/pm_inline_edit.css', __FILE__));
			wp_enqueue_style('pm_inline_edit_style');
			wp_enqueue_style('pm_button_style', plugins_url('/css/pm_btn_style.css', __FILE__));		
	    	wp_enqueue_style('pm_bootstrap', plugins_url('/css/pm_bootstrap.css', __FILE__));        
	    	wp_enqueue_style('pm_codemirror', plugins_url('/css/pm_codemirror.css', __FILE__));  
	    	wp_enqueue_style('pm_fontawesome','//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');      		
		}
		
		wp_register_style('pm_settings', plugins_url('css/style.css', __FILE__));
		wp_enqueue_style('pm_settings');	
		wp_register_style('pm_headfont', "//fonts.googleapis.com/css?family=Fauna+One");
		wp_enqueue_style('pm_headfont');
		wp_enqueue_style( 'wp-color-picker' );			
	}

	public function admin_scripts($hook) {
		
		wp_enqueue_script('jquery');						
		/* pm support script */
  		wp_register_style('pmfb_support_style', '//plugmatter.com/css/pm_support_widget.css',array(),PMFB_VERSION);
  		if(get_option('Plugmatter_Featurebox_License') != ''){
  			$pmfb_hash = explode("-",get_option('Plugmatter_Featurebox_License'));
 			wp_register_script('pmfb_pmsupport','//plugmatter.com/js/pm_support_widget.js?pid=pmfb&pkg='.Plugmatter_PACKAGE.'&hash='.$pmfb_hash[0].'-'.$pmfb_hash[5], array('jquery'),PMFB_VERSION,true);	
  		} else{
  			wp_register_script('pmfb_pmsupport','//plugmatter.com/js/pm_support_widget.js?pid=pmfb&pkg='.Plugmatter_PACKAGE.'&hash=undefined', array('jquery'),PMFB_VERSION,true);
  		}
  		  		 
		if('admin_page_pmfb_edit_template' == $hook ) {

			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_media();
			wp_register_script('pm_inline_edit_style_js',plugins_url('js/inline_edit.js', __FILE__), array('jquery'),PMFB_VERSION);
			wp_enqueue_script('pm_inline_edit_style_js');
			wp_register_script('pm__farbtastic_js',plugins_url('js/farbtastic.js', __FILE__), array('jquery'),PMFB_VERSION);
			wp_enqueue_script('pm__farbtastic_js');
			wp_register_script('pm_image_uploader', plugins_url('js/image_uploader.js', __FILE__), array('jquery','media-upload','thickbox'),PMFB_VERSION);
			wp_enqueue_script('pm_image_uploader');
			wp_register_script('pm_button_editor', plugins_url('/js/pm_button_editor.js', __FILE__),array('jquery','wp-color-picker'),PMFB_VERSION);
			wp_enqueue_script('pm_button_editor');

			wp_register_script('pm_codemirror_js',plugins_url('js/pm_codemirror.js', __FILE__), array('jquery'),PMFB_VERSION);
		    wp_enqueue_script('pm_codemirror_js');
		    wp_register_script('pm_codemirror_css', plugins_url('js/pm_codemirror_css.js', __FILE__),array(),PMFB_VERSION);
			wp_enqueue_script('pm_codemirror_css');

			wp_enqueue_style('pmfb_support_style');
  			wp_enqueue_script('pmfb_pmsupport');

		}else if('toplevel_page_pmfb_settings' == $hook ) {
			wp_register_script('pm_codready_js',plugins_url('js/pm_codeready.js', __FILE__), array('jquery'),PMFB_VERSION);
			wp_enqueue_script('pm_codready_js');

			wp_enqueue_style('pmfb_support_style');
  			wp_enqueue_script('pmfb_pmsupport');
		}else if('admin_page_pmfb_ab_test_stats' == $hook ){
			wp_register_script('jqueryflot',plugins_url('js/jquery.flot.js', __FILE__), array('jquery'),PMFB_VERSION);
			wp_enqueue_script('jqueryflot');
			wp_register_script('excanvas_min',plugins_url('js/excanvas.min.js', __FILE__), array('jquery'),PMFB_VERSION);
			wp_enqueue_script('excanvas_min');			

			wp_enqueue_style('pmfb_support_style');
  			wp_enqueue_script('pmfb_pmsupport');	
		}

		$pkg_ext = strtolower(PMFB_PKG_SLUG);

		if($hook == 'feature-box-pro_page_pmfb_template' || $hook == 'feature-box-pro_page_pmfb_ab_test' || $hook == 'admin_page_pmfb_add_ab_test' || $hook == 'admin_page_pmfb_license'){
			wp_enqueue_style('pmfb_support_style');
  			wp_enqueue_script('pmfb_pmsupport');	
		}
		
	}

	/*
	-----------------------------
		Private functions
	-----------------------------
	*/
	public function set_js_globals() {
		echo "<script type='text/javascript'>
				var pm_plugin_url = '".plugins_url(Plugmatter_DIR_NAME.'/')."';
				var pm_site_url = '".admin_url('admin-ajax.php')."';
				var fonts = ".file_get_contents(Plugmatter_FILE_PATH.'/js/gfonts.json').";
				var button_fluid = 0;
			  </script>";
	}

	public function check_pmfb_pkg_version(){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$pmfb_active_pkg = get_option("PMFB_Plugin_Slug");

		$get_pmfb_installing_pkg = explode("_", get_option("PMFB_INSTALLING_PKG"));
		$pmfb_installing_pkg = end($get_pmfb_installing_pkg);	

	 	if(is_plugin_active($pmfb_active_pkg.'/main.php')){
	 		$get_pmfb_pkg = explode("_",get_option("Plugmatter_PACKAGE"));
	 		$pmfb_pkg = end($get_pmfb_pkg);
	 		
	 		$msg = '';
	 		$deactivate = false;
	 		
	 		switch ($pmfb_pkg) {
		 		case 'lite':
		 			if($pmfb_installing_pkg == "single" || $pmfb_installing_pkg == "pro" || $pmfb_installing_pkg == "dev"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have a Lite version this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}	
		 			break;
		 		case 'single':
		 			if($pmfb_installing_pkg == "lite"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have a 'Single' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}else if($pmfb_installing_pkg == "single"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Single' License of this plugin installed! If you want to re-install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}else if($pmfb_installing_pkg == "pro" || $pmfb_installing_pkg == "dev"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have a 'Single' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}
		 			break;
		 		case 'pro':
		 			if($pmfb_installing_pkg == "lite"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Pro' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";	
		 				$deactivate = true;
		 			}elseif($pmfb_installing_pkg == "single"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Pro' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</div>";
		 				$deactivate = true;
		 			}else if($pmfb_installing_pkg == "pro"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Pro' License of this plugin installed! If you want to re-install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			} elseif($pmfb_installing_pkg == "dev"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Pro' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}
		 			break;
		 		case 'dev':
		 			if($pmfb_installing_pkg == "lite" || $pmfb_installing_pkg == "single" || $pmfb_installing_pkg == "pro"){
		 				$msg = "<div class='error'><p>Oops, looks like you already have the 'Dev' License of this plugin installed! If you want to install it, first deactivate the previously installed feature box plugin.</p></div>";
		 				$deactivate = true;
		 			}
		 			break;
		 		}	


		 	if($deactivate){
		 		delete_option("PMFB_INSTALLING_PKG");
		 		wp_die($msg);		 	
		 	}	
		}
	}

} // class ends

} // if ends