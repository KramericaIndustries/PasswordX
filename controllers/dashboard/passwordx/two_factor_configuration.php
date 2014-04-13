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

                //we need a good api key
                $api_key = $this->post("AUTHY_KEY");
                if( empty( $api_key ) ) {
                    $this->redirect( "/dashboard/users/authy/key_error" );
                }

                Config::save('AUTHY_API_KEY', $this->post("AUTHY_KEY"));
                Config::save('AUTHY_TYPE', $this->post("AUTH_TYPE"));
                Config::save('AUTHY_SMS_TOKENS', $this->post("AUTHY_SMS"));
                Config::save('AUTHY_SERVER_PRODUCTION', $this->post("AUTHY_SERVER"));
                
                $this->redirect( "/dashboard/passwordx/two_factor_configuration/success" );
            }
        } else {
            $this->redirect( "/dashboard/passwordx/two_factor_configuration/token_error" );
        }
    }

}