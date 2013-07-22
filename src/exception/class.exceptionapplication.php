<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.exceptionapplication.php,v 1.2 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Application exception
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 *
 */
class CExceptionApplication extends CExceptionEx
{
    function __construct($message = null, $code = 0, $innerexception = null)
    {
        parent::__construct($message, $code, $innerexception);

    }
}

/*
 *
 * Changelog:
 * $Log: class.exceptionapplication.php,v $
 * Revision 1.2  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.1.2.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 *
 */

?>