<?php  
/**
 * View for the Generic Password Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");
$block_color = array('info','warning','danger');
?>

<div class="bs-callout bs-callout-<?php echo $block_color[$field_5_select_value]; ?>">
	<h4 class="block-title"> 
		<?php  
			if (!empty($field_1_textbox_text)){
				echo htmlentities($field_1_textbox_text, ENT_QUOTES, APP_CHARSET);
			} else {
				echo 'Credentials';
			}
		?>
	</h4>

		<?php  if (!empty($field_2_textbox_text)): ?>

		<div class="credentials-fields">
			<label for="pass-block-username" class="control-label">Username:</label> 
			<input name="pass-block-username" type="text" class="pass-block-username" value="<?php  echo htmlentities($field_2_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
		</div>
			
		<?php  endif; ?>
		<?php  if (!empty($field_3_textbox_text)): ?>
		
		<div class="credentials-fields">
			<label for="pass-block-password" class="control-label">Password:</label> 
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input name="pass-block-password" type="text" class="password_textbox" value="<?php  echo htmlentities($field_3_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
				</span>
			</span>
		</div>

		
		<?php  endif; ?>
		<?php  if (!empty($field_4_textarea_text)): ?>

		<div>
			<span class="username-label">Other notes:</span><br/>
			<span class="notes"><?php  echo nl2br(htmlentities($field_4_textarea_text, ENT_QUOTES, APP_CHARSET)); ?></span>
		</div>
		<?php  endif; ?>
		

</div>