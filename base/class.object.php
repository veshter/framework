<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.object.php,v 1.20 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * An object is an entity that combines descriptions of data and behavior.
 *
 * It has some error reporting and other functions that are useful/common to all derived classes
 *
 * @version $Revision: 1.20 $
 * @package VESHTER
 */
abstract class CObject extends CMolecule
{
    /**
     * Status holder for the object
     *
     * @var CStatus
     */
    protected $status;

    /**
     * List of registed callback functions
     *
     * @var array
     */
    protected $callbacks;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.20 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function GetId()
    {
        return $this->properties['id'];
    }

    function SetId($id)
    {
        $this->properties['id'] = $id;
    }

    /**
     * Gets the guid for the atom
     * This is the preferred way to address VESHTER entities
     *
     * @return CGuid
     */
    function GetGuid()
    {
        return $this->properties['guid'];
    }

    /**
     * Sets the guid for the atom
     * This is the preferred way to address VESHTER entities
     *
     * @param CGuid $guid
     */
    function SetGuid($guid)
    {
        $this->properties['guid'] = $guid;
    }

    /**
     * Enable or disable debugging functionality
     *
     */
    function EnableDebugging($enable = true)
    {
        $this->properties['debug'] = $enable;
        if ($enable)
        {
            CEnvironment::WriteLine('Debugging enabled. All status information will be stored and displayed');
        }

    }

    function GetDebugging ()
    {
        return $this->properties['debug'];
    }

    /**
     * Adds an information message to the status of the object
     *
     * @param string $message The message that will be appended to the status container
     */

    function Notify ($message)
    {
        if (!isset($this->status))
        {
            $this->status = new CStatus();
        }
        $this->status->Add($message, _STATUS_TYPE_INFO);

        if (CEnvironment::GetDebugging())
        {
            CEnvironment::WriteLine(CString::Format('%s %s %s: %s', CDateTime::Now()->ToString(), $this->_ident(), _STATUS_TYPE_INFO, $message));
        }
    }

    /**
     * Used when something is used that was declared depreciated
     * Normally this call is placed the a constructor of the depreciated class
     * When this class is user, the Notification will be triggered.
     *
     * Staff reaction to this notification will ensure clean and upto date code
     *
     * @param string message Message that will be sent to maintenance people
     *
     */
    function NotifyDepreciated($message = "")
    {

        $subject = "A depreciated class/function has been used in " .  _BASEHREF;
        $array = array ("message" => $message, "steps" => debug_backtrace());
        $body = CCollection::ToXMLWorker($array, "backtrace");

        $mail = new CMail();
        $mail->SetFrom(CEnvironment::GetCuratorEmail(), "Framework Notifier");
        $mail->AddTo("webmaster@veshter.com");
        $mail->SetSubject($subject);
        $mail->SetBody($body);

        $mail->Send();
    }



    /**
     * Adds an warning message to the status of the object but doesn't kill the app
     *
     * @param string $message The message that will be appended to the status container
     */
    function Warn ($message)
    {
        if (!isset($this->status))
        {
            $this->status = new CStatus();
        }
        $this->status->Add($message, _STATUS_TYPE_WARNING);

        //CEnvironment::WriteLine("<!-- _STATUS_TYPE_WARNING:$message -->" );
    }


    /**
     * Adds an error message to the status of the object, reports the error and kills the app
     *
     * @param string $message The message that will be appended to the status container
     */
    function Kill($message)
    {
        if (!isset($this->status))
        {
            $this->status = new CStatus();
        }
        $this->status->Add($message, _STATUS_TYPE_ERROR);
         
        print ($this->GetStatus()->ToString());
        exit;
         

    }

    /**
     * Returns the last error message recorded
     *
     * @see CStatus
     * @return string
     */
    function GetLastError()
    {
        //no status was defined
        if (!isset($this->status))
        return "No status information available";
        return $this->status->GetLastError();
    }

    /**
     * Returns all status information recorded about the object
     *
     * @return CStatus
     */
    function GetStatus()
    {
        //$ident = sprintf ("<!-- Status information for $this (%s) -->\n", $this->_ident());
        //
        ////no status was defined
        //if (!isset($this->status))
        //	return $ident . "<!-- No status information available -->\n";
        //return $ident . $this->status->ToString();

        return $this->status;
    }

    function ClearStatus()
    {
        $this->status = new CStatus();
    }

