<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.mail.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_MAIL))
{

    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_MAIL', _PATH_FRAMEWORK_PLUGINS . 'phpmailer' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_MAIL . 'class.phpmailer.php');
}

/**
 * @ignore
 * @package VESHTER
 */
class CMailBase extends PHPMailer {}

/**
 * Mail class able to send various types of email.
 *
 * @version $Revision: 1.11 $
 * @package VESHTER
 */
class CMail extends CObject
{

    /**
     * @var CMailBase
     * @ignore
     */
    protected $base;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.11 $');

        $this->base = new CMailBase();
        $this->SetAppPath("/usr/sbin/sendmail -t -i ");
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Sets message priority (1 = High, 3 = Normal, 5 = low).
     *
     * @param int $priority
     */
    function SetPriority($priority = 3)
    {
        $this->base->Priority = $priority;
    }

    /**
     * Gets message priority (1 = High, 3 = Normal, 5 = low).
     *
     * @return int
     */
    function GetPriority()
    {
        return $this->base->Priority;
    }

    /**
     * Sets the CharSet of the message.
     * @param  string
     */
    function SetCharSet($charset = "iso-8859-1")
    {
        $this->base->CharSet = $charset;
    }

    /**
     * Gets the CharSet of the message.
     * @return string
     */
    function GetCharSet()
    {
        return $this->base->CharSet;
    }

    function SetFrom($address, $name = "")
    {
        $this->base->From = $address;
        $this->base->FromName = $name;
    }

    /**
     * Sets the email address that a reading confirmation will be sent.
     *
     * @param string $address
     */
    function SetConfirmationTo($address)
    {
        $this->base->ConfirmReadingTo  = $address;
    }

    /**
     * Sets the subject of the message
     *
     * @param subject $subject
     */
    function SetSubject($subject = "<no subject>")
    {
        $this->base->Subject = $subject;
    }

    /**
     * Gets the subject of the message
     *
     * @return string
     */
    function GetSubject()
    {
        return $this->base->Subject;
    }

    /**
     * Sets the body of the message.  This can be either an HTML or text body.
     * If HTML then run SetContentType
     *
     * @see SetContentType
     * @param string $body
     */
    function SetBody($body)
    {
        $this->base->Body = CString::Disobfuscate($body);
    }

    /**
     * Gets the body of a message. If you want to use the template merging options, send "true" for $merge
     *
     * @see CDocument
     * @param boolean $merge
     * @return string
     */
    function GetBody ($merge = true)
    {
        if ($merge)
        {
            $doc = new CDocument($this->base->Body, "unknown", false);
            return $doc->ToString();
        }
        return $this->base->Body;
    }


    /**
     * Sets the alternative/text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal body.
     *
     * @see SetBody
     * @param string $altbody
     */
    function SetAltBody($altbody)
    {
        $this->base->AltBody = CString::Disobfuscate($altbody);
    }

    /**
     * Gets the body of a message. If you want to use the template merging options, send "true" for $merge
     *
     * @see CDocument
     * @param boolean $merge
     * @return string
     */
    function GetAltBody ($merge = true)
    {
        if ($merge)
        {
            $doc = new CDocument($this->base->AltBody, "unknown", false);
            return $doc->ToString();
        }
        return $this->base->AltBody;
    }

    function IsHTML($bool = true)
    {
        $this->base->IsHTML($bool);
    }

    /////////////////////////////////////////////////
    // VARIABLE METHODS
    /////////////////////////////////////////////////

    /**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     *
     * @param string $encoding
     */
    function SetEncoding($encoding = "8bit")
    {
        $this->base->Encoding = $encoding;
    }

    /**
     * Gets the encoding of a message
     *
     * @see SetEncoding
     * @return unknown
     */
    function GetEncoding()
    {
        return $this->base->Encoding;
    }

    /**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     *
     * @param  string
     */
    function SetSender($sender = "")
    {
        $this->base->Sender = $sender;
    }

    /**
     * Gets the Sender email (Return-Path) of the message.
     *
     * @see SetSender
     * @param  string
     */
    function GetSender()
    {
        return $this->base->Sender;
    }

