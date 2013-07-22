<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.parsercron.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Cron schedule parser
 *
 * 0 | .--------- minute (0-60)
 * 1 | | .------- hour (0-24)
 * 2 | | | .----- day of the month (1-31)
 * 3 | | | | .--- month (1-12 or jan-dec)
 * 4 | | | | | .- day of the week (0-6 or sun-sat)
 * 5 | | | | | | .- command to execute (not used by parser)
 * | | | | | | |
 * * * * * * * *
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 *
 */

class CParserCron extends CParser
{
    private $minutes;
    private $hours;
    private $days;
    private $months;
    private $weekdays;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    public function Parse($content, $month, $year)
    {
        if (!empty($content))
        {
            $content = preg_replace('/[\s]{2,}/', ' ', $content);

            if (preg_match('/[^-,* \\d]/', $content) !== 0)
            {
                $this->Warn("Cron String contains invalid character");
                return false;
            }

            $this->Notify("<b>Working on cron schedule: $content</b>");
            $this->nodes = @explode(" ", $content);

            if (count($this->nodes) != 5)
            {
                $this->Warn("Cron string is invalid. Too many or too little sections after explode");
                return false;
            }

            $this->ExpandMinutes(); // 0
            $this->ExpandHours(); // 1
            $this->ExpandDays($month, $year); // 2
            $this->ExpandMonths(); // 3
            $this->ExpandWeekdays(); // 4

            return true;
        }
        return false;
    }

    public function GetMinutes()
    {
        return $this->minutes;
    }

    public function GetHours()
    {
        return $this->hours;
    }

    public function GetDays()
    {
        return $this->days;
    }

    public function GetMonths()
    {
        return $this->months;
    }

    public function GetWeekdays()
    {
        return $this->weekdays;
    }

    private function ExpandRange($str)
    {
        if (strstr($str,  ","))
        {
            $arParts = explode(',', $str);
            foreach ($arParts AS $part)
            {
                if (strstr($part, '-'))
                {
                    $arRange = explode('-', $part);
                    for ($i = $arRange[0]; $i <= $arRange[1]; $i++)
                    {
                        $ret[] = $i;
                    }
                }
                else
                {
                    $ret[] = $part;
                }
            }
        }
        elseif (strstr($str,  '-'))
        {
            $arRange = explode('-', $str);
            for ($i = $arRange[0]; $i <= $arRange[1]; $i++)
            {
                $ret[] = $i;
            }
        }
        else
        {
            $ret[] = $str;
        }
        $ret = array_unique($ret);
        sort($ret);
        return $ret;
    }

    private function RemoveDuplicates ($arr, $low, $high)
    {
        $count = count($arr);
        for ($i = 0; $i <= ($count - 1); $i++)
        {
            if ($arr[$i] < $low)
            {
                $this->Notify("Remove out of range element. {$arr[$i]} is outside $low - $high");
                unset($arr[$i]);
            }
            else
            {
                break;
            }
        }

        for ($i = ($count - 1); $i >= 0; $i--)
        {
            if ($arr[$i] > $high)
            {
                $this->Notify("Remove out of range element. {$arr[$i]} is outside $low - $high");
                unset ($arr[$i]);
            }
            else
            {
                break;
            }
        }

        //re-assign keys
        sort($arr);
        return $arr;
    }

    private function ExpandMinutes()
    {
        $minutes = array();

        if ($this->nodes[0] == '*')
        {
            for ($i = 0; $i <= 60; $i++)
            {
                $minutes[] = $i;
            }
        }
        else
        {
            $minutes = $this->ExpandRange($this->nodes[0]);
            $minutes = $this->RemoveDuplicates($minutes, 0, 59);
        }
        $this->Notify("Minutes array" . print_r($minutes, true));
        $this->minutes = $minutes;
    }

    private function ExpandHours()
    {
        $hours = array();

        if ($this->nodes[1] == '*')
        {
            for ($i = 0; $i <= 23; $i++)
            {
                $hours[] = $i;
            }
        }
        else
        {
            $hours = $this->ExpandRange($this->nodes[1]);
            $hours = $this->RemoveDuplicates($hours, 0, 23);
        }

        $this->Notify("Hour array" . print_r($hours, true));
        $this->hours = $hours;
    }

