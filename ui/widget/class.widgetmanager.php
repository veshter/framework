<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.widgetmanager.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Widget that displays managers
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CWidgetManager extends CWidgetThemed
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
    
    protected function RenderVisual()
    {


        //in the event a manager needs to use more memory
        ini_set("memory_limit","128M");

        $manager = new CManager();

        $xml = $this->data['part'];

        $inline = CEnvironment::GetSubmittedVariable('inline', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('inline'));


        if ($inline)
        {

            //$manager->Notify('Marking manager as inline');

            //register in the session for alter use
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('inline', $inline);

            $parentkey = CEnvironment::GetSubmittedVariable('parentkey', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('parentkey'));
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('parentkey', $parentkey);

            $parentkey_value = CEnvironment::GetSubmittedVariable($parentkey, CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable($parentkey));
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable($parentkey, $parentkey_value);

            //$manager->Notify(CString::Format('Setting key to %s = %s', $parentkey, $parentkey_value));
            CEnvironment::RegisterGlobalVariable($parentkey, $parentkey_value);

            //since the template may contain, config params, we should use them
            $doc_config = new CDocument($xml, 'xml', false);

            $doc_config->MergeField('parent', array('keyname' => $parentkey, 'keyvalue' => $parentkey_value));
            $xml = $doc_config->ToString();
        }



        //The use of a manager to configre with the above xml for use
        if ($manager->Configure($xml))
        {
            $this->data['part'] = $manager->ToString();
            	
            //print out the status message
            $temp = new CWidgetStatus();
            if ($manager->GetStatus())
            {
                $temp->LoadData($manager->GetStatus()->GetMessages());
                $status = $temp->ToString();

                $this->data['status'] = $status;
            }
        }
        else
        {
            $this->data['part'] = 'Manager failed to configure correctly';
        }

    }

}


/*
 *
 * Changelog:
 * $Log: class.widgetmanager.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
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
 * Revision 1.7  2009-03-29 20:57:52  dkolev
 * Made the status part of the member variables
 *
 * Revision 1.6  2009-03-21 22:59:10  dkolev
 * Changed status message
 *
 * Revision 1.5  2009-02-03 07:46:51  dkolev
 * *** empty log message ***
 *
 * Revision 1.4  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.3  2008-01-29 01:48:33  dkolev
 * Increased the memory for managers to 128Mb
 *
 * Revision 1.2  2008/01/29 01:22:28  dkolev
 * Removed H@CK for the form in inline managers
 *
 * Revision 1.1  2007/12/20 22:57:36  dkolev
 * Initial import
 *
 */


?>