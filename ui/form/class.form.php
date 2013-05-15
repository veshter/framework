<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.form.php,v 1.2.4.3 2012-09-20 22:59:24 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Basic Form
 *
 * @version $Revision: 1.2.4.3 $
 * @package VESHTER
 *
 */
class CForm extends CGadget
{

    protected $data = null;

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2.4.3 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function Configure(&$xml, $merge = true)
    {
        if (parent::Configure($xml, $merge))
        {

            //if there are elements defined without a tab, use the whole form as a single tab
            $specs = $this->config->GetElement("/form[1]");

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            if (!$datagrid)
            {
                throw new CExceptionNotInitialized("No available datagrids found");
            }



            $this->properties['key'] = $specs['attributes']['key'];

            //select a single row of all available items
            $this->properties['location'] = $specs['attributes']['location'];

            $this->properties['template'] = 	!empty($specs['attributes']['template']) ? $specs['attributes']['template'] : "block.form.generic";
            $this->properties['prefix_template_element'] = 	!empty($specs['attributes']['prefix_template_element']) ? $specs['attributes']['prefix_template_element'] : "block.form.element";
            	
            $this->properties['action'] = 		!empty($specs['attributes']['action']) ? $specs['attributes']['action'] : CEnvironment::GetScriptVirtualName();
            $this->properties['method'] = 		!empty($specs['attributes']['method']) ? $specs['attributes']['method'] : "POST";

            //$this->properties[flag_copy] = 		!empty($specs['attributes'][flag_copy]) ? $specs['attributes'][flag_copy] : "";

            //form labels
            $this->properties['label_ok'] = 	!empty($specs['attributes']['label_ok']) ? $specs['attributes']['label_ok'] : "OK";//"OK"
            $this->properties['label_apply'] = 	!empty($specs['attributes']['label_apply']) ? $specs['attributes']['label_apply'] : "";//"Apply";
            $this->properties['label_reset'] =	!empty($specs['attributes']['label_reset']) ? $specs['attributes']['label_reset'] : "";//"Reset";
            $this->properties['label_cancel'] =	!empty($specs['attributes']['label_cancel']) ? $specs['attributes']['label_cancel'] : "Cancel";


            $c_tab = 0;
            foreach ($specs['childNodes'] as $tab)
            {
                //make sure we are dealing with specs and not something else
                if (($tab['name'] == "tab") || ($tab['name'] == "elements"))
                {
                    //if there is a workgroup specified, check to see if the user has access to this tab
                    if (!empty($tab['attributes']['workgroup']))
                    {
                        $sentry = CEnvironment::GetMainApplication()->GetSentry();
                        $user = CEnvironment::GetMainApplication()->GetUser();

                        if ($user && $sentry)
                        {

                            if ($user->IsSuperuser() || $sentry->ValidateAccess($user->GetId(), $link['workgroup']))
                            {
                                //all good, user has access
                            }
                            else
                            {
                                continue;
                            }
                        }
                        else
                        {
                            throw new CExceptionSecurity('This form uses tab authentication');
                        }
                    }


                    $tab_title = $tab['attributes']['name'];
                    $this->properties['tabs'][count($this->properties['tabs'])] = $tab_title;

                    foreach ($tab['childNodes'] as $element)
                    {
                        //make sure the element has a name
                        if (!empty($element['attributes']['name']) && !empty($element['attributes']['type']))
                        {
                            $type = $element['attributes']['type'];

                            $temp = CElementWebFactory::Create($type);
                            if ($temp != null)
                            {
                                //assign default attributes
                                $temp->SetAttribute('template', CString::Format("%s.%s", $this->properties['prefix_template_element'], $type));

                                //assign all attributes to the newly created element (this will also take care of default values)
                                foreach($element['attributes'] as $name => $value)
                                {
                                    $temp->SetAttribute($name, $value);
                                }

                                //if the element has a persistant (session) variable, look it up
                                if ($temp->GetAttribute('persistent') == 'yes')
                                {
                                    $value = CEnvironment::GetSubmittedVariable($temp->GetAttribute('name'), CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable($temp->GetAttribute('name'), $temp->GetAttribute('value')));
                                }
                                //otherwise, rely on what was submitted or the default value
                                else
                                {
                                    $value = CEnvironment::GetSubmittedVariable($temp->GetAttribute('name'), $temp->GetAttribute('value'));
                                }

                                //value is not an array and could potentially need to be reorganized
                                if (!is_array($value))
                                {
                                    //if the field supports options we go the extra step
                                    if ($temp->SupportsOptions())
                                    {
                                        if (preg_match(CString::Format('/%s/', CParserString::$delimiter_item), $value))
                                        {

                                            $parser = new CParserString();
                                            if ($parser->Parse($value))
                                            {
                                                //TODO: Add more error checking to the options if they were parsed properly or if they were in the correct format to begin with

                                                $value = $parser->GetNodes();

                                                //we want only the first possible group or items, the others are not applicable
                                                $value = $value[0][0];

                                            }
                                        }
                                    }
                                }

                                //get the value that might have been submitted by the user
                                $temp->SetValue($value);


                                //if the element defines a parent key, use it
                                $parentkey = $temp->GetAttribute('parentkey');
                                if (!empty($parentkey))
                                {
                                    $parentkey_value = CEnvironment::GetSubmittedVariable($this->properties['key']);
                                    if (empty($parentkey_value))
                                    {
                                        $temp->SetAttribute(CString::Format('href'), 'unavailable.vesh?do=noparentkey');
                                    }
                                    $temp->SetAttribute(CString::Format('parentkey_value'), $parentkey_value);
                                }

                                //make a diferentiation between regular and command elements
                                //command elements are hidden and are never seen by the end user
                                //regular elements are text boxes, etc
                                $nature = "elements";
                                switch($type)
                                {
                                    case 'hidden':
                                    case 'random':
                                        $nature = 'commands';
                                        break;
                                    case 'imanager':
                                        break;
                                    default:
                                        $this->properties['fields'][] = $temp->GetAttribute('name');
                                }

                                //figure out if there was a value passed in or taken from a data grid
                                $index = count($this->properties[$nature][$tab_title]);
                                $this->properties[$nature][$tab_title][$index]['title'] = $temp->GetTitle();

                                //we need options for this field, if we don't have it will complain
                                if ($temp->SupportsOptions())
                                {
                                    //see if we should look up the options from somewhere
                                    $location = $temp->GetAttribute('location');
                                    if (!empty($location))
                                    {
                                        $helper = new CDatabaseHelper();
                                        $helper->SetLocation($location);
                                        //treat the options as the actual keys specification
                                        $parser = new CParserString();

                                        if ($parser->Parse($temp->GetAttribute('options')))
                                        {
                                            $options = $parser->GetNodes();
                                        }
                                        else
                                        {
                                            throw new CExceptionInvalidFormat("Options lookup specifications as invalid");
                                        }

                                        //we need the last two parts of the options value
                                        $helper->SetKeys($options[0][0]);

                                        $helper->SetWhere($temp->GetAttribute('where'));
                                        $helper->SetOrderBy($temp->GetAttribute('orderby'));
                                        $helper->SetGroupBy($temp->GetAttribute('groupby'));
                                        //use the limit or give the maz possible
                                        $limit = $temp->GetAttribute('limit');
                                        $helper->SetLimit(!empty($limit) ? $limit : -1);

                                        //CEnvironment::Dump($helper);

                                        if ($datagrid->Select($helper->GetLocation(), $helper->GetKeys(), $helper->GetWhere(), $helper->GetLimit(), $helper->GetOrderBy(), $helper->GetGroupBy()))
                                        {
                                            $options = array();
                                            foreach ($datagrid->Get(false) as $option)
                                            {
                                                //make sure there is a secondary "key" value for this option (i.e some ID)
                                                if (!empty($option[1]))
                                                {
                                                   $options[] = CString::Concatenate(array($option[0], $option[1]), CParserString::$delimiter_item, CParserString::$delimiter_item_ascii );
                                                }
                                                else
                                                {
                                                    $options[] = $option[0];
                                                }
                                            }
                                            $temp->SetOptions(CString::Concatenate($options, CParserString::$delimiter_group, CParserString::$delimiter_group_ascii));
                                        }                                                    

                                        //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
                                    }
                                    else
                                    {
                                        $temp->SetOptions($temp->GetAttribute('options'));
                                    }

                                    //CEnvironment::Dump($temp->GetOptions());
                                }



                                $this->properties[$nature][$tab_title][$index]['field'] = $temp;//->ToString();

                                //additional information
                                $this->properties[$nature][$tab_title][$index]['example'] = $temp->GetAttribute("example");
                                $this->properties[$nature][$tab_title][$index]['description'] = $temp->GetAttribute("description");
                            }
                            else
                            {
                                $this->Warn("Could not determine the type of element " . $element['attributes']['name'] . " and it will not be used.");
                            }
                        }

                    }
                }
                $c_tab++;
            }

            return true;
        }
        return false;
    }