    /**
     * Sets the hostname to use in Message-Id and Received headers
     * and as default HELO string. If empty, the value returned
     * by SERVER_NAME is ued or 'localhost.localdomain'.
     *
     * @param string $hostname
     */
    function SetHostname($hostname)
    {
        $this->base->Hostname = $hostname;
    }


    function GetHostname()
    {
        return $this->base->Hostname;
    }

    /**
     * Sets word wrapping on the body of the message to a given number of
     * characters.
     *
     * @param int $count
     */
    function SetWordwrap($count = 0)
    {
        $this->base->WordWrap = $count;
    }

    /**
     * Gets word wrapping on the body of the message
     *
     * @return int
     */

    function GetWordwrap()
    {
        return $this->base->WordWrap;
    }

    /**
     * Sets the path to the mail application
     *
     * @param string $path
     */
    function SetAppPath($path = "/usr/sbin/sendmail -t -i")
    {
        $this->base->Sendmail = $path;
    }

    /**
     * Gets the path to the mail program
     *
     * @return string
     */function GetAppPath()
    {
        return $this->base->Sendmail;
    }

    /**
     * Sets message format/content type.
     * @param string $format Could be "text/html" or "text/plain"
     * @return void
     */
    function SetContentType($format = "text/plain")
    {
        $this->base->ContentType = $format;
    }

    /**
     * Set the type of mail message
     *
     * @param string Available options are "smtp" (send as an SMTP message), "mail" (use PHP mail() function), "sendmail" (use the Sendmail program.) and "qmail" (use the qmail MTA)
     */

    function SetType($type = "mail")
    {
        switch($type)
        {
            case "smtp":
                $this->base->IsSMTP();
                break;
            case "sendmail":
                $this->base->IsSendmail();
                break;
            case "qmail":
                $this->base->IsQmail();
                break;
            case "mail":
            default:
                $this->base->IsMail();
                break;
        }
    }

    /////////////////////////////////////////////////
    // SMTP VARIABLES
    /////////////////////////////////////////////////

    /**
     * Sets the SMTP hosts.  All hosts must be separated by a
     * semicolon.  You can also specify a different port
     * for each host by using this format: [hostname:port]
     * (e.g. "smtp1.example.com:25;smtp2.example.com").
     * Hosts will be tried in order.
     *
     * @param string Host names
     */

    function SetSMTPHost($host = "localhost")
    {
        $this->base->Host = $host;
    }

    /**
     * Gets the SMTP hosts
     *
     * @see SetSMTPHost
     * @return string
     */
    function GetSMTPHost()
    {
        return $this->base->Host;
    }

    /**
     * Sets the SMTP server port.
     *
     * @param int $port
     */
    function SetSMTPPort($port = 25)
    {
        $this->base->Port = $port;
    }

    /**
     * Gets the SMTP server port
     *
     * @return int
     */
    function GetSMTPPort()
    {
        return $this->base->Port;
    }

    /**
     * Sets the SMTP HELO of the message (Default is the hostname).
     *
     * @param unknown_type $msg
     */
    function SetSMTPHeloMessage($msg = "")
    {
        //use the host name if no HELO message was supplied
        if ($msg == "")
        $msg = $this->GetHostname();
        $this->base->Helo = $msg;
    }

    function GetSMTPHeloMessage()
    {
        return $this->base->Helo;
    }

    /**
     * Sets SMTP authentication. Utilizes the Login and Password variables, be sure to set those.
     *
     * @see SetSMTPLogin
     * @see SetSMTPPassword
     * @param boolean $secure Use to specify whether the message needs to supply SMTP login information.
     */
    function SetSMTPAuth($secure = false)
    {
        $this->base->SMTPAuth = $secure;
    }

    function GetSMTPAuth()
    {
        return $this->base->SMTPAuth;
    }

    /**
     * Sets SMTP login. Login name cannot be retrived back from the object using a Get function
     *
     * @param string $login
     */
    function SetSMTPLogin($login)
    {
        $this->base->Username = $login;
    }

