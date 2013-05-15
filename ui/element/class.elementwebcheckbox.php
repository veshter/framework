<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebcheckbox.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web combo box element
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CElementWebCheckbox extends CElementWebWithOptions
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        //TODO: Enable multiple selection
        $this->Warn("Multiple selection is not supported");
    }

    function __destruct()
    {
        parent::__destruct();
    }
}

/*
 *
 * Changelog:
 * $Log: class.elementwebcheckbox.php,v $
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
 * Revision 1.7  2007-05-17 06:24:58  dkolev
 * Reflect C-names
 *
 * Revision 1.6  2007/02/28 00:53:58  dkolev
 * Change the parent class from CElementWeb to CElementWebWithOptions
 *
 * Revision 1.5  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.4  2007/02/26 00:55:23  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>