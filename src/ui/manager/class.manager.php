<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.manager.php,v 1.2.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Callback function name for creating a record
 *
 */
if (!defined('_CALLBACK_CREATE'))
{
    define('_CALLBACK_CREATE', 		'create');
}
/**
 * Callback function name for when the a record is created
 *
 */
if (!defined('_CALLBACK_CREATED'))
{
    define('_CALLBACK_CREATED', 	'created');
}
/**
 * Callback function name for viewing records in a manager
 *
 */
if (!defined('_CALLBACK_VIEW'))
{
    define('_CALLBACK_VIEW', 		'view');
}
/**
 * Callback function name for creating a record
 *
 */
if (!defined('_CALLBACK_VIEW_THIS'))
{
    define('_CALLBACK_VIEW_THIS', 	'view_this');
}
/**
 * Callback function name for when the a record is view
 *
 */
if (!defined('_CALLBACK_VIEWED'))
{
    define('_CALLBACK_VIEWED', 		'viewed');
}
/**
 * Callback function name for editing records in a manager
 *
 */
if (!defined('_CALLBACK_EDIT'))
{
    define('_CALLBACK_EDIT', 		'edit');
}
/**
 * Callback function name for exporting records in a manager
 *
 */
if (!defined('_CALLBACK_EXPORT'))
{
    define('_CALLBACK_EXPORT', 		'export');
}
/**
 * Callback function name for editing a particular
 *
 */
if (!defined('_CALLBACK_EDIT_THIS'))
{
    define('_CALLBACK_EDIT_THIS', 	'edit_this');
}
/**
 * Callback function name for when the a record is edited
 *
 */
if (!defined('_CALLBACK_EDITED'))
{
    define('_CALLBACK_EDITED', 		'edited');
}
/**
 * Callback function name for deleting records in a manager
 *
 */
if (!defined('_CALLBACK_DELETE'))
{
    define('_CALLBACK_DELETE', 		'delete');
}
/**
 * Callback function name for editing a particular
 *
 */
if (!defined('_CALLBACK_DELETE_THIS'))
{
    define('_CALLBACK_DELETE_THIS', 'delete_this');
}
/**
 * Callback function name for when the a record is deleted
 *
 */
if (!defined('_CALLBACK_DELETED'))
{
    define('_CALLBACK_DELETED', 	'deleted');
}
/**
 * A manager is a data manipulator.
 *
 * It may or may not have categories and will have mandatory funcionality such as adding and removing content.
 * It may have custom functionality which can be achieved through the use of callback functions and/or function overrides of the parent class functions.
 *
 * @version $Revision: 1.2.4.1 $
 * @package VESHTER
 * @todo Add logging of user actions so that if someone does something using a manager, a log will keep track
 * @todo Add version/revision control
 */
class CManager extends CGadget
{

    /**
     * Flag signifying inline manager property
     */
    static public $flag_inline = 'inline';

    /**
     * Flag signifying manager command switch
     */
    static public $flag_cmd = 'do';

    static public $flag_name = 'name';

    static public $flag_copy = 'copy';

    protected $parentKey = null;

    function __construct($readonly = false) 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2.4.1 $');

         //register some callbacks
        if(!$readonly)
        {
            $this->RegisterCallback(_CALLBACK_CREATE,		'Create');
            $this->RegisterCallback(_CALLBACK_CREATED,		'Created');
            $this->RegisterCallback(_CALLBACK_EDIT_THIS,	'EditThis');
            $this->RegisterCallback(_CALLBACK_EDITED,		'Edited');
            $this->RegisterCallback(_CALLBACK_DELETE,		'Delete');
            $this->RegisterCallback(_CALLBACK_DELETE_THIS,	'DeleteThis');
            $this->RegisterCallback(_CALLBACK_DELETED, 		'Deleted');
        }
        else
        {
            $this->RegisterCallback(_CALLBACK_EDIT_THIS,	'View');
            $this->RegisterCallback(_CALLBACK_DELETED, 		'View');
        }
        $this->RegisterCallback(_CALLBACK_EXPORT,		'Export');
        $this->RegisterCallback(_CALLBACK_EDIT,			'Edit');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function Configure(&$xml, $merge = true)
    {
        if (parent::Configure($xml, $merge))
        {
            return true;
        }
    }

