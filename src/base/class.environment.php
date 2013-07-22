<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.environment.php,v 1.21 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * System environment information class.
 *
 * PHP already has a way to show information about itself using a public static function called phpinfo().
 * That, however, is not very friendly and we need to have a class that one can ask as necessary.
 *
 * @version $Revision: 1.21 $
 * @package VESHTER
 */
final class CEnvironment extends CEntity
{
    protected static $debug;

    protected static $context;

    protected static $application;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.21 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    static function Initialize()
    {

        CEnvironment::ReportNecessary();
        //CEnvironment::ReportEverything();
        //set_error_handler(array($this, "HandleError"), E_ALL);
        //set_exception_handler(array($this, "HandleException"));
        //register_shutdown_function(array($this, 'ShutDown'));



    }

    static function ReportEverything()
    {
        error_reporting(E_ALL);
    }

    static function ReportNecessary()
    {
        error_reporting(E_ALL ^ E_NOTICE);
    }

    static function RegisterHeader($header)
    {
        if (empty($header))
        {
            throw new CExceptionInvalidData('Cannot register empty headers');
        }

        $file = '';
        $line = -1;
        if(!headers_sent ($file, $line))
        {
            header($header);
        }
        else
        {
            throw new CExceptionEx(CString::Format('Headers have already been in %s on line %s', $file, $line));
        }
    }

    /**
     * Alias to the CTypeLoader method for inclusion of classes
     * @param CTypeLoader $loader
     * @param string $method
     */
    public static function RegisterTypeLoader($loader, $method = 'LoadClass')
    {
        CTypeLoader::RegisterTypeLoader($loader, $method);
    }

    /**
     * Returns the main application of the script
     *
     * @return CApplicationWeb
     */
    static function GetMainApplication()
    {
        $app = CEnvironment::GetCurrentApplication();

        if (empty($app))
        {
            throw new CExceptionEx('The framework has not been initialized');
            //$janitor = new CJanitor('CEnvironment');
            //$janitor->Kill("The framework has not been initialized yet");
        }
        else
        {
            return $app;
        }
    }

    /**
     * API/Framework information similar to phpinfo()
     *
     */
    static function GetInfo()
    {
        return	"No implemented";
    }

    /**
     * Get the version of the XML produced by the framework
     *
     * @return string XML Version
     */
    static function GetVersionXML()
    {

        return "1.0";
    }

    /**
     * Returns encoding of variuos types of information
     *
     * @return string encoding
     */
    public static function GetEncoding()
    {
        return "UTF-8";
    }


    /**
     * The document root directory under which the current script is executing,
     * as defined in the server's configuration file.
     *
     * @return string
     */
    public static function GetDocumentRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Contents of the Accept: header from the current request, if there is one.
     *
     * @return string
     */
    static function GetHttpAccept()
    {
        return $_SERVER['HTTP_ACCEPT'];
    }

    /**
     * Contents of the Accept-Encoding: header from the current request,
     * if there is one. Example: 'gzip'.
     *
     * @return string
     */
    static function GetHttpAcceptEncoding()
    {
        return $_SERVER['HTTP_ACCEPT_ENCODING'];
    }

    /**
     * Contents of the Accept-Language: header from the current request,
     * if there is one. Example: 'en'.
     *
     * @return string
     */
    static function GetHttpAcceptLanguage()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    /**
     * Contents of the Connection: header from the current request,
     * if there is one. Example: 'Keep-Alive'.
     *
     * @return string
     */
    static function GetHttpConnection()
    {
        return $_SERVER['HTTP_CONNECTION'];
    }