    /**
     * Given a month/year, list all the days within that month fell into the week days list.
     */
    private function ExpandDays($month, $year)
    {
        $days = array();


        $daysinmonth = date('t', mktime(0, 0, 0, $month, 1, $year));

        //return everyday of the month if both bit[2] and bit[4] are '*'
        if ($this->nodes[2] == '*' AND $this->nodes[4] == '*')
        {
            	
            $this->Notify("Number of days in $year-$month : $daysinmonth");
            $days = array();
            for ($i = 1; $i <= $daysinmonth; $i++)
            {
                $days[] = $i;
            }
        }
        else
        {
            //create an array for the weekdays
            if ($this->nodes[4] == '*')
            {
                for ($i = 0; $i <= 6; $i++)
                {
                    $arWeekdays[] = $i;
                }
            }
            else
            {
                $arWeekdays = $this->ExpandRange($this->nodes[4]);
                $arWeekdays = $this->RemoveDuplicates($arWeekdays, 0, 7);

                //map 7 to 0, both represents Sunday. Array is sorted already!
                if (in_array(7, $arWeekdays))
                {
                    if (in_array(0, $arWeekdays))
                    {
                        array_pop($arWeekdays);
                    }
                    else
                    {
                        $tmp[] = 0;
                        array_pop($arWeekdays);
                        $arWeekdays = array_merge($tmp, $arWeekdays);
                    }
                }
            }
            $this->Notify("Array for the weekdays" . print_r($arWeekdays, true));

            if ($this->nodes[2] == '*')
            {
                $daysmonth = $this->ExpandRange(CString::Format('1-%d', $daysinmonth));
            }
            else
            {
                $daysmonth = $this->ExpandRange($this->nodes[2]);
                // so that we do not end up with 31 of Feb
                $daysmonth = $this->RemoveDuplicates($daysmonth, 1, $daysinmonth);
            }

            //Now match these days with weekdays
            //var_dump($daysmonth);
            foreach ($daysmonth as $day)
            {
                $wkday = date('w', mktime(0, 0, 0, $month, $day, $year));
                if (in_array($wkday, $arWeekdays))
                {
                    $days[] = $day;
                }
            }
        }
        $this->Notify("Days array matching weekdays for $year-$month". print_r($days, true));
        $this->days = $days;
    }

    private function ExpandMonths()
    {
        $months = array();
        if ($this->nodes[3] == '*')
        {
            for ($i = 1; $i <= 12; $i++)
            {
                $months[] = $i;
            }
        }
        else
        {
            $months = $this->ExpandRange($this->nodes[3]);
            $months = $this->RemoveDuplicates($months, 1, 12);
        }
        $this->Notify("Months array" . print_r($months, true));
        $this->months = $months;
    }

    private function ExpandWeekdays()
    {
        $days = array();

        if ($this->nodes[4] == '*')
        {
            for ($i = 0; $i <= 6; $i++)
            {
                $days[] = $i;
            }
        }
        else
        {
            $days = $this->ExpandRange($this->nodes[4]);
            $days = $this->RemoveDuplicates($days, 0, 6);
        }

        $this->Notify("Days array" . print_r($days, true));
        $this->weekdays = $days;
    }

    /**
     *
     * @param $hour
     * @param $minute
     * @param $second Ignored
     * @param $month
     * @param $day
     * @param $year
     * @return boolean
     */
    public function IsInRange($hour,$minute,$second, $month, $day, $year)
    {
        $usable = true;

        if (!in_array($hour, $this->hours))
        {
            //CEnvironment::WriteLine('Failed on hour: ' . $hour);
            $usable = false;
        }
        if (!in_array($minute, $this->minutes))
        {
            //CEnvironment::WriteLine('Failed on minute: ' . $minute);
            $usable = false;
        }

        //skipping second as it is not specified by the schedule definition

        if (!in_array($month, $this->months))
        {
            //CEnvironment::WriteLine('Failed on month: ' . $month);
            $usable = false;
        }
        if (!in_array($day, $this->days))
        {
            //CEnvironment::WriteLine('Failed on day: ' . $day);
            $usable = false;
        }

        //skipping year as it is not specified by the schedule definition

        $weekday = date('w', mktime($hour,$minute,$second, $month, $day, $year));
        if (!in_array($weekday, $this->weekdays))
        {
            //CEnvironment::WriteLine('Failed on weekday: ' . $weekday);
            $usable = false;
        }

        return $usable;
    }

}


/*
 *
 * Changelog:
 * $Log: class.parsercron.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2010-03-01 09:13:35  dkolev
 * Added documentation
 *
 * Revision 1.2  2009-09-13 13:28:29  dkolev
 * Formatting changes and added IsInRange function
 *
 */

?>