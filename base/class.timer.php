<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.timer.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * Basic timer
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */
class CTimer extends CObject
{
    protected $time;
    protected $span;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Start()
    {
        $this->time = time();
        $this->span = 0;
    }

    function Stop()
    {
        $this->span = time() - $this->time;
    }

    function SetSpan($seconds)
    {
        $this->time = time() - $seconds;
    }

    function GetSpan()
    {
        $this->span = time() - $this->time;
        return $this->span;
    }

    function GetDays()
    {
        $span = $this->GetSpan();
        return floor($span/_DAY);
    }

    function GetHours()
    {
        $span = $this->GetSpan();
        $span -= $this->GetDays()*_DAY;
        return floor($span/_HOUR);
    }

    function GetMinutes()
    {
        $span = $this->GetSpan();
        $span -= $this->GetDays()*_DAY;
        $span -= $this->GetHours()*_HOUR;
        return floor($span/_MINUTE);
    }

    function GetSeconds()
    {
        $span = $this->GetSpan();

        return $span%_MINUTE;
    }

    function ToString ()
    {


        //$days = $span/_DAY;
        //$hours = ($span-$days*_DAY)/_HOUR;
        //$mins = ($span-$days*_DAY-$hours*_HOUR)/_MINUTE;
        //$secs = ($span-$days*_DAY-$hours*_HOUR-$mins*_MINUTE)%_SECOND;

        return CString::Format("%d days, %d hours, %d minutes, %d seconds (%d)", $this->GetDays(), $this->GetHours(), $this->GetMinutes(), $this->GetSeconds(), $this->GetSpan());
    }


}

/*
 *
 * Changelog:
 * $Log: class.timer.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2007-05-17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/02/28 10:05:15  dkolev
 * Initial import
 *
 *
 *
 *
 *
 */
?>