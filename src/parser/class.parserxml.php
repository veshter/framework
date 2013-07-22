<?php

/*
 * %LICENSE% - see LICENSE 
 * 
 * $Id: class.parserxml.php,v 1.4.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * XML Parser based on the XPath
 *
 * @version $Revision: 1.4.4.1 $
 * @package VESHTER
 *         
 */
class CParserXML extends CParser
{

    /**
     *
     * @var DOMDocument
     */
    private $document;

    function __construct ()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4.4.1 $');
        
        $this->document = new DOMDocument();
    }

    function __destruct ()
    {
        parent::__destruct();
    }

    
//     function RegisterNamespace($namespace, $method)
//     {
//         $this->document->
//     }
    
    
    /**
     * Parse an XML string
     *
     * @param ref $content            
     * @param string $what
     *            Are we fetching the content or loading it directly.
     * @return unknown
     */
    function Parse (&$content, $what = "")
    {
        if (empty($content)) 
        {
            $this->Warn("No XML was supplied to parse");
            return false;
        }
        
        if ($what == "fetch") 
        {
            if ($this->document->load($content)) 
            {
                return true;
            }
        } 
        else 
        {
            if ($this->document->loadXML($content)) 
            {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Return the value of an element
     *
     * @see GetElement
     *
     * @param string $element
     *            CElement to lookup
     * @return string String representation of the element
     */
    function GetElementValue ($path)
    {
        $xmlPath = new DOMXPath($this->document);

        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            return $item->nodeValue;
        }
    }

    function GetElement ($path, $returnImmutable = true)
    {
        $xmlPath = new DOMXPath($this->document);
        
        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            if ($returnImmutable == true)
            {
                $immutable = $this->GetImmutableElementFromNode($item);
                
                return $immutable;
            }
            else
            {
                return $item;
            }
            
        }
    }
    
    private function GetImmutableElementFromNode(DOMNode $item)
    {
        $immutable = array();
        
        $immutable['name'] = $item->nodeName;
        
        if (!CString::IsNullOrEmpty($item->nodeValue) && (($item->childNodes == null) || (count($item->childNodes) == 0)))
        {
            $immutable['value'] = $item->nodeValue;
        }        
        
        if ($item->attributes != null)
        {
            foreach ($item->attributes as $attribute)
            {
                $immutable['attributes'][$attribute->name] = $attribute->value;
            }
            $immutable['xpath'] = $item->getNodePath();
            
            foreach ($item->childNodes as $childNode)
            {

                //we don't care about text
                if ($childNode->nodeName != '#text')
                {   
                    $immutable['childNodes'][] = $this->GetImmutableElementFromNode($childNode);
                }
                
            }
        }
        return $immutable;
        
    }

    function GetElementXML ($path)
    {
        $xmlPath = new DOMXPath($this->document);
        
        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            return $this->document->saveXML($item);
        }
    }

    function GetAttribute ($path, $name = null)
    {
        $xmlPath = new DOMXPath($this->document);
        
        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            return $item->getAttribute($name);
        }
    }

    function SetAttribute ($path, $name, $value, $overwrite = true)
    {
        $xmlPath = new DOMXPath($this->document);
        
        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            $item->setAttribute($name, $value);
        }
    }

    function SetAttributes ($path, $attributes, $overwrite = true)
    {
        throw new CExceptionNotImplemented();
    }

    function RemoveAttribute ($path, $name, $overwrite = true)
    {
        $xmlPath = new DOMXPath($this->document);
        
        $arrNodes = $xmlPath->query($path);
        foreach ($arrNodes as $item) 
        {
            $item->removeAttribute($name);
        }
    }

    function AppendChild ($path, $node, $afterText = false, $autoReindex = true)
    {
        throw new CExceptionNotImplemented();
    }

    function RemoveChild ($path, $autoReindex = true)
    {
        throw new CExceptionNotImplemented();
    }

    function ToString ()
    {
        throw new CExceptionNotImplemented();
    }
}

/*
 * Changelog: $Log: class.parserxml.php,v $ Revision 1.4.4.1 2011-11-25 22:17:14
 * dkolev Cleaned up constructors. Imported new captcha functionality Revision
 * 1.4 2010-07-04 18:32:39 dkolev Sniff improvements Revision 1.3 2007-09-27
 * 00:20:20 dkolev Added more friendly functions Revision 1.2 2007/05/17
 * 06:25:00 dkolev Reflect C-names Revision 1.1 2007/02/28 01:03:04 dkolev Moved
 * the directory from parse to parser Revision 1.3 2007/02/26 04:03:27 dkolev
 * Added page-level DocBlock Revision 1.2 2007/02/26 02:50:30 dkolev Added
 * standardized CVS commenting
 */

?>