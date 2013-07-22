<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.workgroup.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Access workgroup
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 *
 */
class CWorkgroup extends CObject
{
    protected $name;
    
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
 * $Log: class.workgroup.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2007-06-25 01:09:53  dkolev
 * Altered the function headers to avoid clashes up the class tree.
 *
 * Revision 1.2  2007/05/17 13:51:21  dkolev
 * Documentation changes
 *
 *
 *
 */

?>