<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.view.php,v 1.3.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * @package VESHTER
 */


/**
 * A basic class that helps generating columns data from some resource
 *
 * @version $Revision: 1.3.4.1 $
 * @package VESHTER
 */
class CColumnHelperView extends CDatabaseHelper
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3.4.1 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    function SetType($type)
    {
        $this->properties['type'] = $type;
    }

    function GetType()
    {
        return $this->properties['type'];
    }

    function SetFormat($format)
    {
        $this->properties['format'] = $format;
    }

    function GetFormat()
    {
        return $this->properties['format'];
    }

    function SetLookupKey($key)
    {
        $this->properties['lookupkey'] = $key;
    }

    function GetLookupKey()
    {
        return $this->properties['lookupkey'];
    }
}

/**
 * A helper class that helps in changing or customizing the view
 *
 * @version $Revision: 1.3.4.1 $
 * @package VESHTER
 */
class CPerspectiveView extends CDatabaseHelper
{

    protected $keyAliases = array();

    protected $pagination = true;


    protected $totalentries = -1;

    /**
     * Current page
     *
     */
    protected $page = -1;

    /**
     * Total number of pages
     */
    protected $pagecount = -1;

    /**
     * Number of entries shown at one go
     */
    protected $range = 25;

    protected $sort = array();

    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3.4.1 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Ignores passed in parameters
     * @see CGadget::Configure()
     */
    function Configure(&$xml, $merge = true)
    {
        //figure out how many total entries we have to potentially show
        $datagrid = CEnvironment::GetMainApplication()->GetDataGrid();

        if ($datagrid->Select($this->GetLocation(), 'COUNT(*) AS total', $this->GetWhere(), -1, $this->GetOrderBy(), $this->GetGroupBy()))
        {
            $data = $datagrid->Get(false);

            //CEnvironment::Dump($data);

            //HACK: Group bys can return more than one row
            if (count($data) > 1)
            {
                $this->totalentries = count($data);
            }
            //otherwise, use the only rows total
            else
            {
                $this->totalentries = $data['0']['total'];
            }
        }

        //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());

        //figure out page we are currently on
        $this->page = CString::Escape(CEnvironment::GetSubmittedVariable('page', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('page', 1)));
        CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('page', $this->page);

        $this->range = CString::Escape(CEnvironment::GetSubmittedVariable('range', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('range', 50)));
        CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('range', $this->range);

        $this->pagecount = $this->totalentries > 0 ? ceil($this->totalentries / $this->range) : 0;

        $orderby = CString::Escape(CEnvironment::GetSubmittedVariable('orderby', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('orderby')));
        //we don't care to save the orderby because the sort variable will persist

        $this->sort = CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('sort');

        //CEnvironment::Dump($orderby);
        //CEnvironment::Dump($this->sort);

        //check to see if the user has requested to order by anything
        if (!empty($orderby))
        {
            $direction = 'ASC';

            //this has never been ordered by
            if (is_array($this->sort) && array_key_exists($orderby, $this->sort))
            {
                if ($this->sort[$orderby] == 'ASC')
                {
                    $direction = 'DESC';
                }
                else
                {
                    $direction = 'ASC';
                }
            }
            else
            {

                //TODO: Make the sort be able to consume more than one selection
                $this->sort = array();
            }


            $this->sort[$orderby] = $direction;

            //save the new sort values
            CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('sort', $this->sort);
        }

        return true;

    }

    public function EnablePagination($enable = true)
    {
        $this->pagination = $enable;
    }

    public function GetKeys()
    {
        if (count($this->keys) != count($this->keyAliases))
        {
            throw new CExceptionInvalidData('Keys and aliases have a different size');
        }

        $keys = array();

        for ($loop = 0; $loop < count($this->keys); $loop++)
        {
            $key = $this->keys[$loop];
            $keyAlias = $this->keyAliases[$loop];

            //make sure the value selected has an explicit name
            if (($key != $keyAlias) && !eregi(' AS ', $keyAlias))
            {
                $key = CString::Format('%s AS %s', $key, $keyAlias);
            }

            $keys[] = $key;

        }

        return $keys;
    }

