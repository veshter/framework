<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.atom.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * "The smallest unit of matter"
 * This is the most basic class that everything derives from in VESHTER
 *
 * @package		VESHTER
 */

abstract class CAtom extends CEntity
{
    protected $parent = null;

    function __construct() 
    {
        parent::__construct();
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    /**
     * Sets the parent of the object
     *
     * @param CAtom $parent
     */
    function SetParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Gets the parent of the object
     *
     * @return CAtom
     */
    function GetParent()
    {
        return $this->parent;
    }
}

/*
 *
 * Changelog:
 * $Log: class.atom.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2007-09-27 00:01:35  dkolev
 * Added parent property
 *
 * Revision 1.8  2007/06/25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.7  2007/06/06 15:27:11  dkolev
 * Removed guids from the atom
 *
 * Revision 1.6  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/04/16 10:46:35  dkolev
 * Added Guid stuff
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>