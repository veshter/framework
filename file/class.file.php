<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.file.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic file
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */

class CFile extends CData
{
    protected $max_width = 0;

    protected $max_height = 0;

    protected $width = 0;

    protected $height = 0;

    protected $filename = 'file.txt';

    protected $binaryData = false;

    protected $type = '';

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function SetBinary($filedata)
    {
        $this->binaryData = $filedata;
    }

    function GetBinary()
    {
        return $this->binaryData;
    }


    function SetType($type)
    {

        $this->type = $type;

    }

    function GetType()
    {

        return $this->type;

    }

    function SetFilename($name)
    {

        $this->filename = $name;

    }

    function GetFilename()
    {

        return $this->filename;

    }

    function Flush($stream = null)
    {
        CEnvironment::RegisterHeader(CString::Format('Content-Type: %s', $this->type));
        CEnvironment::RegisterHeader('Content-Transfer-Encoding: none');

        CEnvironment::RegisterHeader('Pragma: public');
        CEnvironment::RegisterHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');                  // Date in the past
        CEnvironment::RegisterHeader(CString::Format('Last-Modified: %s GMT', gmdate('D, d M Y H:i:s')));
        CEnvironment::RegisterHeader('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
        CEnvironment::RegisterHeader('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
        CEnvironment::RegisterHeader('Pragma: no-cache');

        CEnvironment::RegisterHeader(CString::Format('Content-Disposition: attachment; filename="%s"',$this->filename));

        //output file
        CEnvironment::Write($this->binaryData);



    }

    /**
     * Determines whether the content type is that of an image
     * @param string contentType
     * @return bool
     */
    static function IsImage($contentType)
    {
        return substr($contentType, 0, 5) == 'image';
    }

}


/*
 *
 * Changelog:
 * $Log: class.file.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2008-12-30 16:42:51  dkolev
 * Modified cache headers
 *
 * Revision 1.5  2007/09/27 00:16:58  dkolev
 * Implemented class
 *
 * Revision 1.4  2007/05/17 06:25:05  dkolev
 * Reflect C-names
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
