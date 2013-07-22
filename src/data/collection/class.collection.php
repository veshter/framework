<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.collection.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Collection of (mixed) data
 *
 * @version $Revision: 1.11 $
 * @package VESHTER
 *
 */
abstract class CCollection extends CData
{
    protected $collection = array();

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.11 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Attaches a basic array to a collection
     * @param array The array to attach
     */
    function Attach($collection)
    {
        if (is_array($collection))
        {
            $this->collection = array_merge($this->collection, $collection);
            true;
        }
        return false;

    }

    function GetAt($position)
    {
        return $this->collection[$position];
    }

    function Add($data)
    {
        $this->collection[count($this->collection)-1] = $data;
    }

    function SetAt($data, $position = 0)
    {
        $this->collection[$position] = $data;
    }

    function GetCount()
    {
        return count($this->collection);
    }

    function GetValues()
    {
        return $this->collection;
    }

    function ToXML($root_name = "doc", $drill = 0, $wantencode = false)
    {
        return CCollection::ToXMLWorker($this->collection, $root_name, $drill, $wantencode);
    }

    /**
     * Creates an XML representation of an array
     *
     * @param ref $array The data array. If an object is sent, the function will place it into an array and proceed
     * @param string $root_name
     * @param int $drill Drill down counter used for indentation and presentation purposes only
     * @param boolean $wantencode If this is true, the values of the array will be encoded if necessary. If it is false, the values will be in CDATA segments.
     * @return string XML representation of the array
     */
    static function ToXMLWorker (&$array, $root_name = "doc", $drill = 0, $wantencode = false, &$visited = array())
    {
        //if ($drill > 50)
        //	CAPI::GetAPI()->Kill("Cannot produce an XML representation of the array because it is too large or contains recursion");

        if (is_array($array))
        {
            $xml = "";
            $padding = "";
            if ($drill == 0)
            {
                $xml .= CString::Format('<?xml version="%s" encoding="%s"?>', CEnvironment::GetVersionXML(), CEnvironment::GetEncoding());
                $xml .= CString::Format('<%s>', $root_name);
            }
             
            foreach($array as $key => $value)
            {
                //we are dealing with a framework object
                if (is_object($value) && is_subclass_of($value, 'CObject'))
                {
                    $xml .= $value->ToXML($drill+1);
                }
                //we are dealing with some other object
                else if (is_object($value))
                {
                    $xml .= print_r ($value, true);
                }
                else if(!is_array($value))
                {
                    if (is_numeric($key))
                    $key = "element";
                    $temp = htmlentities($value);
                    if ($temp != $value)
                    {
                        //we will encode the stuff to have not special chracaters and screw up the XML
                        //also force encoding is there is CDATA segment present already
                        if ($wantencode || preg_match("/<\!\[CDATA\[/", $value))
                        $value = $temp;
                        else
                        {
                            $value = CString::Format('<![CDATA[%s]]>', 'details forcibly ignored, encoding required');//value
                        }
                    }
                    $xml .= CString::Format('<%s>%s</%s>', $key, $value, $key);
                }
                else
                {
                    if (is_numeric($key))
                    {
                        switch ($drill)
                        {
                            case 0:
                                $key = "collection";
                                break;
                            case 1:
                                $key = "row";
                                break;
                            default:
                                $key = "element";
                        }

                    }
                     
                    //check to see if this node has been visited (eliminate )
                    $unknown = true;
                    /*
                     for ($loop = 0; $loop < count($visited); $loop++)
                     {
                     if ($visited[$loop] == $value)
                     {
                     $unknown = false;
                     break;
                     }
                     }
                     */
                     

                    //this is a new node
                    if ($unknown)
                    {
                        $xml .= CString::Format('<%s>', $key);
                        $xml .= CCollection::ToXMLWorker($value, $root_name, $drill+1, $wantencode, $visited);
                        $visited[count($visited)] =& $value;
                        $xml .= CString::Format('</%s>', $key);
                    }
                    else
                    {
                        $xml .= CString::Format('<!-- The element (%s - %s) was already visited and will not be prepresented again -->', $key, $value);
                    }
                }
            }
            if (!$drill)
            {
                $xml .= CString::Format('</%s>', $root_name);
            }
             
            return $xml;
        }
        //an object was sent but is was not in an array
        else
        {
            $temp = array($array);
            return CCollection::ToXMLWorker($temp, $root_name, $drill, $wantencode, $visited);
        }
    }

}

/*
 *
 * Changelog:
 * $Log: class.collection.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2010-04-08 18:34:24  dkolev
 * Cleaned up the ToXML method
 *
 * Revision 1.8  2009-09-13 13:27:36  dkolev
 * Fixed the ToXML function
 *
 * Revision 1.7  2009-01-17 20:11:22  dkolev
 * Fixed a typo in the XML output
 *
 * Revision 1.6  2008/06/01 09:50:41  dkolev
 * Disabled XML representation of objects. Too many resources are being used.
 *
 * Revision 1.5  2007/06/15 17:13:37  dkolev
 * Properly attach another collection. Fixed a problem with XML preresentation
 *
 * Revision 1.4  2007/05/17 06:25:03  dkolev
 * Reflect C-names
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