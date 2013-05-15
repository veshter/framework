<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetthemed.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Themed widget which can be wrapped with some theme/template
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 *
 */
abstract class CWidgetThemed extends CWidget
{
    protected $theme;
    
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    function SetTheme($theme)
    {
        if ($theme == null)
        {
            throw new CExceptionNotFound('Could not find a suitable theme');
        }

        $this->theme = $theme;
    }

    protected function GetScriptMergeData($part, &$context)
    {
        $interpreter = new CCodeInterpreter();

        //eval code before merging the main sections
        $data = array();
        $script = $this->data[$part];

        //CEnvironment::Dump($script);

        if (!empty($script))
        {
            $data = $interpreter->EvalScript($script, $context);
        }
        return $data;
    }

    protected function RenderVisual()
    {
        //does nothing, may be overridden later

    }

    function ToString()
    {
        //CEnvironment::Dump($this);

        $premerge = array();
        $postmerge = array();

        //take the pre and post merges from the theme
        $theme = new CWidgetPage();
        $theme->LoadData($this->theme);

        //CEnvironment::Dump(CEnvironment::GetMainApplication()->GetDataGrid()->GetDatabaseLink()->GetStatus());

        $premerge = $this->GetScriptMergeData('part9', $this->data);
        $premerge = array_merge($theme->GetScriptMergeData('part9', $this->data), $premerge);

        $final = new CDocument($this->theme['part'], 'mixed', false);

        //merge any code before the actual page contents
        $final->MergeField('premerge', $premerge);

        //render the visual input (will most likely be overridden
        $this->RenderVisual();

        $final->MergeField('page', $this->data);

        $postmerge = $this->GetScriptMergeData('part10', $this->data);
        $postmerge = array_merge($theme->GetScriptMergeData('part10', $this->data), $postmerge);

        $final->MergeField('postmerge', $postmerge);

        return $final->ToString();
    }

    function Render()
    {
        //TODO: Is this safe? While the page can allow subsequent merges are those really necessary?
        CEnvironment::Write($this->ToString());
    }

}


/*
 *
 * Changelog:
 * $Log: class.widgetthemed.php,v $
 * Revision 1.5  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.4.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.4  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.3  2010-03-01 06:44:34  dkolev
 * Fixed a bug introduced by previous checkin
 *
 * Revision 1.2  2010-03-01 05:04:09  dkolev
 * Cleaned up the code organization to be more readable. Removed redundant calls/merges.
 *
 * Revision 1.1  2010-02-28 19:40:31  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.5  2009-04-09 09:38:23  dkolev
 * Changed merge order
 *
 * Revision 1.4  2009-03-30 01:06:29  dkolev
 * Added context
 *
 * Revision 1.3  2009-03-29 20:58:18  dkolev
 * Split ToString and Render functions
 *
 * Revision 1.2  2009-02-03 07:46:51  dkolev
 * *** empty log message ***
 *
 * Revision 1.1  2007-12-20 22:57:37  dkolev
 * Initial import
 *
 */


?>