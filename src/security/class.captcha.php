<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.captcha.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_RECAPTCHA))
{
    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_RECAPTCHA', _PATH_FRAMEWORK_PLUGINS . 'recaptcha' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_RECAPTCHA . 'recaptchalib.php');
}

/**
 * CAPTCHA image generator
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 */

class CCAPTCHA extends CObject
{
    private $publickey;

    private $privatekey;

    /**
     * Creates a generic captcha client
     * @param $publickey Public key. Get a key from https://www.google.com/recaptcha/admin/create
     * @param $privatekey Private key. Get a key from https://www.google.com/recaptcha/admin/create
     */
    function __construct($publickey, $privatekey)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');
        
        if (CString::IsNullOrEmpty($publickey))
        {
            throw new CExceptionSecurity('Public key cannot be empty');
        }
        
        $this->publickey = $publickey;
        
        if (CString::IsNullOrEmpty($privatekey))
        {
            throw new CExceptionSecurity('Private key cannot be empty');
        }
        
        $this->privatekey = $privatekey;
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Creates a new challenge HTML. Needs to be surrounded by a <form>
     * @param $error If any
     */
    function GetChallengeHTML($error = '')
    {
        return recaptcha_get_html($this->publickey, $error);
    }
    
    /**
     * Checks if the CAPTCHA challenge was attempted
     */
    function IsAttempted()
    {
        $response = CEnvironment::GetSubmittedVariable('recaptcha_response_field');
        
        return !CString::IsNullOrEmpty($response);
    }

    /**
     * Checks the users response to the CAPTCHA challenge. Assumes there is only one challenge per form
     */
    function CheckResponse()
    {
        $challenge = CEnvironment::GetSubmittedVariable('recaptcha_challenge_field');
        $response = CEnvironment::GetSubmittedVariable('recaptcha_response_field');
        
        $resp = recaptcha_check_answer ($this->privatekey, CEnvironment::GetServerAddressPublic(), $challenge, $response);

        //record the failure
        if (!$resp->is_valid)
        {
            $this->Warn($resp->error);
        }
        
        return $resp->is_valid;
    }
}

/*
 *
 * Changelog:
 * $Log: class.captcha.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-06-21 03:08:49  dkolev
 * Initial import
 *

 *
 */

?>