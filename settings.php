<?php 
$saved='0';

if(isset($_POST["plugmatter_enable"] ) && ($_POST["plugmatter_enable"]!="")) {
	$plugmatter_enable = ($_POST['plugmatter_enable'] == "1")?1:0;
	update_option('plugmatter_enable', $plugmatter_enable);
	$saved++;
}

if(isset($_POST["plugmatter_show_temp_optinuser"] ) && ($_POST["plugmatter_show_temp_optinuser"]!="")) {
	$plugmatter_show_temp_optinuser = sanitize_text_field($_POST['plugmatter_show_temp_optinuser']);
	update_option('plugmatter_show_temp_optinuser', $plugmatter_show_temp_optinuser);
	$saved++;
}

if(isset($_POST["global_template"] ) && ($_POST["global_template"]!="")) {
	$global_template = sanitize_text_field($_POST['global_template']);
	update_option('plugmatter_global_template', $global_template);
	$saved++;
}

if(isset($_POST["home_template"] ) && ($_POST["home_template"]!="")) {
	$plugmatter_home_temp = sanitize_text_field($_POST['home_template']);
	update_option('plugmatter_home_temp', $plugmatter_home_temp);
	$saved++;
}

if(isset($_POST["post_template"] ) && ($_POST["post_template"]!="")) {
	$plugmatter_post_temp = sanitize_text_field($_POST['post_template']);
	update_option('plugmatter_post_temp', $plugmatter_post_temp);
	$saved++;
}

if(isset($_POST["page_template"] ) && ($_POST["page_template"]!="")) {
	$plugmatter_page_temp = sanitize_text_field($_POST['page_template']);
	update_option('plugmatter_page_temp', $plugmatter_page_temp);
	$saved++;
}

if(isset($_POST["archieve_template"] ) && ($_POST["archieve_template"]!="")) {
	$plugmatter_archieve_temp = sanitize_text_field($_POST['archieve_template']);
	update_option('plugmatter_archieve_temp', $plugmatter_archieve_temp);
	$saved++;
}

if(isset($_POST["enable_review"] ) && ($_POST["enable_review"]!="")) {
	$plugmatter_enable_on_review = sanitize_text_field($_POST['enable_review']);
	update_option('plugmatter_enable_on_review', $plugmatter_enable_on_review);
	$saved++;
}

if(isset($_POST["reviews"] ) && ($_POST["reviews"]!="")) {
	$plugmatter_num_of_reviews = sanitize_text_field($_POST['reviews']);
	update_option('plugmatter_num_of_reviews', $plugmatter_num_of_reviews);
	$saved++;
}
if(isset($_POST["home_template_review"] ) && ($_POST["home_template_review"]!="")) {
	$plugmatter_home_temp_review = sanitize_text_field($_POST['home_template_review']);
	update_option('plugmatter_home_temp_review', $plugmatter_home_temp_review);
	$saved++;
}
if(isset($_POST["post_template_review"] ) && ($_POST["post_template_review"]!="")) {
	$plugmatter_post_temp_review = sanitize_text_field($_POST['post_template_review']);
	update_option('plugmatter_post_temp_review', $plugmatter_post_temp_review);
	$saved++;
}

if(isset($_POST["page_template_review"] ) && ($_POST["page_template_review"]!="")) {
	$plugmatter_page_temp_review = sanitize_text_field($_POST['page_template_review']);
	update_option('plugmatter_page_temp_review', $plugmatter_page_temp_review);
	$saved++;
}

if(isset($_POST["archieve_template_review"] ) && ($_POST["archieve_template_review"]!="")) {
	$plugmatter_archieve_temp_review = sanitize_text_field($_POST['archieve_template_review']);
	update_option('plugmatter_archieve_temp_review', $plugmatter_archieve_temp_review);
	$saved++;
}


if(isset($_POST["pmfb_track_analytics"] ) && ($_POST["pmfb_track_analytics"]!="")) {
	$pmfb_track_analytics = sanitize_text_field($_POST['pmfb_track_analytics']);
	update_option('plugmatter_track_analytics', $pmfb_track_analytics);
	$saved++;
}