    function ValidateData()
    {
        //fill in the data in the form
        //go through the commands in the elements
        foreach(array('commands', 'elements') as $nature)
        {
            //go through the tab page
            foreach(array_keys($this->properties[$nature]) as $tab)
            {
                //go through the fields
                foreach(array_keys($this->properties[$nature][$tab]) as $index)
                {
                    $temp = $this->properties[$nature][$tab][$index]['field'];


                    //apply to single items and array items
                    $minlength = $temp->GetAttribute('minlength');
                    $maxlength = $temp->GetAttribute('maxlength');

                    if (is_numeric($minlength) && is_numeric($maxlength) && ($minlength > $maxlength))
                    {
                        throw new CExceptionNotConfigured(CString::Format('Minimum value length for %s is greater than the maximum value length', $temp->GetAttribute('name')));
                    }

                    //apply to arrays only
                    $mincount = $temp->GetAttribute('mincount');
                    $maxcount = $temp->GetAttribute('maxcount');

                    if (is_numeric($mincount) && is_numeric($maxcount) && ($mincount > $maxcount))
                    {
                        throw new CExceptionNotConfigured(CString::Format('Minimum value count for %s is greater than the maximum value count', $temp->GetAttribute('name')));
                    }

                    //semi-complex regex
                    $validation = $temp->GetAttribute('validation');

                    $value = $temp->GetValue();

                    //for simplicity's sake we'll compare in terms of arrays
                    if (!is_array($value))
                    {

                        $value = array($value);

                    }

                    try
                    {

                        if (!empty($mincount) && is_numeric($mincount))
                        {
                            if (count($value) < $mincount)
                            {
                                throw new CExceptionInvalidData(CString::Format('Value must have at least %d option(s) selected', $mincount));
                            }
                            //special case
                            else if ((count($value) == 1) && (empty($value[0])))
                            {
                                throw new CExceptionInvalidData(CString::Format('Value must have at least %d option(s) selected', $mincount));
                            }
                        }

                        if (!empty($maxcount) && is_numeric($maxcount))
                        {
                            if (count($value) > $maxcount)
                            {
                                throw new CExceptionInvalidData(CString::Format('Value must have at most %d option(s) selected', $maxcount));
                            }
                        }

                        //go into each element and check the array items
                        foreach ($value as $chunk)
                        {
                            if (!empty($minlength) && is_numeric($minlength))
                            {
                                if (strlen($chunk) < $minlength)
                                {
                                    throw new CExceptionInvalidData(CString::Format('Value must be at least %d character(s) long', $minlength));
                                }
                            }

                            if (!empty($maxlength) && is_numeric($maxlength))
                            {
                                if (strlen($chunk) > $maxlength)
                                {
                                    throw new CExceptionInvalidData(CString::Format('Value must be at most %d character(s) long', $maxlength));
                                }
                            }

                            if (!empty($validation))
                            {
                                if (!preg_match($validation, $chunk))
                                {
                                    throw new CExceptionInvalidData('Value contains invalid charater(s)');
                                }
                            }
                        }
                    }
                    catch (CExceptionEx $ex)
                    {

                        $title = $temp->GetAttribute('title');
                        if (empty($title))
                        {
                            $title = $temp->GetAttribute('name');
                        }

                        $this->Warn(CString::Format('%s contains invalid data which does not pass validation: %s', $title, $ex->getMessage()));
                        return false;
                    }
                }
            }
        }
        return true;
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
    function GetSubmittedVariable($name, $default = null)
    {
        throw new CExceptionNotImplemented();
    }

    /**
     * Get a hash variable that is build from data that may have been submitted through a POST or a GET.
     *
     * @param array $vars
     */
    function GetSubmittedHash($vars = array())
    {

        //CEnvironment::Dump($_POST);
        //CEnvironment::Dump($_FILES);

        $data = array();

        //don't waste time is we didn't receive anything
        if (count($_POST))
        {
            $is_copy = false;



            //check for temporary (temp_[field]_date) fields and set record values and keys
            //fill in the data in the form
            //go through the commands in the elements
            foreach(array('commands', 'elements') as $nature)
            {
                //go through the tab page
                foreach(array_keys($this->properties[$nature]) as $tab)
                {
                    //go through the fields
                    foreach(array_keys($this->properties[$nature][$tab]) as $index)
                    {
                        /**
                         * @param CElement
                         */
                        $temp = $this->properties[$nature][$tab][$index]['field'];

                        $key = $temp->GetAttribute('name');
                        $type = $temp->GetAttribute('type');
                        $format = $temp->GetAttribute('format');
                        $readonly = $temp->GetAttribute('readonly');
                        $encryption = $temp->GetAttribute('encryption');

                        //don't worry about passing fields we won't care about
                        if ($readonly != 'yes')
                        {
                            if (!empty($encryption))
                            {
                                switch($encryption)
                                {
                                    case 'basic':
                                        $temp->SetValue(CPassword::OneWayCrypt($temp->GetValue()));
                                        break;
                                    case 'md5':
                                        $temp->SetValue(md5($temp->GetValue()));
                                        break;
                                    case 'sha1':
                                        $temp->SetValue(sha1($temp->GetValue()));
                                        break;
                                    default:
                                        throw new CExceptionEx(CString::Format('Requested %s encryption is not supported', $encryption));
                                        	
                                }
                            }


                            switch($type)
                            {
                                case 'date':
                                case 'datetime':
                                    $data[$key] = strtotime($temp->GetValue());//empty($temp->GetValue()) ? 0 :
                                    break;
                                case 'browse':
                                    $meta = $_FILES[$key];

                                    //the person didn't actually browse for a file.
                                    if(!empty($meta['tmp_name']))
                                    {
                                        if (is_uploaded_file($meta['tmp_name']))
                                        	
                                        {
                                            $data[$key] = file_get_contents($meta['tmp_name']);

                                            //we are working with an image
                                            if ($imagesize = getimagesize($meta['tmp_name'], $info))
                                            {
                                                $meta['width'] = $imagesize[0];
                                                $meta['height'] = $imagesize[1];
                                                $meta['type'] = image_type_to_mime_type($imagesize[2]);
                                            }
                                            $data[CString::Format('meta_%s', $key)] = CCollection::ToXMLWorker($meta, 'meta');

                                        }
                                        else
                                        {
                                            throw new CExceptionInvalidData(CString::Format('File %s failed to upload', $meta['tmp_name']));
                                        }
                                    }

                                    break;

                                    //ignore some fields no matter what
                                    //mostly applies to inline objects
                                case 'imanager':
                                case 'iview':
                                    break;

                                default:
                                    //CEnvironment::WriteLine(CString::Format('Key %s with type %s was not specifically processed', $key, $type));


                                    $value = $temp->GetValue();

                                    if (is_array($value))
                                    {
                                        $value = CString::Concatenate($value, CParserString::$delimiter_item, CParserString::$delimiter_item_ascii);
                                    }

                                    $data[$temp->GetAttribute('name')] = $value;
                                    break;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    	
    function GetData($data)
    {
        return $this->data;
    }

    function SetData($data)
    {
        $this->data = $data;
    }


    /**
     * Creates a string representation of a form
     * @return string
     */
    function ToString()
    {

        //if there are elements defined without a tab, use the whole form as a single tab
        $specs = $this->config->GetElement("/form[1]");

        //see if we should look up the options from somewhere
        $location = $this->properties['location'];
        if (!empty($location))
        {
            $helper = new CDatabaseHelper();
            $helper->SetLocation($location);
            $helper->SetKeys($this->properties['fields']);
            $helper->SetWhere(CString::Format('%s=%s', $this->properties['key'], CString::Quote(CEnvironment::GetSubmittedVariable($this->properties['key']))));

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            if (!$datagrid)
            {
                throw new CExceptionNotInitialized("No available datagrids found");
            }

            //look up existing data
            if ($datagrid->Select($helper->GetLocation(), $helper->GetKeys(), $helper->GetWhere(), $helper->GetLimit(), $helper->GetOrderBy()))
            {
                //great, we have some data
                $this->data = $datagrid->GetRow(0, false);

            }
            	
            //var_dump($this->data);
        }

        //fill in the data in the form
        //go through the commands in the elements
        foreach(array('commands', 'elements') as $nature)
        {
            if (count($this->properties[$nature]) > 0)
            {
                //go through the tab page
                foreach(array_keys($this->properties[$nature]) as $tab)
                {
                    if (count($this->properties[$nature][$tab]) > 0)
                    {
                        //go through the fields
                        foreach(array_keys($this->properties[$nature][$tab]) as $index)
                        {
                            $temp = $this->properties[$nature][$tab][$index]['field'];
                            $temp->SetValue(!empty($this->data[$temp->GetAttribute('name')]) ? $this->data[$temp->GetAttribute('name')] : $temp->GetValue());
                            $this->properties[$nature][$tab][$index]['field'] = $temp->ToString();
                        }
                    }
                }
            }
        }

        $doc = new CDocument($this->properties['template']);



        if ($doc->MergeField('form', $this->properties) > 0)
        {
            $this->Notify("Form created successfully");
        }
        else
        {
            $this->Notify("Form was not necessary or failed to create");
        }

        //merge tabs if necessary
        if ($doc->MergeBlock('tabs',$this->properties['tabs']) > 0)
        {
            $this->Notify("Form tabs created successfully");
        }
        else
        {
            $this->Notify("Form tabs were not necessary or failed to create");
        }

        //merge the form pages
        if ($doc->MergeBlock('pages', $this->properties['tabs']) > 0)
        {
            //HACK: Never do this again. The only reason I exposed this is because of the form[commands][%p1%] TBS loop
            CEnvironment::RegisterGlobalVariable('form', $this->properties);

            $doc->MergeBlock('commands','array','form[commands][%p1%]');
            $doc->MergeBlock('elements','array','form[elements][%p1%]');


        }
        else
        {
            $this->Notify("Form pages failed to create successfully");
        }

        //CEnvironment::Dump($this->GetStatus());
        //CEnvironment::Dump($this->properties);

        return $doc->ToString();
    }}

/*
 *
 * Changelog:
 * $Log: class.form.php,v $
 * Revision 1.2.4.3  2012-09-20 22:59:24  dkolev
 * Form ereg fix
 *
 * Revision 1.2.4.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2.4.1  2011-11-20 22:55:00  dkolev
 * Added better parsing for form data
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.29  2009-09-13 13:29:02  dkolev
 * Added option merging of global date with configuration XML in the Configure function.
 *
 * Revision 1.28  2009-06-20 20:39:21  dkolev
 * Added datetime field
 *
 * Revision 1.27  2009-06-14 08:01:49  dkolev
 * Added explicit data setting
 *
 * Revision 1.26  2009-05-29 01:23:04  dkolev
 * *** empty log message ***
 *
 * Revision 1.25  2009-05-28 20:15:40  dkolev
 * Removed obsolete prefixes from code
 *
 * Revision 1.24  2009-03-30 01:06:07  dkolev
 * Added the random element
 *
 * Revision 1.23  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.22  2008-10-01 05:12:31  dkolev
 * Reverted to version 1.20
 *
 * Revision 1.20  2008-08-29 08:28:37  dkolev
 * Replaced the $GLOBALS call to a CEnvironment one
 *
 * Revision 1.19  2008-08-18 08:31:42  dkolev
 * Minor fix to remove warnings when trying to access empty array
 *
 * Revision 1.18  2008/06/01 09:51:51  dkolev
 * Added persistent form values (from a session)
 *
 * Revision 1.17  2008/05/13 18:35:37  dkolev
 * Added encryption and unavailable resources warning.
 *
 * Revision 1.16  2008/05/06 04:57:46  dkolev
 * Added explicit datagrids.
 *
 * Revision 1.15  2008/04/28 07:33:08  dkolev
 * Added fix for images. Apparently getimagesize doesn't always return the content/type
 *
 * Revision 1.14  2008/02/02 20:07:55  dkolev
 * Removed the faulty error when someone doesn't browse for a file in a Browse field.
 *
 * Revision 1.13  2008/01/29 05:22:22  dkolev
 * Added protection on individual tabs
 *
 * Revision 1.12  2008/01/29 01:47:05  dkolev
 * Added checking to make sure a file was indeed uploaded when there is a "browse" input field
 *
 * Revision 1.11  2007/11/26 09:23:23  dkolev
 * Fixed a bug that was causing single value variables to be treated as multi options.
 *
 * Revision 1.10  2007/11/25 10:22:05  dkolev
 * Completed validation
 *
 * Revision 1.9  2007/11/12 05:10:22  dkolev
 * Nothing significant. Rearranged the form merging code
 *
 * Revision 1.8  2007/10/25 00:32:56  dkolev
 * Added the ability to the form to return the submitted data in an associated array
 *
 * Revision 1.7  2007/09/26 22:39:50  dkolev
 * Major code reorganization
 *
 * Revision 1.6  2007/06/25 01:06:37  dkolev
 * Fixed incorrect keys from arrays and hashes
 *
 * Revision 1.5  2007/05/17 06:24:59  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/27 21:24:37  dkolev
 * Added multiple option fields support
 *
 * Revision 1.3  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */

?>