    /**
     * Contents of the Host: header from the current request,
     * if there is one.
     *
     * @return string
     */
    static function GetHttpHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Contents of the User-Agent: header from the current request, if there is one.
     * This is a string denoting the user agent being which is accessing the page.
     * A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586).
     *
     * @return string
     */
    static function GetHttpUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Server variable path???
     *
     * @return string
     */
    static function GetPath()
    {
        return $_SERVER['PATH'];
    }

    /**
     * The IP address from which the user is viewing the current page.
     *
     * @return string
     */
    static function GetServerAddressPublic()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * The IP address of the server under which the current script is executing.
     *
     * @return string
     */
    static function GetServerAddressLocal()
    {
        return $_SERVER['SERVER_ADDR'];
    }

    /**
     * The value given to the SERVER_ADMIN (for Apache) directive in the web server configuration file.
     * If the script is running on a virtual host, this will be the value defined for that virtual host.
     *
     * @return string
     */
    static function GetServerAdministrator()
    {
        return $_SERVER['SERVER_ADMIN'];
    }

    /**
     * The name of the server host under which the current script is executing.
     * If the script is running on a virtual host, this will be the value defined for that virtual host.
     *
     * @return string
     */
    static function GetServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * The port on the server machine being used by the web server for communication.
     * For default setups, this will be '80'; using SSL,
     * for instance, will change this to whatever your defined secure HTTP port is.
     *
     * @return string
     */
    static function GetServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * String containing the server version and virtual host name which are added to server-generated pages,
     * if enabled.
     *
     * @return string
     */
    static function GetServerSignature()
    {
        return $_SERVER['SERVER_SIGNATURE'];
    }

    /**
     * Server identification string, given in the headers when responding to requests.
     *
     * @return string
     */
    static function GetServerSoftware()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * What revision of the CGI specification the server is using;
     * i.e. 'CGI/1.1'.
     *
     * @return string
     */
    static function GetGatewayInterface()
    {
        return $_SERVER['GATEWAY_INTERFACE'];
    }

    /**
     * Name and revision of the information protocol via which the page was requested;
     * i.e. 'HTTP/1.0';
     *
     * @return string
     */
    static function GetServerProtocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }


    /**
     * Gets whether the environment was accessed via an ecnrypted link
     * @return boolean
     */
    static function GetServerSSL()
    {
        return (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on'));
    }

    /**
     * SSL protocol
     * i.e. TLSv1
     *
     * @return string
     */
    static function GetServerSSLProtocol()
    {
        return $_SERVER['SSL_PROTOCOL'];
    }

    /**
     * SSL Cipher
     * i.e. RC4-MD5
     *
     * @return string
     */
    static function GetServerSSLCipher()
    {
        return $_SERVER['SSL_CIPHER'];
    }

    /**
     * Which request method was used to access the page; i.e. 'GET', 'HEAD', 'POST', 'PUT'.
     *
     * Note: PHP script is terminated after sending headers
     *(it means after producing any output without output buffering) if the request method was HEAD.
     *
     * @return string
     */
    static function GetRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * The query string, if any, via which the page was accessed.
     *
     * @return string
     */
    static function GetRequestQuery()
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * The URI which was given in order to access this page;
     * for instance, 'http://www.mysite.com/index.html'.
     *
     * @return string
     */
    static function GetScriptUri()
    {
        return $_SERVER['SCRIPT_URI'];
    }

    static function GetReferrerUri()
    {
        return $_SERVER['HTTP_REFERER'];
    }


    /**
     * The URI which was given in order to access this page;
     * for instance, '/index.html'.
     *
     * @return string
     */
    static function GetRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Returns the virtual/mapped name of the script
     * @todo Add example
     */
    static function GetScriptVirtualName()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        {
            //return $_SERVER['REDIRECT_URI');
            return preg_replace('/\?.*$/i', '', $_SERVER['REQUEST_URI']);
        }
        return CEnvironment::GetScriptFilename();
    }

    static function GetScriptFilename()
    {
        return $_SERVER['PHP_SELF'];
    }

    /**
     * Contains the current script's path.
     * This is useful for pages which need to point to themselves.
     *
     * @return string
     */
    static function GetScriptPath()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Host name
     *
     * @return string
     */
    static function GetHostName()
    {
        return $_ENV['HOST'];
    }

    /**
     * Shell
     *
     * @return string
     */
    static function GetShell()
    {
        return $_ENV['SHELL'];
    }

    /**
     * Type of operating system used in the environment where the application is running???
     *
     * @return string
     */
    static function GetOperatingSystem()
    {
        return $_ENV['OSTYPE'];
    }

    /**
     * Returns the email address of the person/robot taking care of the API
     *
     * @return string Curator email address
     */
    static function GetCuratorEmail()
    {
        return "curator@veshter.com";
    }

    /**
     * Get a reference to a currently registered variable (currently in scope)
     *
     * @see RegisterGlobalVariable
     * @see GetSubmittedVariable
     * @param string Name of the variable
     * @return ref Reference to the global variable
     */
    static function &GetGlobalVariable($name)
    {
        return $GLOBALS[$name];
    }

    /**
     * Registers a variable (currently in scope) with the current API instance
     *
     * @see APIGetGlobalVariable
     * @param string Name of the variable
     * @param ref The value itself
     * @return ref Reference to the global variable
     */
    static function &RegisterGlobalVariable($name, &$value)
    {
        $GLOBALS[$name] = $value;
        $_REQUEST[$name] = $value;
        return CEnvironment::GetGlobalVariable($name);
    }

    /**
     * Get a variable that may have been submitted through a POST or a GET.
     * If no submitted value is found the default is used.
     *
     * This function is especially useful when writing code for forms, views or anything that deals with user submitted data
     *
     * @param string $name
     * @param mixed $default
     * @return string
     */
    static function GetSubmittedVariable($name, $default = null)
    {

        if (array_key_exists($name, $_POST))
        {
            return $_POST[$name];
        }
        else if (array_key_exists($name, $_GET))
        {
            return $_GET[$name];
        }
        else if (array_key_exists($name, $_REQUEST))
        {
            return $_REQUEST[$name];
        }
        else if (array_key_exists($name, $GLOBALS))
        {
            return $GLOBALS[$name];
        }

        //we could not determine anything, return the default value
        return $default;

    }

    /**
     * Get a hash variable that is build from data that may have been submitted through a POST or a GET.
     *
     * @param array $vars
     */
    static function GetSubmittedHash($vars = array())
    {
        $hash = array();
        if (is_array($vars))
        {
            foreach ($vars as $var)
            {
                $hash[$var] = CEnvironment::GetSubmittedVariable($var);
            }
        }
        return $hash;
    }

    /**
     * Returns a readable/string representation of an error
     *
     *
     * @param integer $error
     * @return string
     */
    static function GetCodeAsString($code)
    {
        if (is_numeric($code))
        {
            eval('if (defined("_CODE_' . $code . '")) $temp = _CODE_' . $code . '; else $temp="Code ' . $code . ' is not recognized";');
            return $temp;
        }
        else
        return "Unable to determine error content, supply an integer to interpret.";
    }

    /**
     * Prints/Dumps out a string representation of an object
     *
     * @param CObject $object
     */
    static function Dump(&$object)
    {
        print ("<pre>");
        $output = print_r($object, true);

        print (htmlentities($output));

        print ("</pre>");
    }

    /**
     * Tries to include a framework class by looking recursively in a directory
     *
     * @param string $class Name of the path to include
     * @param string $abspath Path to look into
     * @ignore
     */
    function LoadClass ($class, $abspath = _PATH_FRAMEWORK)
    {

        $included = false;

        //skip the first character. In this framework call classes start with 'C'
        $fname = strtolower(substr($class, 0));
        $fn_include = $abspath . "class." . $fname . _EXT;

        //check to see if this particular file exists
        if (file_exists($fn_include))
        {
            CEnvironment::Import($fn_include);

            // Check to see if the include declared the class
            $included = class_exists($class, false);

        }
        else if ($dh = opendir($abspath))
        {
            while (!$included && ($dir = readdir($dh)))
            {
                //don't waste time with . and ..
                if (($dir != ".") && ($dir != ".."))
                {
                    $include_path = $abspath . $dir . _DIRSLASH;
                    //only use directories
                    if (is_dir($include_path) && ($include_path != _PATH_FRAMEWORK_TEMP) && ($include_path != _PATH_FRAMEWORK_PLUGINS))
                    $included = LoadClass($class, $include_path);
                }
            }
            closedir($dh);
        }
        return $included;
    }

    /**
     * Imports/Requires (once) a path from somewhere on the filesystem
     *
     * @todo Implement case In-sensitive imports
     * @param string $path path to require
     * @param boolean $once should the path be required once?
     */
    static function Import($path, $once = true)
    {
        require_once($path);
    }

    /**
     * Sets the environment context
     */
    static function SetContext($context)
    {
        CEnvironment::$context = $context;
    }

    /**
     * Gets the context (usually a string)
     */
    static function GetContext()
    {
        return CEnvironment::$context;
    }

    static function SetCurrentApplication($app)
    {
        CEnvironment::$application = $app;
    }

    static function GetCurrentApplication()
    {
        return CEnvironment::$application;
    }
    /**
     * Set the execution mode of the environment (ie. Release/Debug)
     * This will most likely supercede individual object execution modes
     */
    static function EnableDebugging($enable = true)
    {
        CEnvironment::$debug = $enable;
    }

    static function GetDebugging ()
    {
        return CEnvironment::$debug;
    }


    static function Write($content, $format = "html")
    {
        print ($content);

    }

    static function WriteLine($content, $format = "html")
    {
        $nl = "<br>";

        switch ($format)
        {
            default:
                break;
        }

        CEnvironment::Write(CString::Format("%s%s", $content, $nl));
    }
}

