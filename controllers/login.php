<?php
/**
 * Login Controller
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Login controller
 * @author Stefan Fodor (stefan@unserialized.dk)
 */
class LoginController extends Concrete5_Controller_Login {

    //classwide vars
    protected $authy = null; //!< Authy instance

    /**
     * On start
     */
    public function on_start() {
        parent::on_start(); //who know what secrets parent holds for us?

        $this->authy = Loader::helper("authy");
		
        $this->set( 'two_factor_auth', (Config::get('TWO_FACTOR_METHOD') != 'no_2factor') );
        $this->set( 'otp', (Config::get('AUTH_FACTORS_REQUIRED') == 1) );
        $this->set( 'sms', ( (Config::get('TWO_FACTOR_METHOD') == 'authy') && (Config::get('AUTHY_SMS_TOKENS') != "0") ));
        
		$this->set("nosidebar",true);
    }

    /**
     * View task
     */
    public function view() {
        parent::view(); //callback to parent
		
    }

    /**
     * Do login.
     */
    public function do_login() {

        $ip = Loader::helper('validation/ip');
        $vs = Loader::helper('validation/strings');

        $loginData['success']=0;

        try {
            if(!$_COOKIE[SESSION]) {
                throw new Exception(t('Your browser\'s cookie functionality is turned off. Please turn it on.'));
            }

            if (!$ip->check()) {
                throw new Exception($ip->getErrorMessage());
            }

            //on OTP mode, we dont need password
            $pass = $this->authy->isOTP() ? $this->post('uToken') : $this->post('uPassword');

            if ( (!$vs->notempty($this->post('uName'))) || (!$vs->notempty( $pass ))) {
                if (USER_REGISTRATION_WITH_EMAIL_ADDRESS) {
                    throw new Exception(t('An email address and password are required.'));
                } else {
                    throw new Exception(t('A username and password are required.'));
                }
            }

            if ( !$vs->notempty($this->post('uToken')) && $this->authy->isEnabled() ) {
                throw new Exception(t('A token is required.'));
            }

            /*
             * If OTP login is enabled, make a few changes in the way C5 handles login
             * Search the user list for a user with $this->post('uName'), that is active.
             * Go to step 2, token verification, only if userlist contains 1 elements
             */
            if($this->authy->isOTP()) {

                Loader::model('user_list');
                $ul = new UserList();
                $ul->filterByIsActive(1);

                //search by name or email?
                if( USER_REGISTRATION_WITH_EMAIL_ADDRESS ) {
                    $ul->filterByKeywords( $this->post('uName') );
                } else {
                    $ul->filterByUserName( $this->post('uName') );
                }

                $users = $ul->get(1);

                //do we have a valid username?
                if( count($users) == 1 ) {
                    $u=$users[0];
                } else {
                    //fake it if not
                    $u = new User();
                    $u->loadError(USER_INVALID);
                }

            } else { //default C5 statement

                $u = new User($this->post('uName'), $this->post('uPassword'));
            }

            if ( $u->isError() ) {
                switch($u->getError()) {
                    case USER_NON_VALIDATED:
                        throw new Exception(t('This account has not yet been validated. Please check the email associated with this account and follow the link it contains.'));
                        break;
                    case USER_INVALID:

                        $usr_str = USER_REGISTRATION_WITH_EMAIL_ADDRESS ? 'email' : 'username';
                        $pwd_str = $this->authy->isOTP() ? 'token' : 'password';

                        throw new Exception(t('Invalid ' . $usr_str . ' or ' . $pwd_str . '.'));

                        break;
                    case USER_INACTIVE:
                        throw new Exception(t('This user is inactive. Please contact us regarding this account.'));
                        break;
                }
            } else {

                if( $this->authy->isEnabled() ) {

                    //UI
                    $ui = UserInfo::getByID( $u->getUserID() );
                    $authy_id = $ui->getAttribute('authy_user_id');

                    //If for some reason we dont have the Authy ID stored, try again to get it
                    if( empty($authy_id) ) {

                        list( $country_code, $junk ) = explode( ' ', (string)$ui->getAttribute('phone_country_code') );
                        $country_code = ltrim( $country_code, '+' );

                        $authy_id = $this->authy->getAuthyUserId(
                            $ui->getUserEmail(),
                            $ui->getAttribute('phone_number'),
                            $country_code
                        );

                        //save id DB
                        $ui->setAttribute( 'authy_user_id', $authy_id );
                    }

                    //validate the token
                    if( ! $this->authy->validToken( $this->post('uToken'), $authy_id ) ) {

                        $usr_str = USER_REGISTRATION_WITH_EMAIL_ADDRESS ? 'email or' : 'username or';
                        $msg_str = $this->authy->isOTP() ? $usr_str : '';

                        $loginData['msg']=t('Invalid ' . $msg_str . ' token.');
                        throw new Exception(t('Invalid ' . $msg_str . ' token.'));

                    }

                    //log the user in if OTP
                    if($this->authy->isOTP()) {
                        User::loginByUserID($u->getUserID());
                    }

                }

				//Log the sucess
				$nsa = Loader::helper("nsa");
				$geoIp = $nsa->geoLocateIP( $_SERVER["REMOTE_ADDR"] );
				$location = $_SERVER["REMOTE_ADDR"] . ' (' . (is_object( $geoIp ) ? sprintf( "%s, %s, %s", $geoIp->city, $geoIp->region_name, $geoIp->country_name ) : "unknown location") . ')';
			
				$log_str = "Last successful login from: " . $location; 
				Log::addEntry($log_str,'auth');
				
                $u->recordLogin();

				//Mark that he hasnt seen the msg
				$u->saveConfig('SEEN_LAST_LOGIN',0);

				//Create a session UEK for this user
				$crypto = Loader::helper("crypto");
				$u->plantSessionToken();
				$u->saveSessionUEK( $crypto->computeUEK( $this->post('uPassword') ) );

                //and finish the process
                $loginData['success']=1;
                $loginData['msg']=t('Login Successful');
                $loginData['uID'] = intval($u->getUserID());
            }

            $loginData = $this->finishLogin($loginData);

        } catch(Exception $e) {
        	
			//Log the fail
			$nsa = Loader::helper("nsa");
			
			$geoIp = $nsa->geoLocateIP( $_SERVER["REMOTE_ADDR"] );
			$location = $_SERVER["REMOTE_ADDR"] . ' (' . (is_object( $geoIp ) ? sprintf( "%s, %s, %s", $geoIp->city, $geoIp->region_name, $geoIp->country_name ) : "unknown location") . ')';
			
			$log_str = "Failed login attemp for user " .  $this->post('uName') . " from: " . $location; 
			Log::addEntry($log_str,'failed_auth');
			
            $ip->logSignupRequest();
            if ($ip->signupRequestThreshholdReached()) {
                $ip->createIPBan();
            }
            $this->error->add($e);
            $loginData['error']=$e->getMessage();
        }

        if ($_REQUEST['format']=='JSON') {
            $jsonHelper=Loader::helper('json');
            echo $jsonHelper->encode($loginData);
            die;
        }
    }

