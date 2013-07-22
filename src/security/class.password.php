<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.password.php,v 1.9 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Password generator
 *
 * @version $Revision: 1.9 $
 * @package VESHTER
 * @deprecated
 */
class CPassword extends CObject
{
    protected $content;

    function __construct($length = 7) 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.9 $');
        
        $pool = $this->GetPool();

        $raw = '';

        while(strlen($raw) < $length)
        {
            $raw .= substr($pool,(rand()%(strlen($pool))),1);
        }

        $this->content = $raw;
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    /**
     * One-way string encryption (hashing)
     *
     * @return string The encrypted string
     */
    static function OneWayCrypt($string)
    {
        $salt = $string;
        $str_new = "ve\$hter_";

        $str_length = strlen($string);
        for ($loop = 0; $loop < $str_length; $loop = $loop+8)
        {
            $chunk = substr($string, $loop, 8);
            $str_new .= crypt($chunk, $salt);
        }
        return $str_new;
    }


    function ToString()
    {
        return $this->content;
    }

    private function GetPool($type = 4)
    {
        $str = '';
        switch($type)
        {
            case 1: // a - z
                for($i = 0x61; $i <= 0x7A; $i++)
                {
                    $str .= chr($i);
                }
                return $str;
            case 2: // A - Z
                for($i = 0x41; $i <= 0x5A; $i++)
                {
                    $str .= chr($i);
                }
                return $str;
            case 3: // a - z and A - Z
                $str = $this->GetPool(1);
                $str .= $this->GetPool(2);
                return $str;
            case 4: // 0 - 9, A - Z and a - z
                $str = $this->GetPool(3); // get chars a - z and A - Z first
                for($i = 0x30; $i <= 0x39; $i++)
                {
                    $str .= chr($i); // add chars 0 - 9;
                }
                return $str;
            default:
                return $this->GetPool(4);
        }
    }
}


/*
 *
 * Changelog:
 * $Log: class.password.php,v $
 * Revision 1.9  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.8.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.8  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.7  2009-06-21 03:10:21  dkolev
 * Documentation Improvement
 *
 * Revision 1.6  2007-05-17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/04/16 10:45:51  dkolev
 * Added OneWay crypt function
 *
 * Revision 1.4  2007/03/14 08:05:46  dkolev
 * Moved old code from Token to Password as it is more appropriate
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>
