<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.exceptionex.php,v 1.10 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic VESHTER exception
 *
 * @version $Revision: 1.10 $
 * @package VESHTER
 *
 */

class CExceptionEx extends Exception
{
    private $innerexception = null;

    function __construct($message = null, $code = 0, $innerexception = null)
    {
        parent::__construct($message, $code);
        $this->innerexception = $innerexception;

    }
}

/*
 *
 * Changelog:
 * $Log: class.exceptionex.php,v $
 * Revision 1.10  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.9.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.9  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.8  2007-11-12 05:10:46  dkolev
 * Made the class concrete
 *
 * Revision 1.7  2007/10/08 03:17:43  dkolev
 * Added inner exceptions
 *
 * Revision 1.6  2007/06/15 17:20:24  dkolev
 * Added an actual constructor
 *
 * Revision 1.5  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 00:57:10  dkolev
 * Made class abstract
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