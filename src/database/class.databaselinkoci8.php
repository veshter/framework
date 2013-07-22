<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.databaselinkoci8.php,v 1.6 2013-01-14 21:04:52 dkolev Exp $
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

class CDatabaseLinkOCI8 extends CDatabaseLink
{
    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.6 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }
    function Open($host, $user, $pass, $db = "", $autocommit = true)
    {
        ($this->_db_linkid = OCILogon($user, $pass, $host));// or die("Error on logon:". OCIError());
        $this->_auto_commit = $autocommit;
         
        if ($this->_db_linkid)
        return true;

        $this->Warn("Could not connect to database $db");
        return false;
    }

    function Close()
    {
        OCIFreeStatement($this->_db_qresult);
        OCILogOff($this->_db_linkid) or die ("Error on logoff: ". OCIError());
    }

    function SelectDB($dbname)
    {
        return 1;
    }

    function Query($querystr) {
        ($result = ociparse($this->_db_linkid, $querystr))
        or die("Error in query: ". OCIError());
        if ($this->_auto_commit) {
            OCIExecute($result, OCI_COMMIT_ON_SUCCESS);
        }
        else {
            OCIExecute($result, OCI_DEFAULT);
        }
         
        if ($result == 0)
        {
            $this->Warn("Query failed: ". ocierror($this->_db_linkid));
            return 0;
        }
        else {
            if ($this->_db_qresult)
            OCIFreeStatement($this->_db_qresult);
            $this->rowData = array();
            $this->_db_qresult = $result;
            $this->rowCount = OCIRowCount($this->_db_qresult);
            if (!$this->rowCount) {
                // The query was probably an INSERT/REPLACE etc.
                $this->rowCount = 0;
            }
            return 1;
        }
    }

    function SeekRow ($row = 0) {
        die ("COCI8 does not support SelectDB");
    }

    function ReadRow() {
        if(OCIFetchInto($this->_db_qresult, $this->rowData, OCI_ASSOC)) {
            $this->nextRowNumber++;
            return 1;
        }
        else {
            return 0;
        }
    }

    function Commit() {
        OCICommit($this->_db_linkid);
    }
    function Rollback() {
        OCIRollback($this->_db_linkid);
    }

}


/*
 *
 * Changelog:
 * $Log: class.databaselinkoci8.php,v $
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