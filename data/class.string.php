<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.string.php,v 1.13 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * String
 *
 * @version $Revision: 1.13 $
 * @package VESHTER
 *
 */

class CString extends CData
{
    private $string;

    function __construct($string = '')
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.13 $');

        $this->string = $string;
    }

    function __destruct()
    {
        parent::__destruct();
    }


    function SetString($string)
    {
        $this->string = $string;
    }

    /**
     * Concatenates an array of strings with a given separator
     * @param $strings
     * @param $separator
     * @param $separatorInValue Separator representation if it is found in the individual strings
     */
    static function Concatenate($strings, $separator = '', $separatorInValue = null)
    {
        //if we case that about the separator is inside the individual values, replace it with a safe version
        if (!CString::IsNullOrEmpty($separatorInValue))
        {
            $pattern = CString::Format('/%s/', $separator);

            $strings = preg_replace($pattern, $separatorInValue, $strings);

        }

        //implode the scripts but remove any /'s that might have come from the separator
        //those are useful in regexes but not data generation
        return implode(stripslashes($separator), $strings);
    }

    /**
     * Protect any email addresses that are listed in the contents of the passed in string
     * @param string $output
     * @return string
     */
    static function ProtectEmail($input)
    {
        return preg_replace('/(\S+)@([a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,3}|[0-9]{1,3}))/', '$1&#64;$2', $input);
    }

    /**
     * Deliberately make the contents of the string to be unclear or difficult to understand
     * @param string $output
     * @return string
     */
    static function Obfuscate($input)
    {
        return preg_replace("/[\r\n]/", "", $input);

    }

    /**
     * Wrap the contents of the passed in string so that they are neatly broken into lines where appropriate
     * @param string $output
     * @return string
     */
    static function Disobfuscate($input)
    {
        return preg_replace("/(<\/?)([a-zA-Z0-9\s]+)(\/?>)/", "\${1}\${2}\${3}\r\n", $input);

    }


    static function Format($format)
    {
        //CEnvironment::RegisterGlobalVariable("string", "");
        // Check whether more than one argument was given.
        if ( func_num_args() > 1 )
        {
            // Read all arguments.
            $arguments = func_get_args();

            //print (var_dump($arguments));


            return vsprintf(array_shift($arguments), $arguments) ;

            /*
             // Create a new string for the inserting command.
             $command = "\$string = sprintf(\$format, ";

             // Run through the array of arguments.
             for ( $i = 1; $i < sizeof($arguments); $i++ )
             {
             // Add the number of the argument to the command.
             $command .= "\$arguments[".$i."], ";
             }

             // Replace the last separator.
             $command = eregi_replace(", $", ");", $command);

             print ($format);

             print ($command);

             // Execute the command.
             eval($command);
             */
        }

        return $format;


    }

    function QuoteContents()
    {
        return $this->Quote($this->string);
    }

    static function Quote($string)
    {
        $string = CString::Escape($string);

        // Quote if not a number or a numeric string nor a binary blob
        if(!$string || (is_string($string) && !is_numeric($string)))
        {
            $string = CString::Format('\'%s\'', $string);
            //die ($string);
        }
         

        return $string;

    }

    static function Escape($string)
    {
        // Stripslashes
        //if (get_magic_quotes_gpc())
        //{
        //	$value = stripslashes($string);
        //}

        $string = @mysql_real_escape_string($string);

        return $string;
    }

    static function IsNullOrEmpty($string)
    {
        return $string == false || $string == null || empty($string);
    }

    static function UrlEncode($string)
    {
        return urlencode($string);
    }

    static function UrlDecode($string)
    {
        return urldecode($string);
    }

    function ToString()
    {
        return $this->string;

    }

    /**
     * Return a JavaScript representation of the string
     *
     * @param boolean $enclose USed to determine if there will be <script></script tags
     * @return string
     */
    function ToJavaScript ($enclose = false)
    {
        if ($enclose)
        {
            $js .= "\n<script language=JavaScript>\n<!--\n";
            $js .= "//script begin\n";
        }

        //make a temp copy
        $str = $this->string;

        $str = ereg_replace("'", "\'", $str);
        $str = ereg_replace("\"", "'", $str);

        //allow for javascript in the the javascript
        $str = ereg_replace("script", "scr\"+\"ipt", $str);
        $str = ereg_replace(chr(13), "", $str); //carriage returns

        //bring back any double quotes
        $str = ereg_replace("&quot", "\\\"", $str);


        $str = explode("\n", $str);

        for ($loop = 0; $loop < count($str); $loop++)
        if (!empty($str[$loop]))
        $js .= "document.write(\"" . ereg_replace("\n", "", $str[$loop]) . "\");";

        if ($enclose)
        {
            $js .= "\n//script end\n-->\n</script>";
        }


        return $js;
    }
    /*
     function ToXML($tag)
     {


     }
     */
}


/*
 *
 * Changelog:
 * $Log: class.string.php,v $
 * Revision 1.13  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.12.4.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.12.4.1  2011-11-20 22:55:27  dkolev
 * Added concatination for strings
 *
 * Revision 1.12  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.11  2009-06-21 03:10:21  dkolev
 * Documentation Improvement
 *
 * Revision 1.10  2009-03-30 05:10:27  dkolev
 * Added url encoding and decoding functions
 *
 * Revision 1.9  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.8  2008/03/25 19:09:13  dkolev
 * Changed the Quote function to be more correct in deciding what to quote.
 *
 * Revision 1.7  2008/01/12 04:08:43  dkolev
 * Added Escape function
 *
 * Revision 1.6  2007/11/12 06:48:57  dkolev
 * Changed Quote to return '' when the string is null/false.
 *
 * Revision 1.5  2007/09/27 00:12:04  dkolev
 * Added concat function
 *
 * Revision 1.4  2007/05/17 06:25:02  dkolev
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
 */

?>
