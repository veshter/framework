<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.janitor.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * Class used for framework maintenance.
 * Classes that are <b>not</b> originally extended from the CObject class will most likely have a janitor to take care of them.
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 */
class CJanitor extends CObject
{
    /**
     * Object/application that the janitor is assigned to
     *
     * @var string
     */

    protected $assignment;

    /**
     * Creates a janitor
     *
     * @param string $assignment Name of the object the janitor is taking care of.
     * @return CJanitor
     */
    function __construct($assignment = '')
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        $this->assignment = $assignment;
         
        if (!empty($this->assignment))
        {
            $this->Notify(CString::Format('CJanitor was assigned to %s', $this->assignment));
        }
    }

    function __destruct()
    {
        parent::__destruct();
    }
}



/*
 *
 * Changelog:
 * $Log: class.janitor.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2008-08-18 08:30:31  dkolev
 * Initial import
 *
 * Revision 1.5  2007/05/17 06:25:00  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/04/16 10:49:26  dkolev
 * Minor changes
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */


?>
