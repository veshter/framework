<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.cookie.php,v 1.7 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 *
 * Cookie
 *
 * @version $Revision: 1.7 $
 * @package VESHTER
 *
 */

class CCookie extends CObject
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.7 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
}

/*
 *
 * Changelog:
 * $Log: class.cookie.php,v $
 * Revision 1.7  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.6.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.6  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.5  2009-06-21 03:10:21  dkolev
 * Documentation Improvement
 *
 * Revision 1.4  2007-05-17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>