<div class="error">
			<p style='padding:20px'><img src="<?php echo plugins_url()."/".Plugmatter_DIR_NAME."/images/pmfb_warning.png"; ?>" align='left' style='position:relative;top:-4px;margin-right:10px;'><b>Oops! It looks like you have not added the necessary code snippet to display the feature box on your site. <br>Please follow the instructions below:</b></p>
			<div id="pm_activation_code" class="pm_activation_code">
				<ul class="pmfb_code_tabs">
					<li class="pmfb_active_tab"><a href="#general_theme" class="pmfb_tab" > Any Theme </a></li>
					<li><a href="#thesis_theme" class="pmfb_tab"> Thesis Theme</a></li>
					<li><a href="#genesis_theme" class="pmfb_tab">  Genesis Theme</a></li>
				</ul>

			<div id='general_theme' class="pmfb_theme_item pmfb_theme_item_active">
				
				<div class="read_instruction"> 
				 <p>Add the below code at the end of your theme's header.php file.</p>
				 <p> Path: Appearance → Editor → Header → Append code snippet at the end of the file</p> 
					<div class="highlight_code">
						<?php highlight_string("<?php if (function_exists('plugmatter_custom_hook')) { plugmatter_custom_hook(); } ?>");?>
					</div>

					<p>Read the instructions with screenshots in the <a href="http://plugmatter.com/user-guide#add_code" target="_blank">user guide</a>.</p>
				</div>
				<div class="watch_video">
					<p>See Instructional video to setup code <a href="#">Watch Now</a></p>		
				</div>
				<div style="clear:both;"></div>	
			</div>	
			<div id='genesis_theme' class="pmfb_theme_item">
				
				<div class="read_instruction">
					<p>Append the following line of code in the functions.php file on the below path:</p>
					<p>Path: Appearance → Editor → Theme Functions (functions.php)</p>
					<div class="highlight_code">
					<?php highlight_string(" if (function_exists('plugmatter_custom_hook')) {
	add_action('genesis_after_header', 'plugmatter_custom_hook'); 
} "); ?>
				</div>
				
				<p>Read the instructions with screenshots in the <a href="http://plugmatter.com/user-guide#genesis" target="_blank">user guide</a>.</p>
					
				</div>
				<div class="watch_video">	
					<p>See Instructional video to setup code <a href="#" target="_blank">Watch Now</a></p>		
				</div>	
				<div style="clear:both;"></div>
			</div>
			<div id='thesis_theme' class="pmfb_theme_item">
				
				<div class="read_instruction">
					<p>Append the following line of code in the functions.php file on the below path:</p>
					<p>Path: Appearance → Editor → Theme Functions(functions.php) </p>
					<div class="highlight_code">
					<?php highlight_string(" if (function_exists('plugmatter_custom_hook')) { 
	add_action('thesis_hook_after_container_plugmatter_header_hook', 'plugmatter_custom_hook');
} ");?>
					</div>

					<p>Then go to:</p>
					<p>skin editor  →  header  →  admin  →  header's hook name → plugmatter_header_hook</p> 
					<p> Select the hook name plugmatter_header_hook.</p>
					
					<p>Read the instructions with screenshots in the <a href="http://plugmatter.com/user-guide#thesis" target="_blank">user guide</a>.</p>
				</div>
				<div class="watch_video">
					<p>See Instructional video to setup code <a href="#">Watch Now</a></p>		
				</div>	
				<div style="clear:both;"></div>
			</div>
		</div>
			<p style='border-top:1px solid #dddddd;padding:20px;'> 
				<b>Done adding the code? <a href="<?php echo get_option('siteurl'); ?>" id='pmfb_code_verify' target="_blank">Click to Verify</a>
				<br><br>
				Don't want to do it yourself? No worries, we can set it up for you. Just <a href="http://plugmatter.com/support" target="_blank">contact support</a>.
				</b>
			</p>
		</div>	
