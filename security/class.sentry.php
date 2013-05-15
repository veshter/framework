<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.sentry.php,v 1.15 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Security Sentry object that watches security of a realm or area
 *
 * @version $Revision: 1.15 $
 * @package VESHTER
 *
 */
class CSentry extends CGadget
{
    /**
     * Workgroup which allows all access, even if a person is not logged in
     */
    public static $workgroup_unrestrictedaccess = 'any';

    /**
     * Regardless of whether user user is logged in or not, access will be denied
     */
    public static $workgroup_noaccess = 'none';

    /**
     * Location where this object last was in.
     * Used in returning to the object or for statistics
     *
     * @var string location
     */
    private $location;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.15 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Checks current user access to a workgroup
     *
     * @param unknown_type $workgroup
     */
    function ValidateAccess($guid, $workgroup)
    {
        //all access is allowed to this workgroup
        if ($workgroup == CSentry::$workgroup_unrestrictedaccess)
        {
            return true;
        }
        //no access
        else if ($workgroup == CSentry::$workgroup_noaccess)
        {
            throw new CExceptionSecurity(CString::Format('The %s workgroup does not allow any access', CSentry::$workgroup_noaccess));
        }
        //configurable access
        else
        {
            $c = 0;
            //go through all available datagrids and see if you can get the information
            while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
            {
                //are we able to get information about this user?
                return ($datagrid->Select('profile_privilege
  									left join profile on profile_privilege.profile = profile.guid
  									left join workgroup on profile_privilege.workgroup = workgroup.guid', 
                array('profile_privilege.guid', 'profile_privilege.canread', 'profile_privilege.canwrite'),
                CString::Format('profile.guid=%s and workgroup.codename=%s',
                CString::Quote($guid),
                CString::Quote($workgroup)

                )
                ));
                $c++;
            }

            CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
        }
    }

    function GetCurrentUser()
    {
        $session = CEnvironment::GetMainApplication()->GetSession();

        $token = $session->GetRegisteredVariable('token', null, true);

        $handshake = $token['handshake'];

        //CEnvironment::Dump($blah = array($token['checksum'],sha1(serialize($handshake))));

        //check is the information has been tampered with
        if ($token['checksum'] == sha1(serialize($handshake)))
        {
            //CEnvironment::Dump($token);
            return $this->GetActiveUser($handshake['guid'], $handshake['chunk'], $handshake['timestamp']);
        }
        return null;
    }

    /**
     * Validates the currently logged in user
     *
     * @return CUser valid user value
     */
    function GetActiveUser($guid, $chunk, $timestamp)
    {
        $c = 0;
        //go through all available datagrids and see if you can get the information
        while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
        {
            //are we able to get information about this user?
            if ($datagrid->Select('profile',
            array('guid','su','name_first','name_last','email','status'),
            CString::Format('guid=%s AND secure_chunk=%s AND last_login=%s AND logged_in=%s',
            CString::Quote($guid),
            CString::Quote($chunk),
            CString::Quote($timestamp),
            CString::Quote('yes')
            )
            ))
            {
                $row = $datagrid->GetRow();

                $user = new CUser();

                $user->SetGuid($row['guid']);
                $user->SetNameFirst($row['name_first']);
                $user->SetNameMiddle($row['name_middle']);
                $user->SetNameLast($row['name_last']);
                $user->SetEmail($row['email']);
                $user->SetAccountStatus($row['status']);

                $user->SetSuperuser($row['su']);

                //customize the application to fit the user's requested timezone if any
                $timezone = $user->GetTimeZone();
                if (!CString::IsNullOrEmpty($timezone))
                {
                    CDateTime::SetTimeZone($timezone);
                }

                return $user;

            }

            $c++;

        }
        return null;
    }



