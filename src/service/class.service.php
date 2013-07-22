<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.service.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * "An engine/service is something that produces some effect from a given input."
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 *
 */
class CService extends CObject
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
}

/*
 *
 * Changelog:
 * $Log: class.service.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2007-05-17 06:24:59  dkolev
 * Reflect C-names
 *
 * Revision 1.2  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.1  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>