    public function SetKeyAliases($aliases)
    {
        $this->keyAliases = $aliases;
    }

    public function GetKeyAliases()
    {
        return $this->keyAliases;
    }

    public function SetWhere($where)
    {

        if (!is_array($this->keys) || !count($this->keys))
        {
            throw new CExceptionEx('Specify the view perspective keys before setting the where clause');
        }

        $applyfilter = CEnvironment::GetSubmittedVariable('applyfilter');

        if (empty($where))
        {
            $where = '1';
        }

        //CEnvironment::Dump($this->keyAliases);
        //CEnvironment::Dump($applyfilter);

        if ($applyfilter == 'no')
        {
            //user has requested to remove all filters
            CEnvironment::GetMainApplication()->GetSession()->UnregisterVariable('filter');
        }
        else
        {

            //the user has explicitely requested filtering
            $explicitfilter = ($applyfilter == 'yes');

            //user explicitely requested filtering, pull filter infromation form submitted values
            if ($explicitfilter)
            {
                $filter = CEnvironment::GetSubmittedVariable('filter', CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('filter'));
            }
            //user had not made any explicit request, pull from the session
            else
            {
                $filter = CEnvironment::GetMainApplication()->GetSession()->GetRegisteredVariable('filter');

                //make this available to any documents that may be expecting it
                CEnvironment::RegisterGlobalVariable('filter', $filter);
            }

            //CEnvironment::Dump($filter);

            if (!empty($filter))
            {
                if ($filter == 'null')
                {

                    CEnvironment::GetMainApplication()->GetSession()->UnregisterVariable($filter);
                }
                else
                {
                    foreach ($this->keys as $key)
                    {
                        if (!empty($criteria))
                        {
                            $criteria .= ' OR ';
                        }
                        $criteria .= CString::Format('%s LIKE %s', $key, CString::Quote('%' . $filter . '%'));
                    }
                }


                $where .= CString::Format(' AND (%s) ', $criteria);

                //user has explicitly requested filtering, save into the session for later use
                if ($explicitfilter)
                {
                    CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('filter', $filter);
                }
            }

        }

        //CEnvironment::Dump($where);

        parent::SetWhere($where);
    }

    public function GetLimit()
    {
        if ($this->pagination)
        {

            //there is a problem with the currently selected page
            if (empty($this->page) || ($this->page > $this->pagecount) || ($this->page <= 0))
            {
                $this->page = 1;
                CEnvironment::GetMainApplication()->GetSession()->RegisterVariable('page', $this->page);
            }

            $this->limit_lower = ($this->page-1)*$this->range;
            $this->limit_upper = $this->range;
        }

        return parent::GetLimit();
    }

    public function GetOrderBy()
    {

        if (count($this->sort) == 0)
        {
            return parent::GetOrderBy();
        }

        $sort = '';
        foreach ($this->sort as $field => $direction)
        {

            if (!empty($sort))
            {
                $sort .= ', ';

            }
            $sort .= CString::Format('%s %s', $field, $direction);
        }

        //CEnvironment::Dump($temp = array('self.orderby' => $this->orderby, 'sort array'=>$this->sort, 'sort string'=>$sort));

        return $sort;

    }

    public function GetPages()
    {
        $pages = array();

        if ($this->pagination)
        {

            $bufferlength = 5;
            $leftbuffer = $this->page - $bufferlength;
            if ($leftbuffer <= 0)
            {
                $leftbuffer = 1;
            }

            $rightbuffer = $this->page + $bufferlength;
            if ($rightbuffer > $this->pagecount)
            {
                $rightbuffer = $this->pagecount;
            }

            //for ($loop = $leftbuffer; $loop <= $rightbuffer; $loop++)
            for ($loop = 1; $loop <= $this->pagecount; $loop++)
            {
                $pages[] = $loop;

            }
        }
        return $pages;
    }

