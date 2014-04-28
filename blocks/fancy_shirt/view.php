<?php  defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php  if (!empty($field_1_textbox_text)): ?>
	<?php  echo htmlentities($field_1_textbox_text, ENT_QUOTES, APP_CHARSET); ?>
<?php  endif; ?>

<?php  if (!empty($field_3_textarea_text)): ?>
	<?php  echo nl2br(htmlentities($field_3_textarea_text, ENT_QUOTES, APP_CHARSET)); ?>
<?php  endif; ?>

<?php  if (!empty($field_4_wysiwyg_content)): ?>
	<?php  echo $field_4_wysiwyg_content; ?>
<?php  endif; ?>


