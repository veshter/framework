<?php
/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.datagrid.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 */

/**
 * In memory representation of a query request
 *
 * This object can be polled and return a column/row of data as necessary
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 */
class CDataGrid extends CGadget
{
    protected $connected = false;

    /**
     * Connection for the data gird
     *
     * @var CDatabaseLink
     */
    protected $datalink;

    protected $query;

    protected $source;
    protected $type;
    protected $host;
    protected $login;
    protected $passwd;

    /**
     * Grid data
     *
     * @var array
     */
    protected $grid = null;

    /**
     * Enter description here...
     *
     * @param string $query
     * @param string $source
     * @param string $type
     * @param string $host
     * @param string $login
     * @param string $passwd
     * @return CDataGrid
     */
    function __construct($query = '', $source = _DATASOURCE_NAME, $type = _DATASOURCE_TYPE, $host = _DATASOURCE_HOST, $login = _DATASOURCE_LOGIN, $passwd = _DATASOURCE_PASSWORD, $readonly = false)
    {
        parent::__construct();
        $this->SetVersion("1.2");

        $this->grid = array();

        if (empty($source) || empty($host) || empty($login) || empty($passwd))
        {
            $this->Warn("Not enough information was supplied for data source login. Connection will most likely fail");
        }

        //set the data link correctly
        switch($type)
        {
            case "MySQL":
                $this->datalink = new CDatabaseLinkMySQL;
                break;
            case "OCI8":
                $this->datalink = new CDatabaseLinkOCI8;
                break;
            case "PgSQL":
                $this->datalink = new CDatabaseLinkPgSQL;
                break;
            default:
                $this->Warn("Unknown data link type provided ($type)");
        }

        //save the query for later
        $this->query = $query;

        //save the data source info for later
        $this->source = $source;
        $this->host = $host;
        $this->login = $login;
        $this->passwd = $passwd;

        $this->connected = false;

        //connect to the data source
        $this->Connect();
    }
    
  
    function __destruct() 
    {
        $this->datalink->Close();
        
        parent::__destruct();
    }

    function GetSource()
    {
        return $this->source;
    }

    function GetQuery()
    {
        return $this->query;
    }

    /**
     * Enter description here...
     *
     * @param string $query
     */
    function SetQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Select data from a datagrid
     *
     * @param string $table
     * @param array $keys
     * @param string $where
     * @param int $limit
     * @param string $orderby
     * @param boolean $distinct
     * @return boolean
     *
     */
    function Select($table, $keys, $where = 1, $limit = '0,1', $orderby = null, $groupby = null, $distinct = false)
    {

        //reset the grid contents
        $this->RemoveAll();

        if ($this->Connect())
        {
            $c = 0;
            //$this->Notify("Looking for query results for \"$this->query\"");
            if ($this->datalink->Select($table, $keys, $where, $limit,  $orderby, $groupby, $distinct))
            {


                $this->Notify("Query completed successfully. Adding data source rows to grid");

                while ($this->datalink->ReadRow())
                {
                    //$this->Notify("Adding row #$c");
                    $this->grid[$c] = $this->datalink->GetRow();
                    $c++;
                }


            }
            else
            {
                $this->Warn($this->datalink->GetStatus());
            }
             
            //set the current data source query as the grid query if this grid is to be updated
            $this->SetQuery($this->datalink->GetQuery());

            //stop using the database
            //$this->datalink->Close();

            return ($c > 0);
        }
        return false;
    }

    function Insert ($table, $keys, $values, $update = false)
    {
        if ($this->Connect())
        {
            if ($this->datalink->Insert($table, $keys, $values))
            {
                if ($update)
                {
                    $this->Get(true);
                }
                return true;
            }
        }
        return false;

    }

    function Update ($table, $keys, $values, $where, $update = false)
    {
        if ($this->Connect())
        {
            if ($this->datalink->Update($table, $keys, $values, $where))
            {
                if ($update)
                {
                    $this->Get(true);
                }
                return true;
            }
        }
        return false;
    }

