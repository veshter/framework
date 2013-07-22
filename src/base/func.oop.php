<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: func.oop.php,v 1.13 2010-08-23 14:17:28 dkolev Exp $
 */

/**
 * Object Oriented Programming functions that facilite rapid programming ease-of-use for the framework.
 *
 * @version $Revision: 1.13 $
 * @package VESHTER
 */

/**
 * @package VESHTER
 */


/**
 * "The smallest unit of matter"
 * This is the most basic class that everything derives from in VESHTER
 *
 * @package		VESHTER
 * @ignore
 */
class CTypeLoader
{

    /**
     * Path in which to look for class files
     * @var unknown_type
     */
    private $path;

    public function CTypeLoader($path = _PATH_FRAMEWORK)
    {
        $this->path = $path;
    }

    public static function RegisterTypeLoader($loader, $method = 'LoadClass')
    {
        spl_autoload_register(array($loader, $method));
    }

    /**
     * Gets a suggested filename for a class
     *
     * @return string Suggested filename for the class
     * @ignore
     */
    private static function GetClassFileName($class)
    {
        //skip the first character. In this framework call classes start with 'C'
        return sprintf("class.%s%s", strtolower(substr($class, 1)), _EXT);
    }

    /**
     * Tries to include a framework class by looking recursively in a directory
     *
     * @param string $class Name of the path to include
     * @param string $root Path to look into
     * @ignore
     */
    private static function LoadClassHelper ($class, $root)
    {
        //see if the class is included already
        $included = class_exists($class, false);

        //class is already included we need not do anything
        if ($included)
        {
            return true;
        }

        $fn_include = sprintf("%s%s", $root, CTypeLoader::GetClassFileName($class));

        //print ("<!-- looking for $class in $fn_include -->\n");

        if (file_exists($fn_include))
        {
            require_once($fn_include);

            // Check to see if the include declared the class
            $included = class_exists($class, false);

        }
        else if ($dh = opendir($root))
        {
            while (!$included && ($dir = readdir($dh)))
            {
                //don't waste time with . and ..
                if (($dir != '.') && ($dir != '..') && ($dir != '.cache') && ($dir != '.dev') && ($dir != 'CVS'))
                {
                    $nextroot = $root . $dir . _DIRSLASH;
                    //only use directories
                    if (is_dir($nextroot) && ($nextroot != _PATH_FRAMEWORK_TEMP) && ($nextroot != _PATH_FRAMEWORK_PLUGINS))
                    {
                        $included = CTypeLoader::LoadClassHelper($class, $nextroot);
                    }
                }
            }
            closedir($dh);
        }

        return $included;
    }

    /**
     * Make sure that the class that is requesed is indeed available
     * @ignore
     */
    function LoadClass($class)
    {
        $included = false;

        //see if we should even let files be somewhere outside of the framework directory tree
        //		if (constant('_PATH_INCLUDE_USE'))
        //		{
        //			//print ("<!-- tryiung to load $class from include path -->\n");
        //			//try the include path first
        //
        //			$filename = GetClassFileName($class);
        //
        //			if (file_exists($filename) && include_once($filename))
        //			{
        //				$included = class_exists($class, false);
        //				//print ("<!-- inclusion of $class was successful -->\n");
        //			}
        //			else
        //			{
        //				//print ("<!-- inclusion of $class failed -->\n");
        //			}
        //		}

        //if you still can't find the class, drill down into the framework tree
        if (!$included && !CTypeLoader::LoadClassHelper($class, $this->path))
        {
            //die ("$class could not be found in the framework");
        }
    }
}

/**
 * Register the default loader
 * @ignore
 */
CTypeLoader::RegisterTypeLoader(new CTypeLoader());

/*
 *
 * Changelog:
 * $Log: func.oop.php,v $
 * Revision 1.13  2010-08-23 14:17:28  dkolev
 * Minor formatting
 *
 * Revision 1.12  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.11  2009-04-06 03:44:52  dkolev
 * Stopped supressing the errors on include_once instead, checking it file exists before including it.
 *
 * Revision 1.10  2008-08-18 08:29:58  dkolev
 * If a class cannot be found in VESHTER, we kill the script.
 *
 * Revision 1.9  2007/06/15 17:16:32  dkolev
 * Added some commenting to trace class inclusion better
 *
 * Revision 1.8  2007/05/19 19:37:48  dkolev
 * Made include path to be optionally usable
 *
 * Revision 1.7  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.6  2007/02/26 04:06:24  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.5  2007/02/26 02:59:42  dkolev
 * *** empty log message ***
 *
 *
 * Revision 1.3  2007/02/26 02:53:02  dkolev
 * Disallowed CVS directories to be used for inclusion
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */
?>