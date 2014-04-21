<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Override for logs so that only Admin can clear logs
 * (c) 2014 PasswordX
 * Apache v2 License
 */

class DashboardReportsLogsController extends Concrete5_Controller_Dashboard_Reports_Logs {
	
	
	public function view($page = 0) {
		
		if ($page === "user_error") {
		 $this->set("error",Array("Error: Only the Admin (super user) can clear logs."));
		}
		
	 parent::view($page);	
	}
	
	public function clear($token = '', $type = false) {
	
	 $u = new User();
	 if (!$u->isSuperUser()) {
	  $this->redirect('/dashboard/reports/logs', 'user_error');
	 } else {
	  parent::clear($token, $type);
	 }
	
		
	}
	
}