    function Delete ($table, $where, $limit = '0,1', $update = false)
    {
        if ($this->Connect())
        {
            if ($this->datalink->Delete($table, $where, $limit))
            {
                if ($update)
                {
                    $this->Get(true);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Connect to a database and work with it.
     *
     * @param string $db Database to connect to
     */
    private function Connect()
    {
        if ($this->datalink)
        {
            //if we are not already connected, connect
            if (!$this->connected)
            {
                $this->connected = $this->datalink->Open($this->host, $this->login, $this->passwd, $this->source);
            }

            //something happened and we can't connect to data source
            if (!$this->connected)
            $this->Kill("Cannot connect to data source $this->source using " . $this->datalink->_ident() . ": " . $this->datalink->GetStatus());// on " . _DATASOURCE_HOST . " using the given credentials (" . _DATASOURCE_LOGIN . "/" . _DATASOURCE_PASSWORD . ")");
        }
        else
        {
            $this->Kill("Data link not specified correctly");// on " . _DATASOURCE_HOST . " using the given credentials (" . _DATASOURCE_LOGIN . "/" . _DATASOURCE_PASSWORD . ")");
        }
         
        return $this->connected;
    }

    /**
     * Returns the datasrouce connection link to the caller
     *
     * @return CDatabaseLink
     */
    function &GetDatabaseLink()
    {
        return $this->datalink;
    }

    function GetRowCount()
    {
        return $this->datalink->GetCount();
    }

    /**
     * Returns the grid contents
     *
     * @param boolean $update Requests an update copy of the grid or the current one
     * @return array
     */
    function &Get($update = true)//, $result_type = MYSQL_ASSOC
    {
        //don't do any updating if it is not necessary
        if ($update)
        {
            $this->RemoveAll();

            $this->Notify("Updating grid");

            if (!empty($this->query) && $this->Connect())
            {
                //$this->Notify("Looking for query results for \"$this->query\"");
                if ($this->datalink->Query($this->query))
                {
                    $this->Notify("Query completed successfully. Adding data source rows to grid");
                    $c = 0;
                    while ($this->datalink->ReadRow())
                    {
                        //$this->Notify("Adding row #$c");
                        $this->grid[$c] = $this->datalink->GetRow();
                        $c++;
                    }
                }
                else
                {
                    $this->Warn("Query failed. There is no data in grid");
                    //$this->Warn($this->datalink->GetStatus());
                }

                //stop using the database
                //$this->datalink->Close();

            }
        }
        return $this->grid;

    }

    function &GetRow($row = 0, $update = true)
    {
        $this->Get($update);
        if ($row < count($this->grid))
        {
            return $this->grid[$row];
        }
        return null;
    }

    function &GetValue($row = 0, $column = 0, $update = true)
    {
        //we didn't even get a row, stop
        if (!$this->GetRow($row, $update))
        {
            return null;
        }

        if ($column < count($this->grid[$row]))
        {
            return $this->grid[$row][$column];
        }
        return null;

    }

    /**
     * Executes a raw database query
     *
     */
    function ExecQuery($query)
    {
        return $this->datalink->Query($query);
    }

    /**
     * Reset the grid contents
     *
     */
    function RemoveAll()
    {
        $this->Notify('Removing all data from grid');
        unset($this->grid);
    }

    function ToXML ($drill = 0)
    {
        $array = array(&$this->grid);
        return CAPI::ArrayToXML($array, "grid", $drill);
    }
}

/*

Error: 2000 (CR_UNKNOWN_ERROR)

Message: Unknown MySQL error

Error: 2001 (CR_SOCKET_CREATE_ERROR)

Message: Can't create UNIX socket (%d)

Error: 2002 (CR_CONNECTION_ERROR)

Message: Can't connect to local MySQL server through socket '%s' (%d)

Error: 2003 (CR_CONN_HOST_ERROR)

Message: Can't connect to MySQL server on '%s' (%d)

Error: 2004 (CR_IPSOCK_ERROR)

Message: Can't create TCP/IP socket (%d)

Error: 2005 (CR_UNKNOWN_HOST)

Message: Unknown MySQL server host '%s' (%d)

Error: 2006 (CR_SERVER_GONE_ERROR)

Message: MySQL server has gone away

Error: 2007 (CR_VERSION_ERROR)

Message: Protocol mismatch; server version = %d, client version = %d

Error: 2008 (CR_OUT_OF_MEMORY)

Message: MySQL client ran out of memory

Error: 2009 (CR_WRONG_HOST_INFO)

Message: Wrong host info

Error: 2010 (CR_LOCALHOST_CONNECTION)

Message: Localhost via UNIX socket

Error: 2011 (CR_TCP_CONNECTION)

Message: %s via TCP/IP

Error: 2012 (CR_SERVER_HANDSHAKE_ERR)

Message: Error in server handshake

Error: 2013 (CR_SERVER_LOST)

Message: Lost connection to MySQL server during query

Error: 2014 (CR_COMMANDS_OUT_OF_SYNC)

Message: Commands out of sync; you can't run this command now

Error: 2015 (CR_NAMEDPIPE_CONNECTION)

Message: Named pipe: %s

Error: 2016 (CR_NAMEDPIPEWAIT_ERROR)

Message: Can't wait for named pipe to host: %s pipe: %s (%lu)

Error: 2017 (CR_NAMEDPIPEOPEN_ERROR)

Message: Can't open named pipe to host: %s pipe: %s (%lu)

Error: 2018 (CR_NAMEDPIPESETSTATE_ERROR)

Message: Can't set state of named pipe to host: %s pipe: %s (%lu)

Error: 2019 (CR_CANT_READ_CHARSET)

Message: Can't initialize character set %s (path: %s)

Error: 2020 (CR_NET_PACKET_TOO_LARGE)

Message: Got packet bigger than 'max_allowed_packet' bytes

Error: 2021 (CR_EMBEDDED_CONNECTION)

Message: Embedded server

Error: 2022 (CR_PROBE_SLAVE_STATUS)

Message: Error on SHOW SLAVE STATUS:

Error: 2023 (CR_PROBE_SLAVE_HOSTS)

Message: Error on SHOW SLAVE HOSTS:

Error: 2024 (CR_PROBE_SLAVE_CONNECT)

Message: Error connecting to slave:

Error: 2025 (CR_PROBE_MASTER_CONNECT)

Message: Error connecting to master:

Error: 2026 (CR_SSL_CONNECTION_ERROR)

Message: SSL connection error

Error: 2027 (CR_MALFORMED_PACKET)

Message: Malformed packet

Error: 2028 (CR_WRONG_LICENSE)

Message: This client library is licensed only for use with MySQL servers having '%s' license

Error: 2029 (CR_NULL_POINTER)

Message: Invalid use of null pointer

Error: 2030 (CR_NO_PREPARE_STMT)

Message: Statement not prepared

Error: 2031 (CR_PARAMS_NOT_BOUND)

Message: No data supplied for parameters in prepared statement

Error: 2032 (CR_DATA_TRUNCATED)

Message: Data truncated

Error: 2033 (CR_NO_PARAMETERS_EXISTS)

Message: No parameters exist in the statement

Error: 2034 (CR_INVALID_PARAMETER_NO)

Message: Invalid parameter number

Error: 2035 (CR_INVALID_BUFFER_USE)

Message: Can't send long data for non-string/non-binary data types (parameter: %d)

Error: 2036 (CR_UNSUPPORTED_PARAM_TYPE)

Message: Using unsupported buffer type: %d (parameter: %d)

Error: 2037 (CR_SHARED_MEMORY_CONNECTION)

Message: Shared memory: %s

Error: 2038 (CR_SHARED_MEMORY_CONNECT_REQUEST_ERROR)

Message: Can't open shared memory; client could not create request event (%lu)

Error: 2039 (CR_SHARED_MEMORY_CONNECT_ANSWER_ERROR)

Message: Can't open shared memory; no answer event received from server (%lu)

Error: 2040 (CR_SHARED_MEMORY_CONNECT_FILE_MAP_ERROR)

Message: Can't open shared memory; server could not allocate file mapping (%lu)

Error: 2041 (CR_SHARED_MEMORY_CONNECT_MAP_ERROR)

Message: Can't open shared memory; server could not get pointer to file mapping (%lu)

Error: 2042 (CR_SHARED_MEMORY_FILE_MAP_ERROR)

Message: Can't open shared memory; client could not allocate file mapping (%lu)

Error: 2043 (CR_SHARED_MEMORY_MAP_ERROR)

Message: Can't open shared memory; client could not get pointer to file mapping (%lu)

Error: 2044 (CR_SHARED_MEMORY_EVENT_ERROR)

Message: Can't open shared memory; client could not create %s event (%lu)

Error: 2045 (CR_SHARED_MEMORY_CONNECT_ABANDONED_ERROR)

Message: Can't open shared memory; no answer from server (%lu)

Error: 2046 (CR_SHARED_MEMORY_CONNECT_SET_ERROR)

Message: Can't open shared memory; cannot send request event to server (%lu)

Error: 2047 (CR_CONN_UNKNOW_PROTOCOL)

Message: Wrong or unknown protocol

Error: 2048 (CR_INVALID_CONN_HANDLE)

Message: Invalid connection handle

Error: 2049 (CR_SECURE_AUTH)

Message: Connection using old (pre-4.1.1) authentication protocol refused (client option 'secure_auth' enabled)

Error: 2050 (CR_FETCH_CANCELED)

Message: Row retrieval was canceled by mysql_stmt_close() call

Error: 2051 (CR_NO_DATA)

Message: Attempt to read column without prior row fetch

Error: 2052 (CR_NO_STMT_METADATA)

Message: Prepared statement contains no metadata

Error: 2053 (CR_NO_RESULT_SET)

Message: Attempt to read a row while there is no result set associated with the statement

Error: 2054 (CR_NOT_IMPLEMENTED)

Message: This feature is not implemented yet
*/


/*
 *
 * Changelog:
 * $Log: class.datagrid.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.8  2008-06-01 09:50:11  dkolev
 * Added GROUP BY
 *
 * Revision 1.7  2008/05/06 05:00:36  dkolev
 * Formatting changes
 *
 * Revision 1.6  2007/09/27 00:02:28  dkolev
 * Inheritance changes
 *
 * Revision 1.5  2007/06/25 01:03:20  dkolev
 * Commenting and return by reference changes
 *
 * Revision 1.4  2007/05/17 06:25:02  dkolev
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
 *
 */

?>