/*
 *
 * Changelog:
 * $Log: class.environment.php,v $
 * Revision 1.21  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.20.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.20  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.19  2010-04-08 18:36:12  dkolev
 * Changed default encoding
 *
 * Revision 1.18  2009-09-13 11:53:17  dkolev
 * Added HTTPS property GetServerSSL
 *
 * Revision 1.17  2009-04-09 09:37:04  dkolev
 * When registering global variables, add them to the $_REQUEST also
 *
 * Revision 1.16  2008-08-18 08:28:20  dkolev
 * Added global debugging and removed the global _APP variable
 *
 * Revision 1.15  2008/06/13 15:57:15  dkolev
 * Wrong type of exception was being thrown.
 *
 * Revision 1.14  2008/05/08 04:08:49  dkolev
 * Changed the exception type when the framework is not initialized
 *
 * Revision 1.13  2008/04/08 01:21:35  dkolev
 * Added GetScriptUri
 *
 * Revision 1.12  2008/02/05 08:56:37  dkolev
 * Added SSL functions
 *
 * Revision 1.11  2008/01/29 01:18:23  dkolev
 * Added the GLOBALS array to GetSubmittedVariable
 *
 * Revision 1.10  2007/09/27 00:08:37  dkolev
 * Added context and header manipulation
 *
 * Revision 1.9  2007/06/25 01:04:47  dkolev
 * Reflected CEntity inheritance
 *
 * Revision 1.8  2007/06/15 17:30:50  dkolev
 * Moved exception and error handling in another classes. Took out the application from being registered and make it custom to a client script.
 *
 * Revision 1.7  2007/05/17 06:25:01  dkolev
 * Reflect C-names
 *
 * Revision 1.6  2007/04/16 10:48:17  dkolev
 * Changed the GetRegisteredVariable function
 *
 * Revision 1.5  2007/03/14 08:09:17  dkolev
 * Added report toggling functions so that only main errors are reported or everything when debugging/developing
 *
 * Revision 1.4  2007/02/28 10:05:00  dkolev
 * Added Write and WriteLine functions
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