<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.databaselinkpgsql.php,v 1.6 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Database connection class.
 *
 * @version $Revision: 1.6 $
 * @package VESHTER
 *
 */

class CDatabaseLinkPgSQL extends CDatabaseLink
{
    var $_php_ver_major;
    var $_php_ver_minor;
    var $_php_ver_rel;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.6 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function Open ($host, $user, $pass, $db = "", $autocommit = true)
    {
        list( $this->_php_ver_major,
        $this->_php_ver_minor,
        $this->_php_ver_rel   ) = explode( ".", phpversion() );

        ($this->_db_linkid = @pg_connect( "host=$host password=$pass dbname=$db user=$user" ));// or die("Error on logon:");
         
        if ($this->_db_linkid)
        return true;

        $this->Warn("Could not connect to database $db due to ");
        return false;
    }

    function Close () {
        pg_freeresult( $this->_db_qresult );
        return pg_close( $this->_db_linkid );
    }

    function SelectDB ($dbname) {
        return 0;
    }

    function Query ($querystr) {
        if (!$this->_auto_commit) {
            @pg_exec( $this->_db_linkid, "BEGIN;" );
        }
        $result = pg_exec( $this->_db_linkid, $querystr );
        if ($result == 0) {
            $this->Warn("Query failed: ". pg_errormessage($this->_db_linkid));
            return 0;
        } else {
            if ($this->_db_qresult)
            @pg_freeresult( $this->_db_qresult );
            $this->rowData = array();
            $this->_db_qresult = $result;
            $this->rowCount = @pg_numrows( $this->_db_qresult );
            if (!$this->rowCount) {
                // The query was probably an INSERT/REPLACE etc.
                $this->rowCount = 0;
            }
            $this->nextRowNumber = 0;
            return 1;
        }
    }

    function SeekRow ($row = 0) {
        $this->nextRowNumber = $row;
        return 1;
    }

    function ReadRow ($arrType = PGSQL_ASSOC)
    {
        if ($this->nextRowNumber >= $this->rowCount)
        return 0;
        if ($this->_php_ver_major > 3)
        {
            if ($this->rowData = pg_fetch_array( $this->_db_qresult, $this->nextRowNumber, $arrType ))
            {
                $this->nextRowNumber++;
                return 1;
            } else {
                return 0;
            }
        }
        else
        {
            if ($this->rowData = pg_fetch_array( $this->_db_qresult, $this->nextRowNumber )) {
                $this->nextRowNumber++;
                return 1;
            } else {
                return 0;
            }
        }
    }

    function Commit ()
    {
        return $this->Query("COMMIT;");
    }

    function Rollback ()
    {
        return $this->Query("ROLLBACK;");
    }

    function SetAutoCommit ($autocommit)
    {
        $this->_auto_commit = $autocommit;
    }

}

/*
 *
 * Changelog:
 * $Log: class.databaselinkpgsql.php,v $
 * Revision 1.6  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.5.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.5  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.4  2007-05-17 06:24:59  dkolev
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