if(isset($_POST["pmfb_remove_data"] ) && ($_POST["pmfb_remove_data"]!="")) {
	$pmfb_remove_data = sanitize_text_field($_POST['pmfb_remove_data']);
	update_option('plugmatter_remove_data', $pmfb_remove_data);
	$saved++;
}

//------------------------------------------------------------------------------------------------------

if(isset($_POST["plugmatter_returning"] ) && ($_POST["plugmatter_returning"]!="")) {
	$plugmatter_returning = ($_POST["plugmatter_returning"] == "1")?1:0;
	update_option('plugmatter_returning', $plugmatter_returning);
	if($plugmatter_returning == 0){
		update_option('plugmatter_home_temp_review', "-1");
		update_option('plugmatter_page_temp_review', "-1");
		update_option('plugmatter_post_temp_review', "-1");
		update_option('plugmatter_archieve_temp_review', "-1");
		update_option('plugmatter_num_of_reviews', "0");
	}
	$saved++;
}


if(isset($_POST["plugmatter_show_on_sections"] ) && ($_POST["plugmatter_show_on_sections"]!="")) {
	$plugmatter_show_on_sections = ($_POST["plugmatter_show_on_sections"] == "1")?1:0;
	update_option('plugmatter_show_on_sections', $plugmatter_show_on_sections);
	if($plugmatter_show_on_sections == 0){
		update_option('plugmatter_home_temp', "-1");
		update_option('plugmatter_page_temp', "-1");
		update_option('plugmatter_post_temp', "-1");
		update_option('plugmatter_archieve_temp', "-1");
	}
	$saved++;
}

?>  

<script>
	jQuery(document).ready(function($){

	jQuery("#plugmatter_enable").click(function() {
		if(jQuery(this).attr("checked") == "checked") {
			jQuery("#pm_enabled_div").show();
		} else {
			jQuery("#pm_enabled_div").hide();
		}
	});

	$(".plugmatter_show_temp_optinuser").click(function(){
		if($(this).val() == "0") {
			if(confirm("WARNING: Enabling this feature will make the feature box disappear from your site once the visitor subscribes using the optin form (including yourself)")) {
				
			} else {
				$('#pm_disable_box').attr('checked',true);
			}
			
		}
	});
	
	if(jQuery("#home_template option:selected").val() == "-1" && 
	   jQuery("#post_template option:selected").val() == "-1" && 
	   jQuery("#page_template option:selected").val() == "-1" && 
	   jQuery("#archieve_template option:selected").val() == "-1") {
		jQuery(".target_dsections").hide();
		jQuery("#plugmatter_show_on_sections").prop("checked",false); 
	} else {
		jQuery(".target_dsections").show();
		jQuery("#plugmatter_show_on_sections").prop("checked",true); 	
	}
	
	jQuery("#plugmatter_show_on_sections").click(function() {
		if(jQuery("#plugmatter_show_on_sections").prop("checked") == true) {
			jQuery(".target_dsections").show();
		} else {
			jQuery(".target_dsections").hide();
		}
	});
	
	if(jQuery("#home_template_review option:selected").val() == "-1" && 
	   jQuery("#post_template_review option:selected").val() == "-1" && 
	   jQuery("#page_template_review option:selected").val() == "-1" && 
	   jQuery("#archieve_template_review option:selected").val() == "-1" &&
	   jQuery("#pm_reviews").val() == 0) {
		jQuery(".target_returning").hide();
		jQuery("#plugmatter_returning").prop("checked",false);
	} else {
		jQuery(".target_returning").show();
		jQuery("#plugmatter_returning").prop("checked",true); 	
	}
	
	jQuery("#plugmatter_returning").click(function() {
		if(jQuery("#plugmatter_returning").prop("checked") == true) {
			jQuery(".target_returning").show();
		} else {
			jQuery(".target_returning").hide();
		}
	});	

//-------------------------------------------------------------------------
	jQuery("#pm_reviews").change(function(){
		var val = Math.abs(parseInt(jQuery(this).val(), 10) || 0);
		jQuery(this).val(val > 100 ? 100 : val);	
	});

	jQuery("#pm_reviews").bind('keypress', function (e) {
        return !(e.which != 8 && e.which != 0 &&
                (e.which < 48 || e.which > 57) && e.which != 46);
    });
	
}); 	
</script>


