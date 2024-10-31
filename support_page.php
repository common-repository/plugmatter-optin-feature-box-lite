<?php 
$msg = '';
$msg_reg='';
$reg_done = false;

$license_key = get_option("Plugmatter_Featurebox_License");

if($_POST){
	if(isset($_POST["email"] ) && ($_POST["email"]!="")) {	
	    if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $_POST["email"])) {
    	    $msg="<div class='pm_msg_error'><strong>Please Enter a Valid Email</strong></div>";
    	}
	} else if(isset($_POST["support_subject"]) || isset($_POST["support_description"])) {
		if(isset($_POST["support_subject"]) && $_POST["support_subject"] == "") {
        	$msg="<div class='pm_msg_error'><strong>Please enter subject</strong></div>";
    	} else if(isset($_POST["support_description"]) && $_POST["support_description"] == "") {
        	$msg="<div class='pm_msg_error'><strong>Please enter description</strong></div>";
		}else{
			$res = wp_remote_post("http://plugmatter.com/get_support",
				array('body'=>array(
						"license_key"=>$license_key,
						"email"=>$_POST["email"],
						"subject"=>$_POST["support_subject"],
						"description"=>$_POST["support_description"],
						"siteurl"=>get_option('siteurl'),
						"package"=>Plugmatter_PACKAGE,	
						)
					)
				);
			$res_arr = explode(":",$res["body"]);
			if($res_arr[0] == "EMAIL_SENT") {
				$msg="<div class='pm_msg_success'><strong>Thank you. We'll get back to you very soon.</strong></div>";
			} else {
				$msg="<div class='pm_msg_error'><strong>Failed due to some error, please request again.</strong></div>";
			}
		}
	}
}

?>

<div class='pmadmin_wrap'>
	<div class='pmadmin_headbar'>
		<div class='pmadmin_pagetitle'><h2>Get Support</h2></div>
	    <div class='pmadmin_logodiv'><img src='<?php echo plugins_url()."/".Plugmatter_DIR_NAME."/images/logo.png";?>' height='35'></div>
	</div>
	<?php 	
	if($msg!='') { 
		echo "$msg";
	}
	?>
	<div class='pmadmin_body'>
		<form action="<?php $siteurl = get_option('siteurl');echo $siteurl."/wp-admin/admin.php?page=support_submenu-page"; ?>" id='pm_settings' method="post">	
		<div>
			<?php if(empty($license_key)){ ?>

			<div class='plug_enable_lable' style='width:150px'>Email</div>
			<div class='plug_tgl_btn'>
				<input type='text' name='email' size='32' style='padding:4px;' value='<?php echo isset($_POST["email"])?$_POST["email"]:""; ?>'>
			</div>
			<div style='clear:both'>&nbsp;</div> 

			<?php	}?>
			
			<div class='plug_enable_lable' style='width:150px'>Subject</div>
			<div class='plug_tgl_btn'>
				<input type='text' name='support_subject' size='32' style='padding:4px;' value='<?php echo isset($_POST["support_subject"])?$_POST["support_subject"]:""; ?>'>
			</div>
			<div style='clear:both'>&nbsp;</div>
			<div class='plug_enable_lable' style='width:150px'>Description</div>
			<div class='plug_tgl_btn'>
				<textarea name='support_description' rows='10' cols='55'><?php echo isset($_POST["support_description"])?$_POST["support_description"]:""; ?></textarea> 
			</div>
			<div style='clear:both'>&nbsp;</div>            
		</div>
		<div class="pmadmin_submit" style='margin-top:10px;'>
			<input id="submit" class="pm_primary_buttons" type="submit" value="   Get Support   " name="submit">
		</div>
		<br><br>
	</form>      
	</div></div>