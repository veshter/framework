<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.parser.php,v 1.4.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic parser
 *
 * @todo add more detail to this documentation
 * @version $Revision: 1.4.4.1 $
 * @package VESHTER
 */
abstract class CParser extends CObject
{

    /**
     * List of all document nodes.
     *
     * This array contains a list of all document nodes saved as an
     * associative array.
     *
     * @access private
     * @var    array
     */
    protected $nodes = array();

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4.4.1 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Returns a reference of the nodes in the parser
     *
     * @return &array
     */
    function &GetNodes()
    {
        return $this->nodes;
    }

}

/*
 *
 * Changelog:
 * $Log: class.parser.php,v $
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2007-09-27 00:17:40  dkolev
 * Removed dead code.
 *
 * Revision 1.2  2007/05/17 06:25:00  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/02/28 01:03:04  dkolev
 * Moved the directory from parse to parser
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