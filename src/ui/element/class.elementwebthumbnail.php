<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebthumbnail.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Thumbnail image
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CElementWebThumbnail extends CElementWeb
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


    function SetValue($value)
    {
        if (array_key_exists('width', $this->properties) || array_key_exists('height', $this->properties))
        {
            $parts = explode('.', $value);
            	
            if (count($parts) > 2)
            {
                throw new CExceptionInvalidData(CString::Format('%s is an invalid thumbnail value', $value));
            }
            	
            $value = CString::Format('%s.width_%d.height_%d.%s', $parts[0], $this->properties['width'], $this->properties['height'], $parts[1]);
        }

        parent::SetValue($value);
    }
}

/*
 *
 * Changelog:
 * $Log: class.elementwebthumbnail.php,v $
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
 * Revision 1.1  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.1  2007/06/15 02:47:36  dkolev
 * Added IFrames
 *
 *
 *
 */

?>