    function GetParentKey()
    {
        return $this->parentKey;
    }

    function SetParentKey($key)
    {
        $this->parentKey = $key;
    }

    function Create()
    {
        //$specs = $this->config->GetElement('/manager[1]');

        return $this->EditThis();
    }

    function Created()
    {
        $specs = $this->config->GetElement('/manager[1]');

        $key = CEnvironment::GetSubmittedVariable($specs['attributes']['key']);

        return 'Created new record';
    }

    function Edit()
    {
        $specs = $this->config->GetElement('/manager[1]');
        $view = $this->config->GetElement('/manager[1]/view[1]');

        $this->config->SetAttribute($view['xpath'], 'key', 			$specs['attributes']['key']);
        $this->config->SetAttribute($view['xpath'], 'datagrid', 	$specs['attributes']['datagrid']);
        $this->config->SetAttribute($view['xpath'], 'location', 	$specs['attributes']['location']);

        $xml = $this->config->GetElementXML($view['xpath']);

        $view = null;
        switch ($specs['attributes']['layout'])
        {

            case 'calendar':
                $view = new CViewCalendar();
                break;
            default:
                $view = new CView();
                break;
        }


        if ($view->Configure($xml, false))
        {
            return $view->ToString();
        }
        else
        {
            throw new CExceptionNotConfigured('View could not be configured correctly.');
        }
    }

    function Export()
    {
        //$specs = $this->config->GetElement('/manager[1]');
        throw new CExceptionNotImplemented("Exporting is not supported");

    }

    function EditThis()
    {
        $specs = $this->config->GetElement('/manager[1]');

        //find the correct XML segment for the form we need
        foreach ($specs['childNodes'] as $form)
        {

            //use the form necessary
            if (($form['name'] == 'form') && ($form['attributes']['name'] == _CALLBACK_EDIT_THIS))
            {
                //set nested attributes inherited from the manager
                $this->config->SetAttribute($form['xpath'], "key", 			$specs['attributes']['key']);
                $this->config->SetAttribute($form['xpath'], 'datagrid', 	$specs['attributes']['datagrid']);
                $this->config->SetAttribute($form['xpath'], "location", 	$specs['attributes']['location']);

                $xml = $this->config->GetElementXML($form['xpath']);

                break;

            }
        }

        $form = new CForm();
        if ($form->Configure($xml, false))
        {
            return $form->ToString();
        }
        else
        {
            throw new CExceptionNotConfigured('Form could not be configured correctly.');
        }
    }

