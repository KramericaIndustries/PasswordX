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
	protected $resty				= null;		//!< Our connection hookl
	
    //addresses for authy servers
    const LIVE_SERVER       = "https://api.authy.com";
    const SANDBOX_SERVER    = "http://sandbox-api.authy.com";

    //various constants
    const FORMAT = "json"; //!< we dont want xml, do we?

    /**
     * Create a new object. Set the API key and the server
     */
    public function __construct( $load_config = true ) {
		
    	if( $load_config ) {
        	$this->loadConfig();
    	}

    	//init resty
    	Loader::library("3rdparty/resty");
    	$this->resty = new Resty();
    	$this->resty->setBaseURL( self::LIVE_SERVER . '/protected/' . self::FORMAT);
    }

    /**
     * Loads the class configuration
     */
    private function loadConfig() {

    	//set the values 
    	$this->api_key = Config::get('AUTHY_API_KEY');

    }
    
    /**
     * Returns last stored error
     */
    public function getLastError() {
    	return $this->last_error;
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

        $payload = array( 'user' => array(
        	'email'	=>	$email,
        	'country_code'	=>	$country_code,
        	'cellphone'	=>	$phone_number
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
        $got = $this->req( sprintf("/sms/%s", $authy_id), null, false, ( Config::get('AUTHY_SMS_TOKENS') =="2" ) );

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
		
    	//set the request params
    	$params = array(
    		'api_key'	=>	$this->api_key
    	);

    	if( $force ) {
    		$params['force'] = "true";
    	}
    	
    	//post or get?
    	if( $post ) {
    		$params = array_merge($params, $payload);
    		$resp = $this->resty->post( $path, $params );
    	} else {
    		$resp = $this->resty->get( $path, $params );
    	}

    	//and return
    	return $resp["body"];
    }

}