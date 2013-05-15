<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.status.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 *
 */

/**
 * @package VESHTER
 */

/**
 * @ignore
 */
define ("_STATUS_TYPE_INFO", 		"Information");
/**
 * @ignore
 */
define ("_STATUS_TYPE_WARNING", 	"Warning");

/**
 * @ignore
 */
define ("_STATUS_TYPE_ERROR", 		"Error");

/**
 * Status string container.
 *
 * Status messages are enqueued during execution and can be extracted through ToString
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 */
class CStatus extends CEntity
{
    /**
     * List of status messages
     *
     * @var array
     */
    protected $messages;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Add($msg, $type = _STATUS_TYPE_INFO)
    {
        $index = count($this->messages);
        $this->messages[$index]["id"] = $index;
        $this->messages[$index]["msg"] = $msg;
        $this->messages[$index]["type"] = $type;
        return true;
    }

    /**
     * Displays an error message.
     *
     * This method displays an error messages and stops the execution of the
     * script. This method is called exactly in the same way as the printf
     * function. The first argument contains the message and additional
     * arguments of various types may be passed to this method to be inserted
     * into the message.
     *
     * @param     string $message Error message to be displayed.
     */

    function Report ($message)
    {
        // Check whether more than one argument was given.
        if ( func_num_args() > 1 )
        {
            // Read all arguments.
            $arguments = func_get_args();
             
            // Create a new string for the inserting command.
            $command = "\$message = sprintf(\$message, ";
             
            // Run through the array of arguments.
            for ( $i = 1; $i < sizeof($arguments); $i++ )
            {
                // Add the number of the argument to the command.
                $command .= "\$arguments[".$i."], ";
            }
             
            // Replace the last separator.
            $command = eregi_replace(", $", ");", $command);
             
            // Execute the command.
            eval($command);
        }

        // Display the error message.
        print ("<p><b><!-- VESHTER -->" . $this->_ident() . "/" . $this->getVersion() ."</b>: " . $message);
    }

    /**
     * Gets the last status message
     *
     * @return string
     */
    function GetLastMessage()
    {
        if (count($this->messages) > 0)
        {
            return $this->messages[count($this->messages)-1];
        }
        return null;
    }

    /**
     * Return the last error message recorded
     *
     * @return string
     */
    function GetLastError()
    {
        for ($loop = count($this->messages)-1; $loop >= 0; $loop--)
        {
            if ($this->messages[$loop]['type'] == _STATUS_TYPE_WARNING)
            return $this->messages[$loop]['msg'];
        }
         
        return "No errors have been listed";
    }

    /**
     * Gets all status messages
     *
     * @return array
     */
    function GetMessages()
    {
        return $this->messages;
    }

    function Dump()
    {
        print $this->ToString();
    }

    /**
     * Produces and XML representation of the status queue
     *
     * @return unknown
     */
    function ToString()
    {
        /*$output = "";
         $c = 0;
         if (count($this->messages))
         foreach($this->messages as $message)
         {
         //$this->report ($message);
         $output .= sprintf("#%d.  %s: %s<br>\n", ($c+1), $message["type"], $message["msg"]);
         $c++;
         }


         return $output;
         */
        return CCollection::ToXMLWorker($this->messages, "status");

    }

}

/*
 *
 * Changelog:
 * $Log: class.status.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-11-12 05:11:59  dkolev
 * Added more accessors to get to status messages.
 *
 * Revision 1.5  2007/06/25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.4  2007/05/17 06:25:01  dkolev
 * Reflect C-names
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