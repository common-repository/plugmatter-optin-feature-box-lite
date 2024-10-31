<?php 
/*
Plugin Name: Plugmatter Feature Box Lite
Plugin URI: http://plugmatter.com/feature-box
Description: Plugmatter Optin Feature Box is the Only List Building Plugin that Allows You to Create High Converting Optin Feature Boxes for Your WordPress Site.
Author: Plugmatter
Version: 2.1.39
Author URI: http://plugmatter.com/
*/

//--- Global values---
@define('Plugmatter_PACKAGE', 'plug_featurebox_lite');
@define('PMFB_VERSION', '2.1.39');
@define('PMFB_PKG_SLUG', 'Lite');

delete_option("PMFB_INSTALLING_PKG");
add_option("PMFB_INSTALLING_PKG", 'plug_featurebox_lite');
//--------------------
global $wpdb;
$siteurl = get_option('siteurl');

@define('Plugmatter_FILE_PATH', dirname(__FILE__));
@define('Plugmatter_DIR_NAME', basename(Plugmatter_FILE_PATH));
@define('Plugmatter_FOLDER', dirname(plugin_basename(__FILE__)));
@define('Plugmatter_URL', plugin_dir_url( __FILE__ )); 
@define('Plugmatter_UPNOTE', "<span style='color:red;'>This feature is available in higher packages. <a href='http://plugmatter.com/my/packages' target='_blank'><b>Upgrade Now!</b></a></span>");
//------------------------------------------------

// Activatio and Deactivation
register_activation_hook(__FILE__, array("Plugmatter_FeatureBox", 'on_install'));
register_uninstall_hook(__FILE__ ,  array("Plugmatter_FeatureBox", 'on_uninstall'));

require_once(dirname(__FILE__) .'/class.plugmatter-featurebox.php');
$pmfb = new Plugmatter_FeatureBox();

if(!function_exists('plugmatter_custom_hook')){
	function plugmatter_custom_hook() {
		wp_enqueue_script('jquery');
		$pmfb = new Plugmatter_FeatureBox();
		$pmfb->set_js_globals();
		update_option("PMFB_CODE_READY", true);
		if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") {
			include_once('frontend_lite.php');	
		} else if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_single") {
			include_once('frontend_single.php');
		} else {
			include_once('frontend_pro.php');		
		}
	}
}


?>