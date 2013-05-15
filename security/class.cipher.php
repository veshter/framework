<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.cipher.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Basic cipher for encryption based on a shared key
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CCipher extends CObject
{
    private $type;

    private $mode;

    private $key;

    private $iv;

    function __construct() 
    {
        parent::__construct($key, $type = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_ECB);
        $this->SetVersion('$Revision: 1.3 $');
        
        $this->type = $type;
        $this->mode = $mode;

        $this->key = hash('sha256',$key,TRUE);
        $size = mcrypt_get_iv_size($this->type, $this->mode);
        $this->iv = mcrypt_create_iv($size);
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function Encrypt($plaintext)
    {
        return base64_encode(mcrypt_encrypt($this->type, $this->key, $plaintext, $this->mode, $this->iv));
    }
    function Decrypt($ciphertext)
    {
        return trim(mcrypt_decrypt($this->type, $this->key, base64_decode($ciphertext), $this->mode, $this->iv));
    }
}

/*
 *
 * Changelog:
 * $Log: class.cipher.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-05-26 03:40:19  dkolev
 * Initial import
 *

 *
 */

?>