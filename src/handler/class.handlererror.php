<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.handlererror.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Error handler
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */

class CHandlerError extends CHandler
{
    public static $errortypes = array (
    E_ERROR				=> 'Error',
    E_WARNING 			=> 'Warning',
    E_PARSE 			=> 'Parsing Error',
    E_NOTICE 			=> 'Notice',
    E_CORE_ERROR 		=> 'Core Error',
    E_CORE_WARNING 		=> 'Core Warning',
    E_COMPILE_ERROR 	=> 'Compile Error',
    E_COMPILE_WARNING 	=> 'Compile Warning',
    E_USER_ERROR 		=> 'User Error',
    E_USER_WARNING 		=> 'User Warning',
    E_USER_NOTICE 		=> 'User Notice',
    E_STRICT 			=> 'Runtime Notice',
    E_RECOVERABLE_ERROR => 'Catchable Fatal Error'
    );

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
     * Handle some error
     *
     * @param int $errno
     * @param string $errmsg
     * @param string $filename
     * @param int $linenum
     * @param arrays $vars
     */
    function Handle($errno, $errmsg, $filename, $linenum, $vars)
    {
        // set of errors for which a var trace will be saved
        $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

        $errortype = CHandlerError::$errortypes[$errno];
        $error = array (
			'errornum' => $errno,
			'errortype' => $errortype,
			'errormsg' => $errmsg,
			'scriptname' => $filename,
			'scriptlinenum' => $linenum
        );

        if (in_array($errno, $user_errors))
        {
            $error['vartrace'] = print_r($vars, true);
        }

        $this->Notify('Logging error');
        //log the error
        CEventLog::Log($filename, $errmsg, $error, $errortype, 1, -1, $errno == E_USER_ERROR);

        CEnvironment::Write(CCollection::ToXMLWorker($error));
        exit;
    }
}


/*
 *
 * Changelog:
 * $Log: class.handlererror.php,v $
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