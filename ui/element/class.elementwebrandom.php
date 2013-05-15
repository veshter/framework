<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebrandom.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Random value element
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CElementWebRandom extends CElementWeb
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

    function GetValue()
    {
        return CGuid::NewGuid()->ToString();
    }
}

/*
 *
 * Changelog:
 * $Log: class.elementwebrandom.php,v $
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
 * Revision 1.1  2009-03-30 01:04:58  dkolev
 * Initial import
 *
 *
 */

?>