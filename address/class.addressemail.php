<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.addressemail.php,v 1.8 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Provides basic email address manipulation and validation
 *
 * @version $Revision: 1.8 $
 * @package VESHTER
 *
 */
class CAddressEmail extends CAddress
{
    /**
     *
     * @param string $address
     * @return CAddressEmail
     */    
    function __construct($address = '') 
    {
        parent::__construct($address);
        
        $this->SetVersion('$Revision: 1.8 $');
        $this->SetAddress($address);
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function Validate()
    {


        if (empty($this->address))
        {
            $this->Warn("No address was supplied");
            return false;
        }
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->address))
        {
            $this->Warn($this->address . " is not properly formatted");
            return false;
        }

        if ($this->address == "ve_noemail")
        return true;

        //split the address into usable parts
        list($user, $domain) = split("@", $this->address, 2);

        //print ($GLOBALS["_api"]->GetStatus());

        //check in the database if this address is supposed to be checked
        //		if ($datagrid = CEnvironment::GetMainApplication()->GetDataGrid())
        //		{
        //
        //			$dblink = $datagrid->GetDatabaseLink();
        //			//see if there is an email verification flag set up
        //			if ($dblink->TableExists("config_email_verify"))
        //			{
        //				//$dblink = new CDatabaseLinkMySQL(); //used because of Zend Env inability to resolve classes
        //
        //				$WHERE = sprintf("email=%s OR email=%s OR email=%s",
        //					CString::Quote("*"),
        //					CString::Quote("*@" . $domain),
        //					CString::Quote($user . "@" . $domain)
        //					);
        //				$dblink->Select("config_email_verify", array("status"), $WHERE);
        //
        //				if ($dblink->ReadRow())
        //				{
        //					$row = $dblink->GetRow();
        //
        //					if (!empty($row["status"]))
        //					{
        //						//don't check, return it's OK
        //						if ($row["status"] == "ignored")
        //						{
        //							$this->Notify($this->address . " assumed as valid due to wildcard");
        //							return true;
        //						}
        //						else
        //						{
        //							$this->Warn("Disallowed email address");
        //							return false;
        //						}
        //					}
        //				}
        //			}
        //		}

        //make sure the domain has a mail exchanger
        if(checkdnsrr($domain, "MX"))
        {
            //get mail exchanger records
            if(!getmxrr($domain, $mxhost, $mxweight))
            {
                $this->Warn("Could not retrieve mail exchangers");
                return false;
            }
        }
        else
        {
            //if no mail exchanger, maybe the host itself
            //will accept mail
            $mxhost[] = $domain;
            $mxweight[] = 1;
        }
        //create sorted array of hosts
        for($i = 0; $i < count($mxhost); $i++)
        {
            $weighted_host[($mxweight[$i])] = $mxhost[$i];
        }
        ksort($weighted_host);

        //loop over each host
        foreach($weighted_host as $host)
        {
            //connect to host on SMTP port
            if(!($fp = @fsockopen($host, 25)))
            {
                //couldn't connect to this host, but
                //the next might work
                continue;
            }

            // skip over 220 messages
            // give up if no response for 5 seconds
             
            set_socket_blocking($fp, FALSE);
             
            $stopTime = time() + 5;
            $gotResponse = FALSE;

            while(1)
            {
                //try to get a line from mail server
                $line = fgets($fp, 1024);
                if(substr($line, 0, 3) == "220")
                {
                    //reset timer
                    $stopTime = time() + 5;
                    $gotResponse = TRUE;
                }
                elseif(($line == "") AND ($gotResponse))
                {
                    break;
                }
                elseif(time() > $stopTime)
                {
                    break;
                }
            }
            if(!$gotResponse)
            {
                //this host was unresponsive, but
                //maybe the next will be better
                continue;
            }
             
             
            set_socket_blocking ($fp, TRUE);
            //sign in
            fputs($fp, "HELO " . _BASEHREF . "\r\n");
            fgets($fp, 1024);
            //set from
            fputs($fp, "MAIL FROM: <" . CEnvironment::GetCuratorEmail() . ">\r\n");
            fgets($fp, 1024);
            //try address
            fputs($fp, "RCPT TO: <" . $this->address . ">\r\n");
            $line = fgets($fp, 1024);
            //close connection
            fputs($fp, "QUIT\r\n");
            fclose($fp);
            if(substr($line, 0, 3) != "250")
            {
                //mail server doesn't recognize
                //this address, so it must be bad
                $this->Warn($line);
                return false;
            }
            //address recognized
            else
            {
                $this->Notify($this->address . " appears to be valid");
                return true;
            }
        }

        $this->Warn("Unable to reach a mail exchanger");

        return false;

    }


}

/*
 *
 * Changelog:
 * $Log: class.addressemail.php,v $
 * Revision 1.8  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2008-09-14 01:15:58  dkolev
 * Removed database checking for whitelisted addresses. Will revisit
 *
 * Revision 1.5  2007-05-17 06:25:03  dkolev
 * Reflect C-names
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