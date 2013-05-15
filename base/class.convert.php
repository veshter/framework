<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.convert.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Conversions class
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 */
abstract class CConvert extends CObject
{

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Returns a dollar amount
     *
     * @param string $number
     */
    public static function ToMoney($number)
    {
        return CConvert::ToDouble($number, 2);
    }

    public static function ToFloat($number, $decimals = 2, $separator = ',')
    {
        return CConvert::ToDouble($number, $decimals, $separator);
    }

    /**
     * Convertes a number into a decimal
     *
     * @param unknown_type $number
     * @param unknown_type $decimals
     * @param unknown_type $separator
     * @return unknown
     */
    public static function ToDouble($number, $decimals = 2, $separator = ',')
    {
        $val = floatval($number);
        return number_format($number, $decimals, '.', $separator);

    }


}

/*
 *
 * Changelog:
 * $Log: class.convert.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2009-09-13 13:22:59  dkolev
 * Changed Inheritance
 *
 * Revision 1.5  2007-06-25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.4  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>