    /**
     * Change password
     */
    public function change_password($uHash = '') {
        $db = Loader::db();
        $h = Loader::helper('validation/identifier');
        $e = Loader::helper('validation/error');
        $ui = UserInfo::getByValidationHash($uHash);
        if (is_object($ui)) {
            $hashCreated = $db->GetOne("select uDateGenerated FROM UserValidationHashes where uHash=?", array($uHash));
            if ($hashCreated < (time()-(USER_CHANGE_PASSWORD_URL_LIFETIME))) {
                $h->deleteKey('UserValidationHashes','uHash',$uHash);
                throw new Exception( t('Key Expired. Please visit the forgot password page again to have a new key generated.') );
            } else {

                if (strlen($_POST['uPassword'])) {

                    $authy_id = $ui->getAttribute('authy_user_id');

                    //validate the token
                    if( ! $this->authy->validToken( $_POST['uToken'], $authy_id ) ) {
                        throw new Exception(t('Invalid token.'));
                    }


                    $userHelper = Loader::helper('concrete/user');
                    $userHelper->validNewPassword($_POST['uPassword'],$e);

                    if (strlen($_POST['uPassword']) && $_POST['uPasswordConfirm']!=$_POST['uPassword']) {
                        $e->add(t('The two passwords provided do not match.'));
                    }

                    if (!$e->has()) {
                        $ui->changePassword( $_POST['uPassword'] );
                        $h->deleteKey('UserValidationHashes','uHash',$uHash);
                        $this->set('passwordChanged', true);

                        $u = $ui->getUserObject();
                        if (USER_REGISTRATION_WITH_EMAIL_ADDRESS) {
                            $_POST['uName'] =  $ui->getUserEmail();
                        } else {
                            $_POST['uName'] =  $u->getUserName();
                        }
                        $this->do_login();

                        return;
                    } else { // This else is always used (due to return above), no need for else statement.
                        $this->set('uHash', $uHash);
                        $this->set('changePasswordForm', true);
                        $this->set('errorMsg', join( '<br>', $e->getList() ) );
                    }
                } else {
                    $this->set('uHash', $uHash);
                    $this->set('changePasswordForm', true);
                }
            }
        } else {
            throw new Exception( t('Invalid Key. Please visit the forgot password page again to have a new key generated.') );
        }
    }