<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>General Settings</h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url("/images/logo.png",__FILE__);?>' height='35'></div>
	</div>
	<?php 	
	if($saved!='0') {
	?>
	<div class='pm_msg_success'><strong>Settings saved successfully.</strong></div>
	<?php 
	}

	if(!get_option("PMFB_CODE_READY")){
		
		include "iscodeready.php";
	
	}
	
	if(get_option('plugmatter_ab_test_onoffswitch')=='1') {
		echo "<div class='pm_msg_error' >
				      <strong>Important :</strong> Split-Testing is enabled, all your general settings are overridden.
			 </div>";
	}
	?>
	<div class='pmadmin_body'  style="position:relative">
	<?php 
		if(get_option('plugmatter_ab_test_onoffswitch')=='1') {
			echo "<div class='pm_settings_overlay'>&nbsp;</div>";
		}
	?>	
		<form action="<?php $siteurl = get_option('siteurl');echo admin_url("admin.php?page=pmfb_settings"); ?>" id='pm_settings' method="post"
	onsubmit="javascript:if(jQuery('#global_template').val() == ''){ alert('Please select a default template'); return false;}">
		
			<div>
				<div class='plug_enable_lable'>Enable Feature Box</div>
				<div class='plug_tgl_btn'>
					<input type="hidden" name="plugmatter_enable" value='0'/>
					<input type="checkbox" id="plugmatter_enable" name="plugmatter_enable" class="switch" <?php if(get_option('plugmatter_enable') == 1) echo "checked"; ?> value='1' />
					<label for="plugmatter_enable">&nbsp;</label>
				</div>
				<div style='clear:both'>&nbsp;</div>
			</div>
			<div id='pm_enabled_div' <?php if(get_option('plugmatter_enable') != 1) echo "style='display:none'"; ?>>
				<h3>Display Settings: </h3>	
				<div style="padding-left:30px;">
				<table border='0' style='margin-top:10px;'>
				    <tr>
					<td width='285'><label for="Select Default Template">Select Default Template:</label></td>
					<td width='10'>&nbsp;</td>
					<td>
						<select id="global_template" name="global_template">
							<option value=''>Select Templates</option>
							<?php 
							global $wpdb;
							$table = $wpdb->prefix.'plugmatter_templates';
							$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name	FROM $table	ORDER BY id DESC");
							foreach ( $resultss as $fivesdraft ) {
								$id=$fivesdraft->id;
								$temp_name=$fivesdraft->temp_name;
								$base_temp_name=$fivesdraft->base_temp_name;
							?>
							<option value="<?php echo $id; ?>"
							<?php if(($variable = get_option('plugmatter_global_template'))==$id){echo "selected=selected";} ?>>
								<?php echo $temp_name; ?>
							</option>
							<?php 
							}
							?>
						</select>
					</td>
					</tr>
					<tr><td style='line-height:5px'>&nbsp;</td></tr>
					<tr>
					<td><label>Turn off Feature Box for Subscribed Visitors:</label></td>
					<td width='10'>&nbsp;</td>
					<td>
						<input type="radio" id="pm_disable_box" class="plugmatter_show_temp_optinuser" value="1" name="plugmatter_show_temp_optinuser"	<?php if(get_option('plugmatter_show_temp_optinuser') == '1' || get_option('plugmatter_show_temp_optinuser') == '' ) echo "checked"; ?>>&nbsp;Disable &nbsp;&nbsp; 
						<input type="radio" class="plugmatter_show_temp_optinuser" value="0" name="plugmatter_show_temp_optinuser"	<?php if(get_option('plugmatter_show_temp_optinuser') == 0 && get_option('plugmatter_show_temp_optinuser') != '') echo "checked"; ?>>&nbsp;Enable
					</td>
					</tr>
					
					<tr>
						<td colspan='3'>
					<?php	$plugmatter_show_temp_optinuser = get_option('plugmatter_show_temp_optinuser');
							if(isset($_COOKIE['plugmatter_conv_done']) && $_COOKIE['plugmatter_conv_done'] == '1' && $plugmatter_show_temp_optinuser === '0' ){	 
								 echo "<div style='margin-top:10px;color:gray;background:#fff;padding:15px'>
								 		<b>Note:</b> You are not shown the feature box on your site <br> as the above feature is Enabled and you have subscribed using it.
								 		</div>";
							} ?>
						</td>
					</tr>
					
					<tr><td style='border-bottom:0px solid #dddddd;' colspan='3'>&nbsp;</td></tr>
					<tr><td style='padding-top:20px;' colspan='3'>
						<div class='plug_enable_lable' <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo "style='color:gray'";?>>Target Different Sections</div>
						<div class='plug_tgl_btn'>
							<input type="hidden" name="plugmatter_show_on_sections" value='0'/>
							<input type="checkbox" id="plugmatter_show_on_sections" name="plugmatter_show_on_sections" class="switch"  value='1' <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo "disabled";?>/>
							<label for="plugmatter_show_on_sections">&nbsp;</label>
						</div>
						<div style='float:left;padding-top:5px;'><?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo Plugmatter_UPNOTE;?></div>
						<div style='clear:both'>&nbsp;</div>				
					</td></tr>	
					<tr class='target_dsections'>
						<td valign='top'>Choose Templates:</td>
						<td>&nbsp;</td>
					<td>
						<table>
							<tr><td>Home:</td><td>&nbsp;</td>
							<td>
								<select id="home_template" name="home_template">
								<option value="-1" <?php if(($variable = get_option('plugmatter_home_temp')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_home_temp')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_home_temp'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Post:</td><td>&nbsp;</td>
							<td>
								<select id="post_template" name="post_template">
								<option value="-1" <?php if(($variable = get_option('plugmatter_post_temp')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_post_temp')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_post_temp'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Pages:</td><td>&nbsp;</td>
							<td>
								<select id="page_template" name="page_template">
								<option value="-1" <?php if(($variable = get_option('plugmatter_page_temp')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_page_temp')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_page_temp'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Archive:</td><td>&nbsp;</td>
							<td>
								<select id="archieve_template" name="archieve_template">
								<option value="-1" <?php if(($variable = get_option('plugmatter_archieve_temp')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_archieve_temp')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_archieve_temp'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>						
						</table>
					</td>
					</tr>

					
					<tr><td style='border-bottom:0px solid #dddddd;' colspan='3'>&nbsp;</td></tr>
					<tr><td style='padding-top:20px;' colspan='3'>
						<div class='plug_enable_lable' <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo "style='color:gray'";?>>Target Returning Visitors</div>
						<div class='plug_tgl_btn'>
							<input type="hidden" name="plugmatter_returning" value='0'/>
							<input type="checkbox" id="plugmatter_returning" name="plugmatter_returning" class="switch" <?php if(get_option('plugmatter_returning') == 1) echo "checked"; ?> value='1' <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo "disabled";?>/>
							<label for="plugmatter_returning">&nbsp;</label>
						</div>
						<div style='float:left;padding-top:5px;'><?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite") echo Plugmatter_UPNOTE;?></div>
						<div style='clear:both'>&nbsp;</div>				
					</td></tr>
					
					
					
					<tr class='target_returning'>
					<td valign='top'>Number of Visits:</td>
					<td>&nbsp;</td>
					<td><input type="text" name="reviews" id="pm_reviews" maxlength='3' value="<?php if(get_option('plugmatter_num_of_reviews')){echo $variable =  get_option('plugmatter_num_of_reviews');}else{echo 0;} ?>" size='10' />
					</td>				
					</tr>
					<tr class='target_returning'><td style='line-height:5px;'>&nbsp;</td></tr>						
					<tr class='target_returning'>
					<td  valign='top'>Choose Templates:</td>
					<td>&nbsp;</td>
					<td>
						<table>
							<tr><td>Home:</td><td>&nbsp;</td>
							<td>
								<select id="home_template_review" name="home_template_review">
								<option value="-1" <?php if(($variable = get_option('plugmatter_home_temp_review')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_home_temp_review')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_home_temp_review'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Post:</td><td>&nbsp;</td>
							<td>
								<select id="post_template_review" name="post_template_review">
								<option value="-1" <?php if(($variable = get_option('plugmatter_post_temp_review')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_post_temp_review')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_post_temp_review'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Pages:</td><td>&nbsp;</td>
							<td>
								<select id="page_template_review" name="page_template_review">
								<option value="-1" <?php if(($variable = get_option('plugmatter_page_temp_review')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_page_temp_review')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_page_temp_review'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>
							<tr><td>Archive:</td><td>&nbsp;</td>
							<td>
								<select id="archieve_template_review" name="archieve_template_review">
								<option value="-1" <?php if(($variable = get_option('plugmatter_archieve_temp_review')) == "-1"){echo "selected=selected";} ?>>Default Template</option>
								<option value="-2" <?php if(($variable = get_option('plugmatter_archieve_temp_review')) == "-2"){echo "selected=selected";} ?>>Disable</option>
								<?php 
								global $wpdb;
								$table = $wpdb->prefix.'plugmatter_templates';
								$resultss = $wpdb->get_results("SELECT id,temp_name,base_temp_name FROM $table ORDER BY id DESC");
								foreach ( $resultss as $fivesdraft ) {
									if(($variable = get_option('plugmatter_archieve_temp_review'))==$fivesdraft->id ){ $selected = "selected"; } else { $selected = ""; }
									echo "<option value='$fivesdraft->id' $selected>$fivesdraft->temp_name</option>"; 
								}
								?>
								</select>
							</td>
							</tr>
							<tr><td style='line-height:5px;'>&nbsp;</td></tr>						
						</table>
					</td>
					</tr>				
					<tr><td style='' colspan='3'>&nbsp;</td></tr>				
				</table>
				</div>
				<div class="pmfb_track_analytics">
					<h3>Track Analytics:</h3>
					<div style="padding-left:30px;">
						<label for="pmfb_track_analytics">Enable Google Analyltics Events:</label>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="pmfb_track_analytics" id="" value="1" <?php if(get_option('plugmatter_track_analytics') != 0) echo "checked"; ?> <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite" || get_option("Plugmatter_PACKAGE") == "plug_featurebox_single") echo "disabled";?> /> &nbsp; Enable &nbsp;&nbsp;
						<input type="radio" name="pmfb_track_analytics" id="" value="0" <?php if(get_option('plugmatter_track_analytics') == 0) echo "checked"; ?> <?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite" || get_option("Plugmatter_PACKAGE") == "plug_featurebox_single") echo "disabled";?>/> &nbsp; Disable
						&nbsp;&nbsp;<?php if(get_option("Plugmatter_PACKAGE") == "plug_featurebox_lite" || get_option("Plugmatter_PACKAGE") == "plug_featurebox_single") echo Plugmatter_UPNOTE;?>
					</div>
				</div>
				<div class="pmfb_remove_data">
					<h3>Retain Data on Uninstall:</h3>
					<div style="padding-left:30px;">
						<label for="pmfb_remove_data">Retain templates and split test data on uninstall:</label>&nbsp;&nbsp;
						<input type="radio" name="pmfb_remove_data" id=""  value="0" <?php if(get_option('plugmatter_remove_data') == 0) echo "checked"; ?> /> &nbsp; Yes &nbsp;&nbsp;
						<input type="radio" name="pmfb_remove_data" id="" value="1" <?php if(get_option('plugmatter_remove_data') != 0) echo "checked"; ?> /> &nbsp; No
					</div>
				</div>
			</div>
			<br/><br/>	
			
			
		<div class="pmadmin_submit">
			<input id="submit" class="pm_primary_buttons" type="submit" value="   Save All Changes   " name="submit">
		</div>
		<br><br>
	</form>
</div>
</div>
<div style='margin:0 20px 0 20px;color:gray;background:#fff;padding:15px'><img src="<?php echo plugins_url()."/".Plugmatter_DIR_NAME."/images/support.png"; ?>" align='left' style='margin-right:10px;position:relative;top:-2px'>
    For installation and setup instructions, follow our <a href='http://plugmatter.com/docs/feature-box' target='_blank'>user guide</a>. Or contact <a href='http://plugmatter.com/support' target='_blank'>support</a>.
</div>