    public function GetCurrentPage()
    {
        return $this->page;
    }

    public function GetRanges()
    {
        return array (10,50,100);
    }

    public function GetCurrentRange()
    {
        return $this->range;
    }

    public function ToString()
    {
        throw new CExceptionNotImplemented('A view perspective cannot be printed');
    }
}

/**
 * A view is a data representation utiliti that displays data source information in understandable and usable form
 *
 * @version $Revision: 1.3.4.1 $
 * @package VESHTER
 */
class CView extends CGadget
{

    protected $data = null;

    /**
     * @var CPerspectiveView
     */
    protected $perspective = null;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3.4.1 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Configure(&$xml, $merge = true)
    {
        if (parent::Configure($xml, $merge))
        {
            $specs = $this->config->GetElement("/view[1]");

            //CEnvironment::Dump($specs);


            $this->properties['key'] = $specs['attributes']['key'];

            //select a single row of all available items
            $this->properties['location'] = $specs['attributes']['location'];

            $this->properties['template'] = 	!empty($specs['attributes']['template']) ? $specs['attributes']['template'] : "block.view.generic";
            $this->properties['prefix_template_element'] = 	!empty($specs['attributes']['prefix_template_element']) ? $specs['attributes']['prefix_template_element'] : "block.view.element";


            foreach ($specs['childNodes'] as $element)
            {
                //make sure the element has a name
                if (!empty($element['attributes']['name']) && !empty($element['attributes']['type']))
                {
                    $type = $element['attributes']['type'];

                    //CEnvironment::WriteLine ("Key => value: $key => $value ($loop:" . $this->properties['types'][$loop] . ")<br>");
                    //$column = $this->properties['column'][$loop];

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

                        $location = $temp->GetAttribute('location');
                        if (!empty($location))
                        {
                            $helper = new CColumnHelperView();
                            $helper->SetLocation($location);

                            //we need the last two parts of the options value
                            $helper->SetKeys($temp->GetAttribute('options'));
                            $helper->SetLookupKey($temp->GetAttribute('key'));
                            //$helper->SetWhere($temp->GetAttribute('where'));
                            //$helper->SetOrderBy($temp->GetAttribute('orderby'));
                            //$helper->SetGroupBy($temp->GetAttribute('groupby'));
                            //use the limit or give the maz possible
                            //$limit = $temp->GetAttribute('limit');
                            $helper->SetLimit(1);//!empty($limit) ? $limit : -1);

                            $temp->SetDatabaseHelper($helper);

                            //CEnvironment::Dump($temp);
                        }

                        $this->properties['columns'][$element['attributes']['name']] = $temp;

                        $this->properties['titles'][] = $element['attributes']['title'];
                        $this->properties['fields'][] = $element['attributes']['name'];

                        //the value could be the name of a column in a database table/view or it could be a string
                        //the value could be a combinations/concatination of fields.
                        $value = empty($element['attributes']['value']) ? $element['attributes']['name'] : $element['attributes']['value'];

                        //CEnvironment::Dump($column);

                        $this->properties['values'][] = $value;

                    }
                    else
                    {
                        $this->Warn("Could not determine the type of element " . $element['attributes']['name'] . " and it will not be used.");
                    }
                }
            }

            if (isset($specs['attributes']['location']))
            {
                $this->perspective = new CPerspectiveView();

                $this->perspective->SetLocation($specs['attributes']['location']);
                $this->perspective->SetKeys($this->properties['values']);
                $this->perspective->SetKeyAliases($this->properties['fields']);
                $this->perspective->SetWhere($specs['attributes']['where']);
                $this->perspective->SetOrderBy($specs['attributes']['orderby']);
                $this->perspective->SetGroupBy($specs['attributes']['groupby']);
                if (!empty($specs['attributes']['limit']))
                {
                    $this->perspective->SetLimit($specs['attributes']['limit']);
                    $this->perspective->EnablePagination(false);
                }

                $xml = null;
                return $this->perspective->Configure($xml);
            }
            else
            {
                return true;
            }
        }
        return false;
    }

    function GetData($data)
    {
        return $this->data;
    }

    function SetData($data)
    {
        $this->data = $data;
    }

    protected function FillFields()
    {
        $specs = $this->config->GetElement("/view[1]");

        //see if we should look up the options from somewhere
        $location = $this->properties['location'];

        if (!empty($location))
        {
            $lookups = array();

            $helper = $this->perspective;

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            foreach ($this->data as $index => $row)
            {
                foreach ($row as $key => $value)
                {
                    if (!is_numeric($key))
                    {
                        //$temp = new CElementWeb();

                        $temp = $this->properties['columns'][$key];

                        $value = $row[$temp->GetAttribute('name')];

                        $helper = $temp->GetDatabaseHelper();

                        //CEnvironment::Dump($helper);

                        //we are supposed to lookup the value from somewhere else
                        if (!CString::IsNullOrEmpty($value) && $helper)
                        {
                            //see if there is a looked up value already recorded
                            if (array_key_exists(strval($value), $lookups))
                            {
                                $this->Notify(CString::Format('Using cached lookup for %s', $value));
                                $value = $lookups[$value];
                            }
                            //otherwise look it up
                            else
                            {
                                 
                                 
                                $location = $helper->GetLocation();
                                //only look stuff up if there is a location to look it up from
                                if (!empty($location))
                                {
                                    $keys = $helper->GetKeys();

                                    if (count($keys) != 1)
                                    {
                                        throw new CExceptionInvalidParameter('Invalid lookup key count provided');
                                    }

                                    if ($datagrid->Select($helper->GetLocation(), $helper->GetKeys(), CString::Format('%s=%s', $helper->GetLookupKey(), CString::Quote($value)), $helper->GetLimit()))
                                    {
                                        $this->Notify(CString::Format('Lookup for %s succeeded', $value));
                                        $translation = $datagrid->GetValue();
                                        $lookups[$value] = $translation;
                                        $value = $translation;
                                    }
                                    else
                                    {
                                        $this->Warn(CString::Format('Cannot lookup %s', $value));
                                    }
                                }
                            }

                        }

                        try
                        {
                            $temp->SetValue($value);//!empty($value) ? $value : $temp->GetValue());//'null');//
                            $this->data[$index][$key] = $temp->ToString();
                        }
                        catch (CExceptionEx $ex)
                        {
                            $this->Warn($ex->getMessage());
                            $this->data[$index][$key] = $ex->getMessage();
                        }

                        //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
                        //CEnvironment::Dump($temp);
                    }
                }
            }
        }
    }

    /**
     * Creates a string representation of a form
     *
     * @return string
     */
    function ToString()
    {
        //CEnvironment::EnableDebugging();

        $specs = $this->config->GetElement("/view[1]");

        if ($this->perspective != null)
        {
            $helper = $this->perspective;

            $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($specs['attributes']['datagrid']);

            if (!$datagrid)
            {
                throw new CExceptionNotInitialized("No available datagrids found");
            }

            //look up existing data
            if ($datagrid->Select($helper->GetLocation(), $helper->GetKeys(), $helper->GetWhere(), $helper->GetLimit(), $helper->GetOrderBy(), $helper->GetGroupBy()))
            {
                //great, we have some data
                $this->data = $datagrid->Get(false);

            }
        }

        $lookups = array();

        if (count($this->data))
        {
            $this->FillFields();
        }
        else
        {
            $this->Warn('No data was found or specified');
        }


        //CEnvironment::WriteLine(CString::Format('<!-- query used by view: %s -->', $datagrid->GetDatabaseLink()->GetQuery()));
        //CEnvironment::Dump($this->GetStatus());

        //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());

        //show the data
        $doc = new CDocument($this->properties['template']);

        //column titles
        $doc->MergeBlock('col', $this->properties['titles']) ;

        $doc->MergeBlock('orderby', $this->properties['fields']) ;

        //column keys/names
        //if there are any SQL concats or functions, those should be reslved by now
        $doc->MergeBlock('key', $this->properties['fields']);

        $doc->MergeBlock('row', count($this->data) ? $this->data : array());

        //$doc->MergeField('sessid', CEnvironment::GetMainApplication()->GetSession()->GetSessionId());

        if ($this->perspective != null)
        {
            $doc->MergeField('currentpage', $this->perspective->GetCurrentPage());

            $doc->MergeBlock('page', $this->perspective->GetPages());

            $doc->MergeField('currentrange', $this->perspective->GetCurrentRange());

            $doc->MergeBlock('range', $this->perspective->GetRanges());
        }

        return $doc->ToString();

    }
}

