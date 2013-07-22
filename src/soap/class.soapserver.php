<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.soapserver.php,v 1.2 2010-07-04 18:32:39 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * @ignore
 * @package VESHTER
 */
class CSoapServerBase extends SoapServer
{
    function CSoapServerBase($wsdl)
    {
        @parent::SoapServer($wsdl);
    }
}

/**
 * SOAP server class based on PHP SOAP
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 */

class CSoapServer extends CGadget
{

    /**
     * @var CSoapServerBase
     * @ignore
     */
    protected $base;

    function CSoapServer($wsdl)
    {
        $this->SetVersion('$Revision: 1.2 $');

        $this->base = new CSoapServerBase($wsdl);
    }

    function Attach($class)
    {
        if (!class_exists($class, false))
        {
            throw new CExceptionNotFound(CString::Format('Cannot attach to class %s because it is not found', $class));
        }

        $this->base->setClass($class);
    }

    function Handle()
    {
        $this->base->handle();
    }

    //TODO: Implement persistence
}

/*
 *
 * Changelog:
 * $Log: class.soapserver.php,v $
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