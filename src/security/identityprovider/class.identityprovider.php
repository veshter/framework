<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.identityprovider.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Generic identity provider
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */

abstract class CIdentityProvider extends CGadget
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
}
?>