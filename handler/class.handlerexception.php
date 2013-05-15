<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.handlerexception.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Exception handler
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */

class CHandlerException extends CHandler
{
    
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    /**
     * Handle some exception
     *
     * @param CExceptionEx The thrown exception
     */
    function Handle($exception)
    {
        $this->Notify('Triggering error');
        trigger_error($exception->getMessage(), E_USER_ERROR);

    }
}

/*
 *
 * Changelog:
 * $Log: class.handlerexception.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2008-08-27 08:47:16  dkolev
 * Added activity notification to handler
 *
 * Revision 1.1  2007/06/15 17:24:59  dkolev
 * Initial import
 *
 */

?>