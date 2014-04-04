<?php
/*
* Completes user registration
* (C) Hammerti.me @ 2014
* SF
*/
defined('C5_EXECUTE') or die("Access Denied.");
?>
<style>
input, select {
	width: 300px!important;
}
.countries-autocomplete {
	/*margin-top: 48px!important;*/
	width: 300px!important;
}
.countries-input {
	display: block;
	height: 34px;
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.428571429;
	color: #555;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
</style>

<link href="//cdnjs.cloudflare.com/ajax/libs/authy-forms.css/2.0/form.authy.min.css" media="screen" rel="stylesheet" type="text/css">

<div class="row">
<div class="col-md-10">
<div class="page-header">
	<h1>User Access Activation</h1>
</div>
<div class="alert alert-info" >
	<span class="glyphicon glyphicon-warning-sign"></span> The account is almost activated. We just need your phone number in order to be able to register the user into <strong>Authy</strong>, a service that will help us generate secure tokens for two factor authentication login.
	</div>
</div>
</div>


<form method="post" action="<?php echo $this->url('/activate_account') . $uHash?>" role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Password</label>
    <input type="password" class="form-control" id="uPassword" name="uPassword" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Confirm Password</label>
    <input type="password" class="form-control" id="uPasswordConfirm" name="uPasswordConfirm" placeholder="Confirm Password">
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Country</label><br />
    <select id="authy-countries" class="form-control" name="country"></select>
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Phone Number</label>
	<input id="authy-cellphone" class="form-control" type="text" value="" name="phone" placeholder="Phone Number"/>
  </div>
    
	<input type="hidden" name="country_code" id="country_code" />
	
  <button type="submit" class="btn btn-default">Activate User</button>
 
</form>

<script src="//cdnjs.cloudflare.com/ajax/libs/authy-forms.js/2.0/form.authy.min.js" type="text/javascript"></script>
<script>
$(function(){
	
	$("form").submit(function(){
		
		$("#country_code").val( $("#countries-input-0").val() );
		
		if( $("#uPassword").val().length == 0 || $("#uPasswordConfirm").val().length == 0 ) {
			alert("Please complete the password fields");
			return false;
		}
		
		if( $("#uPassword").val() != $("#uPasswordConfirm").val() ) {
			alert("The two password do not match");
			return false;
		}
		
		if( $("#countries-input-0").val().length() == 0 ) {
			alert("Please enter a country code");
			return false;
		}
		
		return true;
	});
	
});
</script>