<?

/*
 * %LICENSE% - see LICENSE
 * 
 * $Id: class.parserrss.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * RSS Parser
 * 
 * @version $Revision: 1.5 $
 * @package VESHTER
 * @deprecated
 */

class CParserRSS extends CParser
{
	
	private $default_cp = "UTF-8"; 
    private $CDATA = "nochange"; 
    private $cp = ""; 
    private $items_limit = 0; 
    private $stripHTML = false; 
    private $date_format = ""; 
    
    //by the default cache is disabled
	//private $cache_dir = './cache'; 
	//private $cache_time = _HOUR;
	
    // ------------------------------------------------------------------- 
    // Private variables 
    // ------------------------------------------------------------------- 
	protected $channeltags = array (
    	'title', 
    	'link', 
    	'description', 
    	'language', 
    	'copyright', 
    	'managingEditor', 
    	'webMaster', 
    	'lastBuildDate', 
    	'rating', 
    	'docs'
    	); 
    protected $itemtags = array(
    	'title',
    	'link', 
    	'description', 
    	'author', 
    	'category', 
    	'comments', 
    	'enclosure', 
    	'guid', 
    	'pubDate',
    	'source'
    	); 
    protected $imagetags = array(
    	'title',
    	'url',
    	'link',
    	'width',
    	'height'
    	); 
    	
