<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.databaselinkmysql.php,v 1.11 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Database connection class.
 *
 * @version $Revision: 1.11 $
 * @package VESHTER
 *
 */
class CDatabaseLinkMySQL extends CDatabaseLink
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

    function Open ($host, $user, $pass, $db = "", $autocommit = true)
    {
        $newlink = true; //creates a NEW link to the database
        $this->_db_linkid = @mysql_connect ($host, $user, $pass, $newlink);

        if ($this->_db_linkid && $this->SelectDB($db))
        {
            return true;
        }
         

        $this->Kill("Could not connect to database $db on host $host: " . mysql_error());
        return false;
    }

    function Close ()
    {
        if ($this->_db_qresult)
        {
            @mysql_free_result($this->_db_qresult);
        }
        if ($this->_db_linkid)
        {
            return mysql_close ($this->_db_linkid);
        }
        return true;
    }

    function SelectDB ($db)
    {
        if (!empty($db) && (@mysql_select_db ($db, $this->_db_linkid) == true))
        return true;
        return false;
    }
    function Query ($query)
    {

        $this->Notify("Query: " . $query);
        $this->query = $query;

        $result = @mysql_query ($query, $this->_db_linkid);
        if ($result == 0)
        {
            $this->Warn("Query failed: " . mysql_error($this->_db_linkid));
            return false;
        }
        else
        {
            if ($this->_db_qresult)
            @mysql_free_result($this->_db_qresult);
            $this->rowData = array();
            $this->_db_qresult = $result;
            $this->rowCount = @mysql_num_rows ($this->_db_qresult);
            if (!$this->rowCount)
            {
                // The query was probably an INSERT/REPLACE etc.
                $this->rowCount = 0;
            }
            return true;
        }
    }

    function SeekRow ($row = 0)
    {
        if ((!@mysql_data_seek ($this->_db_qresult, $row)) or ($row > $this->rowCount-1))
        {
            printf ("SeekRow: Cannot seek to row %d\n", $row);
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function ReadRow ($result_type = MYSQL_BOTH)
    {
        $result = @mysql_fetch_array ($this->_db_qresult, $result_type);

        if(is_array($result))
        {

            $this->rowData = array();

            //preserve data types
            foreach ($result as $key => $value)
            {
                if (is_numeric($value))
                {
                    //TODO: Not very efficient??
                    $value = floatval($value);
                }


                $this->rowData[$key] = $value;
            }

            $this->nextRowNumber++;
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function Select($table, $keys, $where = 1, $limit = '0,1', $orderby = null, $groupby = null, $distinct = false)
    {

        if (!is_array($keys))
        {
            $keys = array($keys);
        }
         
        $keys_string = CString::Format('%s', implode(", ", $keys));

        $helper = new CDatabaseHelper();
        $helper->SetLimit($limit);
        $limit = CString::Format("LIMIT %s", $helper->GetLimit());
         
        if (!empty($groupby))
        {
            $groupby = CString::Format("GROUP BY %s", $groupby);
        }

        if (!empty($orderby))
        {
            $orderby = CString::Format("ORDER BY %s", $orderby);
        }



        if ($distinct == true)
        {
            $distinct = "DISTINCT";
        }
        else
        {
            $distinct = "";
        }

        $query = CString::Format("SELECT %s %s FROM %s WHERE (%s) %s %s %s;",
        $distinct,
        $keys_string,
        $table,
        $where,
        $groupby,
        $orderby,
        $limit
        );
        return $this->Query($query);
    }

    function Insert ($table, $keys, $values)
    {
        //make sure we are dealing with arrays
        if (!is_array($keys))
        {
            $keys = array($keys);
        }
        if (!is_array($values))
        {
            $values = array($values);
        }

        if (count($keys) != count($values))
        {
            $this->Warn("The number of keys did not match the number of values");
            return false;
        }

        // pull it togather in a string
        $keys_string = CString::Format('%s', implode(", ", $keys));
        $values_string = "";
        // since we want to be a little smart about security we need to make sure quotes are placed in correct places
        foreach($values as $value)
        {
            if (!empty($values_string))
            {
                $values_string .= ", ";
            }
            $values_string .= CString::Quote($value);

        }

        $query = sprintf ("INSERT INTO %s (%s) VALUES (%s);",
        $table,
        $keys_string,
        $values_string
        );
        return $this->Query($query);
    }

    function Update ($table, $keys, $values, $where)
    {

        //make sure we are dealing with arrays
        if (!is_array($keys))
        {
            $keys = array($keys);
        }
        if (!is_array($values))
        {
            $values = array($values);
        }

        if (count($keys) != count($values))
        {
            $this->Warn("The number of keys did not match the number of values");
            return false;
        }

        $c = 0;
        foreach($keys as $key)
        {
            if (!empty($keyvalues_string))
            {
                $keyvalues_string .= ", ";
            }
            $keyvalues_string .= CString::Format('%s = %s', $key, CString::Quote($values[$c]));
            $c++;
        }

        $query = sprintf ("UPDATE %s SET %s WHERE (%s);",
        $table,
        $keyvalues_string,
        $where
        );
         
         
        return $this->Query($query);
    }

    function Delete ($table, $where, $limit = '0,1')
    {
        $helper = new CDatabaseHelper();
        $helper->SetLimit($limit);
        $limit = CString::Format("LIMIT %s", $helper->GetLimit());

        $query = sprintf ("DELETE FROM %s WHERE(%s) $s;",
        $table,
        $where,
        $limit
        );
        return $this->Query($query);
    }

    function Commit ()
    {
        return 1;
    }
    function Rollback ()
    {
        $this->Warn("WARNING: Rollback is not supported by MySQL");
    }

    function TableExists($table)
    {
        if($this->Query("SHOW TABLES LIKE '" . $table . "'"))
        {
            if ($this->rowCount == 1)
            return true;
        }
        return false;
    }

    function FieldExists($table, $field)
    {
        if ($this->TableExists($table))
        {
            if($this->Query("SHOW COLUMNS FROM " . $table . " LIKE '" . $field . "'"))
            {
                if ($this->rowCount == 1)
                return true;
            }
        }
        return false;
    }

}

/*
 *
 * Changelog:
 * $Log: class.databaselinkmysql.php,v $
 * Revision 1.11  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.10.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.10  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.9  2009-06-20 20:44:58  dkolev
 * Added type-casting to the selection command for numbers.
 *
 * Revision 1.8  2009-05-28 20:14:33  dkolev
 * Removed type casting, added casting to the CEntity properties
 *
 * Revision 1.7  2009-01-25 01:29:53  dkolev
 * Added ability to save datatypes for numerics
 *
 * Revision 1.6  2008-06-01 09:50:57  dkolev
 * Added GROUP BY
 *
 * Revision 1.5  2007/09/27 00:13:21  dkolev
 * Changed limit usage. Started using DatabaseHelper
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