    /**
     * Make a request to send an SMS token to a user
     */
    public function request_sms() {

        //are sms allowed
        if(!$this->authy->isSMSAllowed()) {
            throw new Exception( t('SMS Disabled.') );
        }

        //sanity check
        if( !$this->isPost() ) {
            throw new Exception( t('Invalid call.') );
        }

        //get and parse the phone number
        $phone = $this->post('phone');


        //remove and non valid chars
        $phone = preg_replace("/[^0-9]/", "", $phone);

        //remove leading country code 00es
        if( substr($phone,0,2) == "00" ) {
            $phone = substr($phone,2);
        }

        //last sanity check after parsing
        if( empty($phone) ) {
            echo json_encode( array( "status" => "FAIL", "msg" => t("Invalid phone number") ) );
            exit();
        }

        //find the user with the phone number
        Loader::model('user_list');
        $ul = new UserList();

        //filter by the phone number
        $ul->filterByAttribute('phone_number', $phone);

        //and get the first and only result
        $users = $ul->get(1);

        //no users found, maybe he added the prefix to the phone number
        if( count($users) == 0 ) {

            //adjust the phone
            $phone = substr($phone,2);

            //and try a new search
            $ul = new UserList();
            $ul->filterByAttribute('phone_number', $phone);
            $users = $ul->get(1);
        }

        //if still no result, we dont have this number in DB
        if( count($users) == 0 ) {
            echo json_encode( array( "status" => "FAIL", "msg" => t("Non existing phone number") ) );
            exit();
        }

        $ui = UserInfo::getByID( $users[0]->getUserID() );

        //Last sanity check, i promisse
        $authy_id = $ui->getAttribute('authy_user_id');
        if( empty($authy_id) ) {
            echo json_encode( array( "status" => "FAIL", "msg" => t("Invalid Authy Account") ) );
            exit();
        }


        //load the library
        $pkg = Package::getByHandle("c5authy");
        Loader::library('authy', $pkg);
        $authy = new Authy();

        //request the SMS
        if( $authy->requestSMS( $authy_id ) ) {
            //report that everything is ok
            echo json_encode( array( "status" => "OK" ) );
        } else {
            //small problem
            echo json_encode( array( "status" => "FAIL", "msg" => "Error while requesting SMS token" ) );
        }

        //end exit
        exit();
    }

}