<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.securedownload.php,v 1.6 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Address of a resource
 *
 * @version $Revision: 1.6 $
 * @package VESHTER
 * @deprecated
 *
 */
class CSecureDownload extends  CAddress
{

    private $lista;
    private $path;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.6 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function Dow_Security() {
        $this->lista = array(); // Create empty host list
        $this->path = "./"; // set default path do current folder
    }

    function SetPath($path) {
        $this->path = $path;
    }

    function AddHost($host) {
        if (empty($host)) {
            return false;
        }
        $this->lista[] = $host;
        return true;
    }

    function RemoveHost($host) {
        for ($i=0;$i<count($this->lista);$i++) {
            if ($this->lista[$i]==$host) {
                $this->RemoveArrayItem($this->lista,$i);
            }
        }
    }

    function ListHosts() {
        return $this->lista;
    }

    function RemoveArrayItem(&$ar,$item)
    {
        $ar = array_merge(array_splice($ar,0,$item),array_splice($ar,1));
    }

    function DownloadFile($filename) {
        if ($this->ChecaReferer() == true) {
            $fil = $this->$path.$filename;
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=".basename($fil));
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize("$fil"));
            readfile($fil);
            return true;
        } else {
            return false;
        }

    }

    function ChecaReferer() {
        $cont = count($this->lista);

        for ($x=0;$x<$cont;$x++) {
            if (eregi($this->lista[$x],$_SERVER["HTTP_REFERER"])) {
                return true;
                break;
            }

        }

    }

}

/*
 // Example
 $dow = new D_Security(); // Create new object
 $dow->SetPath("./"); // set the folrder where the files are stored

 $dow->AddHost("www.your-website.com"); // Add host to list
 $dow->AddHost($_SERVER["HTTP_REFERER"]); // Add host to list

 if (!$dow->DownloadFile("example.exe")) { // Download the file
 echo "<br>Error: you are trying to download this file from unauthorized site<br>";
 echo "Try again from authorized host now: <a href=downloader.php>link</a>";
 }
 */

/*
 *
 * Changelog:
 * $Log: class.securedownload.php,v $
 * Revision 1.6  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.5.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.5  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.4  2007-05-17 06:25:02  dkolev
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