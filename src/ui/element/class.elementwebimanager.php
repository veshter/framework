<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebimanager.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Inline manager
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CElementWebiManager extends CElementWebiFrame
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
}

/*
 *
 * Changelog:
 * $Log: class.elementwebimanager.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-02-28 19:40:21  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.1  2007-06-15 17:09:42  dkolev
 * Initial import
 *
 * Revision 1.1  2007/06/15 02:47:36  dkolev
 * Added IFrames
 *
 *
 *
 */

?>