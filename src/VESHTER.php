<?php
/**
 * %LICENSE% - see LICENSE
 *
 * $Id: VESHTER.php,v 1.23 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * Framework initialization file.
 *
 * It is used to set up the basic fundamentals of the VESHTER framework.
 *
 * @version $Revision: 1.23 $
 * @package VESHTER
 *
 */


//don't allow people to include/require this file more than one time
if (defined('_VESHTER'))
{
    die('The framework has already been initialized');
}

/**
 * Boolean true
 *
 */

define ('S_OK', 							true);

/**
 * Boolean false
 *
 */
define ('S_FALSE', 							false);

define ('_SLASH', 							'/');
define ('_BACKSLASH', 						'\\');

/**
 * Default slash for be used in directories and web addresses
 */
define ('_DIRSLASH', 				_SLASH);

if (!empty($_ENV['HOSTTYPE']))
{
    switch ($_ENV['HOSTTYPE'])
    {
        //Windows version
        case 'Windows':
            /**
             * @ignore
             */
            define ('_DIRSLASH', 				_BACKSLASH);
            break;

            //UNIX version
        case 'FreeBSD':
        default: //some UNIX platform
            //go with the default
            break;
    }
}

//don't do anything fancy if we are no on the web side
if (array_key_exists('HTTP_HOST', $_SERVER))
{
    /**
     * Protocol used by the current site http, https, etc
     */
    define ('_PROTOCOL', 						strtolower(strtok($_SERVER['SERVER_PROTOCOL'], '/')));

    /**
     * Base href of the current site (ie. http://www.veshter.com)
     */
    define ('_HTTPHOST', 						$_SERVER['HTTP_HOST']);
    define ('_BASEHREF',						_PROTOCOL . '://' . _HTTPHOST);
}

/**
 * File extension for code files 
 * @var unknown_type string
 */
define ('_EXT', 							'.php');

/**
 * Virtual file extension for dynamic scripts
 * @var unknown_type string
 */
define ('_EXT_VIRTUAL', 					'.html');
define ('_SELF', 							$_SERVER['PHP_SELF']);
$_fileinfo = 								explode ('/', _SELF);

if (empty($_fileinfo[count($_fileinfo)-1]))
{
    define ('_FILENAME', 						'index' . _EXT);
    define ('_FILE', 							'index');
}
else
{
    /**
     * @ignore
     */
    define ('_FILENAME', 						$_fileinfo[count($_fileinfo)-1]);
    /**
     * @ignore
     */
    define ('_FILE', 							preg_replace ('/' . _EXT . '/', '', _FILENAME));
}


/**
 * Allow or disallow the use of files that may be used by the framework
 * but are located somewhere outside the framework's directory tree
 *
 */
define ('_PATH_INCLUDE_USE', 				true);
define ('_PATH_ROOT',                       dirname(__FILE__) . _DIRSLASH . '..' . _DIRSLASH);
define ('_PATH_TEMP',                       _PATH_ROOT . 'tmp' . _DIRSLASH);
define ('_PATH_FRAMEWORK', 					dirname(__FILE__) . _DIRSLASH);

define ('_PATH_FRAMEWORK_BASE',				_PATH_FRAMEWORK  . 'base' . _DIRSLASH);
define ('_PATH_FRAMEWORK_DEPRICIATED', 		_PATH_FRAMEWORK  . 'depreciated' . _DIRSLASH);
define ('_PATH_FRAMEWORK_TEMP', 			_PATH_FRAMEWORK . 'temp' . _DIRSLASH);
define ('_PATH_FRAMEWORK_TOOLS', 			_PATH_FRAMEWORK . 'tools' . _DIRSLASH);
define ('_PATH_FRAMEWORK_TOOLS_DATABASE', 	_PATH_FRAMEWORK_TOOLS . 'database' . _DIRSLASH);
define ('_PATH_FRAMEWORK_TOOLS_PARSER', 	_PATH_FRAMEWORK_TOOLS . 'parser' . _DIRSLASH);
define ('_PATH_FRAMEWORK_MODULES', 			_PATH_FRAMEWORK . 'modules' . _DIRSLASH);
define ('_PATH_FRAMEWORK_PLUGINS', 			_PATH_FRAMEWORK  . 'plugins' . _DIRSLASH);
define ('_PATH_WWW', 						'unknown');

