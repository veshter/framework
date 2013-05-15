<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.eraser.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */


/**
 * @package VESHTER
 *
 */


/**
 * Data eraser.
 *
 * An eraser uses relations to deleted linked resources.
 *
 * @see CRelation
 *
 * @version $Revision: 1.11 $
 * @package VESHTER
 *
 */
class CEraser extends CGadget
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.11 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    private function CommitWorker(&$root, $reallyerase = true)
    {

        $this->Notify(sprintf("Working inside of %s", !CString::IsNullOrEmpty($root['attributes']['name']) ? $root['attributes']['name'] : $root['attributes']['location']));

        $dbh = new CDatabaseHelper();

        $key = $root['attributes']['key'];

        //see if we were passed in a value or do we have look the values up
        $dbh->SetKeys($key);
        $dbh->SetValues($root['attributes']['value']);
        $dbh->SetLocation($root['attributes']['location']);
        $dbh->SetWhere($root['attributes']['where']);

        $datagrid = CEnvironment::GetMainApplication()->GetDataGrid($root['attributes']['datagrid']);
        if (!$datagrid)
        {
            throw new CExceptionNotInitialized("No available datagrids found");
        }

        //make sure we have a location to work with
        $location = $dbh->GetLocation();
        if (CString::IsNullOrEmpty($location))
        {
            throw new CExceptionInvalidData("Data location could not be determined");
        }
         
        $where = $dbh->GetWhere();
        if (CString::IsNullOrEmpty($where))
        {
            //throw new CExceptionInvalidData("Data criteria could not be determined");
            $dbh->SetWhere(CString::Format('%s=%s', $root['attributes']['key'],CString::Quote($root['attributes']['value'])));
        }

        //values to delete
        $values = null;



        //make sure there are dependencies before strating to drill down
        //those will be the values which will be deleted on the next drill
        if ($root['childNodes'])
        {

            //no supplied explicit value
            if (empty($value))
            {
                $this->Notify(CString::Format('About to select multiple key values from %s based on %s', $dbh->GetLocation(), $dbh->GetWhere()));

                if ($datagrid->Select($dbh->GetLocation(), $dbh->GetKeys(), $dbh->GetWhere(), -1))
                {
                    $values = $datagrid->Get(false);

                }
                else
                {
                    $this->Warn(CString::Format('Could not retrieve information from %s: %s', $dbh->GetLocation(), $datagrid->GetStatus()->GetLastError()));

                    //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
                }
                 
            }
            else
            {
                //$this->Notify(sprintf("Using %s as the only key value", $value));
                $values = array(array('id' => $value));
            }

            //CEnvironment::Dump($values);
             
            //go through all the possible key values and erase accordingly
            for ($loop = 0; $loop < count($values); $loop++)
            {
                $this->Notify(CString::Format('Working on key with value %s in %s', $values[$loop][$key], $dbh->GetLocation()));


                //go through all children and empty them
                foreach ($root[childNodes] as $child)
                {
                    if (CString::IsNullOrEmpty($child['attributes']['key'])) $child['attributes']['key'] = 'guid';
                    $child['attributes']['where'] = CString::Format('%s=%s', $child['attributes']['foreignkey'], CString::Quote($values[$loop][$key]));
                    $child['attributes']['datagrid'] = $root['attributes']['datagrid'];
                    //CEnvironment::Dump($child);

                    //anything at all does not succeed, fail out of this branch
                    if ($this->CommitWorker($child, $reallyerase))
                    {
                        //success
                        //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
                        //CEnvironment::Dump($this->GetStatus());


                    }
                    else
                    {
                        throw new CExceptionEx(CString::Format('Failed to erase child for %s', $child['attributes']['location']));
                    }


                    //after all the children are commited, erase children too
                    //$this->Notify(sprintf("Erasing from %s where %s", $child[attributes][location], $child[attributes][where]));

                    //$datagrid->Delete($dbh->GetLocation(), )
                }
            }
        }

        //erase the current node
        if ($reallyerase == true)
        {
            $this->Notify(CString::Format('Deleting node where %s from %s', $dbh->GetWhere(), $dbh->GetLocation()));

            if (!$datagrid->Delete($dbh->GetLocation(), $dbh->GetWhere(), -1))
            {
                //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
                $this->Warn(CString::Format('Failed to delete from %s', $dbh->GetLocation()));
                return false;
            }
        }
        else
        {
            $this->Notify(CString::Format('Deletion disabled. Skipping deletion on node where %s from %s', $dbh->GetWhere(), $dbh->GetLocation()));

        }
        return true;

    }

    function Commit($reallyerase = true)
    {
        if ($this->config)
        {
            //if there are elements defined without a tab, use the whole form as a single tab
            $root = $this->config->GetElement("/eraser[1]");

            //let the developer know that nothing will actually be erased
            if (!$reallyerase)
            {
                $this->Notify ('Actual erasing has been disabled');
            }

            if ($this->CommitWorker($root,  $reallyerase))
            {
                $this->Notify('Erasing complete');
            }
            else
            {
                throw new CExceptionEx('Erase failed to complete succesfully');
            }

            //print ($this->GetStatus());
            //CEnvironment::Dump(CEnvironment::GetMainApplication()->GetDataGrid()->GetDatabaseLink()->GetStatus());

            return true;
        }
        else
        {
            $this->Warn("Not configured");
            return false;
        }


        //CEnvironment::Dump($this->GetStatus());
    }
}

/*
 *
 * Changelog:
 * $Log: class.eraser.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2009-06-20 20:44:34  dkolev
 * Fixed a bug where nested children would not be erased
 *
 * Revision 1.8  2008-05-06 04:59:14  dkolev
 * Added explicit datagrids.
 *
 * Revision 1.7  2008/01/29 01:20:58  dkolev
 * Major renovation
 *
 * Revision 1.6  2008/01/05 22:55:47  dkolev
 * Major changes
 *
 * Revision 1.5  2007/09/27 00:13:37  dkolev
 * Inheritance changes
 *
 * Revision 1.4  2007/05/17 06:24:58  dkolev
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