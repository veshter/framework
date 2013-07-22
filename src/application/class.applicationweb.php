<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.applicationweb.php,v 1.18 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Basic Web application
 *
 * @version $Revision: 1.18 $
 * @package VESHTER
 *
 */
class CApplicationWeb extends CApplication
{

    /**
     * Security sentry
     *
     * @var CSentry
     */
    protected $sentry;

    /**
     * Session for the application
     *
     * @var CSession
     */
    protected $session;

    /**
     * Current user for the application
     *
     * @var CUser
     */
    protected $user;

    /**
     * Workgroups of who is able to get to this appliction
     *
     * @var CParserString
     */
    protected $workgroups;
   
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.18 $');

        $this->Notify("Creating session");
        $this->session = new CSession();
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }    

    function Localize()
    {
        if (parent::Localize())
        {
            //the site requires SSL forward to the SSL address
            if (($this->properties['site']['forcessl'] == 'yes') && !CEnvironment::GetServerSSL())
            {
                header(CString::Format('location: https://%s/%s', CEnvironment::GetHttpHost(), CEnvironment::GetRequestUri()));
                exit;
            }


            //create a security sentry to uphold workgroup policies
            $this->Notify("Creating sentry");
            $this->sentry = new CSentry();

            //tell the local scripts where to get custom files from
            $pwd = !empty($this->properties['pwd']) ? $this->properties['pwd'] : '../';
            $this->Notify(CString::Format('Setting working path to %s', $pwd));
            $this->SetWorkingPath($pwd);

            //set the type of application utilization
            $type = !empty($this->properties['type']) ? $this->properties['type'] : 'any';
            $this->Notify(CString::Format('Setting application type to %s', $type));
            $this->SetType($type);

            //set the approrpiate default timezone
            CDateTime::SetTimeZone($this->properties['site']['timezone']);

            return true;
        }
        return false;
    }

    function SetType($type)
    {
        parent::SetType($type);

        if ($type != CSentry::$workgroup_unrestrictedaccess)
        {
            //check if used has necessary priviliges
            $this->RestrictAccess($type);

            $this->ValidateAccess();
        }
    }

    function RestrictAccess($workgroups)
    {
        //localize on the active user on the site
        $this->Notify("Localizing on the current");
        $this->user = $this->sentry->GetCurrentUser();

        if (empty($this->user))
        {
            throw new CExceptionTimeOut('No user found or session expired');
        }

        //set workgroups access for the application
        $this->SetWorkgroups($workgroups);
    }

    /**
     * Validates user access to the requested application resource
     * @return bool True will only be returned if current user has access
     * @throws CExceptionSecurity, CExceptionTimeOut
     */
    function ValidateAccess()
    {

        if (empty($this->user))
        {
            throw new CExceptionTimeOut('No user found or session expired');
        }

        //super user doesn't have to be part of any workgroups
        if ($this->user->IsSuperuser())
        {
            //CEventLog::Log($this->_ident(), 'Validate Access', 'Super user granted', 'Info', 1, 'Unknown');
        }
        else
        {
            $org = $this->workgroups->GetNodes();

            //traverse the possibly passed in workgroups and test for access.
            //if any of the workgroups works, grant access
            foreach ($org as $domain)
            {
                //CEnvironment::WriteLine('Domain: ' . $domain);
                foreach ($domain as $subdomain)
                {
                    //CEnvironment::WriteLine('Sub-Domain: ' . $subdomain);
                    foreach ($subdomain as $workgroup)
                    {
                        //CEnvironment::WriteLine('Workgroup: ' . $workgroup);

                        //this entity has access to the said resource
                        if ($this->sentry->ValidateAccess($this->user->GetGuid(), $workgroup))
                        {
                            return true;
                        }
                    }
                }
            }
            throw new CExceptionSecurity(CString::Format('Access to entity with id %s is denied', $this->user->GetId()));
        }
    }

    /**
     * Gets the sentry for the appliction
     *
     * @return CSentry
     */
    function &GetSentry()
    {
        return $this->sentry;
    }

    /**
     * Gets the session in the application
     *
     * @return CSession
     */
    function &GetSession()
    {
        return $this->session;
    }

    /**
     * Gets the current user
     *
     * @return CUser Current user
     */
    function &GetUser()
    {
        return $this->user;
    }

    /**
     * Gets the current workgroups for the appliction
     *
     * @return CParserString
     */
    function &GetWorkgroups()
    {
        return $this->workgroups;
    }

    function SetWorkgroups($workgroups)
    {
        $this->workgroups = new CParserString();

        $this->workgroups->Parse($workgroups);
    }

    function HookEventHandlers()
    {
        //TODO: broken in 5.3, fix it
        /*
        //handle errors
        $this->Notify('Setting error handler');
        $handler_error = new CHandlerError();
        set_error_handler(array($handler_error, "Handle"), E_ALL ^ E_NOTICE ^ E_WARNING);

        //handle exceptions
        $this->Notify('Setting exception handler');
        $handler_exception = new CHandlerException();
        set_exception_handler(array($handler_exception, "Handle"));

        //handle performance issues
        $this->Notify('Initializing performance janitor');
        $janitor = new CJanitorPerf();
        register_shutdown_function(array($janitor, 'ShutDown'));
        */
    }
}

/*
 *
 * Changelog:
 * $Log: class.applicationweb.php,v $
 * Revision 1.18  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.17.4.3  2012-09-07 19:17:42  dkolev
 * Fixes due to PHP 5.3
 *
 * Revision 1.17.4.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.17.4.1  2011-06-06 14:54:12  dkolev
 * Removed redundant LoadConfiguration method
 *
 * Revision 1.17  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.16  2009-09-13 13:22:39  dkolev
 * Added force SSL option
 *
 * Revision 1.15  2009-06-15 03:44:53  dkolev
 * Proper validation with user guids not ids.
 *
 * Revision 1.14  2009-04-14 12:00:06  dkolev
 * Moved setting of the timezone from LoadConfiguration to Localize
 *
 * Revision 1.13  2009-04-06 03:47:26  dkolev
 * Added timezone
 *
 * Revision 1.12  2009-03-29 20:59:35  dkolev
 * Formatting changes
 *
 * Revision 1.11  2009-03-21 23:00:09  dkolev
 * Consolidated functionality from a child class
 *
 * Revision 1.10  2008-04-08 01:20:30  dkolev
 * Disabled some logging
 *
 * Revision 1.9  2007/10/08 19:20:06  dkolev
 * Implemented access verification
 *
 * Revision 1.8  2007/09/27 00:11:30  dkolev
 * Changes to security validation
 *
 * Revision 1.7  2007/06/25 00:59:55  dkolev
 * Added commenting
 *
 * Revision 1.6  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/03/14 08:12:15  dkolev
 * Removed unused functions
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 * Revision 1.2  2007/02/25 23:36:57  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>