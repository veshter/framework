<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.exceptionnotinitialized.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Not initialized exception
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */

class CExceptionNotInitialized extends CExceptionEx
{
    function __construct($message = null, $code = 0, $innerexception = null)
    {
        parent::__construct($message, $code, $innerexception);
    }
}

/*
 *
 * Changelog:
 * $Log: class.exceptionnotinitialized.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-10-08 03:17:43  dkolev
 * Added inner exceptions
 *
 * Revision 1.5  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 00:57:47  dkolev
 * Removed the SetVersion member call because it doesn't apply
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>