    /**
     * Sets SMTP password. Password cannot be retrived back from the object using a Get function
     *
     * @param string $login
     */
    function SetSMTPPassword($password)
    {
        $this->base->Password = $password;
    }

    /**
     * Sets the SMTP server timeout in seconds.
     *
     * @param int $timeout
     */
    function SetSMTPTimeout($timeout = 10)
    {
        $this->base->Timeout = $timeout;
    }

    /**
     * Gets the SMTP server timeout in seconds.
     *
     * @return int
     */
    function GetSMTPTimeout()
    {
        return $this->base->Timeout;
    }

    /**
     * Sets SMTP class debugging on or off.
     *
     * @param boolean $debug
     */
    function SetSMTPDebug($debug = false)
    {
        $this->base->SMTPDebug = $debug;
    }

    /**
     * Prevents the SMTP connection from being closed after each mail
     * sending.
     *
     * @param boolean $keepalive
     */
    function SetSMTPKeepAlive($keepalive = false)
    {
        $this->base->SMTPKeepAlive = $keepalive;
    }

    /////////////////////////////////////////////////
    // RECIPIENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds a "To" address.
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddTo($address, $name = "")
    {
        $addresses = $address;
         
        if (!is_array($addresses))
        {
            $addresses = preg_split("/[,;|]/", $addresses);
        }
         
        foreach ($addresses as $address)
        {
            $this->base->AddAddress($address, $name);
        }
         
    }

    /**
     * Adds a "Cc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddCC($address, $name = "")
    {
        $addresses = $address;

        if (!is_array($addresses))
        {
            $addresses = preg_split("/[,;|]/", $addresses);
        }

        foreach ($addresses as $address)
        {
            $this->base->AddCC($address, $name);
        }
    }

    /**
     * Adds a "Bcc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddBCC($address, $name = "")
    {
        $addresses = $address;
         
        if (!is_array($addresses))
        {
            $addresses = preg_split("/[,;|]/", $addresses);
        }
         
        foreach ($addresses as $address)
        {
            $this->base->AddBCC($address, $name);
        }
    }

    /**
     * Adds a "Reply-to" address.
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddReplyTo($address, $name = "")
    {
        $addresses = $address;
         
        if (!is_array($addresses))
        {
            $addresses = preg_split("/[,;|]/", $addresses);
        }
         
        foreach ($addresses as $address)
        {
            $this->base->AddReplyTo($address, $name);
        }
    }


    /////////////////////////////////////////////////
    // MAIL SENDING METHODS
    /////////////////////////////////////////////////

    /**
     * Creates message and assigns Mailer. If the message is
     * not sent successfully then it returns false.  Use the ErrorInfo
     * variable to view description of the error.
     * @return bool
     */
    function Send()
    {
        $this->AddCustomHeader("X-MailerEx: " . $this->_ident());
        $this->AddCustomHeader("X-MailerEx-Support: For support or to report abuse, email support@veshter.com");
         
        if ($this->base->Send())
        {
            //everything is good
            return true;
        }
        else
        {
            $this->Warn($this->base->ErrorInfo);
            return false;
        }
    }

    /**
     * Sets the language for all class error messages.  Returns false
     * if it cannot load the language file.  The default language type
     * is English.
     * @param string $lang_type Type of language (e.g. Portuguese: "br")
     * @param string $lang_path Path to the language file directory
     * @access public
     * @return bool
     */
    function SetLanguage($lang_type, $lang_path = "language/")
    {
        $this->base->SetLanguage($lang_type, $lang_path);
    }

    /////////////////////////////////////////////////
    // MESSAGE CREATION METHODS
    /////////////////////////////////////////////////

    /**
     * Creates recipient headers.
     * @access private
     * @return string
     */
    function AddressAppend($type, $addr)
    {
        return $this->base->AddrAppend($type, $addr);
    }

    /**
     * Formats an address correctly.
     * @access private
     * @return string
     */
    function AddressFormat($addr)
    {
        return $this->base->AddrFormat($addr);
    }


