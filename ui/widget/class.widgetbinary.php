<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetbinary.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget which can display binary information
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CWidgetBinary extends CWidget
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
    
    function Render()
    {
        //start parsing the xml
        $parser = new CParserXml();

        if($parser->Parse($this->data['meta_image']))
        {
            //set some variables
            $image = $this->data['image'];
            $name = $parser->GetElementValue('/meta[1]/name[1]');
            $type = $parser->GetElementValue('/meta[1]/type[1]');

            if(substr($type, 0, 5) == 'image')
            {
                	
                $width = $parser->GetElementValue('/meta[1]/width[1]');
                $height = $parser->GetElementValue('/meta[1]/height[1]');
                	
                //Grab sent width variable
                if(CEnvironment::GetSubmittedVariable('width') > 0)
                {
                    $maxwidth = CEnvironment::GetSubmittedVariable('width');
                }
                else
                {
                    $maxwidth = $width;
                }

                //Grab sent height variable
                if(CEnvironment::GetSubmittedVariable('height') > 0)
                {
                    $maxheight = CEnvironment::GetSubmittedVariable('height');
                }
                else
                {
                    $maxheight = $height;
                }

                $file = new CFileImage();
                $file->SetBinary($image);
                $file->SetWidth($width);
                $file->SetHeight($height);
                $file->SetMaxWidth($maxwidth);
                $file->SetMaxHeight($maxheight);
                $file->SetType($type);
                $file->SetFilename($name);
                $file->Flush();
            }
            else
            {
                $file = new CFile();
                $file->SetBinary($image);
                $file->SetType($type);
                $file->SetFilename($name);
                $file->Flush();
            }
        }
        else
        {
            throw new CExceptionInvalidFormat('Meta information for this file is invalid');
        }
    }
}

/*
 *
 * Changelog:
 * $Log: class.widgetbinary.php,v $
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
 * Revision 1.1  2007-12-20 22:57:37  dkolev
 * Initial import
 *
 */


?>