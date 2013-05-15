<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebwithoptions.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web element with options.
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CElementWebWithOptions extends CElementWeb
{
    /**
     * Options for the element
     *
     * @var hash
     */
    protected $options;

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
     * Indicates whether the element can support options
     *
     * @return boolean
     */
    function SupportsOptions()
    {
        return true;
    }

    /**
     * Sets the options for the element
     *
     * @param mixed $options
     */
    function SetOptions($options)
    {



        if (is_array($options))
        {
            if (count($options) == 0)
            throw new Exception("Cannot have empty options for elements that require them");
            $this->options = $options;
        }
        else
        {
            if (strlen($options) == 0)
            throw new Exception("Cannot have empty options for elements that require them");
            	
            $parser = new CParserString();
            if ($parser->Parse($options))
            {
                //TODO: Add more error checking to the options if they were parsed properly or if they were in the correct format to begin with

                $options = $parser->GetNodes();

            }
        }

        //we just want the first token of data. Others are not handled
        $options = $options[0];

        //reorganize the elements to fit as options better
        for ($loop = 0; $loop < count($options); $loop++)
        {
            $this->options[$loop]['value'] = $options[$loop][0];
            	
            //use the name if provided, otherwise use the value itself as a name
            $this->options[$loop]['name'] = !empty($options[$loop][1]) ? $options[$loop][1] : $options[$loop][0];
            	
        }


        return $this->GetOptions();
    }

    function GetOptions()
    {
        return $this->options;
    }

}


/*
 *
 * Changelog:
 * $Log: class.elementwebwithoptions.php,v $
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
 * Revision 1.6  2007-09-27 00:15:47  dkolev
 * Formatting changes
 *
 * Revision 1.5  2007/05/17 06:24:57  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 00:56:43  dkolev
 * Added options support
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