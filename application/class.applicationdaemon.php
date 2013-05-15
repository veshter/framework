<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.applicationdaemon.php,v 1.9 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Daemon application
 *
 * @version $Revision: 1.9 $
 * @package VESHTER
 *
 */
class CApplicationDaemon extends CApplication
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.9 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
    function Localize()
    {
        if (parent::Localize())
        {
             
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
}


/*
 *
 * Changelog:
 * $Log: class.applicationdaemon.php,v $
 * Revision 1.9  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.8.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.8  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.7  2009-12-05 21:40:06  dkolev
 * Added localization
 *
 * Revision 1.6  2009-11-15 00:53:57  dkolev
 * Made the class non-abstract
 *
 * Revision 1.5  2007-10-08 19:20:20  dkolev
 * Added a constructor
 *
 * Revision 1.4  2007/10/02 05:52:54  dkolev
 * Inheritance change
 *
 * Revision 1.3  2007/08/08 11:02:36  dkolev
 * Inheritance change
 *
 * Revision 1.2  2007/05/17 13:51:21  dkolev
 * Documentation changes
 *
 *
 *
 */
?>