    /*
     If you need to call just function with parameters:
     call_user_func_array('Foo',$args);

     If you need to call CLASS method (NOT object):
     call_user_func_array(array('class', 'Foo'),$args);

     If you need to call OBJECT method:
     call_user_func_array(array(&$CObject, 'Foo'),$args);

     If you need to call method of object of object:
     call_user_func_array(array(&$CObject->CObject, 'Foo'),$args);

     If you need to call object method from within the very same object (NOT CLASS!):
     call_user_func_array(array(&$this, 'Foo'),args);

     The call_user_func_array ITSELF can manage any number and any kind of parameters. It can handle ANY FUNCTION too as it is defined and that maybe partipaq wanted to manage.

     What You actually need is object composition not inheritance. Make an instance from arguments.
     */

    /**
     * Determines if the callback requested is actually registered/valid for the object
     *
     * @param unknown_type $callback
     * @return unknown
     */
    function ValidateCallback($callback)
    {
        if ($this->callbacks != null)
        {
            return $this->callbacks[$callback];
        }
        return false;
    }

    /**
     * Assign the callback into the array.
     * This function can be used to reassign existing call backs and replace them with more custom or flexible ones.
     *
     * Only member class functions in parent or children classes are allowed to be callbacks
     *
     * @param string $method
     * @param string $callback
     * @return boolean
     */

    function RegisterCallback($callback, $method)
    {
        if(is_callable(array(&$this, $method)))
        {
            $this->callbacks[$callback] = $method;
            return true;
        }
        /*
         else if (is_array($callback) && is_object($obj = &$callback[0]))
         {
         if(array_key_exists($callback[1],get_object_vars($obj)))
         {
         set_array_key($callback_name, $callback, &$this->callbacks);
         return true;
         }
         $this->Error("Invalid callback for the callback '<i>".$callback_name."</i>'. The property '<i>".$callback[1]."</i>' is not exists in object '<i>".$callback[0]."</i>'.");
         return false;
         }
         else if (is_string($callback))
         {
         set_array_key($callback_name, $callback, &$this->callbacks);
         return true;
         }
         */
        $this->Kill(sprintf("Invalid callback type for callback <i>%s</i>. Function cannot be executed in the requested context", $callback));
        return false;
    }

    /**
     * Executing the callback given by name, and returns its result;
     *
     * @param string $callback the name of callback
     * @param array $args the array of arguments, which will be transferred to the callback
     * @return mixed
     */
    /*

    //First - we must create the objects, which we will use as a 'callback' elements
    $example_callback =new example_callback;


    //We assign a CObject->method() as a callback
    $nwCallback->AssignCallback("examples.example1.method1",array(&$example_callback,"method1"));

    //Again...
    $nwCallback->AssignCallback("examples.example1.method2",array(&$example_callback,"method2"));


    //Assign the CObject->Property as a callback (property is exists)
    $nwCallback->AssignCallback("examples.example1.test",array(&$example_callback,"test"));

    //Assign the sample string
    $nwCallback->AssignCallback("examples.strings.string1","This is 'string1'<br />");

    //Assign the existent function (strtoupper() and getenv() in this example)
    $nwCallback->AssignCallback("php.strtoupper","strtoupper");
    $nwCallback->AssignCallback("php.getenv","getenv");

    //Trying to call this functions...
    //We can transfer the parameters to the function in the second argument.
    //NOTE: Function will receive only one parameter (that type in which it's transferred),
    echo $nwCallback->Execute("php.getenv","REMOTE_ADDR")."<br />";
    echo $nwCallback->Execute("php.strtoupper","strtoupper-ed string")."<br />";

    //Executing the example1::method1() with parameters (sample 'echo' of the parameter)
    $nwCallback->Execute("examples.example1.method1","If you see this, this means, that the string <i>'examples.example1.method1'</i> is the callback <b>example1::method1()</b> trigger.<br />");

    //This callback(example1::method2) will set the new property, given in second argument (array(property_name=>property_value))
    //(It makes a example1::method2(), but not my class:)
    $nwCallback->Execute("examples.example1.method2",array("some_dynamical_property"=>"Property 'example1::some_new_property' is now exists"));

    //So, let's check, that the property example1::some_dynamical_property is exists...
    //We adding the CObject->Property
    $nwCallback->AssignCallback("examples.example1.some_new_property",array(&$example_callback,"some_new_property"));

    //Echo..
    echo $nwCallback->Execute("examples.example1.some_new_property")."<br />"; //

    //Printing the example string was added at the top of script
    echo $nwCallback->Execute("examples.strings.string1");

    ##############################################################################################################
    function &ref_call_user_func_array($callable, $args)
    {
    if(is_scalar($callable))
    {
    // $callable is the name of a function
    $call = $callable;
    }
    else
    {
    if(is_object($callable[0]))
    {
    // $callable is an object and a method name
    $call = "\$callable[0]->{$callable[1]}";
    }
    else
    {
    // $callable is a class name and a static method
    $call = "{$callable[0]}::{$callable[1]}";
    }
    }

    // Note because the keys in $args might be strings
    // we do this in a slightly round about way.
    $argumentString = array();
    $argumentKeys = array_keys($args);
    foreach($argumentKeys as $argK)
    {
    $argumentString[] = "\$args[$argumentKeys[$argK]]";
    }
    $argumentString = implode($argumentString, ', ');
    // Note also that eval doesn't return references, so we
    // work around it in this way...
    eval("\$result =& {$call}({$argumentString});");
    return $result;
    }


    */
    function ExecuteCallback($callback, $parameters = null)
    {
        if (count ($this->callbacks))
        {
            $method = $this->ValidateCallback($callback);
            if (!empty($method))
            {
                //only allow member class functions to be callbacks
                return @call_user_func_array(array(&$this, $method), $parameters);
            }
            throw new CExceptionNotImplemented(sprintf("Invalid callback name for executing. The callback <i>%s</i> does not exist.", $callback));

        }
        throw new CExceptionNotImplemented("No callbacks defined for object");
        //return false;
    }

