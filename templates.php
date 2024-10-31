<?php
$msg = "";
global $wpdb;
$siteurl = get_option('siteurl');
$plugmatter_table_name = $wpdb->prefix.'plugmatter_templates';

if(isset($_GET['action'])) {
	if($_GET['action']== "delete" && $_GET['delete_id']!='') {
		$id= intval($_GET['delete_id']);
		$wpdb->query($wpdb->prepare("DELETE FROM $plugmatter_table_name WHERE id = %d", $id));
		$msg = "<div id='setting-error-settings_updated' class='updated settings-error' style='margin-bottom:20px;'>
				<p>
				    <strong>Your template has been deleted successfully.</strong>
				</p>
		  	  </div>";
	}	
	if($_GET['action']== "clone" && $_GET['clone_id']!='') {
		$id= intval($_GET['clone_id']);
		$org_temp = $wpdb->get_row($wpdb->prepare("SELECT * FROM $plugmatter_table_name WHERE id = %d", $id));
		
		$wpdb->query("INSERT INTO $plugmatter_table_name(temp_name, base_temp_name,params)VALUES('Clone of ".$org_temp->temp_name."', '".$org_temp->base_temp_name."','".addslashes($org_temp->params)."')");
		
		$msg = "<div id='setting-success-settings_updated' class='updated settings-success' style='margin-bottom:20px;'>
				<p>	
				    <strong>Template cloned successfully.</strong>
				</p>
		  	  </div>";
	}		
}

if(isset($_POST['action'])) {
	$temp_name = sanitize_text_field($_POST["temp_name"]);
	$new_params = stripslashes($_POST["params"]);
	
	$id= intval($_POST['template_id']);
	$base_temp_name = sanitize_text_field($_POST["base_temp_name"]);
			
	if($_POST['action']=="edit" && $_POST['template_id']!='') {	

		$wpdb->query($wpdb->prepare("UPDATE $plugmatter_table_name SET temp_name = %s, params = %s WHERE id = %d", $temp_name, $new_params, $id));
	
		$msg = "<div id='setting-error-settings_updated' class='updated settings-error' style='margin-bottom:20px;'>
				<p><strong>Your template has been updated successfully.</strong></p>
		  	  </div>";	
	} else if(isset($_POST["temp_name"] ) && ($_POST["temp_name"]!="") && ($_POST['action'])=="insert" ) {	
		
		$wpdb->query($wpdb->prepare("INSERT INTO $plugmatter_table_name(temp_name, base_temp_name, params) VALUES (%s,%s,%s)", $temp_name, $base_temp_name,$new_params ));
		
		$siteurl = get_option('siteurl');
		$msg = "<div id='setting-error-settings_updated' class='updated settings-error' style='margin-bottom:20px;'>
				<p><strong>Your template has been saved successfully.</strong></p>
		  	  </div>";
	}
}
?>
<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>Templates <a href="<?php echo admin_url("admin.php?page=pmfb_edit_template&action=insert"); ?>">Add New</a></h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url("/images/logo.png", __FILE__ ); ?>' height='35'></div>
	</div>
	<div class='pmadmin_body'>
	<?php echo $msg; ?>
	<div class='plug_list_head'>Your Templates</div>	
<?php 

$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name	FROM $plugmatter_table_name	ORDER BY id DESC");
$result_count = count($resultss);
if($result_count != 0) {
?>

<table class="widefat">
	<thead>
		<tr style="">
			<th width='50%'>Title</th>
			<th>Base Template</th>
			<th>Edit</th>		
			<th>Clone</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Title</th>
			<th>Base Template</th>
			<th>Edit</th>		
			<th>Clone</th>
			<th>Delete</th>
		</tr>
	</tfoot>
<tbody>

<?php 
foreach ( $resultss as $fivesdraft ) {
	$id=$fivesdraft->id;
	$temp_name=$fivesdraft->temp_name;
	$base_temp_name=$fivesdraft->base_temp_name;
?>
	<tr>
		<td class="post-title column-title">
			<strong>
			    <a title="Edit" href="<?php echo admin_url("admin.php?page=pmfb_edit_template&action=edit&template_id=$id"); ?>"><?php echo $temp_name;  ?></a>
			</strong>
		</td>
		<td><?php echo $base_temp_name; ?></td>
		<td>
			<a title="Edit" href="<?php echo admin_url("admin.php?page=pmfb_edit_template&action=edit&template_id=$id"); ?>">Edit</a>
		</td>
		<td>
			<a title="Clone" href="<?php echo admin_url("admin.php?page=pmfb_template&action=clone&clone_id=$id"); ?>">Clone</a>
		</td>
		<td>
			<a title="Delete"  onclick="javascript:check=confirm('Are you sure you want to delete it?');if(check==false) return false;" href="<?php echo admin_url("admin.php?page=pmfb_template&action=delete&delete_id=$id"); ?>">Delete</a>
		</td>
	</tr>
<?php 
}
?>
</tbody>
</table>
<?php 
}else{
	echo "<div id='setting-error-settings_updated' class='updated settings-error'>
			<p>
			<strong>Click \"Add New\" to create a new template.</strong>
			</p>
		  </div>";
}
?>
</div></div>
<div class="pm_clearboth"></div>