    /**
     * Logs in a user but does not check any access rights
     * Assumes the user's credendials have already been checked.
     *
     * @param string $login
     * @param CUser $user
     * @return bool
     */
    function LoginActiveUser($login, $user = null)
    {
        $this->LogoutActiveUser();



        $c = 0;
        //go through all available datagrids and see if you can get the information
        while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
        {
            //are we able to get information about this user?
            if ($datagrid->Select('profile',array('guid','status'),
            CString::Format('login=%s',	CString::Quote($login))
            ))
            {

                $data = $datagrid->GetRow(false);
                $guid = $data['guid'];
                $chunk = CGuid::NewGuid()->ToString();
                $timestamp = time();

                switch($data['status'])
                {
                    case 'active':
                        //update the user table so that the current login is reflected
                        $keys = array('secure_chunk', 'last_login', 'logged_in');
                        $values = array($chunk, $timestamp, 'yes');

                        //specific user information was also given, use that to update the profile
                        if ($user)
                        {
                            $keys = array_merge($keys, array('name_first', 'name_middle', 'name_last', 'email'));
                            $values = array_merge($values, array($user->GetNameFirst(), $user->GetNameMiddle(), $user->GetNameLast(), $user->GetEmail()));
                        }

                        if ($datagrid->Update('profile', $keys, $values, CString::Format('guid=%s',  CString::Quote($guid))))
                        {
                            $session = CEnvironment::GetMainApplication()->GetSession();

                            $token = array();
                            $handshake = array();
                            $handshake['guid'] = $guid;
                            $handshake['chunk'] = $chunk;
                            $handshake['timestamp'] = $timestamp;

                            $token['handshake'] = $handshake;
                            $token['checksum'] = sha1(serialize($handshake));

                            $session->RegisterVariable('token', $token, true);
                            return true;
                        }
                    case 'new':
                        throw new CExceptionSecurity(CString::Format('Account for %s has not been activated', $login));
                    case 'pending':
                        throw new CExceptionSecurity(CString::Format('Account for %s is pending activation', $login));
                    case 'suspended':
                        throw new CExceptionSecurity(CString::Format('Account for %s has been suspended', $login));
                    default:
                        throw new CExceptionSecurity(CString::Format('Account for %s has invalid status of %s', $login, $data['status']));
                }

            }
            else
            {
                $this->Notify(CString::Format('User %s was not found', $login));
            }
            $c++;
        }


        return false;
    }

    function LogoutActiveUser()
    {
        $session = CEnvironment::GetMainApplication()->GetSession();

        $token = $session->GetRegisteredVariable('token', null, true);

        $handshake = $token['handshake'];

        //CEnvironment::Dump($blah = array($token['checksum'],sha1(serialize($handshake))));

        //check is the information has been tampered with
        if ($token['checksum'] == sha1(serialize($handshake)))
        {
            $guid = $handshake['guid'];

            $c = 0;
            //go through all available datagrids and see if you can get the information
            while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
            {
                if ($datagrid->Update('profile', 'logged_in', 'no', CString::Format('guid=%s',  CString::Quote($guid))))
                {
                }
                $c++;
            }

            $session->Destroy();
        }
    }

    function LogOutAllIdleUsers($idletime)
    {
        throw new CExceptionNotImplemented();

    }
}

/*
 *
 * Changelog:
 * $Log: class.sentry.php,v $
 * Revision 1.15  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.14.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.14  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.13  2009-06-15 03:43:45  dkolev
 * User guids are now properly set to guids not ids
 *
 * Revision 1.12  2009-04-06 04:22:18  dkolev
 * Added timezone and user logout
 *
 * Revision 1.11  2009-04-06 03:43:13  dkolev
 * Improved the handshake mechanics and made the sentry more strict
 *
 * Revision 1.10  2009-03-30 05:10:05  dkolev
 * Added status checking to the LoginActiveUser function
 *
 * Revision 1.9  2009-03-21 22:58:47  dkolev
 * Prepared for OpenID
 *
 * Revision 1.8  2008-05-13 18:36:07  dkolev
 * Reflected a database table column name change
 *
 * Revision 1.7  2007/10/08 19:16:51  dkolev
 * Implemented access verification
 *
 * Revision 1.6  2007/09/27 00:21:12  dkolev
 * Inheritance changes
 *
 * Revision 1.5  2007/06/25 01:09:53  dkolev
 * Altered the function headers to avoid clashes up the class tree.
 *
 * Revision 1.4  2007/05/17 06:25:02  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>