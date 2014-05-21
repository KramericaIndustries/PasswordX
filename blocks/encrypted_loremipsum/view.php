<?php  defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="bs-callout bs-callout-info">
<?php  if (!empty($field_1_textbox_text)){ ?>
<div class="credentials-fields">
<label for="pass-block-username" class="control-label">ert:</label>
<input name="pass-block-username" type="text" class="pass-block-username" value="<?php  echo htmlentities($field_1_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
</div><?php } ?>

<?php  if (!empty($field_2_textbox_text)){ ?>
<div class="credentials-fields">
<label for="pass-block-password" class="control-label">Password:</label>
<span class="password_super_block">
<span class="password_block_hash">. </span>
<span class="password_block">
<input name="pass-block-password" type="text" class="password_textbox" value="<?php  echo htmlentities($field_2_textbox_text, ENT_QUOTES, APP_CHARSET); ?>" readonly>
</span>
</span>
</div>
<?php } ?>


</div>