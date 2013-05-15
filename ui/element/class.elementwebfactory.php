<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebfactory.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web element factory.
 *
 * The factory creates web elements based on a specific need/request
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

final class CElementWebFactory extends CObject
{
    /**
     * Creates a web element depending on passed in requested element type
     *
     * @param string $type
     * @return CElementWeb
     */
    static function Create($type)
    {
        $element = null;
        switch($type)
        {
            //input fields
            case "checkbox":
                $element = new CElementWebCheckbox();
                break;
            case "radio":
                $element = new CElementWebRadio();
                break;
                //case "select":
            case "combobox":
                $element = new CElementWebCombobox();
                break;
            case "hidden":
                $element = new CElementWebHidden();
                break;
            case "text":
                $element = new CElementWebText();
                break;
            case "textarea":
                $element = new CElementWebTextarea();
                break;

            case "label":
                $element = new CElementWebLabel();
                break;
            case "thumbnail":
                $element = new CElementWebThumbnail();
                break;

            case "richtextarea":
                $element = new CElementWebRichTextarea();
                break;
            case "date":
                $element = new CElementWebDate();
                break;
            case "datetime":
                $element = new CElementWebDateTime();
                break;
            case "password":
                $element = new CElementWebPassword();
                break;
            case "browse":
                $element = new CElementWebBrowse();
                break;
                
            case "captcha":
                $element = new CElementWebCAPTCHA();
                break;                
                
                //buttons
            case "button":
                $element = new CElementWebButton();
                break;
            case "submit":
                $element = new CElementWebButtonSubmit();
                break;
            case "reset":
                $element = new CElementWebButtonReset();
                break;

                case "iframe":
                $element = new CElementWebiFrame();
                break;
            case "imanager":
                $element = new CElementWebiManager();
                break;
            case "random":
                $element = new CElementWebRandom();
                break;
            case "custom":
            default:
                $element = new CElementWebCustom();
                break;
                //throw new CExceptionNotImplemented(CString::Format('Requested web element (%s) is not supported', $type));
        }
        return $element;
    }
}

/*
 *
 * Changelog:
 * $Log: class.elementwebfactory.php,v $
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
 * Revision 1.11  2009-06-20 20:40:07  dkolev
 * Added datetime field
 *
 * Revision 1.10  2009-03-30 01:05:09  dkolev
 * Added the Random element
 *
 * Revision 1.9  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.8  2007/09/27 00:14:49  dkolev
 * Added browse element
 *
 * Revision 1.7  2007/06/15 17:09:30  dkolev
 * Added I manager
 *
 * Revision 1.6  2007/06/15 02:47:49  dkolev
 * Added IFrames
 *
 * Revision 1.5  2007/05/17 06:24:57  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 00:55:12  dkolev
 * Added RickTextarea support to the factory
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