//add the plugins directory to the include path to avoid issues with requires and require_onces
set_include_path(get_include_path() . PATH_SEPARATOR . _PATH_FRAMEWORK_PLUGINS);

require_once(_PATH_FRAMEWORK_BASE . 'mixed.all' . _EXT);

//Default framework version
define ('_VERSION_FRAMEWORK', 				'5');

//Default framework class version (if none is defined)
define ('_VERSION_FRAMEWORK_CLASS', 		_VERSION_FRAMEWORK . '.1.0.' . _VERSION_FRAMEWORK_BUILD);

define ('_VERSION_FRAMEWORK_RELEASE', 		'1');

CEnvironment::Initialize();

//everything for the framework is define, VESHTER is ready.
define ('_VESHTER', true);

/*
 *
 * Changelog:
 * $Log: VESHTER.php,v $
 * Revision 1.23  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.22.4.5  2012-09-07 19:17:42  dkolev
 * Fixes due to PHP 5.3
 *
 * Revision 1.22.4.4  2012-02-19 20:52:27  dkolev
 * Added virtual file extension
 *
 * Revision 1.22.4.3  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.22.4.2  2011-11-20 22:54:34  dkolev
 * Added temp path
 *
 * Revision 1.22.4.1  2011-03-08 01:24:21  dkolev
 * Version update
 *
 * Revision 1.22  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.21  2010-03-01 06:17:39  dkolev
 * Pulled out version definition in its own (autoincrementable) file
 *
 * Revision 1.20  2009-12-27 19:57:56  dkolev
 * Added the plugins folder to the include path
 *
 * Revision 1.19  2009-09-13 13:32:57  dkolev
 * Version 3.0.0.0 R2
 *
 * Revision 1.18  2009-05-29 01:23:55  dkolev
 * Version 3.0.0.0
 *
 * Revision 1.17  2009-04-06 03:41:26  dkolev
 * Formatting changes
 *
 * Revision 1.16  2009-02-03 07:46:51  dkolev
 * *** empty log message ***
 *
 * Revision 1.15  2009-01-30 07:57:41  dkolev
 * Added release information
 *
 * Revision 1.14  2008/05/06 05:06:03  dkolev
 * Formatting changes.
 *
 * Revision 1.13  2008/03/25 19:13:24  dkolev
 * Removed _HTTPHOST and _BASEHREF constant definitions when they don't apply (cron jobs, etc)
 *
 * Revision 1.12  2007/10/08 19:21:00  dkolev
 * Added some documentation commenting
 *
 * Revision 1.11  2007/09/27 00:24:18  dkolev
 * Formatting changes
 *
 * Revision 1.10  2007/06/15 17:26:42  dkolev
 * Allowed for further customization of client scripts
 *
 * Revision 1.9  2007/05/17 06:25:05  dkolev
 * Reflect C-names
 *
 * Revision 1.8  2007/04/16 10:44:38  dkolev
 * Replaced all double quotes with single quotes
 *
 * Revision 1.7  2007/02/28 01:06:27  dkolev
 * Commenting changes for the _DIRSLASH constant
 *
 * Revision 1.6  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.5  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 * Revision 1.4  2007/02/26 00:01:21  dkolev
 * Fixed build number generation
 *
 * Revision 1.3  2007/02/25 19:05:25  dkolev
 * Commented the S_TRUE and S_FALSE
 *
 * Revision 1.2  2007/02/25 19:03:08  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>