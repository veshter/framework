<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetstatus.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Status widget
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CWidgetStatus extends CWidgetThemed
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
    
    function Render()
    {
        CEnvironment::WriteLine($this->ToString());
    }

    function ToString($template = '', $lookup = false)
    {
        if ($this->data)
        {
            //TODO: Make this representation prettier, use a template
            $report = '';
            foreach ($this->data as $message)
            {
                //CEnvironment::Dump($message);
                //$report .= CString::Format('<b>%s</b>: %s<br>', $message['type'], $message['msg']);
                $report .= CString::Format('%s<br>', $message['msg']);
            }
             
            return $report;
        }
    }

}

/*
 *
 * Changelog:
 * $Log: class.widgetstatus.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-02-28 19:40:31  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.2  2008-01-29 01:23:42  dkolev
 * Improved status message generation
 *
 * Revision 1.1  2007/12/20 22:57:37  dkolev
 * Initial import
 *
 */


?>