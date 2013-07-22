<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.daemon.php,v 1.12 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * A program that runs unattended to perform continuous or periodic systemwide functions, such as network control.
 *
 * @version $Revision: 1.12 $
 * @package VESHTER
 *
 */
abstract class CDaemon extends CGadget
{
    /**
     * Daemon timer
     *
     * @var CTimer
     */

    protected $timer;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.12 $');

        $this->timer = new CTimer();
        $this->timer->Start();
    }

    function __destruct()
    {
        parent::__destruct();
    }


    function StartTimer()
    {
        $this->timer->Start();
    }

    /**
     * Gets the timer for the daemon. The time should be started beforehand to be useful
     *
     * @return CTimer
     */
    function GetTimer()
    {
        return $this->timer;
    }

    function StopTimer()
    {
        $this->timer->Stop();
    }

    /**
     * Suspends timeout monitoring, allowing the daemon to complete execution
     * This function should only be used when daemon have no predictable elapsed time.
     *
     */
    function SuspendTimeout()
    {
        set_time_limit(0);
    }

    /**
     * Returns the results of demon execution
     *
     * @return mixed Execution results
     */
    function Execute()
    {
        throw new CExceptionNotImplemented();
    }

    /**
     * Resumes timeout monitoring, allowing the daemon to die if it takes to long to complete execution
     *
     * @param int $seconds
     */
    function ResumeTimeout($seconds = 30)
    {
        set_time_limit($seconds);
    }

    static function Write($activity, $format = 'text')
    {
        if (!CString::IsNullOrEmpty($activity))
        {
            CEnvironment::Write($activity);
            //ob_flush();
            flush();
        }

    }

    static function WriteLine($activity, $format = 'text')
    {
        $nl = "\n";

        switch ($format)
        {
            case 'html':
                $nl = '<br>';
            default:
                break;
        }

        CDaemon::Write(CString::Format("%s%s", $activity, $nl));
    }
}

/*
 *
 * Changelog:
 * $Log: class.daemon.php,v $
 * Revision 1.12  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.11.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.11  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.10  2010-04-05 03:10:26  dkolev
 * Not flushing when there is no activity
 *
 * Revision 1.9  2008-03-25 19:08:34  dkolev
 * Changes to newline printing and other formatting
 *
 * Revision 1.8  2007/10/08 19:18:41  dkolev
 * Added a constructor
 *
 * Revision 1.7  2007/09/27 00:05:34  dkolev
 * Inheritance changes
 *
 * Revision 1.6  2007/06/15 17:15:34  dkolev
 * Added default timeout of 30 seconds
 *
 * Revision 1.5  2007/05/24 01:07:30  dkolev
 * Added Execute function
 *
 * Revision 1.4  2007/05/17 06:25:05  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/03/14 08:07:11  dkolev
 * Added timout suspension to allow long executions
 *
 * Revision 1.2  2007/02/28 10:09:52  dkolev
 * Adder Write and WriteLine as well as a CTimer
 *
 * Revision 1.1  2007/02/28 00:50:19  dkolev
 * Initial import
 *
 *
 *
 *
 *
 */
?>
