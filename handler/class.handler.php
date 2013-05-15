<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.handler.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Basic event handler
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
abstract class CHandler extends CObject
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Handle($object)
    {
        throw new CExceptionNotImplemented($object);
    }
}

/*
 *
 * Changelog:
 * $Log: class.handler.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2007-06-15 17:24:59  dkolev
 * Initial import
 *
 */

?>