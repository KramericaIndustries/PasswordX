<?php

class LandingPageTypeController extends Controller {
	
	/**
	 * Remove the admin key on first run
	 */
	public function on_start(){
			
		parent::on_start(); //Call back into the BaseController
	}
	
} 
