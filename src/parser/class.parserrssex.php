<?

/*
 * %LICENSE% - see LICENSE
 * 
 * $Id: class.parserrssex.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * A RSS Extension designed to handle RSS content that carries additional information than the default
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 * @deprecated
 */
class CParserRSSEx extends CParserRSS
{
	/**
	 * Constructor for the class that takes in additional fields that the object is going to be looking for in the XML
	 *
	 * @param string $tags Pipe (|) delimitted tags to look for in _addition_ to the default ones
	 */
    
    
    function __construct($tags)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');

        if (!empty($tags))
		{
			$parser = new CParserString();
			
			$parser->Parse($tags);

			//combine the two arrays so that we have all the necessary tags
			$this->itemtags =  array_merge($this->itemtags, $parser->GetNodes());
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
 * $Log: class.parserrssex.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.2.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2007-09-27 00:19:00  dkolev
 * Using a simple parser for custom tags
 *
 * Revision 1.2  2007/05/17 06:25:00  dkolev
 * Reflect C-names
 *
 * Revision 1.1  2007/02/28 01:03:04  dkolev
 * Moved the directory from parse to parser
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