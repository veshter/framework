<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.application.php,v 1.14 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * Basic VESHTER application
 *
 * @version $Revision: 1.14 $
 * @package VESHTER
 *
 */
abstract class CApplication extends CGadget
{
    /**
     * Cache manager for the application
     * @var CCacheManager
     */
    protected $cacheManager;
    
    /**
     * Datagrid used by the API for data retrieval and storage
     *
     * @var array Array of datagrids that will be used in the order added
     */
    protected $datagrids = array();

    protected $datagrid_count = 0;

    function __construct()
    {
        parent::__construct();
        
        $this->SetVersion('$Revision: 1.14 $');
        
        $this->Notify("Creating cache manager");
        $this->cacheManager = new CCacheManager();
        
    }
    
    function __destruct()
    {
        parent::__destruct();
    }

    function Localize()
    {
        //nothing yet, probably some time issues soon
        return true;
    }

    /**
     * Adds a datagrid to the current API instance
     *
     * @param CDataGrid $datagrid
     * @param mixed $alias
     * @return boolean
     */
    function AddDataGrid(&$datagrid, $alias = null)
    {
        if ($datagrid)
        {
            $this->Notify("New datagrid was added successfully");

            //the grid will be explicitely named
            if (!empty($alias))
            {
                $this->datagrids[$alias] = $datagrid;
            }
            else
            {
                $this->datagrids[$this->datagrid_count] = $datagrid;
                $this->datagrid_count++;
            }

            return true;
        }
        else
        {
            $this->Notify("New datagrid failed to add successfully");
            return false;
        }
    }
    
    /**
     * Gets the cache manager for the application
     *
     * @return CCacheManager
     */
    function &GetCacheManager()
    {
        return $this->cacheManager;
    }
    

    function &GetUser()
    {
        return null;
    }

    /**
     * Returns the currently running datagrid
     *
     * @param mixed $alias
     * @return CDataGrid
     */
    function &GetDataGrid($alias = 0)
    {
        if (empty($alias))
        {
            $alias = 0;
        }

        if (array_key_exists($alias, $this->datagrids))
        {
            return $this->datagrids[$alias];
        }
        	
        return null;
    }

    function SetWorkingPath ($path)
    {
        $this->properties['path_working'] = $path;
    }

    function GetWorkingPath ()
    {
        return $this->properties['path_working'];
    }

    /**
     * Sets the type of the application.
     * If type is other than 'any', the application will restrict and validate access
     *
     * @param $type
     * @return unknown_type
     */
    function SetType($type)
    {
        $this->properties['type'] = $type;
    }

    function GetType()
    {
        return $this->properties['type'];
    }

    function LoadConfiguration()
    {

        $visited = array();

        $c = 0;
        //go through all available datagrids and see if you can get the information
        while (($datagrid = $this->GetDataGrid($c)) != null)
        {
            if ($datagrid->Select('config', 'attribute,value', '1', '-1'))
            {
                $this->Notify('Loading configuration');
                $data = $datagrid->Get(0,false);

                foreach ($data as $row)
                {
                    //only set new configuration properties is the key was not found
                    if (!in_array($row['attribute'], $visited))
                    {
                        $this->Notify(CString::Format('Adding "%s" with value "%s" to configuration', $row['attribute'], $row['value']));

                        $this->RegisterNestedProperty(explode('.', $row['attribute']), $row['value']);
                        $visited[] = $row['attribute'];
                    }
                }
            }
            $c++;

        }

        return true;
    }






}


/*
 *
 * Changelog:
 * $Log: class.application.php,v $
 * Revision 1.14  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.13.4.2  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.13.4.1  2011-06-06 14:54:28  dkolev
 * Fixed the incorrect explode
 *
 * Revision 1.13  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.12  2009-11-15 00:52:42  dkolev
 * Added GetUser stub
 *
 * Revision 1.11  2009-04-06 03:45:28  dkolev
 * Added LoadConfiguration blank method to imply inheriting classes need to override it.
 *
 * Revision 1.10  2008-08-18 08:26:29  dkolev
 * Added localize stub
 *
 * Revision 1.9  2008/05/06 05:03:24  dkolev
 * Added explicit grids that are referenced by strings as opposed to integers. Those cannot be iterated over with GetDataGrid. Instead they should be explicitly requested.
 *
 * Revision 1.8  2007/11/12 05:04:31  dkolev
 * Added WorkingDirectory and Type accessors
 *
 * Revision 1.7  2007/10/08 19:19:42  dkolev
 * Added a constructor
 *
 * Revision 1.6  2007/08/08 11:02:20  dkolev
 * Inheritance change
 *
 * Revision 1.5  2007/06/25 00:57:26  dkolev
 * Returns null if there is no grid available in GetDataGrid
 *
 * Revision 1.4  2007/05/17 06:25:03  dkolev
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