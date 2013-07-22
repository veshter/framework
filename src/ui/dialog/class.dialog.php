<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.dialog.php,v 1.2.4.1 2011-11-25 22:17:15 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * A dialog is a popup that displays specified HTML or an embedded web page in an inline frame
 *
 * @version $Revision: 1.2.4.1 $
 * @package VESHTER
 */
class CDialog extends CGadget
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2.4.1 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function Configure(&$xml, $merge = true)
    {

        if (parent::Configure($xml, $merge))
        {
            $specs = $this->config->GetElement("/dialog[1]");

            //CEnvironment::Dump($specs);

            $this->properties['name'] = $specs['attributes']['name'];
            $this->properties['template'] =	!empty($specs['attributes']['template']) ? $specs['attributes']['template'] : "block.dialog.generic";

            $this->properties['width'] = $specs['attributes']['width'];
            $this->properties['height'] = $specs['attributes']['height'];
            	
            $this->properties['modal'] = $specs['attributes']['modal'];

            $this->properties['content'] = $this->config->GetElementValue('/dialog[1]/content[1]');
            	
            $this->properties['url_icon'] = $specs['attributes']['url_icon'];
            	
            $c_callback = 0;
            foreach ($specs['childNodes'] as $child)
            {
                //make sure we are dealing with specs and not something else
                if ($child['name'] == "callback")
                {
                    
                    $callback = $child['attributes']['name'];
                    
                    $method = $this->config->GetElementValue($child['xpath']);
                    
                    //CEnvironment::Dump($callback);
                    $this->RegisterUICallback($callback, $method);
                }
            }
            	
            //$this->uicallbacks
            	
            return true;
        }
        return false;


    }

    /**
     * Creates a string representation of a popup
     *
     * @return string
     */
    function ToString()
    {
        //CEnvironment::EnableDebugging();

        $this->properties['callbacks'] = array();
        foreach ($this->uicallbacks as $key => $value)
        {
            $this->properties['callbacks'][] = array('name' => $key, 'method' => $value);
            	
            	
        }

        //show the data
        $doc = new CDocument($this->properties['template']);


        $doc->MergeField('dialog', $this->properties);

        $doc->MergeBlock('callback', $this->properties['callbacks']);

        return $doc->ToString();

    }
}

/*
 *
 * Changelog:
 * $Log: class.dialog.php,v $
 * Revision 1.2.4.1  2011-11-25 22:17:15  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.2  2009-09-13 13:30:05  dkolev
 * Added option merging of global date with configuration XML in the Configure function.
 *
 * Revision 1.1  2009-04-04 10:42:45  dkolev
 * Initial import
 *
 * Revision 1.26  2009-01-31 03:52:24  dkolev
 * Added a catch so that if a value fails, it does not effect the entire view
 *
 * Revision 1.25  2008-12-15 03:54:18  dkolev
 * Added current range and current page
 *
 * Revision 1.24  2008-12-15 03:43:14  dkolev
 * *** empty log message ***
 *
 * Revision 1.23  2008-10-01 05:15:58  dkolev
 * Reverted from before deletion
 *
 * Revision 1.20  2008-09-17 08:48:55  dkolev
 * Changed pagination to show all pages.
 *
 * Revision 1.19  2008-06-01 12:54:53  dkolev
 * Add key/column aliases
 *
 * Revision 1.18  2008/06/01 09:52:55  dkolev
 * Remove the Configure from the CViewPerspective. Added filters.
 *
 * Revision 1.17  2008/05/18 12:12:27  dkolev
 * Changed from explicit member variables to storing to the object properties in the CViewColumn. Added custom datetime support for view columns
 *
 * Revision 1.16  2008/05/17 23:39:39  dkolev
 * Added links and thumbnails
 *
 * Revision 1.15  2008/05/06 04:56:15  dkolev
 * Added explicit datagrids.
 *
 * Revision 1.14  2008/04/28 07:33:47  dkolev
 * Fixed pagination and added session persistant sorting.
 *
 * Revision 1.13  2008/02/05 23:22:53  dkolev
 * Removed printing of view query.
 *
 * Revision 1.12  2008/02/05 08:58:18  dkolev
 * Pagination fixes
 *
 * Revision 1.11  2008/02/03 10:20:22  dkolev
 * Removed grouping
 * Added pagination
 *
 * Revision 1.9  2007/10/02 06:06:02  dkolev
 * Documentation changes for API doc
 *
 * Revision 1.8  2007/09/23 10:00:26  dkolev
 * Reorganized XML config usage.
 *
 * Revision 1.7  2007/06/25 01:10:38  dkolev
 * Added limits and fixed incorrect hash keys
 *
 * Revision 1.6  2007/05/17 13:51:21  dkolev
 * Documentation changes
 *
 * Revision 1.5  2007/05/17 06:25:05  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 10:09:25  dkolev
 * Removed the global variable registration for $group
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