<?php defined('C5_EXECUTE') or die("Access Denied.");
 /*
  Controller
  Custom Admin panel for PasswordX
  (c) 2014 PasswordX
  MIT License
  AHJ
 */

class DashboardPasswordxAdministrationController extends Controller { 
 public $user;
 public $guide_url;

public function on_start() {
 parent::on_start(); //Call back into the BaseController
  
  $this->user = New User();
  $this->set("uobj",$this->user);
  

}

public function view($message = null, $error = null) {
	  /* Messages */
	if($message && !$error) {
 	 $this->set("message",$message);
	} else if ($error) {
	  $this->set("error",$message);
	 }
	 
	 //Todo: read this out from constant set in site.php pointing to a wiki or online help
	 $this->guide_url = "https://bitbucket.org/hammertimedk/passwordx/issues";
}

}  