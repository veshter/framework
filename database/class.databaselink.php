<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.databaselink.php,v 1.7.4.1 2011-11-25 22:17:14 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Database connection class.
 *
 * @version $Revision: 1.7.4.1 $
 * @package VESHTER
 *
 */
abstract class CDatabaseLink extends CObject
{
    protected $query;
    protected $_db_linkid = 0;
    protected $_db_qresult = 0;
    protected $_auto_commit = false;
    protected $rowData = array();
    protected $nextRowNumber = 0;
    protected $rowCount = 0;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.7.4.1 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * This function is intended for backwards compatibility.
     * @deprecated
     *
     */
    function &GetLinkID()
    {
        return $this->_db_linkid;
    }

    function &GetQuery()
    {
        return $this->query;
    }

    function Open ($host, $user, $pass, $db = "", $autocommit = true)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function Close ()
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function SelectDB ($dbname)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function Query ($querystr)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function SeekRow ($row = 0)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    function ReadRow ($result_type)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function GetRow ()
    {
        return $this->rowData;
    }

    function GetCount()
    {
        return $this->rowCount;
    }


    /**
     * Select data from a database
     *
     * @param string $table
     * @param array $keys
     * @param string $where
     * @param int $limit
     * @param string $orderby
     * @param boolean $distinct
     * @return boolean
     */
    function Select($table, $keys, $where = 1, $limit = '0,1', $orderby = null, $groupby = null, $distinct = false)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
        return false;
    }
    function Insert ($table, $keys, $values)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
        return false;
    }

    function Update ($table, $keys, $values, $where)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
        return false;
    }

    function Delete ($table, $where, $limit = '0,1')
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
        return false;
    }

    function Commit ()
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function Rollback ()
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }
    function SetAutoCommit ($autocommit)
    {
        $this->_auto_commit = $autocommit;
    }

    /**
     * Verfies that a table exists
     *
     * @param string $table
     */
    function TableExists($table)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    /**
     * Get information about a table
     *
     * @param hasharray $table
     */
    function GetTableInfo($table)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    /**
     * Verfies that a field in a table exists
     *
     * @param string $table
     * @param string $field
     */
    function FieldExists($table, $field)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }

    /**
     * Get information about a table field
     *
     * @param string $table
     * @param string $field
     */
    function GetFieldInfo($table)
    {
        $this->Kill(_ERROR_NOTIMPLEMENTEDINCHILD);
    }


}

/*
 *
 * Changelog:
 * $Log: class.databaselink.php,v $
 * Revision 1.7.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.7  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.6  2008-06-01 09:50:57  dkolev
 * Added GROUP BY
 *
 * Revision 1.5  2007/09/27 00:12:49  dkolev
 * Changed limit usage
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