<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.addressweb.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */


/**
 * @package VESHTER
 */

/**
 * Another name for the URL class
 *
 * @see URL
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */
class CAddressWeb extends CAddress
{

    protected $contents;

    function __construct($address = '')
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.8 $');
        $this->SetAddress($address);
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Validate($default_host = '')
    {

        if (!isset($this->address))
        {
            $this->Warn("No URL was specified");
            return false;
        }

        $url_parts = @parse_url($this->address);

        if (empty($url_parts["host"]))
        {
            $this->Notify("No hostname found on link, using default hostname");
            $url_parts["host"] = $default_host;
        }

        if (!empty( $url_parts["path"]))
        {
            $documentpath = $url_parts["path"];
             
            if (!ereg('^/', $documentpath))
            {
                $documentpath = '/' . $documentpath;
            }

        }
        else
        {
            $this->Notify(CString::Quote('%s is a local bookmark. Assuming valid due to precedence', $documentpath));
            return true;
        }

        if (!empty($url_parts["query"]))
        {
            $documentpath .= "?" . $url_parts["query"];
        }

        $host = $url_parts["host"];
        $port = $url_parts["port"];

        if (empty($port))
        $port = "80";
         
        $socket = @fsockopen($host, $port, $errno, $errstr, 30);
        if (!$socket)
        {
            $this->Warn("Could not open socket to domain. Error $errno: $errstr");
            return false;
        }
        else
        {
            fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\n\r\n");//HEAD
            $http_response = fgets( $socket, 1024);

            //see if you get the the response as though it is alive
            if (!ereg("200 OK", $http_response))
            {
                $this->Warn("HTTP-Response: $http_response");
                return false;
            }

            /*$c_lines = 1;
             while (!feof($socket) && ($c_lines < 50))
             {
             $http_response = fgets( $socket, 1024);
             $http_response = trim($http_response);
             if (!empty($http_response))
             {
             //print ("Checking: $http_response<br>");

             //					//make sure the server doesn't think the checked domain is a host.
             //					if (($host != $_ENV["HOSTNAME"]) && (eregi ($_ENV["HOSTNAME"], $http_response)))
             //					{
             //						$this->Warn("Monitoring server, makes the domain a host of the environment.");
             //						fclose ($socket);
             //						return false;
             //					}

             $c_lines++;
             }
             }
             */
             
            //close the socket
            fclose ($socket);
            return true;

        }
        return false;
    }

    function GetContents($default_host = "", $refresh = true)
    {
        if ($refresh)
        {
            $address_full = "";
             
            $url_parts = parse_url($this->address);
             
            if (empty($url_parts['host']))
            {
                $address_full .= "http://" . $default_host;

                if (!ereg('^/', $url_parts['path']))
                $address_full .= '/';
            }
            $address_full .= $this->address;
             
             
            //if ($this->Validate())
            $this->contents = file_get_contents($address_full);
            //else
            //	throw Exception("Address is invalid. Cannot get contents");
        }
        return $this->contents;
    }

}



/*
 *
 * Changelog:
 * $Log: class.addressweb.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2007-05-17 06:25:03  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/02/28 10:11:02  dkolev
 * Moved the functionality from URL and deleted URL completely
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>