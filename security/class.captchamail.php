<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.captchamail.php,v 1.2 2013-01-14 21:04:52 dkolev Exp $
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
 * CAPTCHA image generator for email addresses
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 */

class CCAPTCHAMail extends CObject
{
    private $publickey;

    private $privatekey;

    /**
     * Creates a captcha mail hide client
     * @param $publickey Public key. Get a key from https://www.google.com/recaptcha/mailhide/apikey
     * @param $privatekey Private key. Get a key from https://www.google.com/recaptcha/mailhide/apikey
     */
    function __construct($publickey, $privatekey)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2 $');

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

    function GetMailhideHTML($address)
    {
        return recaptcha_mailhide_html ($this->publickey, $this->privatekey, $address);
    }

    function GetMailhideURL($address)
    {
        return recaptcha_mailhide_url ($this->publickey, $this->privatekey, $address);
    }
}

/*
 *
 * Changelog:
 * $Log: class.captchamail.php,v $
 * Revision 1.2  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.1.2.1  2011-11-25 22:17:14  dkolev
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