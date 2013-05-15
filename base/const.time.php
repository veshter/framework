<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: const.time.php,v 1.3 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * Time constants
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

/**
 * Second
 */
define ("_SECOND", 		1);

/**
 * Minute
 */
define ("_MINUTE", 		60*_SECOND);

/**
 * Hour
 */
define ("_HOUR", 		60*_MINUTE);

/**
 * Day
 */
define ("_DAY", 		24*_HOUR);

/**
 * Month with 28 days
 */
define ("_MONTH28", 	28*_DAY);

/**
 * Month with 29 days
 */
define ("_MONTH29", 	29*_DAY);

/**
 * Month with 30 days
 */
define ("_MONTH30", 	30*_DAY);

/**
 * Month with 31 days
 */
define ("_MONTH31", 	31*_DAY);

/**
 * Year
 */
define ("_YEAR", 		365*_DAY);


/*
 *
 * Changelog:
 * $Log: const.time.php,v $
 * Revision 1.3  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2007-02-26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>