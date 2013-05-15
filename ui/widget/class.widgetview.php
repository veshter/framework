<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetview.php,v 1.2.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget that displays views
 *
 * @version $Revision: 1.2.4.1 $
 * @package VESHTER
 *
 */
class CWidgetView extends CWidgetThemed
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2.4.1 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    protected function RenderVisual()
    {

        $view = new CView();

        $xml = $this->data['part'];

        $inline = CEnvironment::GetSubmittedVariable('inline', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('inline'));


        if ($inline)
        {

            //$view->Notify('Marking view as inline');

            //register in the session for alter use
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('inline', $inline);

            $parentkey = CEnvironment::GetSubmittedVariable('parentkey', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('parentkey'));
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('parentkey', $parentkey);

            $parentkey_value = CEnvironment::GetSubmittedVariable($parentkey, CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable($parentkey));
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable($parentkey, $parentkey_value);

            //$view->Notify(CString::Format('Setting key to %s = %s', $parentkey, $parentkey_value));
            CEnvironment::RegisterGlobalVariable($parentkey, $parentkey_value);

            //since the template may contain, config params, we should use them
            $doc_config = new CDocument($xml, 'xml', false);

            $doc_config->MergeField('parent', array('keyname' => $parentkey, 'keyvalue' => $parentkey_value));
            $xml = $doc_config->ToString();
        }

        //The use of a view to configre with the above xml for use
        if ($view->Configure($xml))
        {
            $this->data['part'] = $view->ToString();
            	
            //print out the status message
            $temp = new CWidgetStatus();
            if ($view->GetStatus())
            {
                $temp->LoadData($view->GetStatus()->GetMessages());
                $status = $temp->ToString();

                $this->data['status'] = $status;
            }
        }
        else
        {
            $this->data['part'] = 'View failed to configure correctly';
        }

        //CEnvironment::Dump(CEnvironment::GetMainApplication()->GetDataGrid()->GetDatabaseLink()->GetStatus());
    }

}


/*
 *
 * Changelog:
 * $Log: class.widgetview.php,v $
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-02-28 19:40:31  dkolev
 * Refactoring
 *
 * Revision 1.1  2009-12-27 19:57:18  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.3  2009-04-09 09:38:53  dkolev
 * Implemented the RenderVisual function
 *
 * Revision 1.2  2009-03-29 20:56:04  dkolev
 * *** empty log message ***
 *
 * Revision 1.1  2009-03-29 20:55:37  dkolev
 * Initial import
 *
 */


?>