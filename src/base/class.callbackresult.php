<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.callbackresult.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 *
 */

/**
 * @package VESHTER
 */

/**
 * Parameters returned/used by callback functions
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 */

class CCallbackResult extends CCollection
{
    protected $result = null;
    protected $nextCallback = null;

    /**
     * Creates a callback result.
     *
     * The callback result may contain parameters that are to be used for the next callback or
     * simple string that is the results of a callback
     *
     * @param mixed $result Result of the callback or parameters for the next callback
     * @param callback $nextCallback The next callback to be executed
     * @return CCallbackResult
     */
    function __construct($result, $nextCallback)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        $this->nextCallback = $nextCallback;

        if (is_array($result))
        {
            $this->collection = $result;
        }
        else
        {
            $this->result = $result;
        }
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Gets the result of a callback
     *
     * @return unknown
     */
    function GetResult()
    {
        return $this->result;
    }

    /**
     * Gets the next callback that should be executed
     *
     * @return callback The callback
     */
    function GetNextCallback()
    {
        return $this->nextCallback;

    }

}

/*
 *
 * Changelog:
 * $Log: class.callbackresult.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2007-06-15 17:28:32  dkolev
 * Initial import
 *
 *
 *
 */

?>