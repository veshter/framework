<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.eventlog.php,v 1.9 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * EventLog is a class that logs events to a database
 *
 * @version $Revision: 1.9 $
 * @package VESHTER
 * @todo Finish email stuff
 */
abstract class CEventLog extends CObject
{

    /**
     * Maximum severity level below which nothing will be logged
     */
    static public $severity = 5;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.9 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }


    /**
     * This function attempts to log the event to the database
     *
     * @param string $source The name of the entity that initiated logging
     * @param string $title The title of the message that will be logged
     * @param string $msg The message that will be logged
     * @param string $type The type of event
     * @param int $severity Integer priority of the event (1 is highest)
     * @param string $user The user the triggered the event
     * @param boolean $sendemail If true, the curator will be emailed the event
     * @return boolean
     */
    static function Log($source, $title, $msg, $type, $severity = 5, $user = "", $sendemail = false)
    {

        // If we get an error force the severity to 1
        if($type == "Error")
        {
            $severity = 1;
        }
        // Preliminary check of severity level
        if($severity > CEventLog::$severity)
        {
            return false;
        }

        // Get the DataGrid
        $datagrid = CEnvironment::GetMainApplication()->GetDataGrid();

        // Construct the arrays of keys and values
        $keys = array('guid', 'source', 'name', 'about', 'type', 'severity', 'daydate', 'user');
        $values = array(CGuid::NewGuid()->ToString(), $source, $title, $msg, $type, $severity, CDateTime::Now()->GetTimeStamp(), $user);

        // Call the Insert command
        // If the Insert is succesful
        if($datagrid->Insert('log', $keys, $values))
        {
            //all good ;-)
        }
        // Else warn the system and send an email to curator
        else
        {
            $to = CEnvironment::GetCuratorEmail();
            // Get the necessary variables
            $from = $to;
             
            //			$address_to = new CAddressEmail($to);
            //			// Test for valid emails
            //			if(!$address_to->Validate())
            //			{
            //				 throw new CExceptionNotConfigured("Curator email is invalid");
            //			}
             
            $mail = new CMail();
             
            $mail->SetFrom($from);
            $mail->AddTo($to);
            $mail->SetContentType('text/xml');
            $mail->SetSubject("Datagrid Error");
            $message = $datagrid->GetDatabaseLink()->GetStatus()->ToString();
            $mail->SetBody($message);
            $mail->Send();

            return false;
        }

        // Now if we need to email the Curator
        if($sendemail)
        {
            $to = CEnvironment::GetCuratorEmail();
            // Get the necessary variables
            $from = $to;
             
            $address_to = new CAddressEmail($to);
            // Test for valid emails
            if(!$address_to->Validate())
            {
                throw new CExceptionNotConfigured("Curator email is invalid");
            }
             
            // Do the thing if everything's good
            else
            {
                // Create Mail
                $mail = new CMail();

                $mail->SetFrom($from);
                $mail->AddTo($to);
                $mail->SetContentType('text/xml');
                $mail->SetSubject(CString::Format('%s: %s', $type, $title));

                $data = array(
							'source' => $source, 
							'title' => $title, 
							'message' => $msg, 
							'type' => $type, 
							'severity' => $severity,
                 
                //additional information
							'headers' => getallheaders(), 
							'stacktrace' => debug_backtrace());				

                $message = CCollection::ToXMLWorker($data, "event");

                $mail->SetBody($message);
                $mail->Send();
            }
        }

        // If everything succeeded
        return true;
    }
}

/*
 *
 * Changelog:
 * $Log: class.eventlog.php,v $
 * Revision 1.9  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.8.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.8  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.7  2009-09-20 02:44:40  dkolev
 * Fixed documentation
 *
 * Revision 1.6  2008-08-18 08:28:45  dkolev
 * Fixed up the event log class to write to v2 log table
 *
 * Revision 1.5  2008/01/28 21:27:59  dkolev
 * Validation of curator email address removed.
 *
 * Revision 1.4  2007/09/27 00:09:20  dkolev
 * Moved hardcoded table name to a static variable
 *
 * Revision 1.3  2007/06/25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.2  2007/06/15 17:29:33  dkolev
 * Minor changes
 *
 * Revision 1.1  2007/05/19 19:37:22  dkolev
 * Initial import
 *
 * Revision 1.3  2007/05/19 07:26:42  adrozdetski
 * Changed all the framework references to the appropriate ones ("C-")
 *
 * Revision 1.2  2007/05/19 07:19:33  adrozdetski
 * Added several features and capabilities
 *
 * Revision 1.1  2007/05/02 16:52:29  adrozdetski
 * The EventLog class in its final stages of preproduction
 *
 *
 */

?>