<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.address.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Provides basic address manipulation and validation
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */
class CAddress extends CObject
{

    protected $address;

    function __construct($address = '') 
    {
        parent::__construct();        
        $this->SetVersion('$Revision: 1.8 $');
        $this->SetAddress($address);
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function SetAddress ($address)
    {
        $this->address = $address;
        return true;
    }

    function GetAddress()
    {
        return $this->address;
    }

    function Validate()
    {
        return true;
    }
}

/*
 *
 * Changelog:
 * $Log: class.address.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-05-17 06:25:03  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/02/28 10:10:19  dkolev
 * Added GetAddress
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>