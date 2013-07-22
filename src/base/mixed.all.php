<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: mixed.all.php,v 1.8 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * Worker file responsible for laying the base for the framework.
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 */


/**
 * Base files required for VESHTER to work properly
 */
foreach (array('const.code', 'const.defaults', 'const.error', 'const.time',	'const.build', 'func.oop') as $const)
{
    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_BASE . $const . _EXT);
}
unset($const);


/*
 *
 * Changelog:
 * $Log: mixed.all.php,v $
 * Revision 1.8  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.7  2010-03-01 06:16:39  dkolev
 * Added version build
 *
 * Revision 1.6  2010-03-01 06:07:29  dkolev
 * Removed temporary variable
 *
 * Revision 1.5  2009-06-15 03:44:09  dkolev
 * CVS comment change to ignore the require_once
 *
 * Revision 1.4  2007-09-27 00:06:05  dkolev
 * Removed empty space on top of file.
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>