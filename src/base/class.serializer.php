<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.serializer.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Converts and restores an object to and from a sequence of bits so that it can be persisted on a storage medium.
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CSerializer extends CObject
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
    /**
     *
     * @param mixed $object
     * @param boolean $compress
     * @param string $format
     * @return string
     */
    static function Serialize($object, $compress = true, $format = 'common')
    {
        $string = serialize($object);

        if ($compress)
        {
            $string = gzcompress($string);
        }

        return $string;
    }

    /**
     *
     * @param string $string
     * @param boolean $compress
     * @param string $format
     * @return mixed
     */
    static function Deseralize($string, $compress = true, $format = 'common')
    {
        if ($compress)
        {
            $string = gzuncompress($string);
        }

        $object = unserialize($string);

        return $object;
    }
}

/*
 *
 * Changelog:
 * $Log: class.serializer.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-09-13 13:26:57  dkolev
 * Initial import
 *
 *
 *
 */

?>