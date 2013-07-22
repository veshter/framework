<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.guid.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * A globally unique identifier (GUID) is used for unique IDs.
 * No two GUIDs are the same no matter what computer they were generated on.
 *
 * @version $Revision: 1.11 $
 * @package VESHTER
 *
 */

class CGuid extends CData
{

    protected $content;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.11 $');

        list($usec, $sec) = explode(" ", microtime());
        $temp = CString::Format("%s-%s-%d-%f-%s", CEnvironment::GetServerAddressPublic(), CEnvironment::GetServerPort(), $sec, $usec, uniqid());

        $this->content = md5($temp);
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * String representation of a GUID.
     *
     * %s-%s-%s-%s-%s will produce 79C8EA28-43BE-A36A-0DF4-CB68ECF1D31F
     *
     * @param string $format String to format the GUID by
     * @return string
     */
    function ToString($format = "")
    {
        $raw = strtoupper($this->content);
        if (!empty($format))
        {
            return CString::Format($format, substr($raw,0,8), substr($raw,8,4), substr($raw,12,4), substr($raw,16,4), substr($raw,20));
        }
        return $raw;
    }

    /**
     * Creates and returns a new GUID
     * @return CGuid
     */
    static function NewGuid()
    {
        return new CGuid();
    }

}

/*
 *
 * Changelog:
 * $Log: class.guid.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2008-03-11 19:11:21  dkolev
 * Removed a nasty space after the closing PHP tag
 *
 * Revision 1.8  2008/01/28 21:28:24  dkolev
 * Added documentation for the NewGuid function.
 *
 * Revision 1.7  2008/01/12 04:07:59  dkolev
 * Added NewGuid static function
 *
 * Revision 1.6  2007/06/25 01:05:40  dkolev
 * Removed incorrect GUID example
 *
 * Revision 1.5  2007/06/15 17:15:04  dkolev
 * Code formatting changes
 *
 * Revision 1.4  2007/06/06 05:26:20  dkolev
 * Added additional formatting
 *
 * Revision 1.3  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.2  2007/04/16 10:52:00  dkolev
 * Removed erronous CVS comments
 *
 * Revision 1.1  2007/03/14 07:36:39  dkolev
 * Initial import
 *
 *
 *
 *
 *
 *
 */

?>