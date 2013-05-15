<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.janitorperf.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Performance janitor. Makes use of timers
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */

class CJanitorPerf extends CJanitor
{
    /**
     * Timer that keeps track of performance
     * @var CTimer
     */
    private $timer;

    function __construct($assignment = '')
    {
        parent::__construct($assignment);
        $this->SetVersion('$Revision: 1.4 $');

        //check to see if server is too buzy to handle request
        $this->PreRunTest();
         
        $this->timer = new CTimer();
        $this->timer->Start();
        
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Test run before execution
     */
    function PreRunTest()
    {

    }

    /**
     * Test run after execution
     */
    function PostRunTest()
    {

    }

    function GetStatistics()
    {

        $stats['memory'] = memory_get_usage();
        $stats['time'] = $this->timer->GetSeconds();
        //$stats['backtrace'] = debug_backtrace();

        return CCollection::ToXMLWorker($stats, 'statistics');

    }

    function LogStats()
    {
        $stats = $this->GetStatistics();
        CEventLog::Log('Gui.Generic', 'Script Stats', $stats, 'Info');
    }

    function ShutDown()
    {
        //TODO: Enable stats
        //$this->LogStats();
        $this->timer->Stop();

        $this->PostRunTest();

        exit;
    }

}

?>