    /**
     * Adds an attachment from a path on the filesystem.
     * Returns false if the file could not be found
     * or accessed.
     * @param string $path Path to the attachment.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return bool
     */
    function AddAttachment($path, $name = "", $encoding = "base64", $type = "application/octet-stream")
    {
        return $this->base->AddAttachment($path, $name, $encoding, $type);
    }


    /**
     * Adds a string or binary attachment (non-filesystem) to the list.
     * This method can be used to attach ascii or binary data,
     * such as a BLOB record from a database.
     * @param string $string String attachment data.
     * @param string $filename Name of the attachment.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return void
     */
    function AddStringAttachment($string, $filename, $encoding = "base64", $type = "application/octet-stream")
    {
        return $this->base->AddStringAttachment($string, $filename, $encoding, $type);
    }

    /**
     * Adds an embedded attachment.  This can include images, sounds, and
     * just about any other document.  Make sure to set the $type to an
     * image type.  For JPEG images use "image/jpeg" and for GIF images
     * use "image/gif".
     * @param string $path Path to the attachment.
     * @param string $cid Content ID of the attachment.  Use this to identify
     *        the Id for accessing the image in an HTML form.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return bool
     */
    function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", $type = "application/octet-stream")
    {
        return $this->base->AddEmbeddedImage($path, $cid, $name, $encoding, $type);
    }

    /////////////////////////////////////////////////
    // MESSAGE RESET METHODS
    /////////////////////////////////////////////////

    /**
     * Clears all recipients assigned in the TO array.  Returns void.
     * @return void
     */
    function ClearAddresses()
    {
        $this->base->ClearAddresses();
    }

    /**
     * Clears all recipients assigned in the CC array.  Returns void.
     * @return void
     */
    function ClearCCs()
    {
        $this->base->ClearCCs();
    }

    /**
     * Clears all recipients assigned in the BCC array.  Returns void.
     * @return void
     */
    function ClearBCCs()
    {
        $this->base->ClearBCCs();
    }

    /**
     * Clears all recipients assigned in the ReplyTo array.  Returns void.
     * @return void
     */
    function ClearReplyTos()
    {
        $this->base->ClearReplyTos();
    }

    /**
     * Clears all recipients assigned in the TO, CC and BCC
     * array.  Returns void.
     * @return void
     */
    function ClearAllRecipients()
    {
        $this->base->ClearAllRecipients();
    }

    /**
     * Clears all previously set filesystem, string, and binary
     * attachments.  Returns void.
     * @return void
     */
    function ClearAttachments()
    {
        $this->base->ClearAttachments();
    }

    /**
     * Clears all custom headers.  Returns void.
     * @return void
     */
    function ClearCustomHeaders()
    {
        $this->base->ClearCustomHeaders();
    }

    /**
     * Returns the appropriate server variable.  Should work with both
     * PHP 4.1.0+ as well as older versions.  Returns an empty string
     * if nothing is found.
     * @access private
     * @return mixed
     */
    function ServerVar($varName)
    {
        return $this->base->ServerVar($varName);
    }

    /**
     * Returns true if an error occurred.
     * @return bool
     */
    function IsError()
    {
        return $this->base->IsError();
    }



    /**
     * Adds a custom header.
     * @return void
     */
    function AddCustomHeader($custom_header)
    {
        $this->base->AddCustomHeader($custom_header);
    }

}


/*
 *
 * Changelog:
 * $Log: class.mail.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2009-06-21 03:43:21  dkolev
 * Added multiple address to To, CC, BCC and ReplyTo
 *
 * Revision 1.8  2009-01-30 08:15:16  dkolev
 * Fixed version header to pull the correct value
 *
 * Revision 1.7  2008-09-21 05:37:41  dkolev
 * Added custom VESHTER headers
 *
 * Revision 1.6  2008-09-14 01:16:30  dkolev
 * The error from sending a message is now recorded if the message fails.
 *
 * Revision 1.5  2007-10-02 06:03:48  dkolev
 * Send returns true/false
 *
 * Revision 1.4  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:29  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 *
 */

?>