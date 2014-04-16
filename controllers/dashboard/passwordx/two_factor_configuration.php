<?php
/**
 * Configuration controller for 2 factor auth
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardPasswordxTwoFactorConfigurationController extends DashboardBaseController {

    /**
     * View task
     * @param string $msg
     */
    public function view( $msg = null ) {

        //sucess message if needed
        if( $msg == "success" ) {
            $this->set("message", t('Configuration updated successfully!'));
        }

        //error msg
        if( $msg == "key_error" ) {
            $this->error = t('Invalid Authy API Key!');
        }

        //error msg
        if( $msg == "token_error" ) {
            $this->error = t('Invalid security token!');
        }

        //send saved config to view
        $this->set( 'TWO_FACTOR_METHOD', Config::get('TWO_FACTOR_METHOD') );
        $this->set( 'AUTH_FACTORS_REQUIRED', Config::get('AUTH_FACTORS_REQUIRED') );
        
        //ga
        $this->set( 'show_secret_warning', $this->showGASecretWarning() );
        
        //authy
        $this->set( 'AUTHY_API_KEY', Config::get('AUTHY_API_KEY') );
        $this->set( 'AUTHY_SMS_TOKENS', Config::get('AUTHY_SMS_TOKENS') );

    }

    /**
     * Task that updates Authy Configuration
     */
    public function update_config() {

        if ($this->token->validate("update_2fa_config")) {
            if ($this->isPost()) {

            	//2f options
            	Config::save('TWO_FACTOR_METHOD', $this->post('TWO_FACTOR_METHOD'));
   
            	if( $this->post('TWO_FACTOR_METHOD') != "no_2factor" ) { 
            		Config::save('AUTH_FACTORS_REQUIRED', $this->post('AUTH_FACTORS_REQUIRED'));
            	}
            	
            	//ga specific
            	if( $this->post('TWO_FACTOR_METHOD') == "google" ) {
           			
            	}
            	
            	//authy specific
            	if( $this->post('TWO_FACTOR_METHOD') == "authy" ) {
            		
            		//check that the key provided, is indeed valid
            		require( DIR_BASE . "/helpers/authy.php" );
            		$authy = new AuthyHelper(false); //do not read config

            		if( !$authy->validAPIKey( $this->post("AUTHY_API_KEY") ) ) {
            			$this->redirect( "/dashboard/passwordx/two_factor_configuration/key_error" );
            			return;
            		}
            		
            		//save data
            		Config::save('AUTHY_API_KEY', $this->post("AUTHY_API_KEY"));
            		Config::save('AUTHY_SMS_TOKENS', $this->post('AUTHY_SMS_TOKENS'));
            	}

                
                $this->redirect( "/dashboard/passwordx/two_factor_configuration/success" );
            }
        } else {
            $this->redirect( "/dashboard/passwordx/two_factor_configuration/token_error" );
        }
    }

    /**
     * Checks if all the users have associated a GA secret with they account
     * @return bool
     */
    private function showGASecretWarning() {
    	
    	Loader::model('user_list');
    	$ul = new UserList();
    	
    	$users = $ul->get(1000);
    	
    	foreach( $users as $thisUser ) {
    		if( $thisUser->getUserObject()->config('ga_secret') == NULL || $thisUser->getUserObject()->config('ga_secret') == "" ) {
    			return true;
    		}
    	}
    	
    	return false;
    	
    }
    
}