<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email
{

	var	$useragent		= "RealEstate";
	var	$mailpath		= "/usr/sbin/sendmail";	// Sendmail path
	var	$protocol		= "mail";	// mail/sendmail/smtp
	var	$smtp_host		= "";		// SMTP Server.  Example: mail.earthlink.net
	var	$smtp_user		= "";		// SMTP Username
	var	$smtp_pass		= "";		// SMTP Password
	var	$smtp_port		= "25";		// SMTP Port
	var	$smtp_timeout	= 5;		// SMTP Timeout in seconds
	var	$smtp_crypto	= "";		// SMTP Encryption. Can be null, tls or ssl.
	var	$crlf	= "\r\n";		// SMTP Encryption. Can be null, tls or ssl.
	var	$newline	= "\r\n";		// SMTP Encryption. Can be null, tls or ssl.
	var	$wordwrap		= TRUE;		// TRUE/FALSE  Turns word-wrap on/off
	var	$wrapchars		= "76";		// Number of characters to wrap at.
	var	$mailtype		= "text";	// text/html  Defines email formatting
	var	$charset		= "utf-8";	// Default char set: iso-8859-1 or us-ascii
	var	$multipart		= "mixed";	// "mixed" (in the body) or "related" (separate)
	var $alt_message	= '';		// Alternative message for HTML emails
	var	$validate		= FALSE;	// TRUE/FALSE.  Enables email validation
	var	$priority		= "3";		// Default priority (1 - 5)
        
    
    public function __construct($params = array())
    {

        if(config_db_item('enable_smtp')) {
            $this->protocol = 'smtp';
            $this->smtp_host = config_db_item('smtp_host');
            $this->smtp_user = config_db_item('smtp_user');
            $this->smtp_pass = config_db_item('smtp_pass');
            $this->smtp_port = config_db_item('smtp_port');
            $this->smtp_crypto = config_db_item('smtp_crypto');
        }

        parent::__construct($params = array());
    }

}