    protected $textinputtags = array(
    	'title',
    	'description',
    	'name',
    	'link'
    	); 

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }
	
    // ------------------------------------------------------------------- 
    // Replace HTML entities &something; by real characters 
    // ------------------------------------------------------------------- 
    function Unhtmlentities ($string) 
    { 
        // Get HTML entities table 
        $trans_tbl = get_html_translation_table (HTML_ENTITIES, ENT_QUOTES); 

        // Flip keys<==>values 
        $trans_tbl = array_flip ($trans_tbl); 
        // Add support for &apos; entity (missing in HTML_ENTITIES) 

        $trans_tbl += array('&apos;' => "'"); 
        
        // Replace entities by values 
        return strtr ($string, $trans_tbl); 
    } 

    /**
	 * Modification of preg_match(); return trimed field with index 1 
     * from 'classic' preg_match() array output 
	 *
	 * @param string $pattern
	 * @param string $subject
	 * @access protected
	 * @return string
	 */
    protected function PregMatch ($pattern, $subject, $limit = '0,1') 
    { 
    	$matches = array();
    	
        // start regular expression 
        preg_match_all($pattern, $subject, $out); 
        
        //we want all the results when $limit is -1
        if ($limit == -1)
        	$limit = count($out[1]);

        for ($loop = 0; $loop < $limit; $loop++)
        {
	        // if there is some result... process it and return it 
	        if(isset($out[1][$loop])) 
	        { 
	            // Process CDATA (if present)
	            // Get CDATA content (without CDATA tag)  
	            if ($this->CDATA == 'content') 
	            { 
	                $out[1][$loop] = strtr($out[1][$loop], array('<![CDATA['=>'', ']]>'=>'')); 
	            } 
	            // Strip CDATA 
	            else if ($this->CDATA == 'strip') 
	            { 
	                $out[1][$loop] = strtr($out[1][$loop], array('<![CDATA['=>'', ']]>'=>'')); 
	            } 
	
	            // If code page is set convert character encoding to required 
	            if ($this->cp != '')
	            	$out[1][$loop] = iconv($this->rsscp, $this->cp.'//TRANSLIT', $out[1]); 
	            // Return result 
	            $matches[$loop] = trim($out[1][$loop]); 
	        } 
        }
        return $matches;
    } 
    
    /**
     * General RSS parsing function
     */
    function Parse ($content, $what = "") 
    {
    	
    	//if the content is a link to something, we need to get it first
    	if ($what == "fetch")
	   	{
	   		if ($rss_file = @fopen($content, 'r')) 
			{ 
				$content = ''; 
				while (!feof($rss_file)) 
				{ 
				    $content .= fgets($rss_file, 4096); 
				} 
				fclose($rss_file); 
			}
	   			
	   	}
    	if (!empty($content))
		{

            // Parse document encoding 
            $temp = $this->PregMatch("'encoding=[\'\"](.*?)[\'\"]'si", $content); 
            $this->nodes['encoding'] = $temp[0];
            
            // if document codepage is specified, use it 
            if ($this->nodes['encoding'] != '') 
            {
            	$this->rsscp = $this->nodes['encoding']; 
            }
            // otherwise use the default codepage 
            else 
            {
            	$this->rsscp = $this->default_cp; 
            } 

            // Parse CHANNEL info 
            preg_match("'<channel.*?>(.*?)</channel>'si", $content, $out_channel); 
            
            foreach($this->channeltags as $channeltag) 
            { 
                $temp = $this->PregMatch("'<$channeltag.*?>(.*?)</$channeltag>'si", $out_channel[1]); 
                if ($temp[0] != '') 
                	$this->nodes[$channeltag] = $temp[0]; // Set only if not empty 
            } 
            // If date_format is specified and lastBuildDate is valid 
            if ($this->date_format != '' && ($timestamp = strtotime($this->nodes['lastBuildDate'])) !==-1) 
            { 
                // convert lastBuildDate to specified date format 
                $this->nodes['lastBuildDate'] = date($this->date_format, $timestamp); 
            } 

            // Parse TEXTINPUT info 
            preg_match("'<textinput(|[^>]*[^/])>(.*?)</textinput>'si", $content, $out_textinfo); 
            
            // This a little strange regexp means: 
            // Look for tag <textinput> with or without any attributes, but skip truncated version <textinput /> (it's not beggining tag) 
            if (isset($out_textinfo[2])) 
            { 
                foreach($this->textinputtags as $textinputtag) 
                { 
                    $temp = $this->preg_match("'<$textinputtag.*?>(.*?)</$textinputtag>'si", $out_textinfo[2]); 
                    if ($temp[0] != '') 
                    	$this->nodes['textinput_'.$textinputtag] = $temp[0]; // Set only if not empty 
                } 
            } 
            // Parse IMAGE info 
            preg_match("'<image.*?>(.*?)</image>'si", $content, $out_imageinfo); 
            if (isset($out_imageinfo[1]))
            { 
                foreach($this->imagetags as $imagetag) 
                { 
                    $temp = $this->PregMatch("'<$imagetag.*?>(.*?)</$imagetag>'si", $out_imageinfo[1]); 
                    if ($temp[0] != '') 
                    	$this->nodes['image_'.$imagetag] = $temp[0]; // Set only if not empty 
                } 
            } 
            
            // Parse ITEMS 
            preg_match_all("'<item(| .*?)>(.*?)</item>'si", $content, $items); 
            $rss_items = $items[2]; 
            $i = 0; 
            $this->nodes['items'] = array(); // create array even if there are no items 
            foreach($rss_items as $rss_item) 
            { 
                // If number of items is lower then limit: Parse one item 
                if ($i < $this->items_limit || $this->items_limit == 0) 
                { 
                    foreach($this->itemtags as $itemtag) 
                    { 
                        $temp = $this->PregMatch("'<$itemtag.*?>(.*?)</$itemtag>'i", $rss_item, -1); 
                        $loop = 0;
                        while ($temp[$loop] != '') 
                        {
                        	//there is more than one option
                        	if (count($temp) > 1)
                        		$this->nodes['items'][$i][$itemtag][$loop] = $temp[$loop]; // Set only if not empty 
                        	else 
                        		$this->nodes['items'][$i][$itemtag] = $temp[$loop]; // Set only if not empty
                        	
                        	//move to the next match
                        	$loop++; 
                        }
                    } 
                    
                    // Strip HTML tags and other bullshit from DESCRIPTION 
                    if ($this->stripHTML && $this->nodes['items'][$i]['description']) 
                        $this->nodes['items'][$i]['description'] = strip_tags($this->unhtmlentities(strip_tags($this->nodes['items'][$i]['description']))); 
                    
					// Strip HTML tags and other bullshit from TITLE 
                    if ($this->stripHTML && $this->nodes['items'][$i]['title']) 
                        $this->nodes['items'][$i]['title'] = strip_tags($this->unhtmlentities(strip_tags($this->nodes['items'][$i]['title']))); 
                    
					// If date_format is specified and pubDate is valid 
                    if ($this->date_format != '' && ($timestamp = strtotime($this->nodes['items'][$i]['pubDate'])) !==-1) 
                    { 
                        // convert pubDate to specified date format 
                        $this->nodes['items'][$i]['pubDate'] = date($this->date_format, $timestamp); 
                    } 
                    // Item counter 
                    $i++; 
                } 
            } 

            $this->nodes['items_count'] = $i; 
            return true; 
        } 
        else // Error in opening return False 
        { 
            return false; 
        } 
    } 
} 

/*
 *
 * Changelog:
 * $Log: class.parserrss.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.2.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2007-09-27 00:18:16  dkolev
 * Added PregMatch
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