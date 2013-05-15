<?
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.gadget.php,v 1.2.4.2 2012-09-07 19:17:42 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * A tool, device or control that is very useful for a particular job
 *
 * @version $Revision: 1.2.4.2 $
 * @package VESHTER
 *
 */
abstract class CGadget extends CObject
{
    /**
     * Callbacks for the UI, commonly JavaScript
     * @var array
     */
    protected $uicallbacks = array();

    /**
     * @var CParserXML Object configuration
     */
    protected $config;

    /**
     * Database helper used to data manipulation and retrieval
     * @var CDatabaseHelper
     */
    protected $databasehelper;

    function __construct() 
    {
        parent::__construct();
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Sets the configuration XML root element name
     */
    public function SetXMLRoot($root)
    {
        $this->properties['xmlroot'] = $root;
    }

    /**
     * Gets the configuration XML root element name
     */
    public function GetXMLRoot()
    {
        return !empty($this->properties['xmlroot']) ? $this->properties['xmlroot'] : 'gadget';
    }


    /**
     * Configures the object with some given XML string
     * This function should be used when the object is explicitely created.
     * If the object is going to created based on a configuration string,
     * it is better to use the static CreateFromXML function.
     *
     * @see CreateFromXML
     * @param string $xml Configuration string
     * @return boolean
     */
    function Configure(&$xml, $merge = true)
    {
        
        $this->config = new CParserXML();

        if ($merge)
        {           
            $doc = new CDocument($xml, 'xml', false);

            $xmlMerged = $doc->ToString();
            
            if ($this->config->Parse($xmlMerged))
            {
                return true;
            }
        }
        else
        {
            if ($this->config->Parse($xml))
            {
                return true;
            }
        }

        $this->Warn($this->config->GetStatus());
        return false;
    }

    /**
     * Gets whether the object is configured
     */
    function IsConfigured()
    {
        return !empty($this->config);
    }

    /**
     * Creates the object from XML.
     *
     * This function should be used if the object is going to be created contingent to some configuration XML.
     * If the object is going to created explicitely and subsequently configured, the class method Configure should be used
     * If successful (ie. if the XML parser and the function both complete successfully),
     * the function will return a reference to the new object. Otherwise, it will return false;
     *
     * @see Configure
     * @param ref $xml
     * @return mixed Reference to the new object or false
     */
    static function &CreateFromXML (&$xml)
    {
        return false;
    }

    public function UpdateDatabaseHelper()
    {
        throw new CExceptionNotImplemented();
    }

    public function SetDatabaseHelper($helper)
    {
        if ($helper instanceof CDatabaseHelper)
        {
            $this->databasehelper = $helper;
        }
        else
        {
            throw new CExceptionInvalidParameter();
        }

    }

    public function GetDatabaseHelper()
    {
        return $this->databasehelper;
    }

    public function RegisterUICallback($callback, $method)
    {
        $this->uicallbacks[$callback] = $method;
    }

    public function SetUICallbacks($callbacks)
    {
        if (is_array($callbacks))
        {
            $this->uicallbacks = $callbacks;
        }
    }

    public function GetUICallbacks($callbacks)
    {
        return $this->uicallbacks;
    }
}


/*
 *
 * Changelog:
 * $Log: class.gadget.php,v $
 * Revision 1.2.4.2  2012-09-07 19:17:42  dkolev
 * Fixes due to PHP 5.3
 *
 * Revision 1.2.4.1  2011-11-25 22:17:15  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.7  2009-09-13 13:29:02  dkolev
 * Added option merging of global date with configuration XML in the Configure function.
 *
 * Revision 1.6  2009-04-04 10:43:20  dkolev
 * Added UI callbacks
 *
 * Revision 1.5  2008-09-26 04:10:12  dkolev
 * Adde XML root
 *
 * Revision 1.4  2008-05-31 04:28:40  dkolev
 * Added changelog comment
 *
 *
 */

?>