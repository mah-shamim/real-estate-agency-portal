<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('session_status')){
    exit('PHP version 5.4 is required for this version of Facebook SDK');
}

if ( session_status() == PHP_SESSION_NONE ) {
  session_start();
}

define('FACEBOOK_SDK_V4_SRC_DIR', APPPATH . 'libraries/facebook/Facebook/');

// Autoload the required files
require_once( APPPATH . 'libraries/facebook/autoload.php' );

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Facebook {
  var $ci;
  var $helper;
  var $session;
  var $permissions;

  public function __construct() {
    $this->ci =& get_instance();
    $this->permissions = array(
                          //'user_location',
                          //'user_birthday',
                          'scope'=>'email'
                        );

    
    if(!isset($this->ci->data['lang_code'])) {
        $this->ci->data['lang_code'] = 'en';
    }
    
    // Initialize the SDK
    FacebookSession::setDefaultApplication( $this->ci->config->item('appId'), $this->ci->config->item('secret') );
    
    $redirect_url_facebook = site_url('frontend/login/'.$this->ci->data['lang_code']);
    
    // Create the login helper and replace REDIRECT_URI with your URL
    // Use the same domain you set for the apps 'App Domains'
    // e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
    $this->helper = new FacebookRedirectLoginHelper($redirect_url_facebook);

    if ( $this->ci->session->userdata('fb_token') ) {
      $this->session = new FacebookSession( $this->ci->session->userdata('fb_token') );

      // Validate the access_token to make sure it's still valid
      try {
        if ( ! $this->session->validate() ) {
          $this->session = null;
        }
      } catch ( Exception $e ) {
        // Catch any exceptions
        $this->session = null;
      }
    } else {
      // No session exists
      try {
        $this->session = $this->helper->getSessionFromRedirect();
      } catch( FacebookRequestException $ex ) {
        // When Facebook returns an error
      } catch( Exception $ex ) {
        // When validation fails or other local issues
      }
    }

    if ( $this->session ) {
      $this->ci->session->set_userdata( 'fb_token', $this->session->getToken() );

      $this->session = new FacebookSession( $this->session->getToken() );
    }
  }

  /**
   * Returns the login URL.
   */
  public function login_url() {
    return $this->helper->getLoginUrl( $this->permissions );
  }
  
  public function logout()
  {
    $this->ci->session->unset_userdata('fb_token');
  }
  
  public function destroySession()
  {
    $this->ci->session->unset_userdata('fb_token');
  }

  /**
   * Returns the current user's info as an array.
   */
  public function getUser() {
    if ( $this->session ) {
      /**
       * Retrieve User’s Profile Information
       */
       
      $foperater = new FacebookRequest( $this->session, 'GET', '/me?fields=name,email,link' );
       
      // Graph API to request user data
      $request = $foperater->execute();

      // Get response as an array
      $user_profile = $request->getGraphObject();

      $user = $user_profile->asArray();

      return $user;
    }
    
    return false;
  }
}