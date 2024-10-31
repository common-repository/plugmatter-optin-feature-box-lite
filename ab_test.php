<?php
$message = "";
global $wpdb;
$siteurl = get_option('siteurl');
$table = $wpdb->prefix.'plugmatter_ab_test';
$temp_tbl = $wpdb->prefix.'plugmatter_templates';


if(isset($_POST)) {
	if(isset($_POST["plugmatter_ab_enable"]) && ($_POST["plugmatter_ab_enable"]!="")) {
		$plugmatter_ab_test_onoffswitch = ($_POST['plugmatter_ab_enable'] == "1")?1:0;
		update_option('plugmatter_ab_test_onoffswitch', $plugmatter_ab_test_onoffswitch);
		if($plugmatter_ab_test_onoffswitch == 0){
			$wpdb->query( "UPDATE $table SET active ='no' ");
		}		
	}	
	
	if(isset($_POST["compaign_name"] ) && ($_POST["compaign_name"]!="") && ($_GET['action'])=="add_new" ) {
		$campaign_name = sanitize_text_field($_POST["compaign_name"]);
		$abtest_box_a = sanitize_text_field($_POST['boxA']);
		$abtest_box_b = sanitize_text_field($_POST['boxB']);
		$abtest_home = sanitize_text_field($_POST['home']);
		$abtest_pages = sanitize_text_field($_POST['pages']);
        $abtest_posts = sanitize_text_field($_POST['posts']);
        $abtest_archives = sanitize_text_field($_POST['archieves']);

		$date=date("d/m/Y");
		$wpdb->query($wpdb->prepare("INSERT INTO $table (compaign_name,boxA,boxB,home,page,post,archieve,start_date)
				VALUES(%s,%s,%s,%s,%s,%s,%s,'".$date."')",$campaign_name,$abtest_box_a,$abtest_box_b,$abtest_home,$abtest_pages,$abtest_posts,$abtest_archives));

		$message = "<div id=\"setting-error-settings_updated\" class='pm_msg_warning'>Your split-test campaign has been saved successfully.</div>";
	}
}


if(isset($_GET['action'])) {
	if(($_GET['action']=="activate" || $_GET['action']=="deactivate") && $_GET['update_id']!='' ){
		$id= intval($_GET['update_id']);
		
		if($_GET['action']=="activate") {

			$wpdb->update(
					"$table",
					array('active' => "yes"	),
					array( 'ID' => "$id" ),
					array(	'%s'),	array( '%d' )
			);
			$wpdb->query( "UPDATE $table SET active ='no' WHERE id != $id" );
			update_option('plugmatter_ab_test_onoffswitch', 1);
			$message = "<div id=\"setting-error-settings_updated\" class='pm_msg_warning'>Your split-test campaign has been activated.</div>";
		} else {
			$wpdb->update(
					"$table",
					array('active' => "no"	),
					array( 'ID' => "$id" ),
					array(	'%s'),	array( '%d' )
			);
			$message = "<div id=\"setting-error-settings_updated\" class='pm_msg_warning'>Your split-test campaign has been deactivated.</div>";
		}
		
	}

	if($_GET['action']=="delete" && $_GET['delete_id']!='') {
		
		$id= intval($_GET['delete_id']);

		$dq = $wpdb->query(
				$wpdb->prepare(
						"
						DELETE FROM $table
						WHERE id = %d
						",
						"$id"
				)
		);
		if($dq) {
			$message = "<div id=\"setting-error-settings_updated\" class='pm_msg_warning'>Your split-test campaign has been deleted successfully.</div>";
		}
	}
}

?>

<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>Split-Testing 
		<?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_pro" || get_option("Plugmatter_PACKAGE") == "plug_featurebox_dev") { ?><a href="<?php echo admin_url('admin.php?page=pmfb_add_ab_test'); ?>">Add New</a><?php }?>
		</h2></h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url("/images/logo.png",__FILE__);?>' height='35'></div>
	</div>
	<div class='pmadmin_body'>
	<?php echo $message; ?>
	<?php if(get_option("Plugmatter_PACKAGE") != "plug_featurebox_pro" && get_option("Plugmatter_PACKAGE") != "plug_featurebox_dev") { 
		echo Plugmatter_UPNOTE;
	} else { ?>
	<br>
	<div class='plug_list_head'>Your Split-Test Campaigns</div>
	<?php 
		
		$resultss = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");		
		$result_count = count($resultss);
		if($result_count != 0) {
		?>
		<table class="widefat">
			<thead>
				<tr style="">
					<th style="width:30%">Campaign Name</th>
					<th style="width:300px">Template A</th>
					<th style="width:300px">Template B</th>					
					<th style="width:300px">On</th>
					<th style="width:300px">Status</th>
					<th style="width:200px">Action</th>

				</tr>
			</thead>
			<tfoot>
				<tr style="">
					<th style="width:30%">Campaign Name</th>
					<th style="width:300px">Template A</th>
					<th style="width:300px">Template B</th>				
					<th style="width:300px">On</th>
					<th style="width:300px">Status</th>
					<th style="width:200px">Action</th>

				</tr>
			</tfoot>
			<tbody>
				<?php 
				foreach ( $resultss as $fivesdraft ) {
					$id=$fivesdraft->id;
					$compaign_name=$fivesdraft->compaign_name;
					$active=$fivesdraft->active;
					$home=$fivesdraft->home;
					$page=$fivesdraft->page;
					$post=$fivesdraft->post;
					$archieve=$fivesdraft->archieve;					
					$list = array();
					if($home == "on"){
						array_push($list, "home");
					} if($page == "on"){
						array_push($list, "page");
					} if($post == "on"){
						array_push($list, "post");
					} if($archieve == "on"){
						array_push($list, "archieve");
					}
					
					$boxA=$fivesdraft->boxA;
					$boxB=$fivesdraft->boxB;
					$results3 = $wpdb->get_row("SELECT temp_name  FROM $temp_tbl WHERE id ='$boxA' ");
					$results4 = $wpdb->get_row("SELECT temp_name  FROM $temp_tbl WHERE id ='$boxB' ");
					
				?>
				<tr>
					<td class="post-title column-title">
						<strong><a href="<?php echo admin_url("admin.php?page=pmfb_ab_test_stats&ab_id=".$id); ?>" ><?php echo $compaign_name;?></a></strong>
					</td>
					<td>
						<?php echo $boxA_name =  $results3->temp_name; ?>
					</td>
					<td>
						<?php echo $boxB_name =  $results4->temp_name; ?>
					</td>					
					<td>
						<?php  echo implode(", ",$list); ?>	
						<?php 
							if($active=="yes"){
								$ap_test_tmp_url = admin_url("admin.php?page=pmfb_ab_test&action=deactivate&update_id=".$id);
								$ap_test_tmp_status = "Deactivate";
							} else { 
								$ap_test_tmp_url = admin_url("admin.php?page=pmfb_ab_test&action=activate&update_id=".$id);
								$ap_test_tmp_status = "Activate";
							} 
						?>	
					</td>
					<td>
						<?php if($active=="yes") { echo "Running"; } else { echo "-"; } ?>	
					</td>
					<td>								
						<a title="Activate/Deactivate" id="act_deact_btn" href="<?php echo $ap_test_tmp_url; ?>" ><?php echo $ap_test_tmp_status; ?></a> / 
						<a title="Delete" onclick="javascript:check=confirm('Are you sure you want to delete this campaign?');if(check==false) return false;"
						href="<?php echo admin_url("admin.php?page=pmfb_ab_test&action=delete&delete_id=$id"); ?>">Delete</a>
					</td>
				</tr>
				<?php 
				}
				?>
			</tbody>
		</table>
	<?php 
		}else{
			echo "<div id='setting-error-settings_updated' class='pm_msg_warning'>Click \"Add New\" to create new split-test campaign.</div>";
		}
	?>	
	<br><br>
	<form action="?page=pmfb_ab_test" method="post" id="pm_ab_form">
		<div class='plug_enable_con'>
			<div class='plug_enable_lable'>Enable Split-Testing</div>
			<div class='plug_tgl_btn'>
				<input type="hidden" name="plugmatter_ab_enable" value='0' />
				<input type="checkbox" id="plugmatter_ab_enable" name="plugmatter_ab_enable" class="switch" <?php if(get_option('plugmatter_ab_test_onoffswitch') == 1) echo "checked"; ?> value='1'/>
				<label for="plugmatter_ab_enable">&nbsp;</label>
			</div>
			<div style='clear:both'>&nbsp;</div>
			<?php 
			if($variable = get_option('plugmatter_ab_test_onoffswitch')=='0') {
				echo "<div id=\"splittest_enable_warning\" class='pm_msg_warning'>
				      <strong>Important :</strong> Enabling this feature will supersede your General Settings
					  </div>";
			}
			?>				
		</div>
		<div style='margin:15px 0 40px 0px'>
			<input id="submit" class="pm_primary_buttons" type="submit" name="submit" value="Save Changes">
		</div>
	</form>	
	<?php } ?>
</div></div>

<script type="text/javascript">
jQuery(document).ready(function() { 
	jQuery("a#act_deact_btn").click(function() {
		jQuery("#plugmatter_ab_enable").prop('checked', true);
		var text = jQuery(this).text();
		check=confirm('Are you sure you want to '+text.toLowerCase()+' this test?');
		if(check==false) return false;	
		jQuery("#pm_ab_form").submit();	
	});
});
</script>