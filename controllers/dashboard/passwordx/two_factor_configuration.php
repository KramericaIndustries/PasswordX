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
            $this->error = t('API key cannot be null!');
        }

        //error msg
        if( $msg == "token_error" ) {
            $this->error = t('Invalid security token!');
        }

        //send saved config to view
        $this->set( 'TWO_FACTOR_METHOD', Config::get('TWO_FACTOR_METHOD') );
        $this->set( 'AUTH_FACTORS_REQUIRED', Config::get('AUTH_FACTORS_REQUIRED') );
        
        //ga
        $this->set( 'GA_TIME_SLICE', Config::get('GA_TIME_SLICE') );
        $this->set( 'show_secret_warning', $this->showGASecretWarning() );
        //global $u;
        //var_dump($u->config('ga_secret'));
        //TODO: authy
        /*$this->set( 'authy_type', Config::get('AUTHY_TYPE') );
        $this->set( 'authy_server_production', Config::get('AUTHY_SERVER_PRODUCTION') );
        $this->set( 'authy_sms_tokens', Config::get('AUTHY_SMS_TOKENS') );
        $this->set( 'authy_api_key', Config::get('AUTHY_API_KEY') );*/

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
            		Config::save('GA_TIME_SLICE', $this->post('GA_TIME_SLICE'));
            	}
            	
            	//authy specific
            	if( $this->post('TWO_FACTOR_METHOD') == "authy" ) {
            		//TODO: authy
            	}
            	
            	
            	
            	
                //we need a good api key
                /*$api_key = $this->post("AUTHY_KEY");
                if( empty( $api_key ) ) {
                    $this->redirect( "/dashboard/users/authy/key_error" );
                }

                Config::save('AUTHY_API_KEY', $this->post("AUTHY_KEY"));
                Config::save('AUTHY_TYPE', $this->post("AUTH_TYPE"));
                Config::save('AUTHY_SMS_TOKENS', $this->post("AUTHY_SMS"));
                Config::save('AUTHY_SERVER_PRODUCTION', $this->post("AUTHY_SERVER"));*/
                
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