<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetfactory.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget factory
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
final class CWidgetFactory extends CObject
{
    /**
     * Creates a widget
     *
     * @return CWidget
     */
    static function CreateWidget($type)
    {
        switch ($type)
        {
            case 'binary':
                return new CWidgetBinary();
            case 'dialog':
                return new CWidgetDialog();
            case 'page':
                return new CWidgetPage();
            case 'manager':
                return new CWidgetManager();
            case 'view':
                return new CWidgetView();
            default:
                throw new CExceptionNotImplemented(CString::Format('The requested content type %s is not supported', $type));
        }
    }

}


/*
 *
 * Changelog:
 * $Log: class.widgetfactory.php,v $
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
 * Revision 1.3  2009-03-29 20:57:12  dkolev
 * Added views and dialogs
 *
 * Revision 1.2  2008-01-05 22:54:09  dkolev
 * Fixed inheritance
 *
 * Revision 1.1  2007/12/20 22:57:37  dkolev
 * Initial import
 *
 */


?>