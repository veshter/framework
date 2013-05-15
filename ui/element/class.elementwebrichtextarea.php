<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.elementwebrichtextarea.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web textarea with WYSIWYG support. It is a word-processor like editor/textarea
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CElementWebRichTextarea extends CElementWebTextarea
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
}

/*
 *
 * Changelog:
 * $Log: class.elementwebrichtextarea.php,v $
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
 * Revision 1.3  2007-07-02 06:09:53  dkolev
 * Removed the annoying "v"
 *
 * Revision 1.2  2007/05/17 06:24:58  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/02/28 00:55:38  dkolev
 * Initial import
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