<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.document.php,v 1.20 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


if (!defined(_PATH_FRAMEWORK_PLUGINS_DOCUMENT))
{
    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_DOCUMENT', _PATH_FRAMEWORK_PLUGINS . 'tinybutstrong' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_DOCUMENT . 'tbs_class.php');
}

/**
 * @ignore
 * @package VESHTER
 */
class CDocumentBase extends clsTinyButStrong{}

/**
 * @ignore
 * @deprecated
 */
define ("_GLOBALVARIABLENAME_DOCUMENT", 	"page");



/**
 * Document class
 *
 * @version $Revision: 1.20 $
 * @package VESHTER
 *
 */
class CDocument extends CObject
{
    static public $keyword_begin = "[var.";
    static public $keyword_end = "]";

    /**
     * @var CDocumentBase
     * @ignore
     */
    protected $base;

    /**
     * Format of the current document
     *
     * @var string
     */
    protected $format;


    /**
     * Runtime cache for reusable templates
     * @var array
     */
    static private $cache = array();


    /**
     * Creates a document from a datagrid template (lookup == true) or from the supplied HTML (lookup == false)
     *
     * @param string $content Name of the template that will be used OR the actual template that will be used.
     * @param string $format Format of the template. HTML, XML etc
     * @param bool $lookup Should be looked up from a database
     * @param int $persistentCacheTimeout Cache timout in seconds
     * @param bool $usePersistentGlobalCache Page specific cache or server global
     */
    function __construct($content = '', $format = 'html', $lookup = true)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.20 $');

        //set up the base object
        $this->base = new CDocumentBase();

        if (CString::IsNullOrEmpty($content))
        {
            $this->Warn('Document has no content');
            //throw new InvalidArgumentException('Document content cannot be empty');
        }

        //we need to look the data up and get it from a somewhere else
        if ($lookup && !CString::IsNullOrEmpty($content))
        {
            //CEnvironment::Dump(CDocument::$cache);
            //CEnvironment::Dump(CDocument::$cache[$content]);

            if (($lookup == true) && array_key_exists($content, CDocument::$cache))
            {
                $this->SetBody(CDocument::$cache[$content]);
                $this->Notify(CString::Format('Template for %s was loaded successfully from cache', $content));
                return true;
            }
            else
            {
                $c = 0;
                //go through all available datagrids and see if you can get the information
                while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
                {
                    $datagrid->SetQuery(
                    sprintf("SELECT %s FROM template WHERE %s",
                    sprintf('body_%s', $format),
                    sprintf('name=%s', CString::Quote($content))
                    )
                    );
                    $this->Notify(sprintf ("Trying to load template from %s using %s", $datagrid->GetSource(), $datagrid->GetQuery()));
                    if ($source = $datagrid->GetValue())
                    {
                        if ($lookup == true)
                        {
                            //save a cached copy
                            CDocument::$cache[$content] = $source;                            
                        }

                        ///CEnvironment::Dump($source);
                        $this->SetBody($source);
                        $this->Notify(CString::Format('Template for %s was loaded successfully from database', $content));

                        return true;
                    }
                    else
                    {
                        $this->Notify($datagrid->GetStatus());

                        //$this->Notify(print_r($datagrid, true));
                    }
                    $c++;
                }
            }
            $this->Warn("Template failed to load successfully");
        }
        //this is dynamic (personalizable content) that was fed to the document
        else
        {
            $this->SetBody($content);
            return true;
        }
        return false;

    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Document/Template keyword
     * ie. %$NAME_FIRST%
     *
     * @see APIDocumentValue
     * @param string $keyword Raw keyword
     * @deprecated
     * @return string Formatted keyword
     */
    static function GetKeyword($keyword)
    {
        $this->NotifyDepreciated('Use an updated keyword construct');
        return CString::Format('%s%s%s', CDocument::$keyword_begin, $keyword, CDocument::$keyword_end);
    }

    function SetFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Returns the format of the document
     *
     * @return string
     */
    function GetFormat()
    {
        return $this->format;
    }

     

    /**
     *
     * @param $title
     * @deprecated
     */
    function SetTitle($title)
    {
        //massage the data a little bit
        $GLOBALS[_GLOBALVARIABLENAME_DOCUMENT]["title"] = $title;
        $GLOBALS[_GLOBALVARIABLENAME_DOCUMENT]["pagetitle"] = $GLOBALS[_GLOBALVARIABLENAME_DOCUMENT]["title"];
        return true;
    }

    function SetBody($body)
    {
        $this->base->Source = $body;

        $self = $_SERVER;
        $self['SCRIPT_VIRTUAL_NAME'] = CEnvironment::GetScriptVirtualName();

        //merge server/self information
        $this->MergeField('self', $self);

        //merge request data
        $this->MergeField('request', $_REQUEST);
    }

    /**
     *@deprecated
     */
    function RestorePseudoCode()
    {
        $this->base->Source = str_replace('&#91;', '[', $this->base->Source);
    }

    /**
     * Returns the source of the document
     *
     * @return string
     */
    function GetBody($merge = true)
    {
        return $this->ToString($merge);
    }


