<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementweb.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web element
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

abstract class CElementWeb extends CElement
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function GetTitle()
    {
        return $this->GetAttribute('title');
    }

    function SetValue($value)
    {
        if ($this->GetAttribute('readonly') != "yes")
        {
            return $this->SetAttribute('value', $value);
        }
        return $this->GetValue();
    }

    function GetValue()
    {
        return $this->GetAttribute('value');
    }

     

}

/*
 *
 * Changelog:
 * $Log: class.elementweb.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-02-28 19:40:21  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.5  2007-06-15 17:10:42  dkolev
 * It used to be the case that we would use [key] as keys where PHP would try to look for a constant. Now the keys are all ['key']
 *
 * Revision 1.4  2007/05/17 06:24:58  dkolev
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
 */

?>