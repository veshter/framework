<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetdialog.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget that displays dialogs
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CWidgetDialog extends CWidgetThemed
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
    
    protected function RenderVisual()
    {

        $dialog = new CDialog();

        $xml = $this->data['part'];

        //The use of a manager to configre with the above xml for use
        if ($dialog->Configure($xml))
        {
            $dialog->SetProperty('title', $this->data['title']);
            	
            $this->data['part'] = $dialog->ToString();
            	
        }
        else
        {
            $this->data['part'] = 'Manager failed to configure correctly';
        }

    }

}


/*
 *
 * Changelog:
 * $Log: class.widgetdialog.php,v $
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
 * Revision 1.3  2009-04-09 09:38:11  dkolev
 * Added title for dialogs from the $data member array.
 *
 * Revision 1.2  2009-04-04 10:46:12  dkolev
 * Implemented RenderVisual
 *
 *
 */


?>