    /**
     * Protect any email addresses that are listed in the contents of the passed in string
     * @param string $output
     * @return string
     */
    static function ProtectEmail(&$output)
    {
        $janitor = new CJanitor();
        $janitor->NotifyDepreciated();
        $output = CString::ProtectEmail($output);
        return $output;
    }

    /**
     * Replaces one or several document fields with a fixed value or one generated by a function
     */
    function MergeField($namelist, $value, $is_function = false)
    {
        //HACK: The TBS base does not return anything on MergeField
        return $this->base->MergeField($namelist, $value, $is_function) || true;
    }

    /**
     * Merges one or several document blocks with records coming from a data source.
     * By default, this method returns the number of merged records
     */
    function MergeBlock($blocklist, $sourceid, $query = '')
    {
        return $this->base->MergeBlock($blocklist, $sourceid, $query);
    }

    function Merge()
    {
        //fix any documents that do not want to conform to using [var.blah] and still have %BLAH%
        $this->base->Source = preg_replace('/\%([A-Z0-9_]*)\%/e', "strtolower('[var.' . _GLOBALVARIABLENAME_DOCUMENT . '.$1;noerr;htmlconv=no]')", $this->base->Source);

        //fix any documents that do not want to conform to using [onshow] and still have %BLAH%
        //[onshow;file=header.htm]
        $this->base->Source = preg_replace('/\%VEPHP include\("(.*)"\); VEPHP\%/', '[onshow;file=default/$1;noerr;htmlconv=no]', $this->base->Source);

        $this->base->meth_Merge_AutoOn($this->base->Source,'onshow',true,true);
        $this->base->meth_Merge_AutoVar($this->base->Source,true);

    }

    function ToString($merge = true, $protectemail = false, $reusablecode = false)
    {
        //do the actual data merging
        if ($merge)
        {

            try
            {
                $application =  CEnvironment::GetMainApplication();


                //CEnvironment::Dump($application);

                if ($application)
                {
                    $this->MergeField('config', $application->GetProperties());

                    $user = CEnvironment::GetMainApplication()->GetUser();

                    //CEnvironment::Dump($user);
                    if ($user)
                    {
                        $this->MergeField('user', $user->GetProperties());
                    }
                }
            }
            catch (CExceptionEx $ex)
            {
                $this->Warn($ex->getMessage());
            }

            $this->Merge();
        }


        $source = $this->base->Source;

        if ($protectemail)
        {
            $source = CString::ProtectEmail($source);
        }

        if ($reusablecode)
        {
            //nothing here
        }

        return $source;
    }


    function ToStringAbsoluteLinks($basehref = _BASEHREF, $merge = true)
    {
        $body = $this->ToString($merge);

        // generate server-only replacement for root-relative URLs
        $server = preg_replace('@^([^\:]*)://([^/*]*)(/|$).*@', '\1://\2/', $basehref);

        // replace root-relative URLs
        $body = preg_replace('@\<([^>]*) (href|src|background)="/([^"]*)"@i', '<\1 \2="' . $server . '\3"', $body);

        // replace base-relative URLs (kludgy, but I couldn't get ! to work)
        $body = preg_replace('@\<([^>]*) (href|src|background)="(([^\:"])*|([^"]*:[^/"].*))"@i', '<\1 \2="' . $server . '\3"', $body);

        return $body;
    }
}

/*
 *
 * Changelog:
 * $Log: class.document.php,v $
 * Revision 1.20  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.19.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.19  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.18  2009-05-26 03:39:44  dkolev
 * Updated code for new version of TBS
 *
 * Revision 1.17  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.16  2008-09-18 16:15:25  dkolev
 * Made the self and request variables always merge in a document.
 *
 * Revision 1.15  2008-06-09 07:21:05  dkolev
 * Changed protecteemail default from true to false. Some forms that use email addresses to send information get errors if the email is obfuscated
 *
 * Revision 1.14  2008/05/31 04:29:07  dkolev
 * Added more parameters to the ToString function
 *
 * Revision 1.13  2008/05/20 01:57:51  dkolev
 * Added try-catch around GetMainApplication call.
 *
 * Revision 1.12  2008/05/18 00:43:27  dkolev
 * Removed unnecessary try-catch blocks
 *
 * Revision 1.11  2008/05/08 04:09:51  dkolev
 * Added a try/catch block to make sure that when the framework is not initialized, document merging does not fail.
 *
 * Revision 1.10  2008/05/06 04:59:01  dkolev
 * Added config and server information as default information for every merge
 *
 * Revision 1.9  2008/02/05 08:56:57  dkolev
 * Added RestorePseudoCode function
 *
 * Revision 1.8  2007/11/12 05:05:44  dkolev
 * Quick fix for MergeField
 *
 * Revision 1.7  2007/09/26 22:38:22  dkolev
 * Changed inheritance
 *
 * Revision 1.6  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/02/28 10:12:54  dkolev
 * Added MergeField
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:57:57  dkolev
 * Made CDocument class non-abstract
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>