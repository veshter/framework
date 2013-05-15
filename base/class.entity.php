<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.entity.php,v 1.12 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * "An entity is something that has a distinct, separate existence, though it need not be a material existence."
 *
 * In particular, abstractions and legal fictions are usually regarded as entities. In general, there is also no presumption that an entity is animate.
 *
 * This is the most basic class that everything derives from in VESHTER
 *
 * @package		VESHTER
 * @copyright	Variably Extensible Scripting Hierarchy for Treatment of Electronic Resources. Copyright (c) 2006-2012 - VESHTER Network, LLC. All rights reserved. Details found at http://www.veshter.com/tos
 * @author		VESHTER Network, LLC, PO Box 1082, Vienna, VA 22182
 * @author		IMPORTANT for developers: Be sure to read the API programming standards/requirements before you do any development.
 * 				Otherwise, if you are working on a future part of the API, your work might be rejected due to inconsistancies
 */

abstract class CEntity
{
    /**
     * Creates a new instance of the class
     */
    function __construct()
    {
        $this->SetVersion('$Revision: 1.12 $');
    }

    /**
     *
     * Destroys the instance of the class
     */
    function __destruct()
    {
    }

    /**
     * Properties of the entity
     *
     * @var array
     */
    protected $properties = array();

    /**
     * Translations of various resources
     *
     * @var array
     */
    protected static $translations = array();

    /**
     * Set the current code version of a class: w.x.y.z
     *
     * The current code version of a class used to identify the class when errors and other information about it is displayed
     *
     * @param string $version The actual version. The only necessary digits are x.y.z because w is specified by the framework
     */
    protected function SetVersion($version)
    {
        preg_match('/[0-9\.]+/', $version, $matches);
        $this->properties['version'] = $matches[0];
    }

    /**
     * Retrives the version of the object
     *
     * @return string Version in string representation
     */
    final function GetVersion()
    {
        $version = !empty($this->properties['version']) ?
        _VERSION_FRAMEWORK . "." . $this->properties['version'] . "." . _VERSION_FRAMEWORK_BUILD :
        _VERSION_FRAMEWORK_CLASS;
         
        $version .=  'r' . _VERSION_FRAMEWORK_RELEASE;

        return $version;
    }

    final function SetProperty($name, $value)
    {
        if (is_numeric($value))
        {
            //TODO: Not very efficient??
            $value = floatval($value);
        }

        $this->properties[$name] = $value;
    }

    /**
     * Sets the properties of the entity
     *
     * @param array
     */
    final function SetProperties($properties)
    {
        $this->properties = $properties;
    }

    final function &GetProperty($name)
    {
        return $this->properties[$name];
    }

    /**
     * Gets the properties of the entity
     *
     * @return array
     */
    final function &GetProperties()
    {
        return $this->properties;
    }


    /**
     *
     * Registers a nested property.
     *
     * For example 'asp.support.contact.name' will be placed recursively and will be acessible by $array['asp']['support']['contact']['name']
     *
     * @param $keys
     * @param $value
     */
    final function RegisterNestedProperty($keys, $value)
    {
        $this->RegisterNestedPropertyWorker($this->properties, $keys, $value);
    }


    /**
     * Helper function for RegisterNestedProperty
     *
     * @param $array
     * @param $keys
     * @param $value
     *
     */
    private function RegisterNestedPropertyWorker(&$array, $keys, $value)
    {

        $key = array_shift($keys);

        if (count($keys))
        {
            $this->RegisterNestedPropertyWorker($array[$key], $keys, $value);
        }
        else
        {
            if (!is_array($array))
            {
                $oldvalue = $array;
                $array = array();
                if (!CString::IsNullOrEmpty($oldvalue))
                {
                    $array['value'] = $oldvalue;
                }
            }
            $array[$key] = $value;
        }
    }

    public static function AddTranslation($key, $value)
    {
        CEntity::$translations[$key] = $value;
    }

    public static function &GetTranslation($key)
    {
        return CEntity::$translations[$key];
    }

    /**
     * String representation of the object
     *
     * @return string
     */
    function ToString()
    {
        return "<!-- There is no string representation for the " . $this->_ident() . " class -->";
    }

    /**
     * Flushes the output buffers of the object.
     * This effectively tries to push all the output so far to the user's browser.
     * @param string Output stream
     * @return string
     */
    function Flush($stream = null)
    {
        return "<!-- There " . $this->_ident() . " class cannot be flushed -->";
    }


    /**
     * XML representation of the object
     *
     * @see APIArrayToXML
     * @param int $drill Drill down counter
     * @return string
     */
    function ToXML($drill = 0)
    {
        return "<!-- There is no XML representation for the " . $this->_ident() . " class -->";
    }


    /**
     * RSS representation of the object
     *
     * @return string
     */
    function ToRSS()
    {
        return "<!-- There is no RSS representation for the " . $this->_ident() . " class -->";
    }


    /**
     * JavaScript representation of the object
     *
     * @return string
     */
    function ToJavaScript()
    {
        return "<!-- There is no JavaScript representation for the " . $this->_ident() . " class -->";
    }

    /**
     * Returns class identification information in string format
     *
     * @ignore
     * @return string
     *
     */
    final function _ident()
    {
        return get_class($this) . " (Version: " . $this->GetVersion() . ")";
    }

    /**
     * Returns the string representation of the object
     *
     * @ignore
     * @return string
     */
    final function __toString() {
        return $this->ToString();
    }

}

/*
 *
 * Changelog:
 * $Log: class.entity.php,v $
 * Revision 1.12  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.11.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.11  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.10  2010-03-01 08:39:53  dkolev
 * Updated copyright date
 *
 * Revision 1.9  2009-06-21 03:25:49  dkolev
 * Documentation Improvement
 *
 * Revision 1.8  2009-06-15 03:43:21  dkolev
 * Moved $version to be a property
 *
 * Revision 1.7  2009-05-28 20:13:31  dkolev
 * Added type casting for numerics
 *
 * Revision 1.6  2009-04-09 09:36:38  dkolev
 * Added SetProperty and GetProperty functions
 *
 * Revision 1.5  2009-04-06 03:44:07  dkolev
 * Added default string representation on entities
 *
 * Revision 1.4  2009-01-30 08:08:36  dkolev
 * Added release information
 *
 * Revision 1.3  2009-01-30 07:57:41  dkolev
 * Added release information
 *
 * Revision 1.2  2007/09/27 00:07:51  dkolev
 * Added properties and translations
 *
 * Revision 1.1  2007/06/25 01:04:47  dkolev
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