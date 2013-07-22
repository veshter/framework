<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.molecule.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * "The smallest unit of a substance that can exist alone and retain the character of that substance."
 *
 * Basic VESHTER class providing some OOP functionality
 *
 * @package		VESHTER
 */

abstract class CMolecule extends CAtom
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
 * $Log: class.molecule.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2007-06-25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 *
 *
 */


?>