<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.token.php,v 1.14 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Token for security related information
 * It can generate password and secure strings
 *
 *
 * @version $Revision: 1.14 $
 * @package VESHTER
 *
 */
class CToken extends CGuid
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.14 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Creates a string that cannot be decrypted
     */
    static function Crypt ($str)
    {
        $salt = "$str";
        $str_new = "ve\$hter_";

        $str_length = strlen($str);
        for ($loop = 0; $loop < $str_length; $loop=$loop+8)
        {
            //print ("$chunk " . crypt($chunk, "$salt") . " : ");
            $chunk = substr($str, $loop, 8);
            $str_new .= crypt($chunk, "$salt");
        }
        return $str_new;
    }
}

/*
 *
 * Changelog:
 * $Log: class.token.php,v $
 * Revision 1.14  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.13.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.13  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.12  2007-09-27 00:21:00  dkolev
 * Added Crypt
 *
 * Revision 1.11  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.10  2007/03/14 08:05:21  dkolev
 * Made the token just be a guid child
 *
 * Revision 1.9  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.8  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>
