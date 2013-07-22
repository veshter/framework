<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.exceptionsecurity.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Security exception
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 *
 */
class CExceptionSecurity extends CExceptionEx
{
    function __construct($message = null, $code = 0, $innerexception = null)
    {
        parent::__construct($message, $code, $innerexception);
    }
}

/*
 *
 * Changelog:
 * $Log: class.exceptionsecurity.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2007-10-08 03:17:43  dkolev
 * Added inner exceptions
 *
 * Revision 1.2  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/03/18 04:02:03  dkolev
 * Initial import
 *
 */

?>