    function Edited()
    {
        $specs = $this->config->GetElement('/manager[1]');

        //find the correct XML segment for the form we need
        foreach ($specs['childNodes'] as $form)
        {

            //use the form necessary
            if (($form['name'] == 'form') && ($form['attributes']['name'] == _CALLBACK_EDIT_THIS))
            {
                //set nested attributes inherited from the manager
                $this->config->SetAttribute($form['xpath'], "key", 			$specs['attributes']['key']);
                $this->config->SetAttribute($form['xpath'], "location", 	$specs['attributes']['location']);

                $xml = $this->config->GetElementXML($form['xpath']);

                break;

            }
        }

        $form = new CForm();
        if ($form->Configure($xml))
        {
            //validate the submitted data before proceeding
            if (!$form->ValidateData())
            {
                $this->Warn($form->GetStatus()->GetLastError());
                return $form->ToString();
            }
        }
        else
        {
            throw new CExceptionNotConfigured('Form could not be configured correctly.');
        }


        $key = CEnvironment::GetSubmittedVariable($specs['attributes']['key']);

        try
        {
            $data = $form->GetSubmittedHash();
        }
        catch (CExceptionEx $ex)
        {
            $this->Warn($ex->getMessage());
            return $form->ToString();
        }
        	
        //CEnvironment::Dump($_POST);
        //CEnvironment::Dump($data);
        //CEnvironment::Dump($specs['attributes']);

        if (count($data))
        {
            $cmd = CEnvironment::GetSubmittedVariable(CManager::$flag_cmd);
            $copy = CEnvironment::GetSubmittedVariable(CManager::$flag_copy);

            if (!empty($copy))
            {
                //remove the key data so that the value is inserted again and not just copied
                unset ($data[$specs['attributes']['key']]);

            }



            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            //if there is a primary key defined we have an existing record that we are editing.
            if (!empty($data[$specs['attributes']['key']]))
            {
                //remove the key because we don't want to update that
                unset($data[$specs['attributes']['key']]);

                //extract the values left from the _POST
                $keys = array_keys($data);
                $values = array_values($data);

                //go through the submitted text data and do final manipulations
                //for ($loop = 0; $loop < count($values); $loop++)
                //{
                //	//clean slashes that might have creeped in
                //	$values[$loop] = stripslashes($values[$loop]);
                //}



                //CEnvironment::Dump($keys);
                //CEnvironment::Dump($values);

                if ($datagrid->Update($specs['attributes']['location'], $keys, $values, sprintf('%s=%s', $specs['attributes']['key'], CString::Quote($key))))
                {
                    $this->Notify(sprintf('Record %s was updated', $key));
                }
                else
                {
                    $this->Warn(sprintf('Record %s was NOT updated', $key));
                    $this->Warn($datagrid->GetDatabaseLink()->GetStatus()->GetLastError());
                    return $this->EditThis();

                }

            }
            //otherwise, we are dealing with a new record
            else
            {
                //the key type is not defined, we will assume guid
                switch ($specs['attributes']['keytype'])
                {
                    case "int":
                        //most likely the key will be autogenereated by the target table
                        break;
                    case "guid":
                    default:
                        $guid = new CGuid();
                        $key = $guid->ToString();
                        $data[$specs[attributes][key]] = $key;
                }

                //extract the values left from the _POST
                $keys = array_keys($data);
                $values = array_values($data);

                //go through the submitted text data and do final manipulations
                //for ($loop = 0; $loop < count($values); $loop++)
                //{
                //	//clean slashes that might have creeped in
                //	$values[$loop] = stripslashes($values[$loop]);
                //}



                //CEnvironment::Dump($keys);
                //CEnvironment::Dump($values);

                if ($datagrid->Insert($specs[attributes][location], $keys, $values))
                {
                    $this->Notify(sprintf('Record %s was created', $key));
                }
                else
                {
                    $this->Warn(sprintf('Record %s was NOT created', $key));
                    $this->Warn($datagrid->GetDatabaseLink()->GetStatus()->GetLastError());
                    return $this->EditThis();
                }
            }

            //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());

            //CEnvironment::Dump($datagrid->GetStatus());
        }
        return $this->Edit();
    }

    function Delete()
    {
        return $this->Deleted();
    }

    function DeleteThis()
    {
        //generate confirmation form
        return false;
    }

