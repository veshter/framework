<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: const.build.php,v 1.27 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * Error string constants
 *
 * @version $Revision: 1.27 $
 * @package VESHTER
 *
 */

/**
 * Framework build number. Updated with every checkin
 */
define ('_VERSION_FRAMEWORK_BUILD', array_sum(array_map('ord', str_split('$Revision: 1.27 $')))); //Default framework version

/*
 *
 * Changelog:
 * $Log: const.build.php,v $
 * Revision 1.27  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.26  2010-04-08 18:36:42  dkolev
 * Cleanup and encoding changes
 *
 * Revision 1.25  2010-04-06 04:51:18  dkolev
 * Minor class changes and reorganization
 *
 * Revision 1.24  2010-03-14 21:33:11  dkolev
 * Added tabby to the static folder and updated jQuery UI. Using mini versions
 *
 * Revision 1.23  2010-03-01 09:13:35  dkolev
 * Added documentation
 *
 * Revision 1.22  2010-03-01 06:16:10  dkolev
 * Autoincrement
 *
 *
 *
 *
 *
 */

?>