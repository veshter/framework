<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.soapclient.php,v 1.2 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */




/**
 * @ignore
 * @package VESHTER
 */
class CSoapClientBase extends SoapClient
{
    function CSoapClientBase($wsdl)
    {
        @parent::SoapClient($wsdl
        , array("trace"=>1,"exceptions"=>1)
        );
    }
}

/**
 * SOAP client class based on PHP SOAP
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 */

class CSoapClient extends CGadget
{


    /**
     * @var CSoapClientBase
     * @ignore
     */
    protected $base;

    function CSoapClient($wsdl)
    {
        $this->SetVersion('$Revision: 1.2 $');

        $this->base = new CSoapClientBase($wsdl);
    }

    /**
     * @ignore
     */
    function __call($name, $arguments)
    {
        //if($this->base instanceof CSoapClientBase && method_exists($this->base, $name))
        {
            try
            {
                //record the request
                $this->Notify($this->base->__getLastRequest());

                $result = @call_user_func_array(array(&$this->base, $name), $arguments);

                //record the response
                $this->Notify($this->base->__getLastResponse());

                return $result;
            }
            catch (SoapFault $ex)
            {
                $this->Warn($this->base->__getLastResponse());
            }
        }
        //		else
        //		{
        //			throw new CExceptionNotImplemented(CString::Format('Method %s is not supported by this SOAP client', $name));
        //		}
        }


        //print_r($client->__getFunctions());
        //print_r($client->__getTypes());


        //print($client->__getLastRequest());
        //print($client->__getLastResponse());


}

/*
 *
 * Changelog:
 * $Log: class.soapclient.php,v $
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-05-26 03:40:35  dkolev
 * Initial import
 *
 * Revision 1.5  2007-09-27 00:05:14  dkolev
 * Inheritance changes
 *
 * Revision 1.4  2007/05/17 06:25:05  dkolev
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