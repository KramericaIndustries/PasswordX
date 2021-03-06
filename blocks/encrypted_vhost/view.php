<?php  defined('C5_EXECUTE') or die("Access Denied.");
/**
 * View for VHost Password Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
 
$ci = Loader::helper('concrete/urls');
$btIcon = $ci->getBlockTypeIconURL($this->getBlockObject());
global $c;
?>

<div class="panel panel-primary blockpanel <?php echo ($c->isEditMode() ? "editmode" : ""); ?>">
      <div class="panel-heading">
        <h3 class="panel-title"><img src="<?php echo $btIcon; ?>"/> <?php echo $this->getBlockObject()->getBlockTypeObject()->getBlockTypeName(); ?></h3>
      </div>
      <div class="panel-body">


<div class="bs-callout bs-callout-info">
	<h4> Hostname </h4>
	<p class="block-title">
		<?php  if (!empty($field_1_textbox_text)): ?>
			<?php  echo htmlentities($field_1_textbox_text, ENT_QUOTES, APP_CHARSET); ?>
		<?php  endif; ?>
	</p>
</div>

<?php  if (!empty($field_2_textbox_text) || !empty($field_3_textbox_text)): ?>
<div class="bs-callout bs-callout-success">
	<h4> FTP </h4>

		<?php  if (!empty($field_2_textbox_text)): ?>
		
		<div class="credentials-fields">
			<label class="username-label control-label">Username:</label> 
			
			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_2_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>
			
			<input type="text" class="pass-block-username" value="<?php  echo htmlentities($field_2_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
		</div>
		
		<?php  endif; ?>

		<?php  if (!empty($field_3_textbox_text)): ?>
		<div class="credentials-fields">
			<label for="pass-block-password" class="control-label">Password:</label> 
			
			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_3_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>
			
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_3_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		</div>	
		<?php  endif; ?>
</div>
<?php  endif; ?>

<?php  if (!empty($field_4_textbox_text) || !empty($field_5_textbox_text)): ?>
<div class="bs-callout bs-callout-success">
	<h4> SSH </h4>

		<?php  if (!empty($field_4_textbox_text)): ?>
		<div class="credentials-fields">
			<label class="username-label control-label">Username:</label>
			
			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_4_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>
			
			<input type="text" class="pass-block-username" value="<?php  echo htmlentities($field_4_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
		</div>	
		<?php  endif; ?>

		<?php  if (!empty($field_5_textbox_text)): ?>
		<div class="credentials-fields">
			<label for="pass-block-password" class="control-label">Password:</label>

			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_5_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>
			
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_5_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		</div>	
		<?php  endif; ?>

</div>
<?php  endif; ?>

<?php  if (!empty($field_6_textbox_text) || !empty($field_7_textbox_text) || !empty($field_8_textbox_text)): ?>
<div class="bs-callout bs-callout-success">
	<h4> Database </h4>

		<?php  if (!empty($field_6_textbox_text)): ?>
		<div class="credentials-fields">
			<label class="username-label control-label">Username:</label>
			
			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_6_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>			
			
			<input type="text" class="pass-block-username" value="<?php  echo htmlentities($field_6_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
		</div>	
		<?php  endif; ?>

		<?php  if (!empty($field_7_textbox_text)): ?>
		<div class="credentials-fields">
			<label for="pass-block-password" class="control-label">Password:</label>
			
			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_7_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>				
			
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_7_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		</div>	
		<?php  endif; ?>

		<?php  if (!empty($field_8_textbox_text)): ?>
		<div class="credentials-fields">
			<label class="username-label control-label">DB Names:</label>

			<div class="userdata_touch_fallback"><?php  echo htmlentities($field_8_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>				
			
			<div class="pass-block-username"><?php  echo htmlentities($field_8_textbox_text, ENT_QUOTES, APP_CHARSET); ?></div>
		</div>	
		<?php  endif; ?>		

</div>
<?php  endif; ?>


		<?php  if (!empty($field_9_textarea_text)): ?>
		<div class="bs-callout bs-callout-info">
			<h4>Other notes:</h4>
			<span class="notes"><?php  echo nl2br(htmlentities($field_9_textarea_text, ENT_QUOTES, APP_CHARSET)); ?></span>
		</div>
		<?php  endif; ?>

		</div>
		</div>