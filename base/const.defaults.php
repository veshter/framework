<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: const.defaults.php,v 1.7 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * Framework default constants.
 *
 * To ensure proper functionality of the framework, some constants that are made avaivale to the client to configure
 * have to be set in the case of the client omitting them. Below are these constants.
 *
 * @version $Revision: 1.7 $
 * @package VESHTER
 */

if (!defined('_DATASOURCE_TYPE'))
{
    /**
     * Default data source
     *
     */
    define ('_DATASOURCE_TYPE', 'MySQL');
}


if (!defined('_REALM'))
{
    /**
     * Default page realm
     *
     */
    define('_REALM', 'general');
}



/*
 *
 * Changelog:
 * $Log: const.defaults.php,v $
 * Revision 1.7  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-02-28 10:05:46  dkolev
 * Neatened code
 *
 * Revision 1.5  2007/02/26 20:55:09  dkolev
 * Replaced double with single quotes
 *
 * Revision 1.4  2007/02/26 20:53:30  dkolev
 * Replaced double with single quotes
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