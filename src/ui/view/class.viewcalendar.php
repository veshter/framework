<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.viewcalendar.php,v 1.6 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Calendar view
 *
 * @version $Revision: 1.6 $
 * @package VESHTER
 */
class CViewCalendar extends CView
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.6 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function Configure(&$xml, $merge = true)
    {
        if (parent::Configure($xml, $merge))
        {

            $specs = $this->config->GetElement("/view[1]");

            $this->properties['key_timestamp_begin'] = $specs['attributes']['key_timestamp_begin'];
            $this->properties['key_timestamp_end'] = $specs['attributes']['key_timestamp_end'];

            $this->properties['key_recurrence'] = $specs['attributes']['key_recurrence'];
            $this->properties['key_hour_begin'] = $specs['attributes']['key_hour_begin'];
            $this->properties['key_hour_end'] = $specs['attributes']['key_hour_end'];
            $this->properties['key_minute_begin'] = $specs['attributes']['key_minute_begin'];
            $this->properties['key_minute_end']  = $specs['attributes']['key_minute_end'];

            $this->properties['template'] = !empty($specs['attributes']['template']) ? $specs['attributes']['template'] : "block.calendar.generic";
            $this->properties['template_embedded'] = !empty($specs['attributes']['template_embedded']) ? $specs['attributes']['template_embedded'] : "block.calendar.generic.day";
            $this->properties['prefix_template_element'] = !empty($specs['attributes']['prefix_template_element']) ? $specs['attributes']['prefix_template_element'] : "block.calendar.element";


            return true;
        }
        return false;
    }


    /**
     * Gets the contents for a particular day in the month. Ignored any hour or minute content when analysing timestamps
     * @param $month
     * @param $day
     * @param $year
     * @return unknown_type
     */
    private function GetDayContent($month, $day, $year)
    {
        $hasRecurrenceKey = !CString::IsNullOrEmpty($this->properties['key_recurrence']);
        $hasTimestampKey = !CString::IsNullOrEmpty($this->properties['key_timestamp_begin']);

        if (!$hasTimestampKey && !$hasRecurrenceKey)
        {
            $this->Warn('No date restictions found');
            return null;
        }

        $hour = $minute = $second = 0;

        $timestamp_begin = mktime($hour,$minute,$second, $month, $day, $year);
        $timestamp_end = $timestamp_begin + _DAY - _SECOND;

        $data = array();
        if (count($this->data))
        {
            foreach ($this->data as $row)
            {

                if ($hasTimestampKey)
                {
                    //make sure the end date is after the begin date
                    //if the end date is before or equals the before date, the end date is reset.
                    if ($row[$this->properties['key_timestamp_begin']] >= $row[$this->properties['key_timestamp_end']])
                    {
                        $row[$this->properties['key_timestamp_end']] = null;

                    }

                    $event_timestamp_begin = $row[$this->properties['key_timestamp_begin']];
                    $event_timestamp_end = $row[$this->properties['key_timestamp_end']];
                    	
                    $state = CString::Format('Range: %s - %s; Event: %s - %s; Difference: %d - %d',
                    date('c', $timestamp_begin),
                    date('c', $timestamp_end),
                    date('c', $event_timestamp_begin),
                    !CString::IsNullOrEmpty($event_timestamp_end) ? date('c', $event_timestamp_end) : 'N/A',
                    $timestamp_begin - $event_timestamp_begin,
                    !CString::IsNullOrEmpty($event_timestamp_end) ? $timestamp_end - $event_timestamp_end : 'N/A'
					
                    );
                    	
                    //CEnvironment::WriteLine($state);
                    	
                    $this->Notify($state);

                    //CEnvironment::WriteLine($state);
                    	

                    if (!CString::IsNullOrEmpty($event_timestamp_begin) &&
                    !CString::IsNullOrEmpty($event_timestamp_end))
                    {
                        //we don't care to show the event if _both_ start and end date are not today

                        //check range of dates
                        if (($event_timestamp_begin < $timestamp_begin) && ($event_timestamp_end < $timestamp_begin))
                        {
                            $this->Warn('Rejected due to timestamp span beginning and ending before current segment');
                            continue;
                        }
                        else if (($event_timestamp_begin > $timestamp_end) && ($event_timestamp_end > $timestamp_end))
                        {
                            $this->Warn('Rejected due to timestamp span beginning and ending after current segment');
                            continue;
                        }
                        else
                        {
                            $this->Notify('Accepted due to timestamp instant restriction within current segment');
                        }
                    }

                    //check explicitely
                    else if (!CString::IsNullOrEmpty($event_timestamp_begin))
                    {
                        if (($event_timestamp_begin >= $timestamp_begin) && ($event_timestamp_begin <= $timestamp_end))
                        {
                            $this->Notify('Accepted due to timestamp instant restriction');
                        }
                        else
                        {
                            $this->Warn('Rejected due to timestamp instant restriction');
                            continue;
                        }
                    }
                }

                //check recurrence
                if ($hasRecurrenceKey)
                {

                    if (!CString::IsNullOrEmpty($row[$this->properties['key_recurrence']]))
                    {
                        $parser = new CParserCron();

                        if ($parser->Parse($row[$this->properties['key_recurrence']], $month, $year))
                        {

                            if ($parser->IsInRange($hour,$minute,$second, $month, $day, $year))
                            {
                                $this->Warn('Accepted due to recurrence restriction: ' . $row[$this->properties['key_recurrence']] . ' encloses ' . "$hour, $minute, $second, $month, $day, $year");
                            }
                            else
                            {
                                $this->Warn('Rejected due to recurrence restriction: ' . $row[$this->properties['key_recurrence']] . ' does not enclose ' . "$hour, $minute, $second, $month, $day, $year");
                                continue;
                            }
                        }
                    }
                }

                $data[] = $row;
            }
        }

        //check for times
        $doc = new CDocument($this->properties['template_embedded']);


        $timespan = array();
        $timespan['timestamp'] = $timestamp_begin;
        $timespan['month'] = $month;
        $timespan['year'] = $year;

        $doc->MergeField('timespan', $timespan);

        $doc->MergeBlock('events', $data);

        return $doc->ToString();

    }

    function ToString($year = null, $month = 1)
    {
        $month = CEnvironment::GetSubmittedVariable('month', date('n'));
        $year = CEnvironment::GetSubmittedVariable('year', date('Y'));

        if ($month == 0)
        {
            $month = 12;
            $year--;
        }
        else if ($month == 13)
        {
            $month = 1;
            $year++;
        }

        $timestamp_begin = mktime (0,0,0, $month, 1, $year);
        $timestamp_end = mktime (0,0,0, $month+1, 1, $year) - _SECOND;

        $daysInTheMonth   = date('t', $timestamp_begin);

        $specs = $this->config->GetElement("/view[1]");

        //see if we should look up the options from somewhere
        if ($this->perspective != null)
        {
            $helper = $this->perspective;

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            if (!$datagrid)
            {
                throw new CExceptionNotInitialized("No available datagrids found");
            }

            if (!CString::IsNullOrEmpty($this->properties['key_timestamp_end']))
            {
                $helper->SetWhere(CString::Format('(%s) AND (((%s != 0) AND (%s <= %d) AND (%s >= %d)) OR ((%s = 0) AND (%s >= %d) AND (%s <= %d)))',
                $helper->GetWhere(),
                $this->properties['key_timestamp_end'],
                $this->properties['key_timestamp_begin'], CString::Escape($timestamp_end),
                $this->properties['key_timestamp_end'], CString::Escape($timestamp_begin),
                $this->properties['key_timestamp_end'],
                $this->properties['key_timestamp_begin'], CString::Escape($timestamp_begin),
                $this->properties['key_timestamp_begin'], CString::Escape($timestamp_end)

                )
                );
            }
            else
            {
                $helper->SetWhere(CString::Format('(%s) AND ((%s >= %d) AND (%s <= %d))',
                $helper->GetWhere(),
                $this->properties['key_timestamp_begin'], CString::Escape($timestamp_begin),
                $this->properties['key_timestamp_begin'], CString::Escape($timestamp_end)

                )
                );
            }

            //look up existing data
            if ($datagrid->Select($helper->GetLocation(), $helper->GetKeys(), $helper->GetWhere(), $helper->GetLimit(), $helper->GetOrderBy(), $helper->GetGroupBy()))
            {
                //great, we have some data
                $this->data = $datagrid->Get(false);
            }
            //CEnvironment::Dump($datagrid->GetQuery());
            //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
            	

        }


        $lookups = array();

        if (count($this->data))
        {
            $this->FillFields();
        }
        else
        {
            $this->Warn('No data was found or specified');
        }

        $doc = new CDocument($this->properties['template']);

        //option shift in week alignment ex. Monday first,Sat Sun first etc
        $columnShift = 0;

        $date_info = getdate($timestamp_begin);
        $offset = $date_info['wday']+$columnShift;
        $day = 1;

        $headers = array();
        for ($loop = -1*$offset+1; $loop < 7-$offset+1; $loop++)
        {
            $headers[] = array('title' => date('l', mktime (0,0,0, $month, $loop, $year)));
        }

        $data = array();

        //buffer the correct number of places so weekdays and days match
        for ($loop = 0; $loop < $offset; $loop++)
        {
            $data[$loop]['timestamp'] = '';
            $data[$loop]['day'] = '';
            $data[$loop]['content'] = '<!-- blank cell -->';
        }

        //fill in the days with any content
        for ($loop = $day; $loop < $daysInTheMonth+1; $loop++)
        {
            $data[$loop+$offset]['timestamp'] = mktime (0,0,0, $month, $loop, $year);
            $data[$loop+$offset]['day'] = $loop;
            $data[$loop+$offset]['content'] = $this->GetDayContent($month, $loop, $year);
        }

        //CEnvironment::Dump($this->GetStatus());

        $timespan = array();
        $timespan['timestamp'] = $timestamp_begin;
        $timespan['month'] = $month;
        $timespan['year'] = $year;

        $doc->MergeField('timespan', $timespan);

        $doc->MergeBlock('headers', $headers);

        $doc->MergeBlock('rows', $data);


        return $doc->ToString();
    }
}

/*
 *
 * Changelog:
 * $Log: class.viewcalendar.php,v $
 * Revision 1.6  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.5.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.5  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.4  2010-04-05 03:11:07  dkolev
 * Class reorganization
 *
 * Revision 1.3  2010-03-01 09:13:35  dkolev
 * Added documentation
 *
 * Revision 1.2  2010-03-01 04:33:08  dkolev
 * Fixed some range issues
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.3  2009-11-15 01:11:38  dkolev
 * Fixed end range to be inclusive.
 *
 * Revision 1.2  2009-09-25 04:00:58  dkolev
 * Fixed a bug where when beginning and end date are the same, the event won't show.
 *
 * Revision 1.1  2009-09-13 13:30:52  dkolev
 * Initial import
 *
 */

?>