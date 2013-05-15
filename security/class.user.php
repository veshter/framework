<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.user.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * User
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */
class CUser extends CObject
{

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function SetLogin($login)
    {
        $this->properties['login'] = $login;
    }

    function GetLogin()
    {
        return $this->properties['login'];
    }

    function SetPassword($password)
    {
        $this->properties['password'] = $password;
    }

    function GetPassword()
    {
        return $this->properties['password'];
    }

    function GetNameFirst()
    {
        return $this->properties['name_first'];
    }

    function SetNameFirst($name)
    {
        $this->properties['name_first'] = $name;
    }

    function GetNameMiddle()
    {
        return $this->properties['name_middle'];
    }

    function SetNameMiddle($name)
    {
        $this->properties['name_middle'] = $name;
    }

    function GetNameLast()
    {
        return $this->properties['name_last'];
    }

    function SetNameLast($name)
    {
        $this->properties['name_last'] = $name;
    }

    function GetEmail()
    {
        return $this->properties['email'];
    }

    function SetEmail($email)
    {
        $this->properties['email'] = $email;
    }

    function SetAccountStatus($status)
    {
        $this->properties['status'] = $status;
    }

    function GetAccountStatus()
    {
        $this->properties['status'];
    }

    function SetSuperuser($sudo)
    {
        $this->properties['is_su'] = strcasecmp($sudo, "yes") == 0;
    }

    function SetTimeZone($timezone)
    {
        $this->properties['timezone'] = $timezone;
    }

    function GetTimeZone()
    {
        return $this->properties['timezone'];
    }

    function IsSuperuser()
    {
        return $this->properties['is_su'];
    }

    function IsActive()
    {
        return strcasecmp($this->properties['status'], "active") == 0;
    }

    function SetProfileUrl($url)
    {
        $this->properties['profile'] = $url;
    }


}


/*
 *
 * Changelog:
 * $Log: class.user.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2009-04-06 03:42:46  dkolev
 * Added timezone
 *
 * Revision 1.5  2009-03-21 22:58:47  dkolev
 * Prepared for OpenID
 *
 * Revision 1.4  2008-05-18 12:11:39  dkolev
 * Changed from explicit member variables to storing to the object properties
 *
 * Revision 1.3  2007/06/25 01:09:53  dkolev
 * Altered the function headers to avoid clashes up the class tree.
 *
 * Revision 1.2  2007/05/17 13:51:21  dkolev
 * Documentation changes
 *
 *
 *
 */

?>