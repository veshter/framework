<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.parserstring.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * String parser
 *
 * @todo add more detail to this documentation
 * @version $Revision: 1.4 $
 * @package VESHTER
 */
class CParserString extends CParser
{
    /**
     * Delimiter used when parsing strings into tokens
     *
     * For example: namefirst:name_last|address_street
     *
     * @var char
     */
    public static $delimiter_token = '\|';
    public static $delimiter_token_ascii = '&#124;';


    /**
     * Delimiter used when parsing embedded strings into subtokens
     *
     * For example: namefirst:name_last|address_street
     *
     * @var char
     */
    public static $delimiter_group = '\^';
    public static $delimiter_group_ascii = '&#94;';

    /**
     * Delimiter used when parsing embedded strings into groups
     *
     * For example: namefirst:name_last|address_street1^address2^address3|
     *
     * @var char
     */

    public static $delimiter_item = '\:';
    public static $delimiter_item_ascii = '&#58;';
    

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Tries to parse the given string and returns a status boolean
     *
     * @return boolean
     */
    function Parse ($content)
    {
        if (!empty($content))
        {
            //break the string on tokens
            $this->nodes = preg_split(CString::Format('/%s/', CParserString::$delimiter_token), $content);

            for ($loop = 0; $loop < count($this->nodes); $loop++)
            {
                //break the tokens into groups
                $this->nodes[$loop] = preg_split(CString::Format('/%s/', CParserString::$delimiter_group), $this->nodes[$loop]);
                for ($inloop = 0; $inloop < count($this->nodes[$loop]); $inloop++)
                {
                    //break the groups into items
                    $this->nodes[$loop][$inloop] = preg_split(CString::Format('/%s/', CParserString::$delimiter_item), $this->nodes[$loop][$inloop]);
                }
            }

            return true;
        }
        return false;
    }
}

/*
 *
 * Changelog:
 * $Log: class.parserstring.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.3  2012-09-07 19:17:42  dkolev
 * Fixes due to PHP 5.3
 *
 * Revision 1.3.4.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3.4.1  2011-11-20 22:55:00  dkolev
 * Added better parsing for form data
 *
 * Revision 1.3  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2007-05-17 06:25:00  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/02/28 01:02:26  dkolev
 * Initial import
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