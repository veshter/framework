<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.fileimage.php,v 1.9 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Basic image file
 *
 * @version $Revision: 1.9 $
 * @package VESHTER
 *
 */

class CFileImage extends CFile
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.9 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function SetWidth($width)
    {
        $this->width = $width;
    }

    function GetWidth()
    {
        return $this->width;

    }

    function SetMaxWidth($width)
    {

        $this->max_width = $width;

    }

    function GetMaxWidth()
    {

        return $this->max_width;

    }

    function SetHeight($height)
    {

        $this->height = $height;

    }

    function GetHeight()
    {

        return $this->height;

    }

    function SetMaxHeight($height)
    {

        $this->max_height = $height;

    }

    function GetMaxHeight()
    {

        return $this->max_height;

    }

    /**
     * Creates an image resource out of a string with the diven dimensions.
     * The resource has to be explicitely destroyed
     *
     */
    static function CreateImageFromString($string, $width, $height, $max_width, $max_height)
    {

        $factor = array();

        $factor['x'] = $max_width/$width;
        $factor['y'] = $max_height/$height;

        $finalwidth = intval(round($width*min($factor)));
        $finalheight = intval(round($height*min($factor)));

        //		CEnvironment::Dump($temp = array('factor' => $factor));
        //		CEnvironment::Dump($temp = array('width' => $finalwidth, 'height' =>$finalheight));
        //		exit;

        //create base image
        $res = imagecreatefromstring($string);

        //the image needs to be resampled because there is a new height/width
        if (min($factor) < 1)
        {
            //resize the image
            $res_resampled = imagecreatetruecolor($finalwidth, $finalheight);
            imagecopyresampled($res_resampled, $res, 0, 0, 0, 0, $finalwidth, $finalheight, $width, $height);
            imagedestroy($res);

            return $res_resampled;
        }
        else
        {

            return $res;
        }
    }


    function Flush($stream = null)
    {
        CEnvironment::RegisterHeader(CString::Format('Content-Type: %s', $this->type));
        CEnvironment::RegisterHeader('Content-Transfer-Encoding: none');

        CEnvironment::RegisterHeader('Pragma: public');
        //CEnvironment::RegisterHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');                  // Date in the past
        CEnvironment::RegisterHeader(CString::Format('Last-Modified: %s GMT', gmdate('D, d M Y H:i:s')));
        //CEnvironment::RegisterHeader('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
        //CEnvironment::RegisterHeader('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
        CEnvironment::RegisterHeader('Cache-Control: pre-check=0, post-check=0, max-age=300');    // HTTP/1.1
        //CEnvironment::RegisterHeader('Pragma: no-cache');

        CEnvironment::RegisterHeader(CString::Format('Content-Disposition: inline; filename="%s"',$this->filename));

        $res = CFileImage::CreateImageFromString($this->binaryData, $this->width, $this->height, $this->max_width, $this->max_height);

        //function hook (i.e. imagejpeg)
        $hook = false;
        switch($this->type)
        {

            case 'image/gif':
                imagegif($res, null);
                break;
            case 'image/png':
                imagepng($res, null, 0, null);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($res, null, 100);
                break;
            default:
                //imagejpeg($res, null, 100);
                throw new CExceptionNotImplemented(CString::Format('The %s format requested is not supported', $this->type));
        }

        imagedestroy($res);


    }

}

/*
 *
 * Changelog:
 * $Log: class.fileimage.php,v $
 * Revision 1.9  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.8.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.8  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.7  2009-08-30 19:03:09  dkolev
 * Transparency fix. GIFs and PNGs are working properly.
 *
 * Revision 1.6  2009-01-31 08:46:36  dkolev
 * Improved the math to generate thumbnails.
 *
 * Revision 1.5  2008-12-30 16:42:51  dkolev
 * Modified cache headers
 *
 * Revision 1.4  2008/05/26 08:18:06  dkolev
 * Added 100 quality to images
 *
 * Revision 1.3  2008/05/25 04:01:58  dkolev
 * *** empty log message ***
 *
 * Revision 1.2  2008/03/25 19:09:51  dkolev
 * Formatting  changes
 *
 * Revision 1.1  2007/09/27 00:17:10  dkolev
 * Initial import
 *
 * Revision 1.4  2007/05/17 06:25:04  dkolev
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