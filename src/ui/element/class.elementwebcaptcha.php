<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebcaptcha.php,v 1.2 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * CAPTCHA element
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 *
 */

class CElementWebCAPTCHA extends CElementWeb
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function GetValue()
    {
        $config = CEnvironment::GetMainApplication()->GetProperties();
      
        $publickey = $config['site']['security']['captcha']['generic']['publickey'];
        $privatekey = $config['site']['security']['captcha']['generic']['privatekey'];

        $captcha = new CCAPTCHA($publickey, $privatekey);

        return $captcha->GetChallengeHTML($error);
    }
}

/*
 *
 * Changelog:
 * $Log: class.elementwebcaptcha.php,v $
 * Revision 1.2  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.1.2.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 *
 */

?>