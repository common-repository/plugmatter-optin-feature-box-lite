<?php
global $wpdb;
$table = $wpdb->prefix.'plugmatter_templates'; 

if(isset($_GET['action'])) {
	if($_GET['action']=="edit" && $_GET['edit_id']!='') {
	 $get_id= intval($_GET['edit_id']);
	 $fivesdrafts = $wpdb->get_results($wpdb->prepare("SELECT id,temp_name,base_temp_name	FROM $table WHERE id=%d", $get_id));
	 
	 foreach ( $fivesdrafts as $fivesdraft ) {
	 	$id=$fivesdraft->id;
	 	$temp_name=$fivesdraft->temp_name;
	 	$base_temp_name=$fivesdraft->base_temp_name;
	 }
	}
}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('input:checkbox').click(function() {
		var count = 0;
		jQuery('input:checkbox').each(function() {			
			var isChecked = jQuery(this).attr('checked')?true:false;
			if(isChecked == false){count++;}				
		});
			if(count >= 4){ 
				alert("Please select any \"Run Test On \" ");
				return false;
			}
	}); 
	
	jQuery('#save_btn').click(function(){	
		if(jQuery("#compaign_name").val() == ""){
			alert("Please insert compaign name");
			return false;
		}
		var boxA = jQuery('select#boxA').val();
		var boxB = jQuery('select#boxB').val();
		if(boxA == boxB) {
			alert("Please select different templates for Box A and Box B");
			return false;
		}		
	});
	
});
</script>

<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>Create a Split-Test Campaign</h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url("/images/logo.png", __FILE__ );?>' height='35'></div>
	</div>
	<div class='pmadmin_body'>
	<form action="<?php $siteurl = get_option('siteurl');echo admin_url("admin.php?page=pmfb_ab_test&action=add_new"); ?>"	method="POST">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="name"> Campaign Name:	</label>
					</th>
					<td>
						<input id="compaign_name" class="regular-text" type="text" required="true" value="" name="compaign_name">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="box A"> Box A:	</label>
					</th>
					<td>
						<select name="boxA" id="boxA" >
							<?php 
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name	FROM $table	ORDER BY id DESC");

								foreach ( $resultss as $fivesdraft )
								{
									$id=$fivesdraft->id;
									$temp_name=$fivesdraft->temp_name;
									$base_temp_name=$fivesdraft->base_temp_name;								
									echo "<option value=\"$id\" >$temp_name</option>";							
								}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="boxb"> Box B:</label>
					</th>
					<td>
						<select name="boxB" id="boxB" >
							<?php 
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name	FROM $table	ORDER BY id ASC");

								foreach ( $resultss as $fivesdraft )
								{
									$id = $fivesdraft->id;
									$temp_name = $fivesdraft->temp_name;
									$base_temp_name = $fivesdraft->base_temp_name;								
									echo "<option value=\"$id\" >$temp_name</option>";							
								}
							?>
					</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="test"> Run Test On:</label>
					</th>
					<td>
						<input type="checkbox" name="home" checked> &nbsp;Home<br> 
						<input type="checkbox"	name="posts"> &nbsp;Posts<br> 
						<input	type="checkbox" name="pages"> &nbsp;Pages <br>						
						<input type="checkbox" name="archieves"> &nbsp;Archives
					</td>
				</tr>
				<tr>
					<th scope="row">

					</th>
					<td colspan="2">
						<input class="pm_primary_buttons" id="save_btn" type="submit" value=" Save "> &nbsp;&nbsp;
						<input class="pm_secondary_buttons" id="cancel_btn" type="button" value=" Cancel " onclick="location.href='<?php $siteurl = get_option('siteurl');echo admin_url("admin.php?page=pmfb_ab_test"); ?>'">					
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div></div>