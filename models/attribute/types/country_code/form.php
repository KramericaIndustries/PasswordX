<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php $f = Loader::helper('form'); ?>

<?php echo $form->text('countryCode'); ?>

<script src="/js/form.authy.min.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
//Load Authy CC selector
window.onload = function() {
    return Authy.UI.instance( "countryCode" );
};
//]]>
</script>