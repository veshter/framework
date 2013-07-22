<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.session.php,v 1.14 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic session management class
 *
 * The session module cannot guarantee that the information you store in a session is
 * only viewed by the user who created the session. You need to take additional measures
 * to actively protect the integrity of the session, depending on the value associated with it.
 *
 * Assess the importance of the data carried by your sessions and deploy additional
 * protections -- this usually comes at a price, reduced convenience for the user.
 *
 * For example, if you want to protect users from simple social engineering tactics,
 * you need to enable session.use_only_cookies. In that case, cookies must be enabled
 * unconditionally on the user side, or sessions will not work.
 *
 * There are several ways to leak an existing session id to third parties. A leaked session
 * id enables the third party to access all resources which are associated with a specific id.
 * First, URLs carrying session ids. If you link to an external site, the URL including the session
 * id might be stored in the external site's referrer logs. Second, a more active attacker might
 * listen to your network traffic. If it is not encrypted, session ids will flow in plain text over
 * the network. The solution here is to implement SSL on your server and make it mandatory for users.
 *
 * @version $Revision: 1.14 $
 * @package VESHTER
 *
 *
 */
class CSession extends CObject
{
    /**
     * CSession name
     *
     * @var string Session name
     */
    private $name;

    /**
     * Session ID
     *
     * @var string Session ID
     */

    private $SID;

    function __construct($name = 'DYNVENIXSESSID') 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.14 $'); 
        
        $this->name = $name;
        session_name($name);
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function Start ()
    {
        if(empty($this->SID))
        {
            session_start();
            //TODO: Why is this messing everything up???????????????????
            //session_regenerate_id();
            $this->SID = session_id();
        }
        return $this->GetSessionId();
    }

    protected function GenerateVariableName($var_name, $is_global)
    {
        if ($is_global)
        {
            $key = CString::Format('VESHTER:%s:%s', CEnvironment::GetServerName(), $var_name);
            $this->Notify(CString::Format('Generating global key equal to %s', $key));

        }
        else
        {
            $key = CString::Format("VESHTER:%s%s:%s:%s", CEnvironment::GetServerName(), CEnvironment::GetScriptVirtualName(), CEnvironment::GetContext(), $var_name);
            $this->Notify(CString::Format('Generating local key equal to %s', $key));
        }
        	
        	
        //encrypt the key using sha1 encryption in case anyone gets a hold the session variables
        return sha1($key);
    }

    function RegisterVariable ($var_name, $var_value, $is_global = false)
    {
        if (empty($this->SID))
        {
            $this->Start();
            //throw new CExceptionNotInitialized("Session was not initilized");
        }

        //get rid of the old value
        $this->UnregisterVariable($var_name);


        if ($var_value == "null")
        {
            $this->Notify(CString::Format("Variable %s was unset on purpose", $var_name));
        }
        //register the variable if it isn't empty
        else if(!empty($var_value))
        {
            $this->Notify(CString::Format('Registering session variable %s (%s) with value %s', $var_name, $this->GenerateVariableName($var_name, $is_global), $var_value));

            return $_SESSION[$this->GenerateVariableName($var_name, $is_global)] = $var_value;

        }

        else
        {
            $this->Warn(CString::Format("Cannot register %d because it is empty", $var_name));
        }
        return false;

    }

    function UnregisterVariable($var_name, $is_global = false)
    {
        if (empty($this->SID))
        {
            $this->Start();
            //throw new CExceptionNotInitialized("Session was not initilized");
        }

        if (!empty($this->SID))
        {
            $this->Notify(CString::Format('Unregistering session variable %s (%s)', $var_name, $this->GenerateVariableName($var_name, $is_global)));
            unset($_SESSION[$this->GenerateVariableName($var_name, $is_global)]);
            return true;
        }
        return false;
    }

    function GetRegisteredVariable($name, $default = null, $is_global = false)
    {
        if (empty($this->SID))
        {
            $this->Start();
            //throw new CExceptionNotInitialized("Session was not initilized");
        }

        $nameWithScope = $this->GenerateVariableName($name, $is_global);



        if (array_key_exists ($nameWithScope, $_SESSION))
        {
            $this->Notify(CString::Format('Current value for %s (%s) is %s', $name, $nameWithScope, $_SESSION[$nameWithScope]));
            return $_SESSION[$nameWithScope];
        }
        else
        {
            $this->Warn(CString::Format('Current value for %s (%s) was not found', $name, $nameWithScope));
        }

        //we could not determine anything, return the default value
        return $default;

    }

    function GetSessionId()
    {
        if (empty($this->SID))
        {
            $this->Start();
            //throw new CExceptionNotInitialized("Session was not initilized");
        }
        return $this->SID;
        //return CString::Format("%s.%s", $this->name, $this->SID);
    }

    function GetName()
    {
        return $this->name;
    }

    function Destroy ()
    {
        if (!empty($this->name))
        {
            @session_destroy();
            //session_unset();
            //unset($_SESSION);
        }

    }
}


/*
 *
 * Changelog:
 * $Log: class.session.php,v $
 * Revision 1.14  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.13.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.13  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.12  2009-09-13 13:29:43  dkolev
 * Improved session variable storage
 *
 * Revision 1.11  2008-08-18 08:32:10  dkolev
 * *** empty log message ***
 *
 * Revision 1.10  2007/12/10 03:10:05  dkolev
 * Removed session_regenerate_id because it was causing problems with session handling. WIll investigate at a later date.
 *
 * Revision 1.9  2007/11/25 10:20:54  dkolev
 * Changed session generation method
 *
 * Revision 1.8  2007/09/27 00:21:39  dkolev
 * Change most session management
 *
 * Revision 1.7  2007/06/25 01:09:53  dkolev
 * Altered the function headers to avoid clashes up the class tree.
 *
 * Revision 1.6  2007/06/15 17:25:55  dkolev
 * It used to be the case that we would use [key] as keys where PHP would try to look for a constant. Now the keys are all ['key']
 *
 * Revision 1.5  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>