/*
 *
 * Changelog:
 * $Log: class.view.php,v $
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2010-04-05 03:11:07  dkolev
 * Class reorganization
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.33  2009-09-13 13:30:43  dkolev
 * Prepared the class for extension by the calendar view
 *
 * Revision 1.32  2009-06-14 08:01:49  dkolev
 * Added explicit data setting
 *
 * Revision 1.31  2009-05-28 20:16:16  dkolev
 * Removed old & commeted out code.
 *
 * Revision 1.30  2009-04-14 11:57:53  dkolev
 * Added GetKeys function. Changed the way keys are supplied from the perspective. Added a stats message when data is not retrieved.
 *
 * Revision 1.29  2009-04-09 09:37:40  dkolev
 * Changed group counts and filters.
 *
 * Revision 1.28  2009-04-06 03:42:27  dkolev
 * Changes the view lookup of partial values to ignore blank values.
 *
 * Revision 1.27  2009-04-05 05:01:39  dkolev
 * Added minor fix for array_key_exists
 *
 * Revision 1.26  2009-01-31 03:52:24  dkolev
 * Added a catch so that if a value fails, it does not effect the entire view
 *
 * Revision 1.25  2008-12-15 03:54:18  dkolev
 * Added current range and current page
 *
 * Revision 1.24  2008-12-15 03:43:14  dkolev
 * *** empty log message ***
 *
 * Revision 1.23  2008-10-01 05:15:58  dkolev
 * Reverted from before deletion
 *
 * Revision 1.20  2008-09-17 08:48:55  dkolev
 * Changed pagination to show all pages.
 *
 * Revision 1.19  2008-06-01 12:54:53  dkolev
 * Add key/column aliases
 *
 * Revision 1.18  2008/06/01 09:52:55  dkolev
 * Remove the Configure from the CPerspectiveView. Added filters.
 *
 * Revision 1.17  2008/05/18 12:12:27  dkolev
 * Changed from explicit member variables to storing to the object properties in the CViewColumn. Added custom datetime support for view columns
 *
 * Revision 1.16  2008/05/17 23:39:39  dkolev
 * Added links and thumbnails
 *
 * Revision 1.15  2008/05/06 04:56:15  dkolev
 * Added explicit datagrids.
 *
 * Revision 1.14  2008/04/28 07:33:47  dkolev
 * Fixed pagination and added session persistant sorting.
 *
 * Revision 1.13  2008/02/05 23:22:53  dkolev
 * Removed printing of view query.
 *
 * Revision 1.12  2008/02/05 08:58:18  dkolev
 * Pagination fixes
 *
 * Revision 1.11  2008/02/03 10:20:22  dkolev
 * Removed grouping
 * Added pagination
 *
 * Revision 1.9  2007/10/02 06:06:02  dkolev
 * Documentation changes for API doc
 *
 * Revision 1.8  2007/09/23 10:00:26  dkolev
 * Reorganized XML config usage.
 *
 * Revision 1.7  2007/06/25 01:10:38  dkolev
 * Added limits and fixed incorrect hash keys
 *
 * Revision 1.6  2007/05/17 13:51:21  dkolev
 * Documentation changes
 *
 * Revision 1.5  2007/05/17 06:25:05  dkolev
 * Reflect C-names
 *
 * Revision 1.4  2007/02/28 10:09:25  dkolev
 * Removed the global variable registration for $group
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