<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetpage.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget which displays (web) pages
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */
class CWidgetPage extends CWidgetThemed
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

    protected function RenderVisual()
    {
        $final = new CDocument($this->data['part'], 'mixed', false);
        $this->data['part'] =  $final->ToString();
    }


}

/*
 *
 * Changelog:
 * $Log: class.widgetpage.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2010-03-01 06:52:30  dkolev
 * Overrode RenderVisual
 *
 * Revision 1.1  2010-02-28 19:40:31  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.7  2009-02-03 07:46:51  dkolev
 * *** empty log message ***
 *
 * Revision 1.6  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.5  2008-05-31 04:30:47  dkolev
 * Moved EvalScript to the new CCodeInterpreter class.
 *
 * Revision 1.4  2008/05/06 04:55:22  dkolev
 * Removed config and server information from the merge. This information is now in CDocument
 *
 * Revision 1.3  2008/01/29 01:23:01  dkolev
 * Improved the eval'd code wrapper
 *
 * Revision 1.2  2008/01/12 04:11:03  dkolev
 * Added try-catch block
 * Added the self variable for merges.
 *
 * Revision 1.1  2007/12/20 22:57:37  dkolev
 * Initial import
 *
 */


?>