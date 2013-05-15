<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widget.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Generic window gadget
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */
abstract class CWidget extends CGadget
{
    protected $data;

    protected $theme;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function LoadData($data)
    {
        $this->data = $data;
    }

    function Render()
    {
        throw new CExceptionNotImplemented('Rendering of this widget is not implemented');
    }
}


/*
 *
 * Changelog:
 * $Log: class.widget.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2010-03-01 05:04:09  dkolev
 * Cleaned up the code organization to be more readable. Removed redundant calls/merges.
 *
 * Revision 1.1  2010-02-28 19:40:31  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.1  2007-12-20 22:57:37  dkolev
 * Initial import
 *
 */


?>