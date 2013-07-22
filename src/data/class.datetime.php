<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.datetime.php,v 1.12 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Date & time class for easier data/time manipulations
 *
 * @see http://us2.php.net/date for formats
 *
 * @version $Revision: 1.12 $
 * @package VESHTER
 *
 */
class CDateTime extends CData
{
    private static $timezone;

    /**
     * Timestamp of the datetime
     *
     * @var int
     */
    protected $timestamp;

    /**
     * Creates a datetime.
     * Throws an exception is no timezone is defined.
     *
     * @param long $timestamp
     * @param string $format
     */
    function __construct($timestamp = null)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.12 $');

        //make sure there is a time zone and we are NOT relying on the server's settings
        if (CString::IsNullOrEmpty(CDateTime::$timezone))
        {
            CDateTime::$timezone = 'America/New_York';
        }

        if (empty($timestamp))
        {
            $this->timestamp = time();
        }
        else
        {
            $this->timestamp = $timestamp;
        }
    }

    function __destruct()
    {
        parent::__destruct();
    }

    static function SetTimeZone($timezone)
    {
        if (CString::IsNullOrEmpty($timezone))
        {
            throw new CExceptionInvalidData('No timezone specified');
        }

        if (date_default_timezone_set($timezone))
        {
            CDateTime::$timezone = $timezone;
        }
        else
        {
            throw new CExceptionInvalidFormat(CString::Format('Timezone %s is invalid', $timezone));
        }

    }

    static function SetTimeZoneByOffset($offset)
    {
        foreach ($abbrarray as $abbr)
        {
            foreach ($abbr as $city)
            {
                if ($city['offset'] == $offset)
                {
                    // remember to multiply $offset by -1 if you're getting it from js
                    CDateTime::SetTimeZone($city['timezone_id']);
                    return;
                }
            }
        }

        throw new CExceptionInvalidFormat('No timezone was found');
    }

    static function GetTimeZone()
    {
        return CDateTime::$timezone;
    }

    function GetTimeStamp()
    {
        return $this->timestamp;
    }

    /**
     * Returns a date object that has the current timestamp
     *
     * @return CDateTime Current timestamp
     */
    static function Now()
    {
        return new CDateTime();
    }


    function GetMonth()
    {
        return date('m', $this->timestamp);
    }

    function GetYear()
    {
        return date('Y', $this->timestamp);
    }

    /**
     * Formats the datetime in the desired format
     *
     * @param string $format
     * @return string
     */
    function ToString($format = "c")
    {
        return date($format, $this->timestamp);
    }

}

/*
 *
 * Changelog:
 * $Log: class.datetime.php,v $
 * Revision 1.12  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.11.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.11  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.10  2009-04-14 11:58:47  dkolev
 * Throw exception when the timezone string is empty
 *
 * Revision 1.9  2009-04-06 03:43:28  dkolev
 * Added timezone
 *
 * Revision 1.8  2008-01-05 22:57:14  dkolev
 * Added Month and Year access functions
 *
 * Revision 1.7  2007/06/15 17:12:34  dkolev
 * Removed embedded format string
 *
 * Revision 1.6  2007/05/17 14:21:26  dkolev
 * Fixed improper inheritance
 *
 * Revision 1.5  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/04/28 08:00:52  dkolev
 * Changed the class to be more intuitive
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