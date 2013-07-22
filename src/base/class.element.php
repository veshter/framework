<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.element.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic VESHTER element.
 *
 * This class can be used as a the base of HTML/XML and other elements.
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

abstract class CElement extends CGadget
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


    /**
     * Set an element attribute value
     *
     * @param string $name Name of the new/modified attribute
     * @param mix $value The value of the attribute
     */
    function SetAttribute($name, $value)
    {

        $this->SetProperty($name, $value);
    }

    /**
     * Gets the value of a currently assigned attribute
     *
     * @param string $name Name of the attribute requested
     * @return mixed The value of the attribute
     */
    function GetAttribute($name)
    {
        return $this->GetProperty($name);
    }

    /**
     * Indicates whether the element can support options
     *
     * @return boolean
     */
    function SupportsOptions()
    {
        return false;
    }

    /**
     * @throws CExceptionNotImplemented
     *
     */
    function SetOptions($options)
    {
        throw new CExceptionNotImplemented(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    /**
     * @throws CExceptionNotImplemented
     *
     */
    function GetOptions()
    {
        throw new CExceptionNotImplemented(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    function ToString()
    {
        $template = $this->GetAttribute('template');
        if (!empty($template))
        {
            $doc = new CDocument($template);

            if ($this->SupportsOptions())
            {
                $options = $this->GetOptions();

                //CEnvironment::Dump($options);

                $value = $this->GetValue();

                if (!is_array($value))
                {
                    $value = array($value);
                }
                //make sure that an option that applies to current value is selected
                for ($loop = 0; $loop < count($options); $loop++)
                {
                    if (in_array($options[$loop]['value'], $value))
                    {
                        $options[$loop]['selected'] = 'yes';
                    }

                }

                //CEnvironment::Dump($value);

                //merge options
                if ($doc->MergeBlock('options', 'array', $options) > 0)
                {

                }
            }

            //merge tabs if necessary
            if ($doc->MergeField('attributes', $this->properties) > 0)
            {

            }
            else
            {
                $this->Notify("Element not necessary or failed to create");
                 
            }
            //var_dump($doc);
        }
        else
        {
            $this->Warn("The element does not have a template defined");
        }

        return $doc->ToString();
    }
}

/*
 *
 * Changelog:
 * $Log: class.element.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.13  2009-05-29 01:22:53  dkolev
 * *** empty log message ***
 *
 * Revision 1.12  2009-05-28 20:15:10  dkolev
 * Removed direct access to properties. Instead using Property functions.
 *
 * Revision 1.11  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.10  2007/11/25 10:21:34  dkolev
 * Added a way to look values up from arrays and simple variables
 *
 * Revision 1.9  2007/09/27 00:14:12  dkolev
 * Inheritance changes
 *
 * Revision 1.8  2007/06/15 17:11:03  dkolev
 * It used to be the case that we would use [key] as keys where PHP would try to look for a constant. Now the keys are all ['key']
 *
 * Revision 1.7  2007/05/17 06:24:58  dkolev
 * Reflect C-names
 *
 * Revision 1.6  2007/02/28 00:53:29  dkolev
 * Added multiple option fields support
 *
 * Revision 1.5  2007/02/26 20:33:12  dkolev
 * Removed the Options functions as they are unnecessary
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