    function Deleted()
    {



        $specs = $this->config->GetElement('/manager[1]');
        $eraser = $this->config->GetElement('/manager[1]/eraser[1]');

        $key = CEnvironment::GetSubmittedVariable($specs['attributes']['key']);


        if ($key)
        {
            if (!is_array($key))
            {
                $key = array($key);
            }

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);
            for ($loop = 0; $loop < count($key); $loop++)
            {
                //if there is a relation, erase the whole tree
                if ($eraser)
                {
                    $this->config->SetAttribute($eraser['xpath'], 'key', 		$specs['attributes']['key']);
                    $this->config->SetAttribute($eraser['xpath'], 'datagrid', 	$specs['attributes']['datagrid']);
                    $this->config->SetAttribute($eraser['xpath'], 'location', 	$specs['attributes']['location']);
                    $this->config->SetAttribute($eraser['xpath'], 'value', 		$key[$loop]);

                    $xml = $this->config->GetElementXML($eraser['xpath']);

                    //CEnvironment::Dump($xml);

                    $erase = new CEraser();
                    if ($erase->Configure($xml))
                    {
                        if ($erase->Commit())
                        {
                            $this->Notify(CString::Format('Record %s and all related data was deleted', $key[$loop]));
                            //CEnvironment::Dump($eraser->GetStatus());
                        }
                        else
                        {
                            $this->Notify(CString::Format('Record %s or its related data was NOT deleted', $key[$loop]));
                            //$this->Notify($datagrid->GetDatabaseLink()->GetStatus());
                        }
                    }
                    else
                    {
                        throw new CExceptionNotConfigured('Could not configure eraser');
                    }
                }
                else
                {
                    if ($datagrid->Delete($specs['attributes']['location'], CString::Format('%s=%s', $specs['attributes']['key'], CString::Quote($key[$loop]))))
                    {
                        $this->Notify(CString::Format('Record %s was deleted', $key[$loop]));
                    }
                    else
                    {
                        $this->Notify(CString::Format('Record %s was NOT deleted', $key[$loop]));
                        //$this->Notify($datagrid->GetDatabaseLink()->GetStatus());
                    }
                }
            }
            return $this->Edit();

        }
        throw new CExceptionNotConfigured('Key was not defined');

        //		$specs = $this->config->GetElement('/manager[1]');
        //
        //		$key = CEnvironment::GetSubmittedVariable($specs[attributes][key]);
        //
        //		if ($key)
        //		{
        //			$datagrid = CEnvironment::GetMainApplication()->GetDataGrid();
        //			if ($datagrid->Delete($specs[attributes][location], sprintf('%s=%s', $specs[attributes][key], CString::Quote($key))))
        //			{
        //				$this->Notify('Record %s was deleted', $key);
            //			}
            //			else
            //			{
            //				$this->Notify('Record %s was deleted', $key);
                //			}
                //			return $this->Edit();
                //		}
                //
        //		return ('Key was not defined');
    }

}


/*
 *
 * Changelog:
 * $Log: class.manager.php,v $
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.17  2009-09-13 13:29:02  dkolev
 * Added option merging of global date with configuration XML in the Configure function.
 *
 * Revision 1.16  2008-05-06 04:56:58  dkolev
 * Added explicit datagrids.
 *
 * Revision 1.15  2008/02/05 08:57:41  dkolev
 * The manager wasn't working right when multiple entries were deleted due to variable misuse.
 *
 * Revision 1.14  2008/01/29 05:23:03  dkolev
 * Changed the view in Edit to not have it's order and group overriden by the manager configuration
 *
 * Revision 1.13  2008/01/29 01:48:33  dkolev
 * Added the eraser/relation functionality.
 * Added checking for form data. If the form throws an exception, the manager can use it properly.
 *
 * Revision 1.12  2007/11/12 05:07:19  dkolev
 * Fixed "Create as new" not working
 *
 * Revision 1.11  2007/10/25 00:37:14  dkolev
 * Added data validation. When the information submitted through the form, the Edited callback will verify it and reject it when it is invalid. This gives the ability to the user to reenter form information
 *
 * Revision 1.10  2007/09/27 00:00:26  dkolev
 * View configuration is trickled down from the manager
 *
 * Revision 1.9  2007/06/25 01:08:15  dkolev
 * Fixed incorrect keys from arrays and hashes
 *
 * Revision 1.8  2007/06/06 14:29:51  dkolev
 * Added Readonly and Guid abilities
 *
 * Revision 1.7  2007/05/17 06:25:00  dkolev
 * Reflect C-names
 *
 * Revision 1.6  2007/04/16 10:53:20  dkolev
 * Removed erronous CVS comments
 *
 * Revision 1.5  2007/02/28 10:12:18  dkolev
 * Changed the CManager not to throw CExceptionEx (because it is abstract) and instead to throw regular Exceptions
 *
 * Revision 1.4  2007/02/28 01:03:43  dkolev
 * Made the static members explicitely public
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