    /**
     * Executes a callback linked list and continues execution until callback list is exhausted.
     * If the specified callback returns a callback reference, the next callback will be executed.
     * Otherwise, the result will be returned to the calling function.
     *
     * @return mixed
     */
    function Execute($callback)
    {
        $output = new CCallbackResult(null, $callback);
         
        while ($output instanceof CCallbackResult)
        {
            //CEnvironment::WriteLine(CString::Format('Running callback %s', $do));
            //CEnvironment::Dump($output);
             
            //run the requested callback
            $output = $this->ExecuteCallback($output->GetNextCallback(), $output->GetValues());

            //if the call didn't succeed nag some about it
            if (!$output)
            {
                $output = sprintf ("Callback %s was not found or failed to execute successfully", $do);
                $this->Warn($output);
            }
        }
         
        //return whatever was generated
        return $output;

    }

    /**
     * Returns a string representation of the default execution
     * @return string
     */
    function ToString()
   	{
   	    //get the requested activity/callback from any posted information
   	    $do = CEnvironment::GetSubmittedVariable('do', 'edit');

   	    return $this->Execute($do);
   	}
}


/*
 *
 * Changelog:
 * $Log: class.object.php,v $
 * Revision 1.20  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.19.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.19  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.18  2009-06-15 03:44:34  dkolev
 * Moved $id, $guid, $debug to be properties
 *
 * Revision 1.17  2009-05-26 03:39:24  dkolev
 * Suppressed errors from call_user_func_array
 *
 * Revision 1.16  2009-02-03 07:46:51  dkolev
 * *** empty log message ***
 *
 * Revision 1.15  2008/08/18 08:29:33  dkolev
 * Based on the CEnvironment's debugging settings, an object may printout every notification sent to a derived class.
 *
 * Revision 1.14  2008/04/28 07:32:08  dkolev
 * Added an Execute function. Made ToString pull Execute
 *
 * Revision 1.13  2008/04/08 01:21:12  dkolev
 * Fixed the Kill function to correctly print status messages
 *
 * Revision 1.12  2008/02/03 10:19:15  dkolev
 * Objects return true when the default Initialize function is called.
 *
 * Revision 1.11  2007/11/12 05:13:51  dkolev
 * Changed the way GetStatus behaves. Now it returns the status array as opposed to generating a string representation of it.
 *
 * Revision 1.10  2007/10/08 19:19:23  dkolev
 * Removed some unreachable code and fixed typos
 *
 * Revision 1.9  2007/09/27 00:10:33  dkolev
 * Commenting changes
 *
 * Revision 1.7  2007/06/15 17:19:18  dkolev
 * *** empty log message ***
 *
 * Revision 1.6  2007/06/06 15:28:05  dkolev
 * Added guids and ids to object
 *
 * Revision 1.5  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 10:08:28  dkolev
 * Added ClearStatus
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