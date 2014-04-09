<?php  defined('C5_EXECUTE') or die("Access Denied.");
/**
 * View for VHost Password Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
?>

<div class="bs-callout bs-callout-info">
	<h4> Hostname </h4>
	<p>
		<?php  if (!empty($field_1_textbox_text)): ?>
			<?php  echo htmlentities($field_1_textbox_text, ENT_QUOTES, APP_CHARSET); ?>
		<?php  endif; ?>
	</p>
</div>

<?php  if (!empty($field_2_textbox_text) || !empty($field_3_textbox_text)): ?>
<div class="bs-callout bs-callout-danger">
	<h4> FTP </h4>
	<p>
		<?php  if (!empty($field_2_textbox_text)): ?>
			<span class="username-label">Username:</span> <input type="text" value="<?php  echo htmlentities($field_2_textbox_text, ENT_QUOTES, APP_CHARSET); ?>"/>
		<?php  endif; ?>
		<br />
		<?php  if (!empty($field_3_textbox_text)): ?>
			<span class="password-label">Password:</span> 
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_3_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		<?php  endif; ?>
	</p>
</div>
<?php  endif; ?>

<?php  if (!empty($field_4_textbox_text) || !empty($field_5_textbox_text)): ?>
<div class="bs-callout bs-callout-danger">
	<h4> SSH </h4>
	<p>
		<?php  if (!empty($field_4_textbox_text)): ?>
			<span class="username-label">Username:</span> <input type="text" value="<?php  echo htmlentities($field_4_textbox_text, ENT_QUOTES, APP_CHARSET); ?>"/>
		<?php  endif; ?>
		<br />
		<?php  if (!empty($field_5_textbox_text)): ?>
			<span class="password-label">Password:</span> 
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_5_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		<?php  endif; ?>
	</p>
</div>
<?php  endif; ?>

<?php  if (!empty($field_6_textbox_text) || !empty($field_7_textbox_text) || !empty($field_8_textbox_text)): ?>
<div class="bs-callout bs-callout-danger">
	<h4> Database </h4>
	<p>
		<?php  if (!empty($field_6_textbox_text)): ?>
			<span class="username-label">Username:</span> 
			<input type="text" value="<?php  echo htmlentities($field_6_textbox_text, ENT_QUOTES, APP_CHARSET); ?>"/>
		<?php  endif; ?>
		<br />
		<?php  if (!empty($field_7_textbox_text)): ?>
			<span class="password-label">Password:</span> 
			<span class="password_super_block">
				<span class="password_block_hash">. </span>
				<span class="password_block">
					<input type="text" class="password_textbox" value="<?php  echo htmlentities($field_7_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" disabled>
				</span>
			</span>
		<?php  endif; ?>
		<br />
		<?php  if (!empty($field_8_textbox_text)): ?>
			<span class="username-label">DB Names:</span> 
			<input type="text" value="<?php  echo htmlentities($field_8_textbox_text, ENT_QUOTES, APP_CHARSET); ?>"/>
		<?php  endif; ?>		
	</p>
</div>
<?php  endif; ?>


		<?php  if (!empty($field_9_textarea_text)): ?>
		<div class="bs-callout bs-callout-info">
			<h4>Other notes:</h4>
			<span class="notes"><?php  echo nl2br(htmlentities($field_9_textarea_text, ENT_QUOTES, APP_CHARSET)); ?></span>
		</div>
		<?php  endif; ?>
