<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.data.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Basic VESHTER data object
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */
abstract class CData extends CMolecule
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }
}

/*
 *
 * Changelog:
 * $Log: class.data.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-06-25 01:10:09  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.5  2007/05/17 14:21:25  dkolev
 * Fixed improper inheritance
 *
 * Revision 1.4  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>