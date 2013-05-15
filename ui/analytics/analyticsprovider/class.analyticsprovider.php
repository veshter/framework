<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.analyticsprovider.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Generic identity provider
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

abstract class CAnalyticsProvider extends CGadget
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
    
    public function __call($name,$parameters)
    {
        if(!preg_match('/^Get/',$name))
        {
            throw new Exception('No such function "' . $name . '"');
        }

        $name = preg_replace('/^Get/','',$name);

        return $this->GetMetric($name);
    }

    public function GetMetric($name)
    {
        throw new CExceptionNotImplemented();
    }
}
?>