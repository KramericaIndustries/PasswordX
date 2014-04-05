<?php
/**
* Authy main library
* @author: Stefan Fodor
 * Built with love by Stefan Fodor @ 2014
*/

class AuthyHelper {

    //class wide variabled
    protected $api_key              = null;     //!< authy api key
    protected $server               = null;     //!< URL to RESTful server
    protected $auth_type            = null;     //!< OTP or 2 Factor? or none?
    protected $sms_token            = null;     //!< Do we allow sms tokens?
	protected $last_error			= null;		//!< Last error message from authy
	
    //addresses for authy servers
    const LIVE_SERVER       = "https://api.authy.com";
    const SANDBOX_SERVER    = "http://sandbox-api.authy.com";

    //various constants
    const FORMAT = "json"; //!< we dont want xml, do we?

    /**
     * Create a new object. Set the API key and the server
     */
    public function __construct() {

        $this->loadConfig();

    }

    /**
     * Loads the class configuration
     */
    private function loadConfig() {

        //get pachage and configuration
        /*$pkg = Package::getByHandle("c5authy");
        Loader::library('authy', $pkg);

        $co = new Config();
        $co->setPackageObject($pkg);

        $production = ( $co->get('authy_server_production') == "1" ? true : false );

        //set the values
        $this->api_key = $co->get('authy_api_key');
        $this->server = $production ? self::LIVE_SERVER : self::SANDBOX_SERVER;
        $this->auth_type = $co->get('authy_type');
        $this->sms_token = $co->get('authy_sms_tokens'); */
    }
    
    /**
     * Returns last stored error
     */
    public function getLastError() {
    	return $this->last_error;
    }

    /**
     * Convenience method to check if OTP
     * @return bool
     */
    public function isOTP(){
        return ($this->auth_type == "1");
    }

    /**
     * Convenience method to check if we should try to SMS
     * @return bool
     */
    public function isSMSAllowed(){
        return ($this->sms_token != "0");
    }

    /**
     * Convenience method to check if authy is enabled
     * @return bool
     */
    public function isEnabled() {
        return ($this->auth_type != "0");
    }
    
    /**
     * Validates an API key
     * @param unknown $key
     */
    public function validAPIKey( $key ) {
    	
    	//sanity check
    	if( empty($key) ) {
    		$this->last_error = "Empty API Key";
    		return false;
    	}
    	
    	//load data
    	$this->api_key = $key;
    	$this->server = self::LIVE_SERVER;
    	
    	//ask authy if the key is valid
        $got = $this->req( '/app/details' );
        
        if( $got->success == false ) {
        	$this->last_error = $got->message;
        }

        return ($got->success == true);

    }

    /**
     * Returns the Authy ID of a certain user
     *
     * @param string $email
     * @param string $phone_number
     * @param string $country_code
     * @throws Exception
     */
    public function getAuthyUserId( $email, $phone_number, $country_code ) {

        //sanity checks
        if( empty( $email ) ) {
            throw new Exception( t('Invalid email address') );
        }

        if( empty( $phone_number ) ) {
            throw new Exception( t('Invalid phone number') );
        }

        if( empty( $country_code ) ) {
            throw new Exception( t('Invalid country code') );
        }

        //build the payload
        $payload = http_build_query( array(
            'user[email]'           => $email,
            'user[cellphone]'       => $phone_number,
            'user[country_code]'    => $country_code
        ));

        //and ask authy
        $got = $this->req( '/users/new', $payload , true );

        //are we ok?
        if( $got->success == false ) {
            throw new Exception( t('Authy error when creating/updating a user: ') . $got->message );
        }

        //if ok, safe navigate to user id
        if( $got->success == true ) {
            if( is_object( $got->user ) ) {
                return $got->user->id;
            }
        }

        //one should not find itself here
        throw new Exception( t('Unexpected Authy reponse') );
    }

    /**
     * Validates a given token
     *
     * @param $token
     * @param $authy_id
     * @return boolean
     * @throws Exception
     */
    public function validToken( $token, $authy_id ) {

        if( empty($token) ) {
            throw new Exception( t('Invalid token!') );
        }

        if( empty($authy_id) ) {
            throw new Exception( t('Invalid authy ID!') );
        }


        $got = $this->req( sprintf("/verify/%s/%s", $token, $authy_id), null, false, true );

        //sanity check
        if( is_object($got) ) {

            //did we got an OK?
            if( $got->success == "true" && $got->token == "is valid" ) {
                return true;
            }

            return false;
        }

        //one should not find itself here
        throw new Exception( t('Authy Error: Unexpected response while verifying token!') );
    }


    /**
     * Requests an SMS
     *
     * @param $authy_id
     */
    public function requestSMS( $authy_id ) {

        if( empty($authy_id) ) {
            throw new Exception( t('Authy Error: Invalid authy ID!') );
        }

        //Send the request
        $got = $this->req( sprintf("/sms/%s", $authy_id), null, false, ($this->sms_token=="2") );

        //sanity check
        if( is_object($got) ) {

            //did we got an OK?
            if( $got->success == true  ) {
                return true;
            }

            return false;
        }

        //one should not find itself here
        throw new Exception( t('Authy Error: Unexpected response while requesting sms token!') );
    }

    /**
     * Sends a request to Authy servers and returns the response decoded
     *
     * @param string $path
     * @param array $payload
     * @param bool $post
     * @param bool $force
     * @return mixed
     */
    private function req( $path, $payload = array(), $post = false, $force = false ) {

        //compose the req url
        $url = sprintf( '%s/protected/%s%s?api_key=%s', $this->server, self::FORMAT, $path, $this->api_key );

        //force an action
        if( $force ) {
            $url .= "&force=true";
        }

        //curl handler
        $ch = curl_init();

        //url to curl
        curl_setopt($ch, CURLOPT_URL, $url);

        //receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // post data
        if( $post ) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
        }

        //ruun
        $server_output = curl_exec($ch);

        //always close the sockets
        curl_close ($ch);

        //perfect
        return json_decode( $server_output );
    }

}